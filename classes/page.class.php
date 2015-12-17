<?php
	class page {
	
		private $lng='fr';
		private $pays='fr';
		private $target;//Type d'affichage de page > popup_ajax, popup_iframe
		public $analytics_id='';
		private $css_files = array();
		private $js_files = array();
		private $trad;
		
		
		protected static $_instance;
		private function __construct() {
			//Récupération target
			if(!empty($_GET['target'])) {
				$this->target=$_GET['target'];
				unset($_GET['target']);
			} else $this->target='';
			
			//Module actuel
			if(defined('MODULE')) $this->module=MODULE;
			else $this->module='';
			
			//$this->load_trad();
		}
		private function __clone() {}
		static function instance(){if(is_null(self::$_instance)){self::$_instance=new self;}return self::$_instance;}
		
		public function get_page_infos() {
			$o = self::instance();
			$infos = array(
				'lng'=>$o->lng,
				'id_user'=>'',//TODO
				'project'=>(!empty_const('PROJECT')?PROJECT:''),
				'plateforme'=>(!empty_const('PLATEFORME')?PLATEFORME:''),
				'marque'=>(!empty_const('MARQUE')?MARQUE:''),
				'module'=>(!empty_const('MODULE')?MODULE:''),
				'trad'=>''
			);
			return $infos;
		}
		
		public function get_langue() {
			$o = self::instance();
			return $o->lng;
		}

		public function set_langue( $lng='') {
			$o=self::instance();
			if( !empty( $lng) ) $o->lng = $lng;
			$o->load_trad();
		}
		
		public function get_pays() {
			$o = self::instance();
			return $o->pays;
		}

		public function set_pays( $pays='') {
			$o=self::instance();
			if( !empty( $pays) ) $o->pays = $pays;
		}
	
		public function get_target() {
			$o=self::instance();
			return $o->target;
		}
	
		public function display_head() {
			$o=self::instance();
			if($o->target=='popup_ajax') return false;
			return true;
		}
	
		public static function get_css() {
			$cache_css = '';
			if( !empty_const( 'CACHE_CSS_KEY') ) $cache_css = '?'.CACHE_CSS_KEY;
			$o=self::instance();
			//Initialisation des styles > tout module, toute marque
			$out = '';
			//if( MODULE == 'backoffice' ) $out.='<link type="text/css" href="'.THEMES.'jquery-ui-1.10.3.custom.css" rel="stylesheet" />'."\n";
			//$out.='<link type="text/css" href="'.THEMES.'colorbox-custom.css" rel="stylesheet" />'."\n";
			//$out.='<link type="text/css" href="'.THEMES.'vendors.css'.$cache_css.'" rel="stylesheet" />'."\n";
			$out.='<link type="text/css" href="'.THEMES.'bootstrap/bootstrap.css'.$cache_css.'" rel="stylesheet" />'."\n";
			$out.='<link type="text/css" href="'.THEMES.'bootstrap/bootstrap-theme.css'.$cache_css.'" rel="stylesheet" />'."\n";
			//$out.='<link type="text/css" href="'.THEMES.'jquery.checkbox.css'.$cache_css.'" rel="stylesheet" />'."\n";
			$out.='<link type="text/css" href="'.THEMES.'jquery-ui.structure.min.css'.$cache_css.'" rel="stylesheet" />'."\n";
			$out.='<link type="text/css" href="'.THEMES.'bootstrap-multiselect.css'.$cache_css.'" rel="stylesheet" />'."\n";
			$out.='<link type="text/css" href="'.THEMES.'global.css'.$cache_css.'" rel="stylesheet" />'."\n";
			
			if( !empty($o->module) && is_file(CHEMIN.'themes/'.$o->module.'.css') ) {
				$out.='<link type="text/css" href="'.THEMES.$o->module.'.css'.$cache_css.'" rel="stylesheet" />'."\n";
			}
			//$out.='<link type="text/css" href="'.THEMES.'nyromodal.css" rel="stylesheet" />'."\n";
			
			if( !empty( $o->css_files ) ) {
				foreach( $o->css_files as $f ) {
					//if( file_exists( $f) ) {
						$out.='<link type="text/css" href="'.$f.''.$cache_css.'" rel="stylesheet" />'."\n";
					//}
				}
			}
			$out.='<link rel="shortcut icon" href="'.APP_URL.'favicon.ico" />';
			
			return $out;
		}

		public function add_css( $file) {
			$o=self::instance();
			if( is_array( $file) ) {
				foreach( $file as $f ) {
					if( !in_array( $f, $o->css_files) ) $o->css_files[] = $f;
				}
			} else {
				if( !in_array( $file, $o->css_files) ) $o->css_files[] = $file;
			}
		}

		public function add_js( $file) {
			$o=self::instance();
			if( is_array( $file) ) {
				foreach( $file as $f ) {
					if( !in_array( $f, $o->js_files) ) $o->js_files[] = $f;
				}
			} else {
				if( !in_array( $file, $o->js_files) ) $o->js_files[] = $file;
			}
		}
		
		public static function get_javascript() {
			$cache_js = '';
			if( !empty_const( 'CACHE_JS_KEY') ) $cache_js = '?'.CACHE_JS_KEY;
			$o=self::instance();
			$out = '';
			//$out.='<script src="http://maps.google.com/maps/api/js?sensor=true&amp;language=fr" type="text/javascript"></script>';
			$out.='<script type="text/javascript" src="'.JAVASCRIPT.'jquery-1.11.3.min.js'.$cache_js.'"></script>'."\n";
			$out.='<script type="text/javascript" src="'.JAVASCRIPT.'bootstrap/bootstrap.min.js'.$cache_js.'"></script>'."\n";
			$out.='<script type="text/javascript" src="'.JAVASCRIPT.'jquery-ui.js'.$cache_js.'"></script>'."\n";
			//$out.='<script type="text/javascript" src="'.JAVASCRIPT.'jquery-1.7.2.min.js'.$cache_js.'"></script>'."\n";
			//$out.='<script type="text/javascript" src="'.JAVASCRIPT.'jquery-migrate-1.2.1.min.js"></script>'."\n";
			//$out.='<script type="text/javascript" src="'.JAVASCRIPT.'jquery-ui-1.11.2.min.js"></script>'."\n";
			$out.='<script type="text/javascript">var CONFIG ="'.CONFIG.'"; var APP_URL="'.$o->get_path().'";</script>';
			$out.='<script type="text/javascript" src="'.JAVASCRIPT.'jquery.checkbox.js'.$cache_js.'"></script>';
			//$out.='<script type="text/javascript" src="'.JAVASCRIPT.'jquery.multiselect.js'.$cache_js.'"></script>';
			$out.='<script type="text/javascript" src="'.JAVASCRIPT.'bootstrap-multiselect.js'.$cache_js.'"></script>';
			$out.='<script type="text/javascript" src="'.JAVASCRIPT.'netlib.js'.$cache_js.'"></script>';
			//$out.='<script type="text/javascript" src="'.JAVASCRIPT.'vendors.min.js'.$cache_js.'"></script>';
			//$out.='<script type="text/javascript" src="'.JAVASCRIPT.'app.min.js'.$cache_js.'"></script>';
			//$out.='<script type="text/javascript" src="'.JAVASCRIPT.'datas.js'.$cache_js.'"></script>';
			
			if( !empty( $o->js_files ) ) {
				foreach( $o->js_files as $f ) {
					//if( is_file( $f) ) {
						$out.='<script type="text/javascript" src="'.$f.''.$cache_js.'"></script>';
					//}
				}
			}
			
			return $out;
		}
	
		function get_analytics() {
			$script = '';
			$o = self::instance();
			$is_ga_actif = false;
			if( !empty( $o->analytics_id) ) {
				if( CONFIG == 'PROD' ) $is_ga_actif = true;
				
				if( CONFIG == 'PROD' ) {
			$script= "
<script type=\"text/javascript\">
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', '".$o->analytics_id."');
ga('require', 'displayfeatures');
ga('require', 'linkid', 'linkid.js');
ga('send', 'pageview');
</script>";
			}
			}
			define( 'IS_GA_ACTIF', $is_ga_actif);
			return $script;
		}

		public static function set_analytics( $code='') {
			$o=self::instance();
			/*if( !empty( $code) )*/ $o->analytics_id = $code;
		}
		
		public function default_personnalisation_email() {
			$tab_perso=array(
				'{PATH}'=>self::get_path(), 
				'{APP_URL}'=>self::get_path()
			);
			return $tab_perso;
		}
		
		public static function get_path($marque=null) {
			return APP_URL;
		}
	
		public static function get_title() {
			$o=self::instance();
			if(!empty( $o->title)) return $o->title;
			elseif( !empty_const( 'TITLE') ) return TITLE;
			else return '';
		}
		
		public static function set_title( $title='') {
			$o=self::instance();
			if( !empty( $title) ) $o->title = $title;
		}
		
		private function load_trad() {
			if(!isset($this->trad)) {
				$this->trad=new traduction();
			}
			$this->trad->load(LANGUAGES.'global.'.$this->lng.'.ini.php');
			if(!empty_const('MARQUE')) {
				$this->trad->load(LANGUAGES.'global.'.MARQUE.'.'.$this->lng.'.ini.php');
				if(is_file(LANGUAGES.$this->module.'.'.MARQUE.'.'.$this->lng.'.ini.php')) {
					$this->trad->load(LANGUAGES.$this->module.'.'.MARQUE.'.'.$this->lng.'.ini.php');
				}
			}
		}
		
		public static function trad($param1, $param2=null, $param3=null) {
			$o=self::instance();
			return $o->trad->get($param1, $param2, $param3);
		}
		
		//Chaîne de traduction paramétrée
		public static function trad2($paramArray, $markerArray = null){
			
			$searchArray = array();
			$replaceArray = array();
			
			$o = self::instance();
			
			for($i=0; $i<3; $i++) {
				if(!isset($paramArray[$i])) $paramArray[$i] = null;
			}
					
			$trad = $o->trad->get($paramArray[0], $paramArray[1], $paramArray[2]);
			
			if($markerArray !== null){
				foreach($markerArray as $k => $v){
					$searchArray[] = $k;
					$replaceArray[] = $v;
				}
				$trad =  str_replace($searchArray, $replaceArray, $trad);
			}
			
			return $trad;	
			
		}
		
		public static function set_trad($value, $param1, $param2=null, $param3=null) {
			$o=self::instance();
			return $o->trad->set($value, $param1, $param2, $param3);
		}
		
		public static function get_trad_portion($param1, $param2=null, $param3=null) {
			$o=self::instance();
			return $o->trad->get($param1, $param2, $param3);
		}
	
	}
?>