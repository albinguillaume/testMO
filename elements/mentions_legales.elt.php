<?php
	$prospection_val = 'oui';
	$prospection_name = 'prospection';
	$prospection_param = array('no_label'=>true);
	if( $value[$prospection_name] && $value[$prospection_name] == $prospection_val ) $prospection_param['checked'] = 'checked';
?>
<div id="contener_mentions_legales"><p><?php echo page::trad2(array('FOOTER', 'mentions_legales'), array('{BOX}'=>form::get_checkbox_elt( $prospection_val, $prospection_val, $prospection_name, $prospection_param))); ?></p></div>