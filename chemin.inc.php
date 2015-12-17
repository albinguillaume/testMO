<?php
//echo time();
	define( 'CACHE_CSS_KEY', '1450257545');
	define( 'CACHE_JS_KEY', '1450257545');
	//error_reporting(E_ALL ^ E_NOTICE);
	if(!defined('CHEMIN')) define('CHEMIN', '');
	define( 'APP_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);
	$folder_url = trim(substr(dirname($_SERVER['SCRIPT_FILENAME']),strlen($_SERVER['DOCUMENT_ROOT']),strlen(dirname($_SERVER['SCRIPT_FILENAME']))),'/');
	//if( !empty( $folder_url) ) $folder_url = str_replace( 'tml/', '', $folder_url);
	//define( 'APP_URL', str_replace( $_SERVER['DOCUMENT_ROOT'], 'http://'.$_SERVER['HTTP_HOST'], APP_DIR));
	//define( 'APP_URL', 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).DIRECTORY_SEPARATOR);
	
	//$documentroot = $_SERVER['DOCUMENT_ROOT'];
	//$folder_url = trim(substr(APP_DIR,strlen($documentroot),strlen(APP_DIR)),'/');
	$protocol='http';
	if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
		$protocol='https';
	} elseif( !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
		$protocol='https';
	}
	define( 'PROTOCOL_URL', $protocol);
	
	//$folder_app_url = str_replace( 'backoffice', '', $folder_url);
	$folder_app_url = $folder_url;
	if( substr( $folder_app_url, -1, 1) == DIRECTORY_SEPARATOR ) $folder_app_url = substr( $folder_app_url, 0, -1);
	
	//define( 'APP_URL', PROTOCOL_URL.'://'.$_SERVER['HTTP_HOST'].((!empty($folder_app_url)&&strpos( $folder_app_url, 'backoffice')==false)?DIRECTORY_SEPARATOR.$folder_app_url:'').DIRECTORY_SEPARATOR);
	define( 'APP_URL', PROTOCOL_URL.'://'.$_SERVER['HTTP_HOST'].DIRECTORY_SEPARATOR);
	define( 'URL_LINK', PROTOCOL_URL.'://'.$_SERVER['HTTP_HOST'].(!empty($folder_url)?DIRECTORY_SEPARATOR.$folder_url:'').DIRECTORY_SEPARATOR);
	define( 'CLASSES', APP_DIR.'classes'.DIRECTORY_SEPARATOR);
	define( 'LIBS', APP_DIR.'libs'.DIRECTORY_SEPARATOR);
	define( 'ELEMENTS', APP_DIR.'elements'.DIRECTORY_SEPARATOR);
	define( 'LANGUAGES', APP_DIR.'languages'.DIRECTORY_SEPARATOR);
	define( 'INI', APP_DIR.'config'.DIRECTORY_SEPARATOR);
	define( 'THEMES', APP_URL.'themes'.DIRECTORY_SEPARATOR);
	define( 'IMAGES', THEMES.'images'.DIRECTORY_SEPARATOR);
	define( 'JAVASCRIPT', APP_URL.'javascript'.DIRECTORY_SEPARATOR);
	define( 'LOGS', APP_DIR.'logs'.DIRECTORY_SEPARATOR);
	
	
	require_once LIBS.'library.inc.php';
	require_once LIBS.'securite.inc.php';
	require_once LIBS.'functions.inc.php';
	
	include LIBS.'init.inc.php';
	
?>