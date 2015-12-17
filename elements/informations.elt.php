
<form id="form_info" method="post">
<?php echo form::get_input( 'action', 'hidden', 'form_info'); ?>
<?php echo form::get_input( 'idc', 'hidden', $c->id); ?>

<div id="page_informations" class="page_contenu">
	<img id="page_title" src="<?php echo IMAGES; ?>page_title.png" alt="<?php echo page::trad('HEADER', 'title'); ?>" />
	<div id="page_subtitle"><?php echo page::trad('HEADER', 'subtitle'); ?></div>
	<div id="page_content_contener">
		<div id="page_content">
			<div id="contener_intro">
				<div id="intro1"><?php echo page::trad2(array('INFORMATIONS', 'intro1'), array('{NOM}'=>$c->nom, '{PRENOM}'=>$c->prenom)); ?></div>
				<div id="intro2"><?php echo page::trad('INFORMATIONS', 'intro2'); ?></div>
			</div>
			<div class="bloc_infos_contener">
				<div id="bloc_votre_maserati" class="bloc_infos">
					<h2><?php echo page::trad('INFORMATIONS', 'title_votre_maserati'); ?></h2>
					<?php echo form::get_select( 'modele', $modeles, !empty( $value['modele'])?$value['modele']:'', array( 'label'=>$tab_labels['modele'], 'error'=>$tab_erreur['modele'])); ?>
					<?php echo form::get_input( 'immat', 'text', $value['immat'], array('placeholder'=>$tab_labels['immat'], 'error'=>$tab_erreur['immat'], 'class'=>'text')); ?>
					<div id="label_date_achat"><?php echo page::trad('INFORMATIONS', 'label_date_achat'); ?></div>
					<?php echo form::get_select( 'date_achat_jour', $date_achat_jours, !empty( $value['date_achat_jour'])?$value['date_achat_jour']:'', array( 'label'=>$tab_labels['date_achat_jour'], 'error'=>$tab_erreur['date_achat_jour'])); ?><!--
					--><?php echo form::get_select( 'date_achat_mois', $date_achat_mois, !empty( $value['date_achat_mois'])?$value['date_achat_mois']:'', array( 'label'=>$tab_labels['date_achat_mois'], 'error'=>$tab_erreur['date_achat_mois'])); ?><!--
					--><?php echo form::get_select( 'date_achat_annee', $date_achat_annees, !empty( $value['date_achat_annee'])?$value['date_achat_annee']:'', array( 'label'=>$tab_labels['date_achat_annee'], 'error'=>$tab_erreur['date_achat_annee'])); ?>
				</div>
				<div id="bloc_votre_maserati_visuel" class="bloc_infos"><img id="votre_maserati_visuel" src="<?php echo SPACER; ?>" alt="" /></div>
			</div>
			<div class="bloc_infos_contener">
				<div id="bloc_vos_hobbies" class="bloc_infos">
					<h2><?php echo page::trad('INFORMATIONS', 'title_vos_hobbies'); ?></h2>
					<?php echo form::get_select( 'sport', $sports, !empty( $value['sport'])?$value['sport']:'', array( 'error'=>$tab_erreur['sport'], 'multiple'=>'multiple')); ?>
					<?php echo form::get_select( 'style_musical', $style_musical, !empty( $value['style_musical'])?$value['style_musical']:'', array( 'error'=>$tab_erreur['style_musical'], 'multiple'=>'multiple')); ?>
					<?php echo form::get_input( 'artiste', 'text', $value['artiste'], array('placeholder'=>$tab_labels['artiste'], 'error'=>$tab_erreur['artiste'], 'class'=>'text')); ?>
					<?php echo form::get_input( 'autre', 'text', $value['autre'], array('placeholder'=>$tab_labels['autre'], 'error'=>$tab_erreur['autre'], 'class'=>'text')); ?>
				</div>
				<div id="bloc_votre_passion" class="bloc_infos">
					<h2><?php echo page::trad('INFORMATIONS', 'title_votre_passion'); ?></h2>
					<div id="intro_votre_passion"><?php echo page::trad('INFORMATIONS', 'label_votre_passion'); ?></div>
					<div id="contener_passion_elts" class="contener_input_radio_elts radio_big<?php echo ($tab_erreur['passion'])?' error':''; ?>">
						<?php
							$name = 'passion';
							$cls = 'gauche';
							$val = !empty($value[$name])?$value[$name]:'';
							foreach( $passions as $k => $v ) {
								$p = array( 'label_before'=>false, 'error'=>$tab_erreur[$name]);
								if( $val && $val == $k ) $p['checked'] = 'checked';
								echo '<span class="contener_input_radio_elt '.$cls.'">'.form::get_radio_elt( $v, $k, $name, $p).'</span>';
								if( $cls == 'gauche' ) $cls = 'droite';
								else $cls = 'gauche';
							}
						?>
					</div>
				</div>
			</div>
			<div class="bloc_infos_contener">
				<div id="bloc_vos_coordonnees" class="bloc_infos">
					<h2><?php echo page::trad('INFORMATIONS', 'title_vos_coordonnees'); ?></h2>
					<div id="intro_vos_coordonnees"><?php echo page::trad('INFORMATIONS', 'intro_vos_coordonnees'); ?></div>
					<div class="fields_row">
						<div class="contener_fields contener_fields_gauche">
							<?php echo form::get_input( 'adresse1', 'text', $value['adresse1'], array('placeholder'=>$tab_labels['adresse1'], 'error'=>$tab_erreur['adresse1'], 'class'=>'text')); ?>
						</div>
						<div class="contener_fields contener_fields_droite">
							<?php echo form::get_input( 'adresse2', 'text', $value['adresse2'], array('placeholder'=>$tab_labels['adresse2'], 'error'=>$tab_erreur['adresse2'], 'class'=>'text')); ?>
						</div>
					</div>
					<div class="fields_row">
						<div class="contener_fields contener_fields_gauche">
							<?php echo form::get_input( 'code_postal', 'text', $value['code_postal'], array('placeholder'=>$tab_labels['code_postal'], 'error'=>$tab_erreur['code_postal'], 'validation' => 'cp_'.LANGUE, 'class'=>'text')); ?>
						</div>
						<div class="contener_fields contener_fields_droite">
							<?php echo form::get_input( 'ville', 'text', $value['ville'], array('placeholder'=>$tab_labels['ville'], 'error'=>$tab_erreur['ville'], 'class'=>'text')); ?>
						</div>
					</div>
					<div class="fields_row">
						<div class="contener_fields contener_fields_gauche">
							<?php echo form::get_input( 'telephone', 'text', $value['telephone'], array('placeholder'=>$tab_labels['telephone'], 'error'=>$tab_erreur['telephone'], 'validation' => 'telephone_'.LANGUE, 'class'=>'text')); ?>
						</div>
					</div>
					<div id="vos_coordonnees_optins" class="">
						<p><?php echo page::trad('INFORMATIONS', 'optin_intro'); ?></p>
						<p><?php echo form::get_label( page::trad('INFORMATIONS', 'label_optin'), 'label_optin', array('class'=>'label_glob')); ?><?php
								$name = 'optin';
								$val = !empty($value[$name])?$value[$name]:'';
								foreach( $oui_non as $k => $v ) {
									$p = array( 'label_before'=>false, 'error'=>$tab_erreur[$name]);
									if( $val && $val == $k ) $p['checked'] = 'checked';
									echo '<span class="contener_input_radio_elt">'.form::get_radio_elt( $v, $k, $name, $p).'</span>';
								}
							?><?php echo form::get_label( page::trad('INFORMATIONS', 'label_optin_sms'), 'label_optin', array('class'=>'label_glob')); ?><?php
								$name = 'optin_sms';
								$val = !empty($value[$name])?$value[$name]:'';
								foreach( $oui_non as $k => $v ) {
									$p = array( 'label_before'=>false, 'error'=>$tab_erreur[$name]);
									if( $val && $val == $k ) $p['checked'] = 'checked';
									echo '<span class="contener_input_radio_elt">'.form::get_radio_elt( $v, $k, $name, $p).'</span>';
								}
							?><?php echo form::get_label( page::trad('INFORMATIONS', 'label_optin_courrier'), 'label_optin', array('class'=>'label_glob')); ?><?php
								$name = 'optin_courrier';
								$val = !empty($value[$name])?$value[$name]:'';
								foreach( $oui_non as $k => $v ) {
									$p = array( 'label_before'=>false, 'error'=>$tab_erreur[$name]);
									if( $val && $val == $k ) $p['checked'] = 'checked';
									echo '<span class="contener_input_radio_elt">'.form::get_radio_elt( $v, $k, $name, $p).'</span>';
								}
							?></p>
					</div>
				</div>
			</div>
		<?php echo form::get_input( 'bt_sub', 'submit', page::trad('COORDONNEES', 'label_bt_sub'), array('class'=>'bt_submit')); ?>
		</div>
	</div>
