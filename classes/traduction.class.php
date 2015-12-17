<?php
	class traduction {
		
		private $traduction = array();
		
		function __construct() {}
		
		public function load($file_path) {
			$this->traduction = array_merge($this->traduction, parse_ini($file_path));
		}
		
		public function get($param1, $param2=null, $param3=null) {
			$path=$param1;
			for($i=2;$i<=4;$i++) {
				if(!empty(${'param'.$i})) $path.='|'.${'param'.$i};
			}
			if( !empty( $path) ) {
				$tab_path = split( "[|.]", $path);
				
				if( count( $tab_path) == 1 ) {
					if( !empty( $this->traduction[$tab_path[0]]) )
						return $this->traduction[$tab_path[0]];
				} elseif( count( $tab_path) == 2 ) {
					if( !empty( $this->traduction[$tab_path[0]][$tab_path[1]]) )
						return $this->traduction[$tab_path[0]][$tab_path[1]];
				} elseif( count( $tab_path) == 3 ) {
					if( !empty( $this->traduction[$tab_path[0]][$tab_path[1]][$tab_path[2]]) )
						return $this->traduction[$tab_path[0]][$tab_path[1]][$tab_path[2]];
				} elseif( count( $tab_path) == 4 ) {
					if( !empty( $this->traduction[$tab_path[0]][$tab_path[1]][$tab_path[2]][$tab_path[3]]) )
						return $this->traduction[$tab_path[0]][$tab_path[1]][$tab_path[2]][$tab_path[3]];
				}
			}
			return '';
		}
		
		public function set($value, $param1, $param2=null, $param3=null) {
			$path=$param1;
			for($i=2;$i<=4;$i++) {
				if(!empty(${'param'.$i})) $path.='|'.${'param'.$i};
			}
			if( !empty( $path) ) {
				$tab_path = split( "[|.]", $path);
				
				if( count( $tab_path) == 1 ) {
					if( !empty( $this->traduction[$tab_path[0]]) )
						$this->traduction[$tab_path[0]] = $value;
				} elseif( count( $tab_path) == 2 ) {
					if( !empty( $this->traduction[$tab_path[0]][$tab_path[1]]) )
						$this->traduction[$tab_path[0]][$tab_path[1]] = $value;
				} elseif( count( $tab_path) == 3 ) {
					if( !empty( $this->traduction[$tab_path[0]][$tab_path[1]][$tab_path[2]]) )
						$this->traduction[$tab_path[0]][$tab_path[1]][$tab_path[2]] = $value;
				} elseif( count( $tab_path) == 4 ) {
					if( !empty( $this->traduction[$tab_path[0]][$tab_path[1]][$tab_path[2]][$tab_path[3]]) )
						$this->traduction[$tab_path[0]][$tab_path[1]][$tab_path[2]][$tab_path[3]] = $value;
				}
			}
			return '';
		}
		
		/* //Renvoie le tableau de correspondance des valeurs trouvées dans $tab, traduites
		public function get_array($tab, $path) {
			foreach($tab as $key=>$value) {
				$tab[$key] = $this->get($path.'|'.$value);
			}
			return $tab;
		}*/
		
	}
?>