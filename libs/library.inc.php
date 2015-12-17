<?php

$TAB_MOIS=array(1=>'janvier', 2=>'février', 3=>'mars', 4=>'avril', 5=>'mai', 6=>'juin', 7=>'juillet', 8=>'août', 9=>'septembre', 10=>'octobre', 11=>'novembre', 12=>'décembre');

function default_autoload( $class_name) {
	if(file_exists(CLASSES.$class_name.'.class.php'))
		require_once CLASSES.$class_name.'.class.php';
}
spl_autoload_register('default_autoload');

function get_active_db(){
	$sql='SELECT DATABASE()';
	$sqlresult=mysql_query($sql);
	$row=mysql_fetch_row($sqlresult);
	$active_db=$row[0];
	return $active_db;
}

function current_page() {
	$tab_url=explode('/',$_SERVER["PHP_SELF"]);
	return str_replace('.php','',$tab_url[count($tab_url)-1]);
}

function pr($tab, $echo=true) {
	$out='';
	if($echo) $out.='<pre class="pr">';
	$out.=print_r( $tab, true);
	if($echo) $out.='</pre>';
	if($echo) echo $out;
	else return $out;
}

function empty_const($const_name) {
	if(!defined($const_name)) return true;
	$val=constant($const_name);
	if(empty($val)) return true;
	return false;
}

function empty_array($tab) {
	if(!is_array($tab)) return true;
	foreach($tab as $val) {
		$val=trim($val);
		if(!empty($val)) return false;
	}
	return true;

}
	//Tri d'un tableau associatif $tableau par le champ $field dans le sens $sens ('ASC' ou 'DESC')
	//	> $field et $sens peuvent être des array, et le tri sera effectué dans l'ordre pour chacune de ces colonnes
	function tri_colonne($tableau,$field,$sens='ASC') {
		if(!is_array($field)) $field=array($field);
		if(!is_array($sens)) $sens=array($sens);
		foreach($field as $col) {
			${$col}=array();
			foreach ($tableau as $key => $row) {
				${$col}[$key] = $row[$col];
			}
		}
		$params='';
		foreach($field as $i=>$col) {
			if($params) $params.=', ';
			if($sens[$i]=='DESC') $params.= '$'.$col.', SORT_DESC';
			else $params.= '$'.$col.', SORT_ASC';
		}
		eval('array_multisort('.$params.', $tableau);');

		return $tableau;
	}

function post_get($varname) {
	if(isset($_POST[$varname])) $data = $_POST[$varname];
	elseif(isset($_GET[$varname])) $data = $_GET[$varname];
	if(isset($data)) return $data;
	return null;
}

function is_url($str) {
	if(strpos($str,'http://')!==false) return true;
	return false;
}

function format_for_url($str) {
	return urlencode(str_replace('/',' ',$str));
}

function nombre_format($number) {
	return number_format($number, 0, '.', ' ');
}

function euro_format($number) {
	if(intval($number)==$number) return $number;
	return number_format($number, 2, '.', ' ');
}

function telephone_format($val) {
	$val=trim($val);
	$chiffres='';
	for($i=0;$i<strlen($val);$i++) {
		if(is_numeric($val[$i])) $chiffres.=$val[$i];
	}
	if(strlen($chiffres)==9) $chiffres = '0'.$chiffres;
	if(strlen($chiffres)!=10) return $val;
	$out='';
	for($i=0;$i<strlen($chiffres);$i++) {
		$out.=$chiffres[$i];
		if($i%2 && $i<(strlen($chiffres)-1)) $out.=' ';
	}
	//echo "'".$out."'";
	return $out;
}

function get_subdomain() {
	$uri = $_SERVER['SCRIPT_URI'];
	$tab=explode('/',$uri);
	$host=(!empty($tab[2])?$tab[2]:'');
	$tab_host=explode('.',$host);
	if(count($tab_host)<3) $subdomain='';
	elseif(count($tab_host)==3 && $tab_host[0]=='www') $subdomain='';
	elseif($tab_host[0]=='www') $subdomain=$tab_host[1];
	else $subdomain=$tab_host[0];
	return $subdomain;
}

//Récupération de l'extension d'une chaîne (s'il y en a une)
function get_extension($nom_fic) {
	$tab=explode('.',$nom_fic);
	return $tab[count($tab)-1];
}
	
