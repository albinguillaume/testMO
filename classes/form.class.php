<?php
class form {
	
	var $javascript_validate_function=array();
	var $input_radio=array();
	var $input_radio_prefix=array();
	
	private static $_instance;
	
	
	private function __construct() {}
	private function __clone() {}
	public function __destruct() {}
	
	static function _o() { // Get Object
		if( is_null(self :: $_instance) ) { self::$_instance = new form(); }
		return self::$_instance;
	}
	
	function reset() {
		if( !is_null(self :: $_instance) ) self::$_instance = null;
	}
	
function getJsValidationFunction($form_id='') {
	$o = self::_o();
	//pr( $o->javascript_validate_function);

	$scriptOutput = '<script type="text/javascript">'."\n";
	/*
	$scriptOutput .= '$(\'#'.$form_id.'\').submit(function(event){'."\n";
	$scriptOutput .= implode( "", $o->javascript_validate_function)."\n";
	$scriptOutput .= 'event.preventDefault();';
	$scriptOutput .= '});'."\n";
	*/
	$scriptOutput .= 'var form_erreur = Array();'."\n";
	$scriptOutput .= implode( "", $o->javascript_validate_function)."\n";
	if( !empty( $o->input_radio) ) {
		foreach( $o->input_radio as $name => $values ) {
$scriptOutput .= '
			function ___v_'.$name.'() {
				var fieldVal = getValFromRadioName( \''.$o->input_radio_prefix[$name].'\');
				erreur = false;
				if( empty( fieldVal) ) { erreur = true; form_erreur.push(\'true\'); }
				return !erreur;
			}
			function ___s_'.$name.'() {
';
if( !empty( $values) ) {
	foreach( $values as $i => $l ) {
$scriptOutput .= '
				setErreur( "'.$i.'");
';
	}
}
$scriptOutput .= '
			}
			function ___u_'.$name.'() {
				';
if( !empty( $values) ) {
	foreach( $values as $i => $l ) {
$scriptOutput .= '
				unsetErreur( "'.$i.'");
';
	}
}
$scriptOutput .= '
			}
';
		}
	}
	$scriptOutput .= '</script>'."\n";
	
	return $scriptOutput;
}
	
// nettoie une string pour pouvoir nomer une variable, php, js
// http://stackoverflow.com/questions/12339942/sanitize-strings-for-legal-variable-names-in-php
function sanitize($input)
{
	if (!@preg_match('/\pL/u', 'a')) $pattern = '/[^a-zA-Z0-9]/';
	else $pattern = '/[^\p{L}\p{N}]/u';
	return preg_replace($pattern, '', (string) $input);
}

function get_select( $name, $data, $value='', $param=array()) {
	$o = self::_o();
	if( is_array($value) && empty( $param) ) {
		$param = $value;
		$value = '';
	}
	//echo $value;
	$prefix = (!empty( $param['prefix']))?$param['prefix'].'_':'';
	$prefix_form = (!empty( $param['prefix']))?$param['prefix']:'';
	$attr = array();
	if( !empty( $name) ) {
		if( !empty( $prefix) ) $attr['name'] = $prefix_form.'['.$name.']';
		else $attr['name'] = 'form['.$prefix.$name.']';
		$attr['id'] = 'id_'.$prefix.$name;
	}
	$isMultiple = false;
	if( !empty($param['multiple']) && $param['multiple'] == 'multiple' ) {
		$attr['name'] .= '[]';
		$isMultiple = true;
	}
	$attr = form::addAttrFromParam( $attr, $param, array( 'id', 'class', 'style', 'multiple'));
	if( !empty( $param['error']) && empty( $attr['class']) ) $attr['class'] = 'error';
	elseif( !empty( $param['error']) && !empty( $attr['class']) ) $attr['class'] .= ' error';
	
	$html = '<span class="contener_select'.(!empty( $param['error'])?' error':'').'" id="contener_'.$attr['id'].'"><select '.form::getAttrStr( $attr).'>';
		if( isset( $param['label']) ) $html .= '<option value="">'.$param['label'].'</option>';
	if( !empty( $data) ) {
		foreach( $data as $k => $v ) {
			$sel = '';
			if( $isMultiple && is_array($value) && in_array($k, $value) ) $sel = ' selected="selected"';
			if( $k==$value ) $sel = ' selected="selected"';
			$html .= '<option'.$sel.' value="'.$k.'">'.$v.'</option>';
		}
	}
	$html .= '</select></span>';
	
/*function validField_'.$prefix.$name.'() {
	var fieldVal = $("#'.$attr['id'].'").val();
	var erreur = false;
	if( empty( fieldVal) ) { erreur = true; form_erreur.push(\'true\'); setErreur( "'.$attr['id'].'"); }
	else { unsetErreur( "'.$attr['id'].'"); }
	return !erreur;
}*/
	$o->javascript_validate_function[] = '
			function ___v_'.$prefix.$name.'() {
				var fieldVal = $("#'.$attr['id'].'").val();
				erreur = false;
				if( empty( fieldVal) ) { erreur = true; form_erreur.push(\'true\'); }
				return !erreur;
			}
			function ___s_'.$prefix.$name.'() {
				setErreur( "'.$attr['id'].'");
			}
			function ___u_'.$prefix.$name.'() {
				unsetErreur( "'.$attr['id'].'");
			}
';
	return $html;
}

function get_radio_elt( $lbl, $val, $name, $param=array()) {
	$o = self::_o();
	$prefix = (!empty( $param['prefix']))?$param['prefix'].'_':'';
	$id = 'id_'.$prefix.$name.'_'.form::sanitize( $val);
	$input_param = array_merge( $param, array('value'=>$val, 'id'=>$id));
	$label_param = array_merge( $param, array( 'for'=>$id, 'id'=>'label_'.$id));
	if( !empty( $param['error']) && empty( $label_param['class']) ) $label_param['class'] = 'error';
	elseif( !empty( $param['error']) && !empty( $label_param['class']) ) $label_param['class'] .= ' error';
	$html = '';
	$label = form::get_label( $lbl, $prefix.$name, $label_param);
	if( !empty( $param['no_label']) && $param['no_label'] === true ) $label = '';
	if( !empty( $param['label_before']) && $param['label_before'] === true ) $html .= $label;
	$html .= form::get_input( $prefix.$name, 'radio', $val, $input_param);
	if( empty( $param['label_before']) || $param['label_before'] !== true ) $html .= $label;
	$o->input_radio[$prefix.$name][$id] = $val;
	if( !empty( $prefix) ) $o->input_radio_prefix[$prefix.$name] = $prefix.'['.$name.']';
	else $o->input_radio_prefix[$prefix.$name] = 'form['.$prefix.$name.']';
	return $html;
}

function get_checkbox_elt( $lbl, $val, $name, $param=array()) {
	$o = self::_o();
	$prefix = (!empty( $param['prefix']))?$param['prefix'].'_':'';
	$id = 'id_'.$prefix.$name.'_'.form::sanitize( $val);
	$input_param = array_merge( $param, array('value'=>$val, 'id'=>$id));
	$label_param = array_merge( $param, array( 'for'=>$id, 'id'=>'label_'.$id));
	if( !empty( $param['error']) && empty( $label_param['class']) ) $label_param['class'] = 'error';
	elseif( !empty( $param['error']) && !empty( $label_param['class']) ) $label_param['class'] .= ' error';
	$html = '';
	$label = form::get_label( $lbl, $prefix.$name, $label_param);
	if( !empty( $param['no_label']) && $param['no_label'] === true ) $label = '';
	if( !empty( $param['label_before']) && $param['label_before'] === true ) $html .= $label;
	$html .= form::get_input( $prefix.$name, 'checkbox', $val, $input_param);
	if( empty( $param['label_before']) || $param['label_before'] !== true ) $html .= $label;
	return $html;
}

function get_label( $lbl, $name='', $param=array()) {
	$o = self::_o();
	if( is_array($name) ) {
		$param = $name;
		$name = '';
	}
	$prefix = (!empty( $param['prefix']))?$param['prefix'].'_':'';
	$attr = array();
	if( !empty( $name) ) {
		$attr['for'] = 'id_'.$prefix.$name;
		$attr['id'] = 'label_id_'.$prefix.$name;
	}
	$attr = form::addAttrFromParam( $attr, $param, array( 'id', 'for', 'class', 'style'));
	if( !empty( $param['erreur']) && empty( $attr['class']) ) $attr['class'] = 'error';
	elseif( !empty( $param['erreur']) && !empty( $attr['class']) ) $attr['class'] .= ' error';
	return '<label'.form::getAttrStr( $attr).'>'.$lbl.'</label>';
}

function get_input( $name, $type, $value='', $param=array()/*, $restrictions=''*/ ) {
	$o = self::_o();
	if( is_array($value) ) {
		$param = $value;
		$value = '';
	}
	//echo $value;
	$prefix = (!empty( $param['prefix']))?$param['prefix'].'_':'';
	$prefix_form = (!empty( $param['prefix']))?$param['prefix']:'';
	$attr = array();
	if( !empty( $name) ) {
		if( $type == 'file' ) {
			if( !empty( $prefix) ) $attr['name'] = $prefix_form.'_'.$name.'';
			else $attr['name'] = 'form_'.$prefix.$name.'';
		} else {
			if( !empty( $prefix) ) $attr['name'] = $prefix_form.'['.$name.']';
			else $attr['name'] = 'form['.$prefix.$name.']';
		}
		$attr['id'] = 'id_'.$prefix.$name;
	}
	if( !empty( $value) || $type=='checkbox' || $type=='radio' ) $attr['value'] = $value;
	if( !empty( $type) ) $attr['type'] = $type;
	
	$attr = form::addAttrFromParam( $attr, $param, array( 'id', 'class', 'style', 'placeholder', 'src', 'checked', 'autocomplete'));
	
	if( !empty( $param['error']) && empty( $attr['class']) ) $attr['class'] = 'error';
	elseif( !empty( $param['error']) && !empty( $attr['class']) ) $attr['class'] .= ' error';
	
	if( !empty( $attr['name']) && $type == 'checkbox' && (empty( $param['single_checkbox']) || $param['single_checkbox'] !== true ) ) $attr['name'] .= '[]';
	
	if( !in_array( $type, array( 'hidden', 'submit', 'image', 'radio', 'checkbox')) ) {
		$other_cond = '';
		if( !empty( $param['validation']) ) {
			$other_cond = ' || !verif_champ( fieldVal, "'.$param['validation'].'")';
		}
		
		/*
		if(!empty($restrictions)){
			$restrictions = explode('|', $restrictions);
			
			$restrictionsOutpuList = array(
				'required'=>'empty(fieldVal)',
				'email'=>'!verif_email(fieldVal)',
				'telephone'=>'verif_champ( fieldVal, \'telephone\')',
			);
			
			for($i=0; $i<count($restrictions); $i++){
				if(array_key_exists($restrictions[$i], $restrictionsOutpuList)){
					if($i==0){
						$restrictionsJs = $restrictionsOutpuList[$restrictions[$i]];
					}elseif($i>=1){
						$restrictionsJs .= ' || '.$restrictionsOutpuList[$restrictions[$i]];
					}
				}
			};
		
			$scriptOutput = '
				$(function() {
					var fieldVal = $("#'.$attr['id'].'").val();
					var erreur = false;
					if( '.$restrictionsJs.' ) { erreur = true; setErreur( "'.$attr['id'].'"); }
					else { unsetErreur( "'.$attr['id'].'"); }
					console.log(erreur);
					return !erreur;
				});';
		}else{
			$scriptOutput = '';
		}
		*/
		
		/*	function validField_'.$prefix.$name.'() {
				var fieldVal = $("#'.$attr['id'].'").val();
				erreur = false;
				if( empty( fieldVal)'.$other_cond.' ) { erreur = true; form_erreur.push(\'true\'); setErreur( "'.$attr['id'].'"); }
				else { unsetErreur( "'.$attr['id'].'"); }
				return !erreur;
			}*/
		$scriptOutput = 
		'
			function ___v_'.$prefix.$name.'() {
				var fieldVal = $("#'.$attr['id'].'").val();
				erreur = false;
				if( empty( fieldVal)'.$other_cond.' ) { erreur = true; form_erreur.push(\'true\'); }
				return !erreur;
			}
			function ___s_'.$prefix.$name.'() {
				setErreur( "'.$attr['id'].'");
			}
			function ___u_'.$prefix.$name.'() {
				unsetErreur( "'.$attr['id'].'");
			}';
		
		
		$o->javascript_validate_function[] = $scriptOutput;
	}
	
	return '<span class="contener_input_'.$type.(!empty( $param['error'])?' error':'').'" id="contener_'.$attr['id'].'"><input'.form::getAttrStr( $attr).' /></span>';
}

function get_textarea( $name, $value='', $param=array() ) {
	$o = self::_o();
	if( is_array($value) ) {
		$param = $value;
		$value = '';
	}
	//echo $value;
	$prefix = (!empty( $param['prefix']))?$param['prefix'].'_':'';
	$prefix_form = (!empty( $param['prefix']))?$param['prefix']:'';
	$attr = array();
	if( !empty( $name) ) {
		if( !empty( $prefix) ) $attr['name'] = $prefix_form.'['.$name.']';
		else $attr['name'] = 'form['.$prefix.$name.']';
		$attr['id'] = 'id_'.$prefix.$name;
	}
	//if( !empty( $value) ) $attr['value'] = $value;
	
	$attr = form::addAttrFromParam( $attr, $param, array( 'id', 'class', 'style', 'placeholder', 'src', 'checked'));
	
	if( !empty( $param['error']) && empty( $attr['class']) ) $attr['class'] = 'error';
	elseif( !empty( $param['error']) && !empty( $attr['class']) ) $attr['class'] .= ' error';
	
	//if( !empty( $attr['name']) && $type == 'checkbox' ) $attr['name'] .= '[]';
	
	if( true ) {//!in_array( $type, array( 'hidden', 'submit', 'image', 'radio', 'checkbox')) ) {
		$other_cond = '';
		if( !empty( $param['validation']) ) {
			$other_cond = ' || !verif_champ( fieldVal, "'.$param['validation'].'")';
		}
		
			/*function validField_'.$prefix.$name.'() {
				var fieldVal = $("#'.$attr['id'].'").val();
				erreur = false;
				if( empty( fieldVal)'.$other_cond.' ) { erreur = true; form_erreur.push(\'true\'); setErreur( "'.$attr['id'].'"); }
				else { unsetErreur( "'.$attr['id'].'"); }
				return !erreur;
			}*/
		$scriptOutput = 
		'
			function ___v_'.$prefix.$name.'() {
				var fieldVal = $("#'.$attr['id'].'").val();
				erreur = false;
				if( empty( fieldVal)'.$other_cond.' ) { erreur = true; form_erreur.push(\'true\'); }
				return !erreur;
			}
			function ___s_'.$prefix.$name.'() {
				setErreur( "'.$attr['id'].'");
			}
			function ___u_'.$prefix.$name.'() {
				unsetErreur( "'.$attr['id'].'");
			}';
		
		
		$o->javascript_validate_function[] = $scriptOutput;
	}
	
	return '<span class="contener_textarea'.(!empty( $param['error'])?' error':'').'" id="contener_'.$attr['id'].'"><textarea'.form::getAttrStr( $attr).'>'.$value.'</textarea></span>';
}

function getAttrStr( $attr=array()) {
	$str = '';
	if( count( $attr) > 0 ) {
		foreach( $attr as $k => $v ) {
			$str .= ' '.$k.'="'.$v.'"';
		}
	}
	return $str;
}

function addAttrFromParam( $attr, $param, $attr_list) {
	for( $i = 0 ; $i < count( $attr_list) ; $i++ ) {
		if( isset( $param[$attr_list[$i]]) ) {
			$attr[$attr_list[$i]] = $param[$attr_list[$i]];
		}
	}
	return $attr;
}
}