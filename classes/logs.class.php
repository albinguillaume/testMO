<?php
class logs {
	var $logs=false;
	var $datas=array();
	private static $_instance;
	
	private function __construct() {}
	private function __clone() {}
	
	static function instance() {
		if (is_null(self :: $_instance)) {
			self :: $_instance = new logs();
		}
		return self :: $_instance;
	}
	
	static function add($data_type,$nature,$data,$flag_email=false) {
		$o=logs::instance();
		
		$o->datas[$data_type][]=$data;
		if(class_exists('page')) {
			$page_datas = page::get_page_infos();
		} else {
			$page_datas = array();
		}
		if(!defined('DATE_NOW')) define('DATE_NOW',date('Y-m-d'));
		if(!defined('DH_NOW')) define('DH_NOW',date('Y-m-d H:i:s'));
		
		$data_to_write=array();
		$data_to_write[] = DH_NOW;
		$data_to_write[] = $nature;
		$data_to_write[] = $_SERVER['PHP_SELF'];
		$data_to_write[] = $_SERVER['QUERY_STRING'];
		if(isset($page_datas['plateforme'])) $data_to_write[] = $page_datas['plateforme'];
		if(isset($page_datas['module'])) $data_to_write[] = $page_datas['module'];
		if(isset($page_datas['auth_id'])) $data_to_write[] = $page_datas['auth_id'];
		if(!isset($page_datas['project'])) $page_datas['project'] = '';
		$data_to_write = array_merge($data_to_write, $data);
		
		//Ecriture dans le fichier de logs, de façon atomique
		$log_file=LOGS.$data_type.'-'.substr(DATE_NOW,0,7).'.csv';
		$F = fopen($log_file,"a");
		if(!$F) {
			mail(ROOT_EMAIL,$page_datas['project'].' ['.CONFIG.'] [ERROR_NOT_STORED]','Lors de l\'écriture dans le fichier de log '.$log_file);
			return false;
		} else {
			fputs($F,implode(';',$data_to_write)."\n");
			fclose($F);
			chmod ($log_file, 0775); 
		}
		if($nature=='error' || $flag_email) {//S'il s'agit d'une "erreur", on envoie un mail à l'administrateur
			mail(ADMINISTRATOR,SESSION_PREFIX.' '.CONFIG.' [ERROR]',utf8_decode(implode("\n",$data_to_write)));
		}
		return true;
	}
	
	static function draw() {
		$o=logs::instance();
		
		echo '<br /><br />---------- LOGS -------------';
		echo '<pre>';
		var_dump($o->datas);
		echo '</pre>';
	}
	
}
?>