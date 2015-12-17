<?php
	class stats_pages_all extends mysql {
	
		var $id; var $session_id; var $site; var $version; var $url; var $protocol; var $domain; var $str_get; var $str_post; var $created;
		var $_table='stats_pages_all';
		var $_primary_key='id';
		var $_champs=array('id'=>'int', 'session_id'=>'int', 'site'=>'varchar', 'version'=>'varchar', 'url'=>'varchar', 'protocol'=>'varchar', 'domain'=>'varchar', 'str_get'=>'text', 'str_post'=>'text', 'created'=>'datetime');
		
		private static  $skipFields = array('id','session_id','created','str_get','str_post');
		public static  $sharedInfos = null;
		
		
		private function __construct() {}
		
		/*
		public static function getInstance()
		{
			if (is_null(self::$instance))
			{
			  self::$instance = new self();
			}
			return self::$instance;
		}
		*/
		
		/**
		* Récupérer la liste des champs à insérer sous forme de
		*
		* @param: array	$infos les champs et les valeurs à insérer en base
		*/
		public static function getTrackParams()
		{
			$instance = new self();
			$tmp = array_diff(array_keys($instance->_champs), self::$skipFields);
			$out = array();
			foreach($tmp as $k){
				$out[] = "'$k'=>''";
			}
			return 'array('.implode(',', $out).')';
		}
		
		
		/**
		* Tracking d'une requete
		*
		* Tracking global
		*
		* @param: array	$infos Les champs et les valeurs à insérer en base (sont obligatoires)
		* @return 	type	description
		*/
		public static function track($infos = array(), $addGlobals=true)
		{
			$instance = new self();
			
			$instance->site = SESSION_PREFIX;
			$instance->version = VERSION;
			
			if( self::$sharedInfos !== null ){
				throw new Exception( __CLASS__ . '::' . __FUNCTION__ . '() ~ La method track ne peut être appelé qu\'une fois. [En théorie en début de script]');
			}
			
			# On renseigne chaque champs de la table s'ils sont présents dans les infos
			# Sauf les infos GET et POST
			foreach( array_diff(array_keys($instance->_champs), self::$skipFields)  as $k){
				// On empeche la surchage de certains champs
				if(in_array($k, self::$skipFields)){
					continue;
				}
				if(isset($infos[$k])){
					$instance->$k = $infos[$k];
				}
			}
			
			# URL DE LA REQUETE
			if((!isset($instance->url) || empty($instance->url))){
				if( isset($_SERVER['REQUEST_URI']) ){
					$instance->url = preg_replace('#/?\?.+$#','',$_SERVER['REQUEST_URI']);
				}
			}
			
			# Filtre, on ne comptabilise notamment les assets (ex: le tableau js marques_modeles)
			if( preg_match('#^/?javascript#',$instance->url) ){
				return true;
			}
			
			
			
			# PROTOCOL DE LA REQUETE
			if( (!isset($instance->protocol) || empty($instance->protocol))){
				if( isset($_SERVER['SERVER_PROTOCOL']) ){
					$instance->protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https' : 'http';
				}
			}

			# DOMAINE DE LA REQUETE
			if( (!isset($instance->domain) || empty($instance->domain))){
				if( isset($_SERVER['HTTP_HOST']) ){
					$instance->domain = $_SERVER['HTTP_HOST'];
				}
			}

			# Si demandé, ajout des données GET et POST
			$instance->str_get = $addGlobals && !empty($_GET) ? serialize($_GET) : '';
			$instance->str_post = $addGlobals && !empty($_POST) ? serialize($_POST) : '';
			$instance->session_id = isset($_SESSION[SESSION_PREFIX]) && isset($_SESSION[SESSION_PREFIX]['session_id']) ? $_SESSION[SESSION_PREFIX]['session_id']: '';

			$instance->save();

			self::$sharedInfos = get_object_vars($instance);
			
			return true;
		}
		
	}
?>