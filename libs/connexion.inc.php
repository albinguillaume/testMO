<?php
	$bdd_params = conf::get('BDD');
	if(!empty($bdd_params)) {
		mysql_connect($bdd_params['server'], $bdd_params['user'], $bdd_params['pass'])
		or die('Erreur connexion SQL');
		mysql_query("SET CHARACTER SET 'utf8'")
		or die('Erreur connexion SQL');
		mysql_query("SET NAMES 'utf8'")
		or die('Erreur connexion SQL');
		if(!empty_const('CON_BDD')) {
			mysql_select_db(CON_BDD)
			or die('Erreur connexion SQL');
		}
	}
?>