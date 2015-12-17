<?php
	class maserati_owners_code_tridente extends mysql {

		var $id; var $type; var $code; var $created; var $deleted;
		var $_table='maserati_owners_code_tridente';
		var $_primary_key='id';
		var $_champs=array('id'=>'int', 'type'=>'varchar', 'code'=>'varchar', 'created'=>'datetime', 'deleted'=>'datetime');
		
		function isExist( $c) {
			$ct = new maserati_owners_code_tridente();
			$ct->code = $c;
			$nb = $ct->find();
			//echo $ct->_query;
			if( $nb > 0 ) {
				$ct->fetch();
				if( $ct->id > 0 ) return true;
			}
			return false;
		}
	}
?>