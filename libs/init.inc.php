<?php

	date_default_timezone_set( 'Europe/Paris');
	setlocale (LC_TIME, 'fr_FR.utf8','fra');
	define('DH_VIDE','0000-00-00 00:00:00');
	define('DATE_VIDE','0000-00-00');
	define('DH_NOW_TIMESTAMP', mktime());
	define('DH_NOW', date( 'Y-m-d H:i:s', DH_NOW_TIMESTAMP));
	define('DATE_NOW', date( 'Y-m-d', DH_NOW_TIMESTAMP));

	session_start();
	$isCountrySel = false;
	$isLanguageSel = false;
	// recuperation de la langue
	if (isset($_GET['langue']) && in_array(strtolower($_GET['langue']), array('fr', 'en', 'es', 'pt', 'nl'))) {
		define( 'LANGUE', strtolower($_GET['langue']));
		$isLanguageSel = true;
		//$_SESSION[SESSION_PREFIX]['LNG'] = $_GET['lng'];
	}/* elseif (isset($_SESSION[SESSION_PREFIX]['LNG'])) {
		define( 'LNG', $_SESSION[SESSION_PREFIX]['LNG']);
	}*/ else {
		/*$_d = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']); 
		$_l = strtolower(substr(trim($_d[0]),0,2));
		if (isset($_l) && in_array($_l, array('it','fr','de'))) {
			define( 'LNG', $_l);
			$_SESSION[SESSION_PREFIX]['LNG'] = $_l;			
		} else {
			define( 'LNG', 'de');
			$_SESSION[SESSION_PREFIX]['LNG'] = 'de';
		}*/
		define( 'LANGUE', 'en');
	}
	page::set_langue( LANGUE);
	if (isset($_GET['pays']) && in_array(strtolower($_GET['pays']), array('fr', 'lu', 'mc', 'be', 'es', 'pt'))) {
		define( 'PAYS', strtolower($_GET['pays']));
		$isCountrySel = true;
	} else {
		define( 'PAYS', strtolower($_GET['pays']));
	}
	page::set_pays(PAYS);
	
	// Récupération de la config
	conf::load_file(INI.'config.ini.php');
	if( !empty_const('CONFIG_FILE') && is_file(CONFIG_FILE) ) {
		conf::load_file(CONFIG_FILE);
	} elseif(!empty_const('MODULE') && is_file(INI.MODULE.'.ini.php')) {
		conf::load_file(INI.MODULE.'.ini.php');
	}
	if( !empty_const('PAYS') && is_file(INI.'config.'.PAYS.'.ini.php') ) {
		conf::load_file(INI.'config.'.PAYS.'.ini.php');
	}
	
	if(($val = conf::get('GLOBAL', 'config'))!==false) define('CONFIG', $val);
	else define('CONFIG', 'TEST');
	if(($val = conf::get('GLOBAL', 'config_mail'))!==false) define('CONFIG_MAIL', $val);
	else define('CONFIG_MAIL', 'TEST');
	if(($val = conf::get('GLOBAL', 'config_email'))!==false) define('CONFIG_EMAIL', $val);
	else define('CONFIG_EMAIL', 'TEST');
	if(($val = conf::get('GLOBAL', 'site_name'))!==false) define('PROJECT', $val);
	else define('PROJECT', '');
	if(($val = conf::get('GLOBAL', 'site_name'))!==false) define('SESSION_PREFIX', $val);
	else define('SESSION_PREFIX', '');
	if(($val = conf::get('GLOBAL', 'title_page'))!==false) define('TITLE', $val);
	else define('TITLE', '');
	if(($val = conf::get('GLOBAL', 'analytics_id'))!==false) page::set_analytics( $val);
	else page::set_analytics( '');
	
	

	// Gestion des TAG
	if( !empty( $_GET['tag']) ) $_SESSION[SESSION_PREFIX]['TAG'] = $_GET['tag'];
	if( !empty( $_GET['campaignid']) ) $_SESSION[SESSION_PREFIX]['campaignid'] = $_GET['campaignid'];
	if( empty( $_SESSION[SESSION_PREFIX]['utm']) ) $_SESSION[SESSION_PREFIX]['utm'] = array();
	if( !empty( $_GET['utm_source']) ) $_SESSION[SESSION_PREFIX]['utm']['source'] = $_GET['utm_source'];
	if( !empty( $_GET['utm_medium']) ) $_SESSION[SESSION_PREFIX]['utm']['medium'] = $_GET['utm_medium'];
	if( !empty( $_GET['utm_term']) ) $_SESSION[SESSION_PREFIX]['utm']['term'] = $_GET['utm_term'];
	if( !empty( $_GET['utm_content']) ) $_SESSION[SESSION_PREFIX]['utm']['content'] = $_GET['utm_content'];
	if( !empty( $_GET['utm_campaign']) ) $_SESSION[SESSION_PREFIX]['utm']['campaign'] = $_GET['utm_campaign'];
	
	// Affichage de toutes les erreurs quand CONFIG=TEST
	if( CONFIG == 'TEST' ) {
		//error_reporting(E_ALL);
		ini_set( 'display_errors', true);
	}
	
	if(($val = conf::get('BDD', 'bdd'))!==false) define('CON_BDD', $val);
	else define( 'CON_BDD', '');
	
	if(($val = conf::get('EMAIL', 'EMAILING'))!==false) define('EMAILING', CHEMIN.$val.DIRECTORY_SEPARATOR);
	else define( 'EMAILING', 'emailing'.DIRECTORY_SEPARATOR);
	define( 'EMAILING_DIR', APP_DIR.EMAILING);
	define( 'EMAILING_URL', APP_URL.EMAILING);
	
	if(($val = conf::get('EMAIL', 'EMAIL_TEST'))!==false) define('EMAIL_TEST', $val);
	else define( 'EMAIL_TEST', 'webmaster@lesnetworkeurs.com');
	if(($val = conf::get('EMAIL', 'EXP_NAME'))!==false) define('EXP_NAME', $val);
	else define( 'EXP_NAME', '');
	if(($val = conf::get('EMAIL', 'EXP_EMAIL'))!==false) define('EXP_EMAIL', $val);
	else define( 'EXP_EMAIL', 'webmaster@lesnetworkeurs.com');
	if(($val = conf::get('EMAIL', 'EXP_EMAIL_REPLY'))!==false) define('EXP_EMAIL_REPLY', $val);
	else define( 'EXP_EMAIL_REPLY', EXP_EMAIL);
	if(($val = conf::get('EMAIL', 'ADMINISTRATOR'))!==false) define('ADMINISTRATOR', $val);
	else define( 'ADMINISTRATOR', 'webmaster@lesnetworkeurs.com');
	if(($val = conf::get('EMAIL', 'EMAILING_CHARSET'))!==false) define('EMAILING_CHARSET', $val);
	else define( 'EMAILING_CHARSET', 'utf-8');
	
	define( 'SPACER', THEMES.'images/spacer.gif');

	require_once LIBS.'connexion.inc.php';
	
	if( empty( $_SESSION[SESSION_PREFIX]['session_id']) ) {
		$_SESSION[SESSION_PREFIX]['session_id'] = stats_sessions::getSession();
	}
	
	$version = 'PROD';
	if( strpos( $_SERVER['HTTP_HOST'], 'lesnetworkeurs') !==false) $version = 'MAQUETTE';
	if( php_sapi_name() == 'cli' ) {
		if( strpos( APP_DIR, 'var/www/html/maserati-minisites/www-') !== false ) $version = 'MAQUETTE';
	}
	define( 'VERSION', $version);
	
	//Inclusion d'un fichier d'inclusion spécifique au module sélectionné => /libs/
	if(!empty_const('MODULE') && is_file(LIBS.MODULE.'.inc.php')) {
		include LIBS.MODULE.'.inc.php';
	}
	
?>