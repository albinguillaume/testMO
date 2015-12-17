<?php
	include 'chemin.inc.php';
	
	$countries = conf::get( 'COUNTRIES');
	$languages = conf::get( 'LANGUAGES');
	$modeles = conf::get( 'MODELES');
	$date_achat_jours = array();
	for($i = 1 ; $i <= 31 ; $i++ ) $date_achat_jours[$i] = str_pad($i, 2, '0', STR_PAD_LEFT);
	$date_achat_mois = array();
	for($i = 1 ; $i <= 12 ; $i++ ) $date_achat_mois[$i] = ucfirst( page::trad('MOIS', $i));
	$date_achat_annees = array();
	for($i = intval(date( 'Y')) ; $i >= 1950 ; $i-- ) $date_achat_annees[$i] = str_pad($i, 2, '0', STR_PAD_LEFT);
	$step = 'home';
	if( $isCountrySel && $isLanguageSel ) $step = 'coordonnees';
	$sports = page::trad('SPORTS');
	$style_musical = page::trad('STYLE_MUSICAL');
	$artistes = page::trad('ARTISTES');
	$passions = page::trad('PASSIONS');
	
	$tab_labels = array(
		'country'=>'Please choose your country',
		'language'=>'Select your language', 
		'nom'=>page::trad('COORDONNEES', 'label_nom'), 
		'prenom'=>page::trad('COORDONNEES', 'label_prenom'), 
		'num_tridente'=>page::trad('COORDONNEES', 'label_num_tridente'), 
		'email'=>page::trad('COORDONNEES', 'label_email'), 
		'modele'=>page::trad('INFORMATIONS', 'label_modele'), 
		'immat'=>page::trad('INFORMATIONS', 'label_immat'), 
		'date_achat_jour'=>page::trad('INFORMATIONS', 'label_date_achat_jour'), 
		'date_achat_mois'=>page::trad('INFORMATIONS', 'label_date_achat_mois'), 
		'date_achat_annee'=>page::trad('INFORMATIONS', 'label_date_achat_annee'), 
		'sport'=>page::trad('INFORMATIONS', 'label_sport'), 
		'style_musical'=>page::trad('INFORMATIONS', 'label_style_musical'), 
		'artiste'=>page::trad('INFORMATIONS', 'label_artiste'), 
		'autre'=>page::trad('INFORMATIONS', 'label_autre'), 
		'adresse1'=>page::trad('INFORMATIONS', 'label_adresse1'), 
		'adresse2'=>page::trad('INFORMATIONS', 'label_adresse2'), 
		'code_postal'=>page::trad('INFORMATIONS', 'label_code_postal'), 
		'ville'=>page::trad('INFORMATIONS', 'label_ville'), 
		'telephone'=>page::trad('INFORMATIONS', 'label_telephone'), 
	);
	
	$oui_non = array(
		'oui'=>page::trad('INFORMATIONS', 'label_oui'), 
		'non'=>page::trad('INFORMATIONS', 'label_non')
	);
	
	$field = array(
		'country'=>'select',
		'language'=>'select',
		'nom'=>'text',
		'prenom'=>'text',
		'num_tridente'=>'text',
		'email'=>'text', 
		'modele'=>'select', 
		'immat'=>'text', 
		'date_achat_jour'=>'select', 
		'date_achat_mois'=>'select', 
		'date_achat_annee'=>'select', 
		'sport'=>'select', 
		'style_musical'=>'select', 
		'artiste'=>'text', 
		'autre'=>'text', 
		'passion'=>'radio',
		'adresse1'=>'test', 
		'adresse2'=>'test', 
		'code_postal'=>'test', 
		'ville'=>'test', 
		'telephone'=>'test',
		'optin'=>'radio',
		'optin_sms'=>'radio',
		'optin_courrier'=>'radio'
	);
	
	$tab_erreur = array();
	$value = array();
	foreach( $field as $k => $t ) {
		$value[$k] = '';
		$tab_erreur[$k] = false;
	}
	
	$stat_action = '';
	if( !empty( $_POST['form']) && !empty( $_POST['form']['action']) && $_POST['form']['action'] == 'form_pays_langue' ) {
		$step = 'home';
		$value['country'] = !empty( $_POST['form']['country'])?$_POST['form']['country']:'';
		$value['language'] = !empty( $_POST['form']['language'])?$_POST['form']['language']:'';
		
		if( empty($value['country']) || !array_key_exists($value['country'], $countries) ) { $tab_erreur['country'] = true; }
		if( empty($value['language']) || !array_key_exists($value['language'], $languages) ) { $tab_erreur['language'] = true; }
		
		if( !in_array( 'true', $tab_erreur) ) {
			header( "Location: ".APP_URL.strtoupper($value['country'])."_".strtoupper($value['language']).DIRECTORY_SEPARATOR);
			die();
		}
		
	} elseif( !empty( $_POST['form']) && !empty( $_POST['form']['action']) && $_POST['form']['action'] == 'form_coord' ) {
		$step = 'coordonnees';
		$uid = !empty( $_POST['form']['uid'])?$_POST['form']['uid']:'';
		$value['nom'] = !empty( $_POST['form']['nom'])?$_POST['form']['nom']:'';
		$value['prenom'] = !empty( $_POST['form']['prenom'])?$_POST['form']['prenom']:'';
		$value['num_tridente'] = !empty( $_POST['form']['num_tridente'])?$_POST['form']['num_tridente']:'';
		$value['email'] = !empty( $_POST['form']['email'])?$_POST['form']['email']:'';
		$value['prospection'] = !empty( $_POST['form']['prospection'])?$_POST['form']['prospection'][0]:'';
		
		if( empty($value['nom']) ) { $tab_erreur['nom'] = true; }
		if( empty($value['prenom']) ) { $tab_erreur['prenom'] = true; }
		if( empty($value['num_tridente']) || !verif_champ( $value['num_tridente'], 'num_tridente') || !maserati_owners_code_tridente::isExist($value['num_tridente']) ) { $tab_erreur['num_tridente'] = true; }
		if( empty($value['email']) || !verif_champ( $value['email'], 'email') ) { $tab_erreur['email'] = true; }
		
		if( !in_array( 'true', $tab_erreur) ) {
			
			$c = new maserati_owners();
			$c->hash = $uid;
			$nbc = $c->find();
			if( $nbc > 0 ) {
				$c->fetch();
			} else {
				$c->pays = PAYS;
				$c->langue = LANGUE;
				$c->civilite = '';
				$c->nom = $value['nom'];
				$c->prenom = $value['prenom'];
				$c->num_tridente = $value['num_tridente'];
				$c->email = $value['email'];
				$c->prospection = $value['prospection'];
				$c->version = VERSION;
				$c->campaignid = (!empty($_SESSION[SESSION_PREFIX]['campaignid']))?$_SESSION[SESSION_PREFIX]['campaignid']:'';
				$c->session_id = !empty( $_SESSION[SESSION_PREFIX]['session_id'])?$_SESSION[SESSION_PREFIX]['session_id']:0;
				$c->get_var = !empty( $_SESSION[SESSION_PREFIX]['get_var'])?$_SESSION[SESSION_PREFIX]['get_var']:'';
				$c->save();
			}
			$step = 'informations';
		}
		
	} elseif( !empty( $_POST['form']) && !empty( $_POST['form']['action']) && $_POST['form']['action'] == 'form_info' ) {
		$step = 'informations';
		$value['idc'] = !empty( $_POST['form']['idc'])&&intval($_POST['form']['idc'])>0?intval($_POST['form']['idc']):0;
		$value['modele'] = !empty( $_POST['form']['modele'])?$_POST['form']['modele']:'';
		$value['immat'] = !empty( $_POST['form']['immat'])?$_POST['form']['immat']:'';
		$value['date_achat_jour'] = !empty( $_POST['form']['date_achat_jour'])?$_POST['form']['date_achat_jour']:'';
		$value['date_achat_mois'] = !empty( $_POST['form']['date_achat_mois'])?$_POST['form']['date_achat_mois']:'';
		$value['date_achat_annee'] = !empty( $_POST['form']['date_achat_annee'])?$_POST['form']['date_achat_annee']:'';
		$value['sport'] = !empty( $_POST['form']['sport'])?$_POST['form']['sport']:array();
		$value['style_musical'] = !empty( $_POST['form']['style_musical'])?$_POST['form']['style_musical']:array();
		$value['artiste'] = !empty( $_POST['form']['artiste'])?$_POST['form']['artiste']:'';
		$value['autre'] = !empty( $_POST['form']['autre'])?$_POST['form']['autre']:'';
		$value['passion'] = !empty( $_POST['form']['passion'])?$_POST['form']['passion']:'';
		$value['adresse1'] = !empty( $_POST['form']['adresse1'])?$_POST['form']['adresse1']:'';
		$value['adresse2'] = !empty( $_POST['form']['adresse2'])?$_POST['form']['adresse2']:'';
		$value['code_postal'] = !empty( $_POST['form']['code_postal'])?$_POST['form']['code_postal']:'';
		$value['ville'] = !empty( $_POST['form']['ville'])?$_POST['form']['ville']:'';
		$value['telephone'] = !empty( $_POST['form']['telephone'])?$_POST['form']['telephone']:'';
		$value['optin'] = !empty( $_POST['form']['optin'])?$_POST['form']['optin']:'';
		$value['optin_sms'] = !empty( $_POST['form']['optin_sms'])?$_POST['form']['optin_sms']:'';
		$value['optin_courrier'] = !empty( $_POST['form']['optin_courrier'])?$_POST['form']['optin_courrier']:'';
		$value['prospection'] = !empty( $_POST['form']['prospection'])?$_POST['form']['prospection'][0]:'';
		
		if( $value['idc'] > 0 ) $c = new maserati_owners($value['idc']);
		if( empty( $c->created) ) { $tab_erreur['uid'] = true; }
		if( empty($value['modele']) ) { $tab_erreur['modele'] = true; }
		if( empty($value['immat']) ) { $tab_erreur['immat'] = true; }
		if( empty($value['date_achat_jour']) || !array_key_exists($value['date_achat_jour'], $date_achat_jours) ) { $tab_erreur['date_achat_jour'] = true; }
		if( empty($value['date_achat_mois']) || !array_key_exists($value['date_achat_mois'], $date_achat_mois) ) { $tab_erreur['date_achat_mois'] = true; }
		if( empty($value['date_achat_annee']) || !array_key_exists($value['date_achat_annee'], $date_achat_annees) ) { $tab_erreur['date_achat_annee'] = true; }
		/*if( empty($value['sport']) ) { $tab_erreur['sport'] = true; }
		if( empty($value['style_musical']) ) { $tab_erreur['style_musical'] = true; }
		if( empty($value['artiste']) ) { $tab_erreur['artiste'] = true; }
		if( empty($value['autre']) ) { $tab_erreur['autre'] = true; }*/
		if( empty($value['passion']) ) { $tab_erreur['passion'] = true; }
		if( empty($value['adresse1']) ) { $tab_erreur['adresse1'] = true; }
		if( empty($value['code_postal']) || !verif_champ( $value['code_postal'], 'cp_'.LANGUE) ) { $tab_erreur['code_postal'] = true; }
		if( empty($value['ville']) ) { $tab_erreur['ville'] = true; }
		if( empty($value['telephone']) || !verif_champ( $value['telephone'], 'telephone_'.LANGUE) ) { $tab_erreur['telephone'] = true; }
		if( empty($value['optin']) ) { $tab_erreur['optin'] = true; }
		if( empty($value['optin_sms']) ) { $tab_erreur['optin_sms'] = true; }
		if( empty($value['optin_courrier']) ) { $tab_erreur['optin_courrier'] = true; }
		//pr( $_POST);
		if( !in_array( 'true', $tab_erreur) ) {
			$c->modele = $value['modele'];
			$c->immat = $value['immat'];
			$c->date_achat = str_pad( $value['date_achat_annee'], '4', '0', STR_PAD_LEFT).'-'.str_pad( $value['date_achat_mois'], '2', '0', STR_PAD_LEFT).'-'.str_pad( $value['date_achat_jour'], '2', '0', STR_PAD_LEFT);
			$c->date_achat_jour = $value['date_achat_jour'];
			$c->date_achat_mois = $value['date_achat_mois'];
			$c->date_achat_annee = $value['date_achat_annee'];
			$c->sport = implode( ';', $value['sport']);
			$c->style_musical = implode( ';', $value['style_musical']);
			$c->artiste = $value['artiste'];
			$c->autre = $value['autre'];
			$c->passion = $value['passion'];
			$c->adresse1 = $value['adresse1'];
			$c->adresse2 = $value['adresse2'];
			$c->code_postal = $value['code_postal'];
			$c->ville = $value['ville'];
			$c->telephone = $value['telephone'];
			$c->optin = $value['optin'];
			$c->optin_sms = $value['optin_sms'];
			$c->optin_courrier = $value['optin_courrier'];
			$c->prospection = $value['prospection'];
			$c->save();
			$step = 'confirmation';
		}
	}
	
	$site_maserati_url = '';
	$fb_maserati_url = '';
	$site_maserati_url_confirm = '';
	$site_maserati_url_footer = '';
	$site_maserati_url_ultrashort = '';
	
	if( in_array( PAYS, array( 'fr', 'mc') ) ) {
		$site_maserati_url = 'http://www.maserati.fr/maserati/fr/fr/index.html';
		$site_maserati_url_confirm = 'http://www.maserati.fr/maserati/fr/fr/index.html';
		$site_maserati_url_footer = 'www.maserati.fr';
		$site_maserati_url_ultrashort = 'Maserati.fr';
		$fb_maserati_url = 'https://www.facebook.com/MaseratiFrance';
	} elseif( in_array( PAYS, array( 'es') ) ) {
		$site_maserati_url = 'http://www.maserati.es/maserati/es/es/index.html';
		$site_maserati_url_confirm = 'http://www.maserati.es/maserati/es/es/index.html';
		$site_maserati_url_footer = 'www.maserati.es';
		$site_maserati_url_ultrashort = 'Maserati.es';
		$fb_maserati_url = 'https://www.facebook.com/maserati.espana';
	} elseif( in_array( PAYS, array( 'pt') ) ) {
		$site_maserati_url = 'http://www.maserati.com/maserati/en/en/index.html';
		$site_maserati_url_confirm = 'http://www.maserati.com/maserati/en/en/index.html';
		$site_maserati_url_footer = 'www.maserati.com';
		$site_maserati_url_ultrashort = 'Maserati.com';
		$fb_maserati_url = 'https://www.facebook.com/Maserati';
	} elseif( in_array( PAYS, array( 'be') ) ) {
		$site_maserati_url = 'http://www.maserati.be/maserati/be/fr/index.html';
		$site_maserati_url_confirm = 'http://www.maserati.be/maserati/be/fr/index.html';
		$site_maserati_url_footer = 'www.maserati.be';
		$site_maserati_url_ultrashort = 'Maserati.be';
		$fb_maserati_url = 'https://www.facebook.com/MaseratiBeLux/';
		if( LANGUE == 'nl' ) {
			$site_maserati_url = 'http://www.maserati.be/maserati/be/nl/index.html';
			$site_maserati_url_confirm = 'http://www.maserati.be/maserati/be/nl/index.html';
			$site_maserati_url_footer = 'www.maserati.be';
			$site_maserati_url_ultrashort = 'Maserati.be';
			$fb_maserati_url = 'https://www.facebook.com/MaseratiBeLux/';
		}
	} elseif( in_array( PAYS, array( 'lu') ) ) {
		$site_maserati_url = 'http://www.maserati.lu/maserati/lu/fr/index.html';
		$site_maserati_url_confirm = 'http://www.maserati.lu/maserati/lu/fr/index.html';
		$site_maserati_url_footer = 'www.maserati.lu';
		$site_maserati_url_ultrashort = 'Maserati.lu';
		$fb_maserati_url = 'https://www.facebook.com/MaseratiBeLux/';
		if( LANGUE == 'nl' ) {
			$site_maserati_url = 'http://www.maserati.be/maserati/be/nl/index.html';
			$site_maserati_url_confirm = 'http://www.maserati.be/maserati/be/nl/index.html';
			$site_maserati_url_footer = 'www.maserati.be';
			$site_maserati_url_ultrashort = 'Maserati.lu';
			$fb_maserati_url = 'https://www.facebook.com/MaseratiBeLux/';
		}
	}
	
	if( LANGUE == 'en' ) {
		$site_maserati_url = 'http://www.maserati.com';
		$site_maserati_url_confirm = 'http://www.maserati.com';
		$site_maserati_url_footer = 'www.maserati.com';
		$site_maserati_url_ultrashort = 'Maserati.com';
		$fb_maserati_url = 'https://www.facebook.com/Maserati';
	}
	
	
	include ELEMENTS.'_head.elt.php';
	include ELEMENTS.'_top.elt.php';
	
	include ELEMENTS.$step.'.elt.php';
	
	include ELEMENTS.'_bottom.elt.php';
	include ELEMENTS.'_foot.elt.php';
?>