function ext2ctype($extension) {
		switch($extension) {
			case "pdf": $ctype="application/pdf"; break;
			case "exe": $ctype="application/octet-stream"; break;
			case "zip": $ctype="application/zip"; break;
			case "doc": case "docx": $ctype="application/vnd.ms-word"; break;
			case "xls": $ctype="application/vnd.ms-excel"; break;
			case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			case "gif": $ctype="image/gif"; break;
			case "png": $ctype="image/png"; break;
			case "jpeg": case "jpg": $ctype="image/jpg"; break;
			case "mp3": $ctype="audio/mpeg"; break;
			case "wav": $ctype="audio/x-wav"; break;
			case "mpeg": case "mpg": case "mpe": $ctype="video/mpeg"; break;
			case "mov": $ctype="video/quicktime"; break;
			case "avi": $ctype="video/x-msvideo"; break;
			case "flv": $ctype="video/x-flv"; break;
			case "txt": $ctype="application/octet-stream"; break;
			case "csv": $ctype="application/csv-tab-delimited-table"; break;
			default:
				return false;
				//$ctype='application/octet-stream';
				break;
		}
		return $ctype;
}
	
	//Conversion d'une date Y-m-d en timestamp
	function timestamp($dh) {
		$tab=explode(' ',$dh);
		$tab_date=explode('-',$tab[0]);
		if(empty($tab[1])) $tab[1] = '00:00:00';
		$tab_heure=explode(':',$tab[1]);
		return mktime(intval($tab_heure[0]),intval($tab_heure[1]),intval($tab_heure[2]),intval($tab_date[1]),intval($tab_date[2]),intval($tab_date[0]));
	}
	
	/**
	 * Conversion d'une date du format 'Y-m-d H:i:s' en 'd/m/Y'
	 * @param string $date_bdd
	 * @return string
	 */
	function datetimeTodate_ymd($date_bdd){	
		$tabTmp = explode(' ', $date_bdd);	
		$tab_date = explode("-", $tabTmp[0]);
		return $tab_date[2]."/".$tab_date[1]."/".$tab_date[0];
	}
	
	/**
	 * Conversion d'une date du format 'Y-m-d H:i:s' en 'd/m/Y'
	 * @param string $date_bdd
	 * @return  string
	 */
	function datetimeToheure_hm($date_bdd){
		$tabTmp = explode(' ', $date_bdd);		
		$tab_heure = explode(":", $tabTmp[1]);
		$result = $tab_heure[0]."h";
		if($tab_heure[1]!='00') $result.=$tab_heure[1];
		return $result;
	}
	
	function date_formater($date_ymd_hm,$format='dmyhis') {
		$format='|'.$format;//Pour faire des strpos sans être gêné
		if(!$date_ymd_hm || $date_ymd_hm==DH_VIDE || $date_ymd_hm==DATE_VIDE) return "";
		$tab=explode(" ",$date_ymd_hm);
		$tab_date=explode("-",$tab[0]);
		$tab_his=explode(":",$tab[1]);
		$out='';
		if(strpos($format,'d')) $out.=$tab_date[2];
		if(strpos($format,'m')) $out.=($out?'/':'').$tab_date[1];
		if(strpos($format,'y')) $out.=($out?'/':'').$tab_date[0];
		if(strpos($format,'h')) $out.=($out?' ':'').$tab_his[0];
		if(strpos($format,'i')) $out.=':'.$tab_his[1];
		if(strpos($format,'s')) $out.=':'.$tab_his[2];
		return $out;
	}
	
	function date_ymd_text($date_ymd, $format='txt_j jj txt_m') {
		if(!$date_ymd || dh_vide($date_ymd) || date_vide($date_ymd)) return "";
		$tab=explode(" ",$date_ymd);
		$date_ymd=$tab[0];
		$tab_date=explode("-",$date_ymd);
		$tab_format=explode(' ',$format);
		$out='';
		foreach($tab_format as $f) {
			switch($f) {
				case 'txt_j':
					$elt=page::trad('TAB_JOURS',date('N',timestamp($date_ymd)));
					break;
				case 'txt_m':
					$elt=page::trad('TAB_MOIS',intval($tab_date[1]));
					break;
				case 'jj':
					$elt=intval($tab_date[2]);
					break;
				case 'mm':
					$elt=$tab_date[1];
					break;
				case 'yyyy':
					$elt=intval($tab_date[0]);
					break;
			}
			if(!empty($out)) $out.=' ';
			if(!empty($elt)) $out.=$elt;
		}
		return $out;
		return page::trad('TAB_JOURS',date('w',timestamp($date_ymd)))." ".intval($tab_date[2])." ".page::trad('TAB_MOIS',intval($tab_date[1]));
	}

	//Conversion d'une date du format 'Y-m-d' en 'd/m/Y'
	function date_dmy($date_ymd){
		if(!$date_ymd || dh_vide($date_ymd) || date_vide($date_ymd)) return "";
		$tab=explode(" ",$date_ymd);
		$date_ymd=$tab[0];
		$tab_date=explode("-",$date_ymd);
		return $tab_date[2]."/".$tab_date[1]."/".$tab_date[0];
	}	
	
	//Conversion d'une date du format 'Y-m-d' en 'd/m/Y' (la date de départ peut être également au format 'Y-m-d H:i:s')
	function date_ymd($date_dmy){
		$tab_date=explode("/",$date_dmy);
		return $tab_date[2]."-".$tab_date[1]."-".$tab_date[0];
	}
	
	//Vérification de la cohérence d'une date
	function verif_date($date) {
		$tab_date=explode('-',$date);
		if(count($tab_date)!=3) return false;
		if(!checkdate(intval($tab_date[1]),intval($tab_date[2]),intval($tab_date[0]))) return false;
		return true;
	}
	
	function date_dmy_add_jour($date_str, $nbj) {
		list($date, $time) = explode(' ', $date_str);
		list($year, $month, $day) = explode('-', $date);
		list($hour, $minute, $second) = explode(':', $time);
		return mktime($hour, $minute, $second, $month, $day+$nbj, $year);
	}
	
	function date_addjour($date_ymd,$nb_jours) {
		$ts=timestamp($date_ymd);
		$ts+=$nb_jours*TIME_DAY;
		return date('Y-m-d',$ts);
	}
	
	//Ajout d'un nombre de zéros défini devant la valeur
	function add_zero($var,$nb_char) {
		return str_pad($var, $nb_char, '0',STR_PAD_LEFT);
	}
	
	function add_sign ($val) {
		if($val>=0) return '+'.$val;
		else return '-'.abs($val);
	}
	
	//Conversion d'une date du format 'd/m/Y' en 'Y-m-d' (la date de départ peut être également au format 'd/m/Y H:i:s')
	function dh_vide($dh) {
		if($dh==DH_VIDE) return true;
		return false;
	}
	
	function date_vide($d) {
		if($d==DATE_VIDE) return true;
		return false;
	}
	
	function is_ferie($date) {//$date1 et $date2 peuvent être indifféremment des formats de dates ou des timestamp...
		if(strpos($date,'-')) $date=timestamp($date);
		$sql="SELECT * FROM jour_ferie WHERE mois='".date('n',$date)."' AND jour='".date('j',$date)."' AND (annee='".date('Y',$date)."' OR annee='0')";
		$res=mysql_query($sql);
		if(mysql_num_rows($res)) return true;
		return false;
	}
	
	function nb_feries($date1,$date2) {//$date1 et $date2 peuvent être indifféremment des formats de dates ou des timestamp...
		$duree_jour=24*60*60;
		if(strpos($date1,'-')) $date1=timestamp($date1);
		if(strpos($date2,'-')) $date2=timestamp($date2);
		$nb_feries=0;
		$tmp_jour=$date1;
		while($tmp_jour<$date2) {
			if(date('w',$tmp_jour)!=0) {//Si c'est un dimanche, on n'exécute pas la requête, car ils sont chomés de ttes façons, et déjà comptabilisés autrement.
				$sql="SELECT * FROM jour_ferie WHERE mois='".date('n',$tmp_jour)."' AND jour='".date('j',$tmp_jour)."' AND (annee='".date('Y',$tmp_jour)."' OR annee='0')";
				$res=mysql_query($sql);
				if(mysql_num_rows($res)) $nb_feries++;//C'est un jour férié
			}
			$tmp_jour+=$duree_jour;
		}
		return $nb_feries;
	}
	
	function nb_dimanches($date1,$date2) {//$date1 et $date2 peuvent être indifféremment des formats de dates ou des timestamp...
		$duree_jour=24*60*60;
		if(strpos($date1,'-')) $date1=timestamp($date1);
		if(strpos($date2,'-')) $date2=timestamp($date2);
		$nb_dimanches=0;
		$tmp_jour=$date1;
		while($tmp_jour<$date2) {
			if(date('w',$tmp_jour)==0) $nb_dimanches++;//C'est un dimanche
			$tmp_jour+=$duree_jour;
		}
		return $nb_dimanches;
	}
	
	function varier_date($echelle,$nombre,$date_source)  {
		$date=explode('-',$date_source);
		if($echelle=='annee') {
			$date[0]+=$nombre;
		}
		if($echelle=='mois') {
			$date[1]+=$nombre;
			while($date[1]<=0) {
				$date[0]-=1;
				$date[1]+=12;
			}
			while($date[1]>12) {
				$date[0]+=1;
				$date[1]-=12;
			}
		}
		return $date[0].'-'.add_zero($date[1],2).'-'.add_zero($date[2],2);
	}
	
	function recherche_colonne($tableau,$value,$field) {
		foreach($tableau as $ind=>$datas) {
			if($datas[$field]==$value) return $ind;
		}
		return false;
	}
	
	//Génération classique d'un ensemble de boutons radio
	function field_radio($name,$list_vals,$selected=NULL,$options=array()) {
		if(empty($options['order'])) $options['order']='input';
		$out='';
		foreach($list_vals as $val=>$lab) {
			$out.='<span class="radio_field">';
			$input='<input type="radio" class="radio'.($options['class']?' '.$options['class']:'').'" name="'.$name.'" id="form_'.$name.'_'.str_replace('+','_',str_replace(' ', '', $val)).'" value="'.$val.'"'.((isset($selected) && $val==$selected)?'checked="checked"':'').($options['actions']?' '.$options['actions']:'').' />';
			$label='<label for="form_'.$name.'_'.str_replace('+','_',str_replace(' ', '', $val)).'" class="radio">'.$lab.'</label>';
			if($options['order']=='label') $out.=$label.' '.$input;
			else $out.=$input.' '.$label;
			$out.='</span>'."\n";
		}
		return $out;
	}
	
	//Génération classique d'un ensemble de boutons checkbox
	function field_checkbox($name,$list_vals,$selected=array(),$options=array()) {
		if(empty($options['order'])) $options['order']='input';
		$out='';
		foreach($list_vals as $val=>$lab) {
			$out.='<span class="radio_field">';
			$input='<input type="checkbox" class="radio'.($options['class']?' '.$options['class']:'').'" name="'.$name.'" id="form_'.str_replace('[]', '', $name).'_'.str_replace('+','_',str_replace(' ', '', $val)).'" value="'.$val.'"'.(in_array($val, $selected)?'checked="checked"':'').($options['actions']?' '.$options['actions']:'').' />';
			$label='<label for="form_'.str_replace('[]', '', $name).'_'.str_replace('+','_',str_replace(' ', '', $val)).'" class="radio">'.$lab.'</label>';
			if($options['order']=='label') $out.=$label.' '.$input;
			else $out.=$input.' '.$label;
			$out.='</span>'."\n";
		}
		return $out;
	}
	
	function input_value($val) {
		return htmlspecialchars($val, ENT_COMPAT, 'UTF-8');
	}
	
	function field_select($name,$list_vals,$selected=NULL,$options=array()) {
		$out='';
		//$out.='<select name="'.$name.'"'.($options['id']?' id="'.$options['id'].'"':'').($options['style']?' style="'.$options['style'].'"':'').($options['actions']?' '.$options['actions']:'').'>';
		$out.='<select name="'.$name.'"'.($options['id']?' id="'.$options['id'].'"':'').($options['class']?' class="'.$options['class'].'"':'').($options['style']?' style="'.$options['style'].'"':'').($options['actions']?' '.$options['actions']:'');
		if($options['att']) $out.=' '.$options['att'];
		if($options['other']) $out.=' onchange="javascript:if(this.value==\'other_value\') $(\''.$name.'_free\').css(\'visibility\',\'visible\'); else $(\''.$name.'_free\').css(\'visibility\',\'hidden\');"';
		$out.='>';
		if(isset($options['null'])) {
			$out.='<option value="'.$options['null']['value'].'"';
			//if($options['other']) $out.=' onclick="javascript:$(\''.$name.'_free\').style.visibility=\'hidden\'"';
			$out.='>'.($options['null']['label']?$options['null']['label']:'-------------------------').'</option>';
		}
		$found=false;
		foreach($list_vals as $val=>$lab) {
			$out.='<option value="'.$val.'"';
			if($selected==$val) {
				$found=true;
				$out.=' selected="selected"';
			}
			if($options['other']) $out.=' onclick="javascript:$(\'#'.$name.'_free\').css(\'visibility\', \'hidden\');"';
			$out.='>'.$lab.'</option>';
		}
		if($options['other']) {
			$out.='<option value="other_value"';
			if(!$found && isset($selected)) $out.=' selected="selected"';
			$out.=' onclick="javascript:$(\'#'.$name.'_free\').css(\'visibility\', \'visible\');"';
			$out.='>'.$options['other']['value'].'</option>';
		}
		$out.='</select>';
		if($options['other']) {
			$out.='<span class="free_value" id="'.$name.'_free"';
			if($found || !isset($selected)) $out.=' style="visibility:hidden;"';
			$out.='>';
			if(isset($options['other']['label'])) $out.='<label for="'.$name.'_free">'.$options['other']['label'].'</label>';
			$out.='<input class="text'.(isset($options['other']['input_class'])?' '.$options['other']['input_class']:'').'" type="text" name="'.$name.'_free"';
			if(!$found && $selected) $out.=' value="'.input_value($selected).'"';
			$out.=' />';
			$out.='</span>';
		}
		return $out;
	}
	
	function field_heure ($name,$selected,$plage_h='08:00-18:00',$options=array()) {
		$tab_heure=explode('-', $plage_h);
		$heure_deb=intval($tab_heure[0]);
		$heure_fin=intval($tab_heure[1]);
		$list_vals=array();
		for($h=$heure_deb;$h<=$heure_fin;$h++) {
			if(!empty($options['minut_unit'])) {
				$minutes=0;
				while($minutes<60) {
					$val_h=add_zero($h,2).':'.add_zero($minutes,2);
					if($val_h>=$tab_heure[0] && $val_h<=$tab_heure[1]) $list_vals[$val_h] = $val_h;
					$minutes+=$options['minut_unit'];
				}
			} else {
				$val_h=add_zero($h,2).':00';
				if($val_h>=$tab_heure[0] && $val_h<=$tab_heure[1]) $list_vals[$val_h] = $val_h;
			}
		}
		return field_select($name,$list_vals,$selected,$options);
	}
	
	function list_semaines($annee=2011) {
		$weeks=array();
		$duree_jour=24*60*60;
		$duree_week = 7*$duree_jour;
		$duree_6jours = 6*$duree_jour;
		$duree_4jours = 4*$duree_jour;
		$ts = timestamp($annee.'-01-01 12:00:00');
		$nb_jours = 0;
		while(date('w',$ts)!=1) {
			$nb_jours++;
			$ts+=$duree_jour;
		}
		if($nb_jours>3.5) $semaine_deb = 2;
		else $semaine_deb=1;
		$jour_deb = date('Y-m-d', $ts);
		$num_week=$semaine_deb;
		while(date('Y', $ts)==$annee) {
			$weeks[$num_week] = array('deb'=>date('Y-m-d', $ts), 'fin'=>date('Y-m-d', $ts+$duree_6jours), 'fin_ouvre'=>date('Y-m-d', $ts+$duree_4jours));
			$ts += $duree_week;
			$num_week++;
		}
		return $weeks;
	}
	
	//Génération d'une clé md5 aléatoire de la longueur spécifiée
	function genere_cle($longueur=5) {
		$chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		$cle="";
		for($i=0;$i<$longueur;$i++){
			$car=rand(0,strlen($chaine)-1);
			$cle.=$chaine[$car];
		}
		return md5($cle);
	}
	
	function genere_chaine($longueur=5) {
		$chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		$cle="";
		for($i=0;$i<$longueur;$i++){
			$car=rand(0,strlen($chaine)-1);
			$cle.=$chaine[$car];
		}
		return $cle;
	}
	
	function genere_password($longueur=5) {
		$chaine = "abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789";
		$cle="";
		for($i=0;$i<$longueur;$i++){
			$car=rand(0,strlen($chaine)-1);
			$cle.=$chaine[$car];
		}
		return $cle;
	}
	
	function initiales_str($str) {
		$out="";$i=0;$attente_char=true;
		for($i=0;$i<strlen($str);$i++) {
			if(eregi("^([-])$",$str[$i])) {
				$out.=$str[$i];
				$attente_char=true;
			} else {
				if($attente_char) $out.=$str[$i];
				$attente_char=false;
			}
		}
		$out.=".";
		return $out;
	}
	
	function empty_tab($tab) {
		foreach($tab as $str) {
			if(!empty($str)) return false;
		}
		return true;
	}
	
	function array_delete_blank($tab) {
		$tab_out=array();
		$i_last=count($tab)-1;
		while($i_last>=0 && empty($tab[$i_last])) $i_last--;
		for($i=0;$i<=$i_last;$i++) {
			$tab_out[$i]=$tab[$i];
		}
		return $tab_out;
	}
	
	//$headers est un tableau contenant des en-têtes de colonnes
	//$tab est un tableau à indexer avec les colonnes $headers
	function array_change_keys ($tab,$headers) {
		$donnees=array();
		foreach($headers as $ind=>$head) {
			$donnees[$head]=$tab[$ind];
		}
		return $donnees;
	}
	
	function is_prix($data) {
		$data=trim(utf8_decode(str_replace('€','',$data)));
		//if(ereg('^-?([0-9 ,.]*)+€$',$data)) {echo $data.' > okereg<br />';return true;}
		if(preg_match('/^([-]?)([0-9 ,.]+)$/',$data)) return true;
		return false;
	}
	
	function unformat_prix($prix) {
		return intval(str_replace(array(' ',',','€'),array('','.',''),$prix));
	}
	
	/*function parse_ini ( $filepath ) {
		$ini = file( $filepath );
		//$ini = $this->replace_var();
		if ( count( $ini ) == 0 ) { return array(); }
		$sections = array();
		$values = array();
		$globals = array();
		$i = 0;
		foreach( $ini as $line ){
			$line = trim( $line );
			// Comments
			if ( $line == '' || $line{0} == ';' ) { continue; }
			// Sections
			if ( $line{0} == '[' ) {
				$sections[] = substr( $line, 1, -1 );
				$i++;
				continue;
			}
			// Key-value pair
			list( $key, $value ) = explode( '=', $line, 2 );
			$key = trim( $key );
			$value = str_replace('|',"\n",trim( $value ));
			if ( $i == 0 ) {
				// Array values
				if ( substr( $line, -1, 2 ) == '[]' ) {
					$globals[ $key ][] = $value;
				} else {
					$globals[ $key ] = $value;
				}
			} else {
				// Array values
				if ( substr( $line, -1, 2 ) == '[]' ) {
					$values[ $i - 1 ][ $key ][] = $value;
				} else {
					$values[ $i - 1 ][ $key ] = $value;
				}
			}
		}
		for( $j=0; $j<$i; $j++ ) {
			$result[ $sections[ $j ] ] = $values[ $j ];
		}
		return $result + $globals;
	}*/
	
