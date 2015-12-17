
<div id="page_home">
<form id="form_pays_langue" method="post">
<?php echo form::get_input( 'action', 'hidden', 'form_pays_langue'); ?>

	<!--img id="page_home_bg" src="<?php echo IMAGES; ?>page_home_bg.png" alt="" /-->
	<img id="page_home_title" src="<?php echo IMAGES; ?>page_home_title.png" alt="Welcome on Maserati Owners" />
	<div id="page_home_language"><?php echo form::get_select( 'language', $languages, !empty( $value['language'])?$value['language']:'', array( 'label'=>$tab_labels['language'], 'error'=>$tab_erreur['language'])); ?></div>
	<div id="page_home_country"><?php echo form::get_select( 'country', $countries, !empty( $value['country'])?$value['country']:'', array( 'label'=>$tab_labels['country'], 'error'=>$tab_erreur['country'])); ?></div>
</form>
</div>
<?php echo form::getJsValidationFunction(); ?>
<script type="text/javascript">
	$(document).ready(function() {
		$( "#id_language, #id_country" ).selectmenu().selectmenu( "menuWidget").addClass( "selectmenu-overflow" );
		$( "#id_language" ).selectmenu({change:function() {
			$('#form_pays_langue').submit();
			return false;
		}});
		$( "#id_country" ).selectmenu({change:function() {
			$('#form_pays_langue').submit();
			return false;
		}});
		$('#form_pays_langue').submit(valid_form_pays_langue);
	});

	function valid_form_pays_langue() {
		if( ___v_country() && ___v_language() ) {
			return true;
		}
		return false;
	}
</script>
