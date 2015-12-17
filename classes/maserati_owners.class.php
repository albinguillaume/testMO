<?php
	class maserati_owners extends mysql {

		var $id; var $pays; var $langue; var $civilite; var $nom; var $prenom; var $num_tridente; var $email; var $modele; var $immat; var $date_achat; var $date_achat_jour; var $date_achat_mois; var $date_achat_annee; var $sport; var $style_musical; var $artiste; var $autre; var $passion; var $adresse1; var $adresse2; var $code_postal; var $ville; var $telephone; var $optin; var $optin_sms; var $optin_courrier; var $prospection; var $campaignid; var $session_id; var $hash; var $version; var $get_var; var $created; var $modified; var $deleted;
		var $_table='maserati_owners';
		var $_primary_key='id';
		var $_champs=array('id'=>'int', 'pays'=>'varchar', 'langue'=>'varchar', 'civilite'=>'varchar', 'nom'=>'varchar', 'prenom'=>'varchar', 'num_tridente'=>'varchar', 'email'=>'varchar', 'modele'=>'varchar', 'immat'=>'varchar', 'date_achat'=>'date', 'date_achat_jour'=>'int', 'date_achat_mois'=>'int', 'date_achat_annee'=>'int', 'sport'=>'varchar', 'style_musical'=>'varchar', 'artiste'=>'varchar', 'autre'=>'varchar', 'passion'=>'varchar', 'adresse1'=>'varchar', 'adresse2'=>'varchar', 'code_postal'=>'varchar', 'ville'=>'varchar', 'telephone'=>'varchar', 'optin'=>'varchar', 'optin_sms'=>'varchar', 'optin_courrier'=>'varchar', 'prospection'=>'varchar', 'campaignid'=>'varchar', 'session_id'=>'varchar', 'hash'=>'varchar', 'version'=>'varchar', 'get_var'=>'text', 'created'=>'datetime', 'modified'=>'datetime', 'deleted'=>'datetime');
		var $_null = array('date_achat', 'date_achat_jour', 'date_achat_mois', 'date_achat_annee', 'modified', 'deleted');
	}
?>