<?php
	class net_mail {
		public $config_mail = "";
		public $config_admin = "";
		public $config_email_test = "";
		public $config_exp_email = "";
		public $config_exp_name = "";
		public $config_exp_email_reply = "";
		public $email_defaut = "webmaster@lesnetworkeurs.com";
		public $config_exp_mail_defaut = "webmaster@lesnetworkeurs.com";
		public $header = "";
		public $body = "";
		public $boundary = "";
		public $boundary_pj = "";
		public $piece_jointe = "";
		public $tracking_class;
		public $config_file;
		public $config_ini;
		
		public $emailing_charset = 'iso-8859-1';
		public $rep_emailing = "emailing/";
		public $sujet;
		public $dest;
		public $cc;
		public $contenu_txt;
		public $contenu_html;
		public $tab_piece_jointe;
		public $params;
		public $perso;
		
		
		/*
			Description : Définit les propriétés de la classe
		*/
		function __construct() {
			// Si config n'est pas défini, on met TEST par défaut
			if( defined( "CONFIG_EMAIL") ) {
				$this -> config_mail = CONFIG_EMAIL;
			} elseif( defined( "CONFIG") ) {
				$this -> config_mail = CONFIG;
			} else {
				$this -> config_mail = "TEST";
			}
			if( defined( "ADMINISTRATOR") ) {
				$this -> config_admin = ADMINISTRATOR;
			} else {
				$this -> config_admin = $this -> email_defaut;
			}
			if( defined( "EMAIL_TEST") ) {
				$this -> config_email_test = EMAIL_TEST;
			} else {
				$this -> config_email_test = $this -> email_defaut;
			}
			if( defined( "EXP_EMAIL") ) {
				$this -> config_exp_email = EXP_EMAIL;
			} else {
				$this -> config_exp_email = $this -> config_exp_mail_defaut;
			}
			if( defined( "EXP_NAME") ) {
				$this -> config_exp_name = EXP_NAME;
			}
			if( defined( "EXP_EMAIL_REPLY") ) {
				$this -> config_exp_email_reply = EXP_EMAIL_REPLY;
			} else {
				$this -> config_exp_email_reply = $this -> config_exp_email;
			}
			if( defined( "EMAILING") ) {
				$this -> rep_emailing = EMAILING;
			}
			if( defined( "EMAILING_CHARSET") ) {
				$this -> emailing_charset = EMAILING_CHARSET;
			}
			if( defined( "EMAILING_TRACKING_CLASS") ) {
				$this -> tracking_class = EMAILING_TRACKING_CLASS;
			}
			$this->load_config();
			
			$this -> sujet = "";
			$this -> dest = "";
			$this -> cc = "";
			$this -> contenu_txt = "";
			$this -> contenu_html = "";
			$this -> header = "";
			$this -> body = "";
			$this -> boundary = "";
			$this -> boundary_pj = "";
			$this -> tab_piece_jointe = array();
			$this -> params = array();
			$this -> perso = array();
			
		}
		
		function load_config() {
			if(!isset($this->config_ini)) $this->config_ini=array();
			//Si la classe conf n'existe pas, on ne l'utilise pas.
			if(is_file(CLASSES.'conf.class.php')) {
				$this->config_ini['OBJETS']=conf::get('EMAIL_SUBJECTS');
				$this->config_file='object';
			}
			if(defined('EMAILING_CONFIG')) load_config_from_file();
		}
		
		function load_config_from_file($ini_file='') {
			if(!empty($ini_file)) $source_donnees=$ini_file;
			elseif(defined('EMAILING_CONFIG')) $source_donnees=EMAILING_CONFIG;
			else return false;
			if($this->config_file!=$source_donnees) {
				//Rafraîchissement de la configuration à partir du fichier ini
				$this->config_ini=parse_ini($source_donnees);
				if($exp_name=$this->get_from_config('CONFIG','EXP_NAME')) $this -> config_exp_name = $exp_name;
				if($exp_email=$this->get_from_config('CONFIG','EXP_EMAIL')) $this -> config_exp_email = $exp_email;
				if($rep_emailing=$this->get_from_config('CONFIG','FOLDER_TPL')) $this -> rep_emailing = CHEMIN.$rep_emailing;
				$this->config_file=$source_donnees;
			}
		}
		
		function get_from_config($param1, $param2=null) {
			if( empty($param2) ) {
				if( !empty( $this->config_ini[$param2]) )
					return $this->config_ini[$param2];
			} else {
				if( !empty( $this->config_ini[$param1][$param2]) )
					return $this->config_ini[$param1][$param2];
			}
			return false;
		}
		
		/*
			Description : Construit et envoi l'email
		*/
    public function send() {
			// Controle si tout est OK pour création du mail
			if( $this -> controle_info_mail() == false ) return false;
			// Construction des piece jointe
			$this -> set_piece_jointe();
			// Construction des headers
			$this -> get_header();
			//echo $this -> header;
			// Construction du contenu de l'email
			$this -> get_body();
			//echo "\n".$this -> body;
			// remplacement des variables dans le mail
			$this -> replace_perso();
			// Encodage du sujet
			$this -> encode_objet();
			// Envoi du mail
			if( $this -> config_mail == "PROD" ) {
				$envoi_res = mail( $this -> dest, $this -> sujet, $this -> body, $this -> header);
			} else {
				$this -> sujet = "TEST : ".$this -> sujet;
				$this -> body .= "\n\n<!-- to: ".$this -> dest.'-->';
				if( $this -> cc != "" ) $this -> body .= "\n-- cc: ".$this -> cc;
				$envoi_res = mail( $this -> config_email_test, $this -> sujet, $this -> body, $this -> header);
			}
			
			//Sauvegarde de l'envoi, si demandé
			if(!empty($this->tracking_class)) {
				$classname = $this->tracking_class;
				call_user_func(array($classname, 'track'), $this, $envoi_res);
				//${$classname} :: track($this, $envoi_res);
			}
			
			return $envoi_res;
		}
		
		/*
			Description : Contrôle si tout est OK pour la construction et l'envoi du mail
		*/
		private function controle_info_mail() {
			if( $this -> sujet == "" ) {
				logs::add('emailing','error',array('Error controlling mail datas !', 'Subject missing'));
				return false;
			}
			if( $this -> dest == "" ) {
				logs::add('emailing','error',array('Error controlling mail datas !', 'Dest missing'));
				return false;
			}
			if( $this -> contenu_html == "" && $this -> contenu_txt == "" ) {
				logs::add('emailing','error',array('Error controlling mail datas !', 'No content found'));
				return false;
			}
			
			return true;
		}
		
		/*
			Description : Construit le contenu HTML avec l'entête si $head = true 
			paramètres : $head : Booléen (true / false)
		*/
		private function make_contenu_html( $head=false) {
			$html = "";
			if( $head ) {
				$html .= "Content-Type: Text/HTML; charset=\"".$this->emailing_charset."\"\nContent-Transfer-Encoding: 8bit\n\n";
			}
			$html .= $this -> contenu_html."\n";
			return $html;
		}
		
		/*
			Description : Construit le contenu TXT avec l'entête si $head = true 
			paramètres : $head : Booléen (true / false)
		*/
		private function make_contenu_txt( $head) {
			$txt = "";
			$saut_ligne = "";
			if( $head ) {
				$txt .= "Content-Type: Text/Plain; charset=\"".$this->emailing_charset."\" format=flowed"."\n\n";
				$saut_ligne = "\n";
			}
			$txt .= $this -> contenu_txt.$saut_ligne;
			return $txt;
		}
		
		/*
			Description : Remplace la variable {BOUNDARY} par sa valeur
		*/
		private function make_contenu_pj( $bound) {
			$pj = "";
			$pj .= str_replace( '{BOUNDARY}', $bound, $this -> piece_jointe);
			return $pj;
		}
		
		/*
			Description : Construit le corp du mail
		*/
		private function get_body() {
			$this -> body = "";
			//$this -> body .= "--".$this -> boundary."\n";
			
			if( count( $this -> tab_piece_jointe) > 0 && $this -> contenu_html != "" && $this -> contenu_txt != "" ) {
				
				// Pièce jointe + HTML + TXT
				
				$this -> body .= "--".$this -> boundary."\n";
				$this -> body .= $this -> make_contenu_txt( true);
				$this -> body .= "\n\n--".$this -> boundary."\n";
				$this -> body .= $this -> make_contenu_html( true);
				$this -> body .= "\n\n--".$this -> boundary."--\n\n";
				$this -> body .= $this -> make_contenu_pj( $this -> boundary_pj);
				$this -> body .= "\n\n--".$this -> boundary_pj."--\n";
				
			} elseif( count( $this -> tab_piece_jointe) > 0 ) {
				
				// Pièce jointe + HTML ou TXT
				
				$this -> body .= "--".$this -> boundary."\n";
				if( $this -> contenu_html != "" ) {
					$this -> body .= $this -> make_contenu_html( true);
				} elseif( $this -> contenu_txt != "" ) {
					$this -> body .= $this -> make_contenu_txt( true);
				}
				$this -> body .= $this -> make_contenu_pj( $this -> boundary);
				$this -> body .= "\n\n--".$this -> boundary."--\n";
								
			} elseif( $this -> contenu_html != "" && $this -> contenu_txt != "" ) {
				
				// Contenu HTML + TXT
				
				$this -> body .= "--".$this -> boundary."\n";
				$this -> body .= $this -> make_contenu_txt( true);
				$this -> body .= "\n\n--".$this -> boundary."\n";
				$this -> body .= $this -> make_contenu_html( true);
				$this -> body .= "\n\n--".$this -> boundary."--\n\n";
				
			} elseif( $this -> contenu_html != "" && $this -> contenu_txt == "" ) {
				
				// HTML Seulement
				
				$this -> body .= $this -> make_contenu_html( false);
				//$this -> body .= "\n\n--".$this -> boundary."--\n\n";
				
			} elseif( $this -> contenu_txt != "" && $this -> contenu_html == "" ) {
				
				// TXT Seulement
				
				$this -> body .= $this -> make_contenu_txt( false);
				//$this -> body .= "\n\n--".$this -> boundary."--\n\n";
				
			}
		}
		
		/*
			Description : Construit les headers du mail.
		*/
		private function get_header() {
		
			$this -> header = "";
			$this -> boundary = "-----=".md5( uniqid( rand() ) );
			$this -> boundary_pj = "-----=".md5( uniqid( rand() ) );
			
			
			$mime_type = "MIME-Version: 1.0";
			$from = "From: ";
			$reply = "";
			if( !empty( $this -> params['EXP_EMAIL']) ) {
				if( $this -> params['EXP_NOM'] ) $from .= $this -> params['EXP_NOM']." <";
				$from .= $this -> params['EXP_EMAIL'];
				if( $this -> params['EXP_NOM'] ) $from .= ">";
				$reply .= "Reply-To: ".$this -> params['EXP_EMAIL']."";
			} else {
				if( $this -> config_exp_name != "" ) {
					$from .= $this->encode_string($this -> config_exp_name)." <".$this -> config_exp_email.">";
					$reply .= "Reply-To: ".$this -> config_exp_email_reply."";
				} else {
					$from .= $this -> config_exp_email."";
					$reply .= "Reply-To: ".$this -> config_exp_email_reply."";
				}
			}
			$cc = "";
			$bcc = "";
			$pri = "";
			if( $this -> cc != "" && $this -> config_mail == "PROD" ) $cc .= "cc: ".$this -> cc."";
			if( !isset( $this -> params['NO_BCC']) || $this -> params['NO_BCC'] != 1 ) $bcc .= "bcc: ".$this -> config_admin."";
			if( isset( $this -> params['PRIORITY']) && $this -> params['PRIORITY'] == 1 )  $pri .= "X-Priority: 1\n";
			
			
			
			if( count( $this -> tab_piece_jointe) > 0 && $this -> contenu_html != "" && $this -> contenu_txt != "" ) {
				
				// Pièce jointe + HTML + TXT
				
				$this -> header = $mime_type."\n";
				$this -> header .= "Content-Type: multipart/mixed;"."\n";
				$this -> header .= " boundary=\"".$this -> boundary_pj."\""."\n";
				$this -> header .= $from."\n".$reply."\n";
				if( $cc != "" ) $this -> header .= $cc."\n";
				if( $bcc != "" ) $this -> header .= $bcc."\n";
				if( $pri != "" ) $this -> header .= $pri."\n";
				$this -> header .= "--".$this -> boundary_pj."\n";
				$this -> header .= "Content-Type: multipart/alternative;\n";
				$this -> header .= " boundary=\"".$this -> boundary."\"\n";
				$this -> header .= "\n\n\n";
				
			} elseif( count( $this -> tab_piece_jointe) > 0 ) {
				
				// Pièce jointe + HTML ou TXT
				
				$this -> header = $mime_type."\n";
				$this -> header .= "Content-Type: multipart/mixed;"."\n";
				$this -> header .= " boundary=\"".$this -> boundary."\""."\n";
				$this -> header .= $from."\n".$reply."\n";
				if( $cc != "" ) $this -> header .= $cc."\n";
				if( $bcc != "" ) $this -> header .= $bcc."\n";
				if( $pri != "" ) $this -> header .= $pri."\n";
				//$this -> header .= "--".$this -> boundary."\n";
				
			} elseif( $this -> contenu_html != "" && $this -> contenu_txt != "" ) {
				
				// Contenu HTML + TXT
				
				$this -> header = $mime_type."\n";
				$this -> header .= "Content-Type: multipart/alternative;"."\n";
				$this -> header .= " boundary=\"".$this -> boundary."\""."\n";
				$this -> header .= $from."\n".$reply."\n";
				if( $cc != "" ) $this -> header .= $cc."\n";
				if( $bcc != "" ) $this -> header .= $bcc."\n";
				if( $pri != "" ) $this -> header .= $pri."\n";
				//$this -> header .= "--".$this -> boundary."\n";
				
			} elseif( $this -> contenu_html != "" && $this -> contenu_txt == "" ) {
				
				// HTML Seulement
				
				$this -> header = $mime_type."\n";
				$this -> header .= "Content-Type: Text/HTML; charset=\"".$this->emailing_charset."\"\nContent-Transfer-Encoding: 8bit\n";
				//$this -> header .= " boundary=\"".$this -> boundary."\""."\n";
				$this -> header .= $from."\n".$reply."\n";
				if( $cc != "" ) $this -> header .= $cc."\n";
				if( $bcc != "" ) $this -> header .= $bcc."\n";
				if( $pri != "" ) $this -> header .= $pri."\n";
				//$this -> header .= "--".$this -> boundary."\n";
				
			} elseif( $this -> contenu_txt != "" && $this -> contenu_html == "" ) {
				
				// TXT Seulement
				
				$this -> header = $mime_type."\n";
				$this -> header .= "Content-Type: Text/Plain; charset=\"".$this->emailing_charset."\" format=flowed"."\n";
				$this -> header .= " boundary=\"".$this -> boundary."\""."\n";
				$this -> header .= $from."\n".$reply."";
				if( $cc != "" ) $this -> header .= "\n".$cc;
				if( $bcc != "" ) $this -> header .= "\n".$bcc;
				if( $pri != "" ) $this -> header .= "\n".$pri;
				//$this -> header .= "\n";
				
			}
			
		}
		
		/*
			Description : Met en place le contenu des pièce jointe 
		*/
		private function set_piece_jointe() {
			
			if( count( $this -> tab_piece_jointe) > 0 ) {
				$this -> piece_jointe = "";
				//Attachement de chacune des pièces jointes
				foreach( $this -> tab_piece_jointe as $libelle => $fichier ) {
					//echo $libelle."<br />".$fichier."<br /><br />";
					$fp = fopen( $fichier, "r");
					$attachment = fread( $fp, filesize( $fichier)); 
					$attachment = chunk_split( base64_encode( $attachment));
					fclose( $fp);
					$this -> piece_jointe .= "--{BOUNDARY}\r\n";
					$this -> piece_jointe .= "Content-Type: application/octet-stream; name=\"".$libelle."\"\n";
					$this -> piece_jointe .= "Content-Transfer-Encoding: base64\n";
					//$this -> piece_jointe .= "Content-Disposition: attachment; filename=\"".$libelle."\"\n";
					$this -> piece_jointe .= "Content-Disposition: inline; filename=\"".$libelle."\"\n";
					$this -> piece_jointe .= "\n"; 
					$this -> piece_jointe .= $attachment."\n"; 
					$this -> piece_jointe .= "\n\n"; 
				}
			}
		}
		
		/*
			Description : Remplace les variables dans le contenu du mail ( après génération )
		*/
		private function replace_perso() {
			
			foreach( $this -> perso as $key => $val ) {
				$this -> sujet = str_replace( $key, $val, $this -> sujet );
				$this -> body = str_replace( $key, $val, $this -> body);
			}
			
		}
		
		function get_objet_from_config($gabarit) {
			$this->sujet = str_replace("\n",'|',$this->get_from_config('OBJETS',$gabarit));
			if(empty($this->sujet)) return false;
			return true;
		}
		
		/*
			Description : définit le contenu de l'email depuis des fichiers
			paramètres : $gabarit => nom du gabarit, $objet => sujet du mail
		*/
		public function make_contenu_from_file( $gabarit, $objet="") {
			
			$body_html = "";
			$body_txt = "";
			
			if( is_file( $this -> rep_emailing.$gabarit.".txt") ) {
				$fic = fopen( $this -> rep_emailing.$gabarit.".txt", "r");
				$body_txt= fread( $fic, filesize( $this -> rep_emailing.$gabarit.".txt"));
				fclose($fic);
			}
			if( is_file( $this -> rep_emailing.$gabarit.".html") ) {
				$fic = fopen( $this -> rep_emailing.$gabarit.".html", "r");
				$body_html = fread( $fic, filesize( $this -> rep_emailing.$gabarit.".html"));
				fclose( $fic);
			}
			
			$this -> contenu_txt = $body_txt;
			$this -> contenu_html = $body_html;
			
			if(empty($this -> contenu_txt) && empty($this -> contenu_html)) {
				logs::add('emailing','error',array('Error controlling mail datas !', 'No content found for email template "'.$gabarit.'"'));
			}
			
			if(empty($objet)) {
				$this->get_objet_from_config($gabarit);
			} else {
				$this -> sujet = $objet;
			}
			
		}
		
		/*
			Description : définit le contenu de l'email depuis un tableau associatif
			paramètres : $tab => tableau associatif ( txt, html, sujet)
		*/
		public function make_contenu_from_array( $tab) {
			
			$body_html = "";
			$body_txt = "";
			$objet = "";
			
			if( array_key_exists( "txt", $tab ) && $tab["txt"] != "" ) {
				$body_txt = $tab["txt"];
			}
			if( array_key_exists( "html", $tab ) && $tab["html"] != "" ) {
				$body_html = $tab["html"];
			}
			if( array_key_exists( "sujet", $tab ) && $tab["sujet"] != "" ) {
				$objet = $tab["sujet"];
			}
			
			$this -> contenu_txt = $body_txt;
			$this -> contenu_html = $body_html;
			$this -> sujet = $objet;
			
		}
		
		/*
			Description : Encode le sujet du mail
		*/
		private function encode_objet() {
			$this -> sujet = $this->encode_string($this -> sujet);
		}
		
		//Description : Encode la chaîne au format mail
		private function encode_string($str) {
			if( strpos( $str, $this->emailing_charset)===false ) {
				$str='=?'.$this->emailing_charset.'?Q?'.str_replace( '+', '_', str_replace( '%', '=', urlencode( $str) ) ).'?=';
			}
			return $str;
		}
		
		
	}
?>