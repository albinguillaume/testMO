<?php
	class conf {
		
		private $conf = array();
		
		protected static $_instance;
		private function __construct() {}
		private function __clone() {}
		static function instance(){if(is_null(self::$_instance)){self::$_instance=new self;}return self::$_instance;}
		
		function load_file($filename) {
			$o = self::instance();
			//$o->conf = array_merge($o->conf, parse_ini( $filename));
			$o->conf = multimerge($o->conf, parse_ini( $filename));
		}
		
		function get() {
			$o = self::instance();
			//Construction de l'instruction
			$instr = '$out = $o->conf';
			$args = func_get_args();
			foreach($args as $arg) {
				$instr .= '[\''.addslashes($arg).'\']';
			}
			$instr.=';';
			eval($instr);
			if(isset($out)) return $out;
			return false;
		}
		
	}
?>