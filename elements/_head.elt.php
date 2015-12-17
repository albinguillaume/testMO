<?php if(page::display_head()) {?>
<!DOCTYPE html>
<html lang="<?php echo page::get_langue(); ?>" class="html_page_<?php echo $step; ?>">
	<head>
		<title><?php echo page::get_title(); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta charset="utf-8">
		<?php echo page::get_css(); ?>
		<?php echo page::get_javascript(); ?>
		<?php echo page::get_analytics(); ?>
	</head>
	<body class="body_page_<?php echo $step; ?>">
<?php } ?>