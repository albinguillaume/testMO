
<div id="page_confirmation" class="page_contenu">
	<img id="page_title" src="<?php echo IMAGES; ?>page_title.png" alt="<?php echo page::trad('HEADER', 'title'); ?>" />
	<div id="page_subtitle"><?php echo page::trad('HEADER', 'subtitle'); ?></div>
	<div id="page_content_contener">
		<div id="page_content">
			<div id="contener_intro">
				<div id="intro1"><?php echo page::trad('CONFIRMATION', 'intro1'); ?></div>
				<div id="intro2"><b><?php echo page::trad('CONFIRMATION', 'intro21'); ?></b>&nbsp;<?php echo page::trad('CONFIRMATION', 'intro22'); ?></div>
				<div id="intro3"><?php echo page::trad('CONFIRMATION', 'intro3'); ?></div>
			</div>
			<div class="contener_link"><a href="<?php echo $site_maserati_url_confirm; ?>"><?php echo page::trad(array('CONFIRMATION', 'label_bt_prolonger'), array('{SITE_NAME}'=>$site_maserati_url_ultrashort)); ?></a></div>
		</div>
	</div>
</div>