function parse_ini ( $filepath ) {
		$saut_ligne='|';
		$separator='.';
		
		// Lecture du fichier
		$ini = file( $filepath );
		// Si le fichier est vide, un renvoi un tableau vide
		if ( count( $ini ) == 0 ) { return array(); }
		
		// Définition des variables
		$arbre = array();
		$section_act = "";
		// Pour chaque ligne du fichier
		foreach( $ini as $line ){
			$line = trim( $line );
			// Ligne de Commentaires
			if ( $line == '' || $line{0} == ';' ) { continue; }
			// Ligne de Sections
			if ( $line{0} == '[' ) {
			//if ( $line{0} == '[' && $line{1} != ']' ) {
				$section_act = substr( $line, 1, -1 );
				continue;
			}
			// Ligne couple key = value
			list( $key, $value ) = explode( '=', $line, 2 );
			$key = trim( $key );
			// ||| représente un sauf de ligne dans le fichier ini
			$value = str_replace($saut_ligne,"\n",trim( $value ));
			// Concaténation de la section avec les key pour obtenir le chemin complet
			if( $section_act != "" ) $key = $section_act.".".$key;
			// séparation du chemin 
			$tab_k = explode( $separator, $key);
			// Initialisation du pointeur au début de l'arbre
			$tab = &$arbre;
			// On boucle sur toute l'arbo sauf le dernier 
			for( $j = 0 ; $j < ( count( $tab_k) - 1 ) ; $j++ ) {
				// Si la key n'est pas un tableau, on supprime l'ancienne valeur et on converti en tableau
				if( !is_array( $tab) ) {
					$tab = array();
				}
				// Si la key n'existe pas deja, on la crée
				if( !array_key_exists( $tab_k[$j], $tab) ) {
					$tab[$tab_k[$j]] = array();
				}
				// Mise a jour du pointeur sur la key créé
				$tab = &$tab[$tab_k[$j]];
			}
			// Le dernier element représente la VRAI key
			$k = $tab_k[count( $tab_k) - 1];
			// Si la key finit par des crochet, on ajoute la valeur au tableau
			if ( substr( $k, -2, 2 ) == '[]' ) {
				$tab[ $k ][] = $value;
			} else {
				$tab[ $k ] = $value;
			}
		}
		return $arbre;
	}
	
	function multimerge ($array1, $array2) {
		if( empty( $array1) ) return $array2;
		if( empty( $array2) ) return $array1;
		if (is_array($array2) && count($array2)) {
			foreach ($array2 as $k => $v) {
				if (is_array($v) && count($v)) {
					if( substr( $k, 0, 1) == '!' ) { // Bidouille delphine + albin pour section ini
						$k2 = substr( $k, 1);
						$array1[$k2] = $v;
					} else {
						$array1[$k] = multimerge($array1[$k], $v);
					}
				} else {
					$array1[$k] = $v;
				}
			}
		} else {
			$array1 = $array2;
		}
		return $array1;
	}
	/* //Fonctions gérant les encodings
	//Bonne idée, mais non fonctionnel pour le moment
	function detect_encoding($filename) {
		$file_content = file_get_contents($filename);
		
		$bom=substr($file_content, 0, 2);
		
		if($bom === chr(0xff).chr(0xfe)  || $bom === chr(0xfe).chr(0xff)){
			// UTF16 Byte Order Mark present
			$encoding = 'UTF-16';
		} else {
			$file_sample = $file_content + 'e'; //read first 1000 bytes
			// + e is a workaround for mb_string bug
			//$encoding = mb_detect_encoding($file_content , "UTF-8, UTF-7, ASCII, EUC-JP,SJIS, eucJP-win, SJIS-win, JIS, ISO-2022-JP, ISO-8859-2", true);
			$encoding = mb_detect_encoding($file_content, "UTF-8, ISO-8859-2", true);
			//echo $encoding;
		}
		
		if($encoding=='ISO-8859-2') return 'windows-1250';
		return $encoding;
	}
	
	
	function manage_encoding($f, $encoding) {
		if ($encoding) {
			stream_filter_append($f, 'utf8_encode');//'convert.iconv.'.$encoding.'/UTF-8');
		}
		return $f;
	}*/
	
	function create_path($path) {
		$marques=array('fiat','alfa','lancia','jeep');
		$path = substr($path, 0, strrpos($path,'/'));
		if(empty($path)) return false;
		$parcours='';
		$arbo=explode('/',$path);
		$upload_found=false;
		$marque_found=false;
		foreach($arbo as $folder) {
			$parcours.=$folder.'/';
			if(!is_dir($parcours)) {
				if($folder!='../' && $upload_found && $marque_found) {
					//Création du répertoire
					mkdir($parcours);
					chmod($parcours, 0775);
				} else return false;
			}
			if($folder=='upload') $upload_found=true;
			elseif(in_array($folder, $marques)) $marque_found=true;
		}
		return true;
	}
	
	function get_online_file($src_file,$dest_file) {
		//Création du chemin
		if(!create_path($dest_file)) return false;
		
		//Récupération du contenu
		$file_content=file_get_contents($src_file);
		if(empty($file_content)) return false;
		
		//Création du fichier localement
		if(!$f = fopen($dest_file, 'w')) return false;
		if(!fwrite($f, $file_content)) return false;
		fclose($f);
		
		//Changement des droits
		chmod ($dest_file, 0777);
		return true;
	}
	
	function miniaturize($image_file, $dest_file, $max_width=-1, $max_height=-1, $options=array()) {
		ini_set('memory_limit', '50M');
		
		if(!file_exists($image_file)) return false;//le fichier à redimensionner n'existe pas !
		
		$tab_ext=array('.jpg','.gif','.png');
		$ext=recuperer_extension($image_file);
		if(!in_array($ext, $tab_ext)) return false;//extension incorrecte !
		
		//Calcul des dimensions de la miniature
		$size=@getimagesize($image_file);
		if(!empty($size[0]) && (empty($max_width) || $size[0]<=$max_width) && !empty($size[1]) && (empty($max_height) || $size[1]<=$max_height)) return false;
		if($max_width>0 && $max_height>0){
    	$new_width = $max_width;
      $new_height=round(($new_width * $size[1]) /$size[0]);
      if($new_height > $max_height) {
      	$new_height = $max_height;
        $new_width=round(($size[0]*$new_height)/$size[1]);
      }       
		} else {  
      if($max_width>0){
  			if($size[0]>=$max_width){
  				$new_width=$max_width;
  				$new_height=round($size[1]*$new_width/$size[0]);
  			} else {            
  				$new_width = $size[0];
  				$new_height = $size[1];
        }
  		}
      if($max_height>0){
  			if($size[1]>=$max_height){
  				$new_height=$max_height;
  				$new_width=round($size[0]*$new_height/$size[1]);
  			} else {            
  				$new_width = $size[0];
  				$new_height = $size[1];
        }
  		}
		}
		if($new_width<=0 || $new_height<=0) return false;
		
		//Miniaturisation
		if($ext =='.jpg')	$src = imagecreatefromjpeg($image_file);
		if($ext =='.gif')	$src = imagecreatefromgif($image_file);
		if($ext =='.png')	$src = imagecreatefrompng($image_file);
		$im=imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($im, $src, 0, 0, 0, 0, $new_width,$new_height, $size[0], $size[1]);
		if($ext == '.jpg')	ImageJpeg ($im, $dest_file);
		if($ext == '.gif')	ImagePng ($im, $dest_file);
		if($ext == '.png')	ImagePng ($im, $dest_file);
		
		return true;
	}
	
	//Récupération du nom du dernier fichier présent dans le répertoire spécifié
	function nom_dernier_fichier($exp_reg, $dir="."){
		$files = array();
		$handle = opendir($dir);
		while($file = readdir($handle)) {
			if(preg_match ($exp_reg,$file)) {
				$files[] = $file;
			}
		}
		closedir($handle);
		sort($files);
		return $files[count($files)-1];
	}
	
	//Ajout d'un suffixe en fonction du nombre de fichiers de même nom présents
	// > pour éviter de remplacer un fichier, on indique un numéro pour chaque version
	function add_suffix_fileroot($path,$fileroot,$ext) {
		$nom_fic=$fileroot.$ext;
		$suffixe=0;
		while (file_exists($path.$nom_fic) && $i<999999) {
			$suffixe++;
			$nom_fic=$fileroot."(".$suffixe.")".$ext;
		}
		return substr($nom_fic,0,strlen($nom_fic)-strlen($ext));
	}
	function add_suffix_fileroot2($path,$fileroot,$ext) {
		if(strpos($ext,'.')===false) $ext = '.'.$ext;
		$nom_fic=$fileroot.$ext;
		$suffixe=0;
		while (file_exists($path.$nom_fic) && $i<999999) {
			$suffixe++;
			$nom_fic=$fileroot."--".$suffixe.$ext;
		}
		return substr($nom_fic,0,strlen($nom_fic)-strlen($ext));
	}

	function string2array($string) {
		$lng = mb_strlen($string, mb_internal_encoding() );
		$array=array();
		for($i=0;$i<$lng;$i++) {
			$array[] = mb_substr($string,$i,1,mb_internal_encoding());
		}
		return $array;
	}
	
	function array_combine2($arr1, $arr2) {
		$count = min(count($arr1), count($arr2));
		return array_combine(array_slice($arr1, 0, $count), array_slice($arr2, 0, $count));
	}
	
	function format_nom_fichier($chaine) {
  	$autorise=string2array('abcdefghijklmnopqrstuvwxyz0123456789-_.');
  	$caractere=string2array('àáâãäåòóôõöøèéêëçìíîïùúûüÿñ');
  	$remplacement=string2array('aaaaaaooooooeeeeciiiiuuuuyn');
		$replace = array_combine2($caractere, $remplacement);
  	$chaine=strtolower($chaine);
  	$chaine=str_replace(' ','',$chaine);
		$lng=mb_strlen($chaine, mb_internal_encoding());
		$chaine_array=array();
		for($i=0;$i<$lng;$i++) {
			$c = mb_substr($chaine,$i,1,mb_internal_encoding());
  		if(!in_array($c,$autorise)) {
				if(array_key_exists($c, $replace)) $chaine_array[] = $replace[$c];
				else $chaine_array[]='_';
			} else $chaine_array[] = $c;
  	}
  	return implode('',$chaine_array);
  	/*$autorise='abcdefghijklmnopqrstuvwxyz0123456789-_.';
  	$chaine=strtolower($chaine);
  	$chaine=str_replace(' ','',$chaine);
  	$caractere='àáâãäåòóôõöøèéêëçìíîïùúûüÿñ';
  	$remplacement='aaaaaaooooooeeeeciiiiuuuuyn';
  	$chaine=strtr($chaine,$caractere,$remplacement);
  	for($i=0;$i<strlen($chaine);$i++) {
  		if(!strpos('|'.$autorise,$chaine[$i])) $chaine[$i]='_';
  	}
  	return $chaine;*/
	}
	
	function format_nom_variable($chaine) {
  	$autorise=string2array('abcdefghijklmnopqrstuvwxyz0123456789_');
  	$caractere=string2array('àáâãäåòóôõöøèéêëçìíîïùúûüÿñ');
  	$remplacement=string2array('aaaaaaooooooeeeeciiiiuuuuyn');
		$replace = array_combine($caractere, $remplacement);
  	$chaine=strtolower($chaine);
  	$chaine=str_replace(' ','',$chaine);
		$lng=mb_strlen($chaine, mb_internal_encoding());
		$chaine_array=array();
		for($i=0;$i<$lng;$i++) {
			$c = mb_substr($chaine,$i,1,mb_internal_encoding());
  		if(!in_array($c,$autorise)) {
				if(array_key_exists($c, $replace)) $chaine_array[] = $replace[$c];
				else $chaine_array[]='_';
			} else $chaine_array[] = $c;
  	}
  	$nom_var=implode('',$chaine_array);
  	//Cas où la chaîne commence par un chiffre
  	if(''.intval($chaine_array[0])==$chaine_array[0]) {
  		$nom_var = '_'.$nom_var;
		}
  	return $nom_var;
	}
	
	function uploadable($file_key, $options=array()) {

		$file_types=array('file','image','video','sound');
		if(empty($options['type']) || !in_array($options['type'], $file_types)) {
			$options['type'] = 'file';
		}
		if(empty($options['extensions'])) {
			if($options['type']=='sound') {
				$options['extensions'] = array('mp3');
			} elseif($options['type']=='video') {
				$options['extensions'] = array('flv');
			} else {
				
				$options['extensions'] = array('jpg','gif','png');
				if($options['type']=='file') {
				
					$options['extensions'] = array_merge($options['extensions'], array('pdf','xls','doc','ppt','zip'));
				}
			}
		}

		if(empty($options['weight'])) {
			$options['weight'] = 52428800;//50Mo
		}
		if(empty($_FILES[$file_key])) return 'No upload found';
		
		//Vérification de l'extension
		$filename = $_FILES[$file_key]['name'];
		$ext = strtolower(get_extension($filename));
		if(!in_array($ext, $options['extensions'])) return 'Incorrect file type';
		
		//Vérifications du poids du fichier
		if(!empty($options['weight'])) {
			if ($_FILES[$file_key]['size']>$options['weight'] || $_FILES[$file_key]['size']<=0) return "Votre fichier dépasse le poids maximal (".($options['weight']/1024)." Ko).";
		}
		
		//Vérifications des dimensions, si demandé
		if(!empty($options['size'])) {
			$tab_size=getimagesize($_FILES[$file_key]["tmp_name"]);
			if (!empty($options['size']['width']) && $tab_size[0] > $options['size']['width']) return "Votre fichier dépasse la largeur maximale (".$options['size']['width']." pixels). ";
			if (!empty($options['size']['height']) && $tab_size[1] > $options['size']['height']) return "Votre fichier dépasse la hauteur maximale (".$options['size']['height']." pixels). ";
			if (!empty($options['size']['exacte_width']) && $tab_size[0] != $options['size']['exacte_width']) return "Votre fichier n'à pas le bonne largeur (".$options['size']['exacte_width']." pixels). ";
			if (!empty($options['size']['exacte_height']) && $tab_size[1] != $options['size']['exacte_height']) return "Votre fichier n'à pas le bonne hauteur (".$options['size']['exacte_height']." pixels). ";
		}
		return '';
	}
	
	//Upload de fichier
	function upload($file_key, $targetPath, $options=array()) {
		
		$tempFile = $_FILES[$file_key]['tmp_name'];
		$targetFile = $_FILES[$file_key]['name'];
		$ext = get_extension($_FILES[$file_key]['name']);
		
		//Si demandé, une vérification est effectuée (paramètres par défaut)
		if(!empty($options['check'])) {
			$error=uploadable($file_key);
			if(!empty($error)) return false;
		}
		
		//Renommage du fichier
		if(!empty($options['filename'])) {
			$targetFile = $options['filename'];
		} else {//Sinon, nommage par défaut, à partir du nom de fichier
			$targetFile = format_nom_fichier($targetFile);
		}
		if(empty($options['flag_overwrite'])) {
			//Ajout d'un suffixe au fichier s'il existe déjà
			$i=0;
			if(preg_match('#\.[a-zA-Z]+$#',$targetFile)){
				$name_root=substr($targetFile,0,strrpos($targetFile,'.'.$ext));
			} else {
				$name_root=$targetFile;
			}
			$targetFile = $name_root.'_'.str_pad( '0', 3, '0', STR_PAD_LEFT).'.'.$ext;
			while(file_exists($targetPath.$targetFile) && $i<999999) {
				$i++;
				//$targetFile=$name_root.'('.$i.').'.$ext;
				$targetFile=$name_root.'_'.str_pad( $i, 3, '0', STR_PAD_LEFT).'.'.$ext;
			}
		}
		
		//Création du répertoire > TODO > désactiver cette possibilité après quelques temps d'exploitation, pour éviter les erreurs...
		create_path($targetPath);
		
		move_uploaded_file($tempFile,$targetPath.$targetFile);
		
		if(!empty($options['miniaturize'])) {
			$max_width=!empty($options['miniaturize']['width'])?$options['miniaturize']['width']:-1;
			$max_height=!empty($options['miniaturize']['height'])?$options['miniaturize']['height']:-1;
			miniaturize($targetPath.$targetFile, $targetPath.$targetFile, $max_width, $max_height);
		}
		
		chmod($targetPath.$targetFile, 0775);
		return $targetFile;
	}
	
	function donnees_google_analytics($profileID,$date_stats) {
	require_once(LIBS.'analytics.lib.php');
	
		$profileId="ga:".$profileID;
		$tab_date=explode('-',$date_stats);
	
		//create class
		$analytics = new Analytics();
		
		//données retournées
		$donnees=array();
		
		//paramètres
		$parameters = array(
			'hier' => array('start'=>$date_stats, 'end'=>$date_stats),
			'mois' => array('start'=>$tab_date[0].'-'.$tab_date[1].'-01', 'end'=>$date_stats),
			'an' => array('start'=>$tab_date[0].'-01-01', 'end'=>$date_stats),
		);
		//print_r($parameters);
		
		// login
		$bLoginOk = $analytics->login('lanciafrance@gmail.com', 'agenceone');
		if(!$bLoginOk) return false;
		
		//login correct
		// get profiles
		//$entries = $analytics->getAccountsListFeed();
			
		foreach($parameters as $periode=>$dates) {
			$analytics->setStartDate($dates['start']);
			$analytics->setEndDate($dates['end']);
			
		  // get page views
	    $pv = $analytics->getPageViews($profileId);
	
	    // get visitors count
	    $vi = $analytics->getUniqueVisitors($profileId);
	    
	    $donnees['visiteurs_'.$periode] = number_format($vi[0]['visitors'],0,'.',' ');
			$donnees['pages_'.$periode] = number_format($pv[0]['pageviews'],0,'.',' ');
		}
		//echo '<pre>';print_r($donnees);echo '</pre>';	
		return $donnees;
	}
	
	function zipCreate( $zip_name, $files, $path='') {
		if( empty( $files) ) return false;
		$str_file = '';
		if( is_array( $files) ) {
			$file_in_zip = array();
			foreach( $files as $f => $fn ) {
				if(file_exists( $path.$fn) && !array_key_exists( basename( $path.$fn), $file_in_zip) ) {
					$str_file .= ' "'.$path.$fn.'"';
					$file_in_zip[basename( $path.$fn)] = 1;
				}
			}
			//pr( $file_in_zip);
		} else {
			if( file_exists( $path.$files) ) {
				$str_file .= ' "'.$path.$files[$f].'"';
			}
		}
		if( empty( $str_file) ) return false;
		
		exec( 'zip -j '.$path.$zip_name.$str_file, $output);
		
		//Suppression des fichiers sources
		if( is_array( $files) ) {
			foreach( $file_in_zip as $fn => $flag ) {
				if(file_exists( $path.$fn)) {
					@unlink($path.$fn);
				}
			}
		}
		
		
		return true;
	}
	
	function save_table( $tableName) {
		if( empty( $tableName) ) return false;
		// Récupération de la requette de création de la table
		$req = "SHOW CREATE TABLE `".$tableName."`";
		$res = mysql_query( $req);
		$row = mysql_fetch_assoc( $res);
		$extension = date( "Ymd_His");
		$prefix = 'zzzbkp__';
		// Modification du nom de la table avec le nom de la nouvelle table
		$req_dupli = str_replace( '`'.$tableName.'`', '`'.$prefix.$tableName.$extension.'`', $row["Create Table"]);
		// Création de la nouvelle table
		$res = mysql_query( $req_dupli);
		
		// Si data == true => Remplissage de la nouvelle table avec les enregistrement de la table source
		$req_insert = "INSERT INTO `".$prefix.$tableName.$extension."` SELECT * FROM `".$tableName."`";
		$res = mysql_query( $req_insert);
	}
	
	
	/* Fonctions SQL */
	//Récupération de la requête SQL pour copier une table
	//Le tableau $tab_id contiendra après exécution la liste des id des éléments trouvés
	//'flag_create_table'> true ou 'drop'(suppression si existe)
	function dump_sql($src_table,$dest_table='',$params=array(), &$stream=null) {
		/*if(empty($dest_table)) $dest_table=$src_table;
		if(empty($params['primary'])) $params['primary']='id';
		$dump=array();
		if(!empty($params['flag_create_table'])) {
			$insert= str_replace('`'.$src_table.'`','`'.$dest_table.'`',mysql_fetch_array(mysql_query("SHOW CREATE TABLE $src_table")));
			if($params['flag_create_table']=='drop') $dump[]="DROP TABLE IF EXISTS $dest_table;";
			$dump[]=$insert[1].";";
		}
		$req_table = mysql_query("SELECT * FROM $src_table");
		$nbr_champs = mysql_num_fields($req_table);
		while ($ligne = mysql_fetch_array($req_table)) {
			$tab_id[]=$ligne[$params['primary']];
			//var_dump($ligne);
			$insertion= "INSERT INTO $dest_table VALUES (";
			for ($i=0; $i<$nbr_champs; $i++) {
				$insertion.= "'" . mysql_real_escape_string($ligne[$i]) . "', ";
			}
			$insertion= substr($insertion, 0, -2);
			$insertion.= ");";
			$dump[]=$insertion;
		}
		return $dump;*/
		$crlf="\n";$escape='`';
		if(empty($dest_table)) $dest_table=$src_table;
		$dump='';
		$strTableStructure = "Table structure for table";
		$strDumpingData = "Dumping data for table";
		
		$dump.="# ---- Date de la sauvegarde : ".DH_NOW."   ----------------$crlf";
		$dump.='# '.$strTableStructure.' '.$escape.$src_table.$escape.$crlf;
		$dump.=$crlf;		
		$dump.=get_table_def($src_table, $dest_table, $crlf, $escape).";$crlf";		
		$dump.="# $strDumpingData '$src_table'$crlf";
		if(!empty($stream)) {
			fputs ($stream , $dump);
			get_table_content($src_table, $dest_table, $crlf, $escape, $stream);
		} else {
			$dump.=get_table_content($src_table, $dest_table, $crlf, $escape);
		}
		
		return $dump;
	}
	
	function get_table_def($src_table, $dest_table, $crlf, $escape) {
		$out='';
		//$out .= 'DROP TABLE IF EXISTS '$escape.$dest_table.$escape.';'.$crlf;
		$create = mysql_fetch_assoc(mysql_query('SHOW CREATE TABLE '.$escape.$src_table.$escape));
		$out .= str_replace($escape.$src_table.$escape, $escape.$dest_table.$escape, $create['Create Table']);
		$out .= ';'.$crlf;
		return $out;
		/*$schema_create = "";
		$schema_create .= 'DROP TABLE IF EXISTS '.$escape.$dest_table.$escape.';'.$crlf;
		$schema_create .= 'CREATE TABLE '.$escape.$dest_table.$escape.' ('.$crlf;
		$result = mysql_query('SHOW FIELDS FROM '.$escape.$src_table.$escape) or mysql_die();
		while($row = mysql_fetch_array($result)) {
			$schema_create .= '   '.$escape.$row[Field].$escape.' '.$row[Type];
			if(isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0")) $schema_create .= " DEFAULT '$row[Default]'";
			if($row["Null"] != "YES") $schema_create .= " NOT NULL";
			if($row["Extra"] != "") $schema_create .= " $row[Extra]";
			$schema_create .= ",$crlf";
		}
		$schema_create = ereg_replace(",".$crlf."$", "", $schema_create);
		$result = mysql_query('SHOW KEYS FROM '.$escape.$src_table.$escape) or mysql_die();
		while($row = mysql_fetch_array($result)) {
			$kname=$row['Key_name'];
			if(($kname != "PRIMARY") && ($row['Non_unique'] == 0)) $kname="UNIQUE|$kname";
			if(!isset($index[$kname])) $index[$kname] = array();
			$index[$kname][] = $row['Column_name'];
		}
		
		while(list($x, $columns) = @each($index)) {
			$schema_create .= ",$crlf";
			if($x == "PRIMARY") $schema_create .= " PRIMARY KEY (" . implode($columns, ", ") . ")";
			elseif (substr($x,0,6) == "UNIQUE") $schema_create .= " UNIQUE ".substr($x,7)." (".implode($columns,", ").")";
			else $schema_create .= " KEY $x (" . implode($columns, ", ") . ")";
		}
		$schema_create .= "$crlf)";
		return (stripslashes($schema_create));*/
	}
	
	function get_table_content($src_table, $dest_table, $crlf, $escape, &$stream=null) {
		$out='';$table_list='';
		$result = mysql_query("SELECT * FROM $src_table") or mysql_die();
		$i = 0;$block_size=100;
		while($row = mysql_fetch_row($result)) {
			if(empty($table_list)) {
				$table_list = "(";
				for($j=0; $j<mysql_num_fields($result);$j++) $table_list .= $escape.mysql_field_name($result,$j).$escape.", ";
				$table_list = substr($table_list,0,-2);
				$table_list .= ")";
			}
			if($i%$block_size==0) $schema_insert = ';'.$crlf.'INSERT INTO '.$escape.$dest_table.$escape.' '.$table_list.' VALUES'.$crlf.'(';
			else $schema_insert = ",$crlf(";
			for($j=0; $j<mysql_num_fields($result);$j++) {
				if(!isset($row[$j])) $schema_insert .= " NULL,";
				elseif($row[$j] != "") $schema_insert .= " '".mysql_real_escape_string($row[$j])."',";
				else $schema_insert .= " '',";
			}
			$schema_insert = ereg_replace(",$", "", $schema_insert);
			$schema_insert .= ")";
			if(!empty($stream)) fputs ($stream , $schema_insert);
			else $out.=$schema_insert;
			$i++;
		}
		if(!empty($stream)) return true;
		return $out.';'.$crlf;
	}
	
	function html_clean($str, $charset = 'UTF-8') {
		$str = preg_replace('/\0+/', '', $str);
		$str = preg_replace('/(\\\\0)+/', '', $str);
		$str = preg_replace('#(&\#*\w+)[\x00-\x20]+;#u',"\\1;",$str);
		$str = preg_replace('#(&\#x*)([0-9A-F]+);*#iu',"\\1\\2;",$str);
		$str = preg_replace("/%u0([a-z0-9]{3})/i", "&#x\\1;", $str);
		$str = preg_replace("/%([a-z0-9]{2})/i", "&#x\\1;", $str);
		if (preg_match_all("/<(.+?)>/si", $str, $matches)) {
			for ($i = 0; $i < count($matches['0']); $i++) {
				$str = str_replace($matches['1'][$i], 
					new_html_entity_decode($matches['1'][$i], $charset), 
					$str);
			}
		}
		$str = preg_replace("#\t+#", " ", $str);
		$str = str_replace(array('<?php', '<?PHP', '<?', '?>'), array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
		
		$words = array('javascript', 'vbscript', 'script', 'applet', 'alert', 'document', 'write', 'cookie', 'window');
		foreach ($words as $word) {
			$temp = '';
			for ($i = 0; $i < strlen($word); $i++) {
				$temp .= substr($word, $i, 1)."\s*";
			}
			
			$temp = substr($temp, 0, -3);
			$str = preg_replace('#'.$temp.'#s', $word, $str);
			$str = preg_replace('#'.ucfirst($temp).'#s', ucfirst($word), $str);
		}
		$str = preg_replace("#<a.+?href=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>.*?</a>#si", "", $str);
		$str = preg_replace("#<img.+?src=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>#si", "", $str);
		$str = preg_replace("#<(script|xss).*?\>#si", "", $str);
		$str = preg_replace('/<(.*?)>/ie', "'<' . preg_replace(array('/javascript:[^\"\']*/i', '/(onblur|onchange|onclick|onfocus|onload|onmouseover|onmouseup|onmousedown|onselect|onsubmit|onunload|onkeypress|onkeydown|onkeyup|onresize)[ \\t\\n]*=[ \\t\\n]*[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes('\\1')) . '>'", $str);
		//$str = preg_replace('#(<[^>]+.*?)(onblur|onchange|onclick|onfocus|onload|onmouseover|onmouseup|onmousedown|onselect|onsubmit|onunload|onkeypress|onkeydown|onkeyup|onresize)[^>]*>#iU',"\\1>",$str);
		$str = preg_replace('#<(/*\s*)(alert|applet|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|layer|link|meta|object|plaintext|style|script|textarea|title|xml|xss)([^>]*)>#is', "&lt;\\1\\2\\3&gt;", $str);
		
		$str = preg_replace('#(alert|cmd|passthru|eval|exec|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2(\\3)", $str);
	
		$bad = array(
			'document.cookie'		=> '',
			'document.write'		=> '',
			'window.location'		=> '',
			"javascript\s*:"		=> '',
			"Redirect\s+302"		=> '',
			'<!--'								=> '&lt;!--',
			'-->'								=> '--&gt;'
		);
	
		foreach ($bad as $key => $val) {
			$str = preg_replace("#".$key."#i", $val, $str);	 
		}
		return $str;
	}
			
	function new_html_entity_decode($str, $charset='ISO-8859-1') {
		if (stristr($str, '&') === FALSE) return $str;
	
		if (function_exists('html_entity_decode') && (strtolower($charset) != 'utf-8' OR version_compare(phpversion(), '5.0.0', '>='))) {
			$str = html_entity_decode($str, ENT_COMPAT, $charset);
			$str = preg_replace('~&#x([0-9a-f]{2,5})~ei', 'chr(hexdec("\\1"))', $str);
			return preg_replace('~&#([0-9]{2,4})~e', 'chr(\\1)', $str);
		}
		
		$str = preg_replace('~&#x([0-9a-f]{2,5});{0,1}~ei', 'chr(hexdec("\\1"))', $str);
		$str = preg_replace('~&#([0-9]{2,4});{0,1}~e', 'chr(\\1)', $str);
	
		if (stristr($str, '&') === FALSE) {
			$str = strtr($str, array_flip(get_html_translation_table(HTML_ENTITIES)));
		}
		
		return $str;
	}
?>