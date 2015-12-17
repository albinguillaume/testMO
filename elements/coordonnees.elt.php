
<form id="form_coord" method="post">
<?php echo form::get_input( 'action', 'hidden', 'form_coord'); ?>
<?php echo form::get_input( 'uid', 'hidden', $_SESSION[SESSION_PREFIX]['session_id'].'_'.uniqid().'_'.time()); ?>

<div id="page_coordonnees" class="page_contenu">
	<img id="page_title" src="<?php echo IMAGES; ?>page_title.png" alt="<?php echo page::trad('HEADER', 'title'); ?>" />
	<div id="page_subtitle"><?php echo page::trad('HEADER', 'subtitle'); ?></div>
	<div id="page_content_contener">
		<div id="page_content">
			<div id="contener_intro">
				<div id="intro1"><?php echo page::trad('COORDONNEES', 'intro1'); ?></div>
				<div id="intro2"><?php echo page::trad('COORDONNEES', 'intro2'); ?></div>
			</div>
			<div id="fields_contener">
				<?php echo form::get_input( 'nom', 'text', $value['nom'], array('placeholder'=>$tab_labels['nom'], 'error'=>$tab_erreur['nom'], 'class'=>'text')); ?>
				<?php echo form::get_input( 'prenom', 'text', $value['prenom'], array('placeholder'=>$tab_labels['prenom'], 'error'=>$tab_erreur['prenom'], 'class'=>'text')); ?>
				<?php echo form::get_input( 'num_tridente', 'text', $value['num_tridente'], array('placeholder'=>$tab_labels['num_tridente'], 'error'=>$tab_erreur['num_tridente'], 'validation' => 'num_tridente', 'class'=>'text')); ?>
				<?php echo form::get_input( 'email', 'text', $value['email'], array('placeholder'=>$tab_labels['email'], 'error'=>$tab_erreur['email'], 'validation' => 'email', 'class'=>'text')); ?>
			</div>
			<?php echo form::get_input( 'bt_sub', 'submit', page::trad('COORDONNEES', 'label_bt_sub'), array('class'=>'bt_submit')); ?>
		</div>
	</div>
</div>
<?php include ELEMENTS.'_bottom_link.elt.php'; ?>
<?php include ELEMENTS.'mentions_legales.elt.php'; ?>
<?php echo form::getJsValidationFunction(); ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#form_coord').submit(valid_form_coord);
		
	});

	function valid_form_coord() {
		var erreur = false;
		if( !___v_nom() ) { erreur = true; ___s_nom(); }
		else { ___u_nom(); }
		if( !___v_prenom() ) { erreur = true; ___s_prenom(); }
		else { ___u_prenom(); }
		if( !___v_num_tridente() ) { erreur = true; ___s_num_tridente(); }
		else { ___u_num_tridente(); }
		if( !___v_email() ) { erreur = true; ___s_email(); }
		else { ___u_email(); }
		
		return !erreur;
	}
</script>

</form>