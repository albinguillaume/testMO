<?php
	class mysql {
		
		var $_table;
		var $_primary_key;//dans le cas où la table utilise une clé primaire multiple, $_primary_key sera un array
		var $_champs;
		var $_exists;
		var $_historizable=false;//Peut être mis à true, si une table d'historisation existe
		var $_null=array('created','modified','deleted');//Liste des champs dont la valeur NULL est autorisée
		var $_table_encoding='utf8_unicode_ci';
		var $table_prefix='';
		var $_likable=array();
		
		function mysql($id=0) {
			if(!empty($id)) return $this->get($id);
		}
		
		function sql_table_name() {
			return $this->table_prefix.$this->_table;
		}
		
		function exists() {
			if(isset($this->_exists)) return $this->_exists;
			else {
				$this->_query = "SELECT ".$this->sql_table_name().".* FROM " . $this->sql_table_name() . " WHERE " . $this->make_primary_condition() ;
				if (!$res = $this->query($this->_query)) return false;
				if (mysql_num_rows($res)) $this->_exists=true;
				else $this->_exists=false;
			}
			return $this->_exists;
		}
		
		function make_primary_condition($id=NULL) {
			if(!is_array($this->_primary_key)) {
				if(!empty($id))
					return $this->_primary_key . " = '" . $this->object2sql($id)."'";
				else
					return $this->_primary_key . " = '" . $this->object2sql($this->{$this->_primary_key})."'";
			} else  {
				$cond='';
				for($i=0;$i<count($this->_primary_key);$i++) {
					if($i>0) $cond .= ' AND ';
					if(isset($id)) $cond .= $this->_primary_key[$i] . " = '" . $this->object2sql($id[$this->_primary_key[$i]])."'";
					else $cond .= $this->_primary_key[$i] . " = '" . $this->object2sql($this->{$this->_primary_key[$i]})."'";
				}
				return $cond;
			}
		}
		
		function get($id) {
		//print_r(get_class_vars(get_class($this)));
			if (empty($id)) return false;
			
			$this->_query = "SELECT ".$this->sql_table_name().".* FROM " . $this->sql_table_name() . " WHERE " . $this->make_primary_condition($id) ;
			
			//S'il s'agit d'une clé multiple, on renseigne quand même les données, même si la ligne n'existe pas
			if(is_array($this->_primary_key)) {
				foreach($this->_primary_key as $key) {
					$this->{$key} = $this->sql2object($id[$key]);
				}
			}
			if (!$res = $this->query($this->_query)) return false;
			if (!$o = mysql_fetch_assoc($res)) return false;
			$this->_exists=true;
			
			foreach($this->_champs as $key => $val) {
				if(isset($o[$key])) $this->{$key} = $this->sql2object($o[$key]);
				elseif(!in_array($key, $this->_null)) $this->{$key} = '';
				elseif(isset($this->{$key})) unset($this->{$key});
			}

			if(method_exists($this, 'afterGet')) $this->afterGet();

			return true;
		}
		
		function save() {
			if(method_exists($this, 'beforeSave')) $this->beforeSave();
			
			if ((is_array($this->_primary_key) && !$this->exists()) || (!is_array($this->_primary_key) && empty($this->{$this->_primary_key}))) {
			
				$this->_query = "INSERT INTO " . $this->sql_table_name() . " (";
				$i = 0;
				$query_part1 = "";
				$query_part2 = "";
				foreach($this->_champs as $key => $val) {
					
					if(!is_array($this->_primary_key) || $key!=$this->_primary_key) {
						
						$virgule = ", ";
						if($i == 0) {
							$virgule = "";
						}
						$query_part1 .= $virgule . $key;
						if(isset($this->{$key})) $query_part2 .= $virgule . "'" . $this->object2sql($this->{$key}) . "'";
						elseif($key=='created') {
							$query_part2 .= $virgule . "'" . DH_NOW . "'";
							$this->created = DH_NOW;
						} elseif(!in_array($key, $this->_null)) $query_part2 .= $virgule . "''";
						else $query_part2 .= $virgule . "NULL";
						
						$i++;
						
					}
					
				}
				
				
				$this->_query .= $query_part1 . ") VALUES (" . $query_part2 . ")";
				//return $this->_query;
				
				if (!$res = $this->query($this->_query)) return false;

				if(!is_array($this->_primary_key)) $this->{$this->_primary_key} = mysql_insert_id();
				
			} else {
				
				$this->_query = "UPDATE ".$this->sql_table_name()." SET ";
				$i = 0;
				foreach($this->_champs as $key => $val) {
					
					if($key != $this->_primary_key) {
						
						$virgule = ", ";
						if($i == 0) {
							$virgule = "";
						}
						if($key=='modified') {
							$this->_query .= $virgule . $key . " = '" . DH_NOW . "'";
							$this->modified = DH_NOW;
						} elseif(isset($this->{$key})) $this->_query .= $virgule . $key . " = '" . $this->object2sql($this->{$key}) . "'";
						elseif(!in_array($key, $this->_null)) $this->_query .= $virgule . $key . " = ''";
						else $this->_query .= $virgule . $key . " = NULL";
						
						$i++;
						
					}
					
				}
				$this->_query .= " WHERE " . $this->make_primary_condition();
				//return $this->_query;
				if (!$res = $this->query($this->_query)) return false;
				
			}
			if(method_exists($this, 'afterSave')) $this->afterSave();
			return true;
		}

		//Paramètres possibles :
		//	SELECT > à injecter dans le SELECT
		//	WHERE > à injecter dans le WHERE
		//	ORDER BY > à spécifier en ORDER BY
		//	LIMIT > à spécifier en LIMIT
		//	FROM > à ajouter à la table en guise de FROM (par exemple "LEFT JOIN autre_table ON condition")
		//	FLAG_DELETED > à ajouter si on veut récupérer les éléments DELETED
		function find($params=array()) {
		
			//Création du SELECT*
			$select=$this->_table.'.*';
			if(!empty($params['SELECT'])) {
				if(substr(trim($params['SELECT']),0,1)!=',') $select.=', ';
				$select.=$params['SELECT'];
			}
			if(!empty($params['DISTINCT'])) $select = 'DISTINCT '.$params['DISTINCT'];
			if(!empty($params['LIMIT'])) $select = 'SQL_CALC_FOUND_ROWS '.$select;
			$this->_query = "SELECT ".$select;
			$this->_query .= " FROM " . $this->sql_table_name()." AS ".$this->_table." ".((!empty($params['FROM']))?' '.$params['FROM']:'');

			//Création des conditions de la sélection
			$cond = array();
			foreach($this->_champs as $key => $type) {
				if(isset($this->{$key})) {
					if(strpos($type,'char')!==false || strpos($type,'text')!==false || strpos($type,'date')!==false) {
						if(!empty($params['BINARY_FIELDS']) && in_array($key, $params['BINARY_FIELDS'])) $flag_binary=true;
						else $flag_binary=false;
						$cond[] = "`".$key."` ".(in_array($key, $this->_likable)?'LIKE':'=').($flag_binary?'BINARY ':'')." '" . $this->object2sql($this->{$key}) . "'";
					} else {
						$cond[] = "`".$key."` = '" . $this->object2sql($this->{$key}) . "'";
					}
				}
			}
			if(!empty($params['WHERE'])) {
				if(is_array($params['WHERE'])) {
					foreach($params['WHERE'] as $c)
						$cond[]=$c;
				} else {
					$cond[]=$params['WHERE'];
				}
			}
			//Permet d'abstraire la notion de suppression > un élement supprimé est en fait un élément qui a le champs "deleted" à NULL
			//	on rajoute donc toujours la condition "deleted IS NOT NULL" dans toute requête, pour ne jamais ressortir les éléments supprimés
			if(!isset($this->deleted) && empty($params['FLAG_DELETED']) && array_key_exists('deleted',$this->_champs)) $cond[]="deleted IS NULL";
				
			if(count($cond) > 0) {
				$this->_query .= " WHERE ";
				for($i = 0 ; $i < count($cond) - 1 ; $i++)
					$this->_query .= "(".$cond[$i].")" . " AND ";
				$this->_query .= "(".$cond[count($cond) - 1].")";
			}
			
			//Création du order by
			if(!empty($params['GROUP BY'])) $this->_query .= ' GROUP BY '.$params['GROUP BY'];
			
			//Création du order by
			if(!empty($params['ORDER BY'])) $this->_query .= ' ORDER BY '.$params['ORDER BY'];
			
			if(!empty($params['LIMIT'])) $this->_query .= ' LIMIT '.$params['LIMIT'];

			if(!$this->_res = $this->query($this->_query)) return false;

			return mysql_num_rows($this->_res);
		}
		
		function get_found_rows() {
			return mysql_result(mysql_query("SELECT FOUND_ROWS()"),0);
		}

		function fetch() {
			if(!$this->_res) return false;
			if($o = mysql_fetch_assoc($this->_res)) {
				$this->_exists=true;
			
				foreach($this->_champs as $key => $val) {
					if(isset($o[$key])) $this->{$key} = $this->sql2object($o[$key]);
					elseif(!in_array($key, $this->_null)) $this->{$key} = '';
					elseif(isset($this->{$key})) unset($this->{$key});
				}
				
				if(method_exists($this, 'afterGet')) $this->afterGet();
				
				$this->_row=$o;
				return true;
			}
			return false;
		}
		
		function archive() {
			if(array_key_exists('archived',$this->_champs)) {
				$this->archived=DH_NOW;
				if(!$this->save()) return false;
			} else {
				return false;
			}
			return true;
		}
		
		function delete() {
			if(array_key_exists('deleted',$this->_champs)) {
				if(!$this->exists()) return false;
				$this->deleted=DH_NOW;
				if(!$this->save()) return false;
			} else {
				$this->_query = "DELETE FROM " .$this->sql_table_name();
				$this->_query .= " WHERE " . $this->make_primary_condition() ;
				if (!$res = $this->query($this->_query)) return false;
			}
			return true;
		}
		
		function query($sql) {
			//Quelques retouches éventuellement...
			$res=mysql_query($sql);
			if($res===false) {
				if(defined('MODULE')) {//Fait pour n'activer ceci que sur la partie LINK
					logs::add('mysql','error',array('mysql.class.php', $sql));
				}
			}
			return $res;
		}
		
		public static function make_sql_list_where($field,$value,$separateur=';') {
			return "(".$field."='".$value."' OR ".$field." LIKE '%".$separateur.$value.$separateur."%' OR ".$field." LIKE '".$value.$separateur."%' OR ".$field." LIKE '%".$separateur.$value."')";
		}
		
		function object2sql($data) {
			//Conversion charset inutile, car c'est mysql qui gère ça tout seul !
			//if(!strpos($this->_table_encoding,'utf8')===false) {$data=utf8_decode($data);echo 'decode'.$data.' ';}
			return mysql_real_escape_string($data);
		}
		
		function sql2object($data) {
			//Conversion charset inutile, car c'est mysql qui gère ça tout seul !
			//if(strpos($this->_table_encoding,'utf8')===false) {$data=utf8_encode($data);echo 'encode'.$data.' ';}
			return stripslashes($data);
		}
		
		//Conversion d'un objet en array
		function object2array() {
			$tab_out=array();
			foreach($this->_champs as $key=>$type) {
				$tab_out[$key]=$this->{$key};
			}
			return $tab_out;
		}
		
		//Sauvegarde des modifications effectuées dans une table _histo
		function historize() {
			if($this->_historizable) {
				//Si l'enregistrement n'existe pas, pas d'historisation
				if(!$this->id) return false;
				
				$class = get_class($this);
				$tosave=new $class($this->id);
				
				//Crée une copie de la ligne actuelle dans "$class_histo"
				$class_histo = $class.'_histo';
				$histo=new $class_histo();
				$histo->table_prefix=$tosave->table_prefix;
				$histo->{'id_'.$class}=$tosave->id;
				$histo->historized=DH_NOW;
				foreach($tosave->_champs as $name=>$type) {
					if($name!=$tosave->_primary_key) {
						$histo->{$name}=$tosave->{$name};
					}
				}
				$histo->save();
				//echo $histo->_query;
				return $histo->save();
			}
			return false;
		}
		
	}
?>