<?php
	class stats_pages_actions extends mysql {

		var $id; var $session_id; var $site; var $version; var $stat_all_id; var $entity_id; var $type; var $sous_type; var $tag; var $action; var $created;
		var $_table='stats_pages_actions';
		var $_primary_key='id';
		var $_champs=array('id'=>'int', 'session_id'=>'int', 'site'=>'varchar', 'version'=>'varchar', 'stat_all_id'=>'int', 'entity_id'=>'int', 'type'=>'varchar', 'sous_type'=>'varchar', 'tag'=>'varchar', 'action'=>'varchar', 'created'=>'datetime');
		
		
		
		
		/**
		* Tracking d'une requete
		*
		* Tracking global
		*
		* @param: array	$infos Les champs et les valeurs à insérer en base (sont obligatoires)
		* @return 	type	description
		*/
		public static function track($page = '', $infos = array())
		{
			$instance = new self();
			$tracker = stats_pages_all::$sharedInfos;
			
			if(  ! isset($tracker) || !is_array($tracker) || empty($tracker) ){
				throw new Exception( __CLASS__ . '::' . __FUNCTION__ . '() ~ Aucun tracker principal trouvé. [un appel à stats_pages_all::track() doit être fait en tete de site]');
				return;
			}
			
			$instance->stat_all_id = $tracker['id'];
			$instance->site = SESSION_PREFIX;
			$instance->version = VERSION;
			if( isset($tracker['session_id']) ){
				$instance->session_id = $tracker['session_id'];
			}
			
			if( !isset($tracker['url']) ){
				throw new Exception( __CLASS__ . '::' . __FUNCTION__ . '() ~ Le tracking d\'actions necessite l\'url du tracker principal.');
			}

			# Decoupage de l'url
			$chunks = explode('/', preg_replace('#^/|/$#','',$tracker['url'])); # On supprime les slashs de debut et de fin
			$len = count($chunks);
			
			//$instance->type = $chunks[0];
			
			$instance->type = $page;

			if(isset($infos) && isset($infos['entity_id']) ){ $instance->tag = $infos['entity_id']; }
			if(isset($infos) && isset($infos['sous_type']) ){ $instance->sous_type = $infos['sous_type']; }
			if(isset($infos) && isset($infos['tag']) ){ $instance->tag = $infos['tag']; }
			if(isset($infos) && isset($infos['action']) ){ $instance->tag = $infos['action']; }

			$instance->save();

			return true;
		}
		
	}
	
	
/**

	Quelques requetes
	----
	-- Dernière action pour chaque utilisateur. Triées de la plus recente à la plus ancienne
	----
		SELECT CONCAT_WS(' ', CONCAT_WS('','(', CAST(s.user_id AS CHAR) ,')') ,u.nom, u.prenom) identite, sa.url, s.stat_all_id, s.type, s.sous_type, s.tag, s.action, s.created
			FROM `stats_pages_actions` s
			INNER JOIN  (SELECT sub.user_id, MAX(sub.id) uniq FROM `stats_pages_actions` sub GROUP BY sub.user_id order by uniq DESC) sq ON sq.user_id = s.user_id AND sq.uniq = s.id
			LEFT JOIN users u on u.id = s.user_id
			LEFT JOIN stats_pages_all sa on sa.id = s.stat_all_id
		-- Exclure les anonymes
		WHERE u.id <> 0
		ORDER BY s.created DESC
	
	
*/

?>