</div>
<?php include ELEMENTS.'_bottom_link.elt.php'; ?>
<?php include ELEMENTS.'mentions_legales.elt.php'; ?>
<?php echo form::getJsValidationFunction(); ?>
<script type="text/javascript">
var visuel_path = '<?php echo IMAGES; ?>visuels/';
var mod_witout_img = ['quattroporte_serie_v', 'gransport', 'gransport_spyder', 'coue_4200gt', 'spyder', '3200gt', 'autre'];
function changeVisuelModel( m) {
	if( !empty( m) && $.inArray( m, mod_witout_img) < 0 ) {
		$('#votre_maserati_visuel').attr('src', visuel_path+m+'.jpg');
	} else {
		$('#votre_maserati_visuel').attr('src', '<?php echo SPACER; ?>');
	}
}

	$(document).ready(function() {
		$('#form_info').submit(valid_form_info);
		$('input:radio').checkbox();
		$( "#id_modele, #id_date_achat_jour, #id_date_achat_mois, #id_date_achat_annee" ).selectmenu();
		$( "#id_modele" ).selectmenu( "menuWidget").addClass("selectmenu-overflow");
		$( "#id_modele" ).selectmenu({change:function() {
			changeVisuelModel( $( "#id_modele" ).val());
			return false;
		}});
		$( "#id_date_achat_jour" ).selectmenu( "menuWidget").addClass("selectmenu-overflow");
		$( "#id_date_achat_mois" ).selectmenu( "menuWidget").addClass("selectmenu-overflow");
		$( "#id_date_achat_annee" ).selectmenu( "menuWidget").addClass("selectmenu-overflow");
		$( "#id_sport" ).multiselect({
			nonSelectedText: '<?php echo $tab_labels['sport']; ?>'
			,maxHeight: 200
			,onChange: function(option, checked) {
                    // Get selected options.
                    var sel_id = 'id_sport';
                    var selectedOptions = $('#'+sel_id+' option:selected');
     
                    if (selectedOptions.length >= 2) {
                        // Disable all other checkboxes.
                        var nonSelectedOptions = $('#'+sel_id+' option').filter(function() {
                            return !$(this).is(':selected');
                        });
     
                        var dropdown = $('#'+sel_id+'').siblings('.multiselect-container');
                        nonSelectedOptions.each(function() {
                            var input = $('input[value="' + $(this).val() + '"]');
                            input.prop('disabled', true);
                            input.parent('li').addClass('disabled');
                        });
                    }
                    else {
                        // Enable all checkboxes.
                        var dropdown = $('#'+sel_id+'').siblings('.multiselect-container');
                        $('#'+sel_id+' option').each(function() {
                            var input = $('input[value="' + $(this).val() + '"]');
                            input.prop('disabled', false);
                            input.parent('li').addClass('disabled');
                        });
                    }
			
			}
		});
		$( "#id_style_musical" ).multiselect({
			nonSelectedText: '<?php echo $tab_labels['style_musical']; ?>'
			,onChange: function(option, checked) {
                    // Get selected options.
                    var sel_id = 'id_style_musical';
                    var selectedOptions = $('#'+sel_id+' option:selected');
     
                    if (selectedOptions.length >= 2) {
                        // Disable all other checkboxes.
                        var nonSelectedOptions = $('#'+sel_id+' option').filter(function() {
                            return !$(this).is(':selected');
                        });
     
                        var dropdown = $('#'+sel_id+'').siblings('.multiselect-container');
                        nonSelectedOptions.each(function() {
                            var input = $('input[value="' + $(this).val() + '"]');
                            input.prop('disabled', true);
                            input.parent('li').addClass('disabled');
                        });
                    }
                    else {
                        // Enable all checkboxes.
                        var dropdown = $('#'+sel_id+'').siblings('.multiselect-container');
                        $('#'+sel_id+' option').each(function() {
                            var input = $('input[value="' + $(this).val() + '"]');
                            input.prop('disabled', false);
                            input.parent('li').addClass('disabled');
                        });
                    }
			
			}
		});
		/*$( "#id_sport" ).multiselect({
			minWidth:'auto'
			,header: false
			,noneSelectedText:'<?php echo $tab_labels['sport']; ?>'
			,selectedList: 2
			,click: function(e){
				if( $(this).multiselect("widget").find("input:checked").length > 2 ){
					//alert( 'error');
					return false;
				}
			}
		});
		$( "#id_style_musical" ).multiselect({
			minWidth:'auto'
			,header: false
			,noneSelectedText:'<?php echo $tab_labels['style_musical']; ?>'
			,selectedList: 2
			,click: function(e){
				if( $(this).multiselect("widget").find("input:checked").length > 2 ){
					//alert( 'error');
					return false;
				}
			}
		});*/
		/*$( "#id_sport" ).selectmenu( "menuWidget").addClass("selectmenu-overflow");
		$( "#id_style_musical" ).selectmenu( "menuWidget").addClass("selectmenu-overflow");*/
	});

	function valid_form_info() {
		var erreur = false;
		if( !___v_modele() ) { erreur = true; ___s_modele(); }
		else { ___u_modele(); }
		if( !___v_immat() ) { erreur = true; ___s_immat(); }
		else { ___u_immat(); }
		if( !___v_date_achat_jour() ) { erreur = true; ___s_date_achat_jour(); }
		else { ___u_date_achat_jour(); }
		if( !___v_date_achat_mois() ) { erreur = true; ___s_date_achat_mois(); }
		else { ___u_date_achat_mois(); }
		if( !___v_date_achat_annee() ) { erreur = true; ___s_date_achat_annee(); }
		else { ___u_date_achat_annee(); }
		if( !___v_passion() ) { erreur = true; ___s_passion(); }
		else { ___u_passion(); }
		if( !___v_adresse1() ) { erreur = true; ___s_adresse1(); }
		else { ___u_adresse1(); }
		if( !___v_code_postal() ) { erreur = true; ___s_code_postal(); }
		else { ___u_code_postal(); }
		if( !___v_ville() ) { erreur = true; ___s_ville(); }
		else { ___u_ville(); }
		if( !___v_telephone() ) { erreur = true; ___s_telephone(); }
		else { ___u_telephone(); }
		if( !___v_optin() ) { erreur = true; ___s_optin(); }
		else { ___u_optin(); }
		if( !___v_optin_sms() ) { erreur = true; ___s_optin_sms(); }
		else { ___u_optin_sms(); }
		if( !___v_optin_courrier() ) { erreur = true; ___s_optin_courrier(); }
		else { ___u_optin_courrier(); }
		
		return !erreur;
	}
</script>

</form>
