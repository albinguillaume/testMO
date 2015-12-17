<?php
	class stats_sessions extends mysql {
		
		var $id; var $site; var $version; var $hash; var $campaignid; var $utm_source; var $utm_medium; var $utm_term; var $utm_content; var $utm_campaign; var $ip; var $session_id; var $uniqid; var $created; var $modified; var $deleted;
		var $_table='stats_sessions';
		var $_primary_key='id';
		var $_champs=array('id'=>'int', 'site'=>'varchar', 'version'=>'varchar', 'hash'=>'varchar', 'campaignid'=>'varchar', 'utm_source'=>'varchar', 'utm_medium'=>'varchar', 'utm_term'=>'varchar', 'utm_content'=>'varchar', 'utm_campaign'=>'varchar', 'ip'=>'varchar', 'session_id'=>'varchar', 'uniqid'=>'varchar', 'created'=>'datetime', 'modified'=>'datetime', 'deleted'=>'datetime');
		
		function getSession() {
			
			$o = new stats_sessions();
			$o->site = SESSION_PREFIX;
			$o->version = VERSION;
			$o->session_id = session_id();
			$o->uniqid = uniqid();
			$o->hash = $o->session_id.'_'.$o->uniqid;
			
			$o->campaignid = (!empty($_SESSION[SESSION_PREFIX]['campaignid']))?$_SESSION[SESSION_PREFIX]['campaignid']:'';
			
			$o->utm_source = (!empty($_SESSION[SESSION_PREFIX]['utm']['source']))?$_SESSION[SESSION_PREFIX]['utm']['source']:'';
			$o->utm_medium = (!empty($_SESSION[SESSION_PREFIX]['utm']['medium']))?$_SESSION[SESSION_PREFIX]['utm']['medium']:'';
			$o->utm_term = (!empty($_SESSION[SESSION_PREFIX]['utm']['term']))?$_SESSION[SESSION_PREFIX]['utm']['term']:'';
			$o->utm_content = (!empty($_SESSION[SESSION_PREFIX]['utm']['content']))?$_SESSION[SESSION_PREFIX]['utm']['content']:'';
			$o->utm_campaign = (!empty($_SESSION[SESSION_PREFIX]['utm']['campaign']))?$_SESSION[SESSION_PREFIX]['utm']['campaign']:'';
			$o->ip = $_SERVER['REMOTE_ADDR'];
			$o->save();
			return $o->id;
		}
	}
?>