<?php
/**
 * Generic Settings Page Template.
 * 
 * @var \Poeticsoft\Heart\Admin\AbstractPage $page
 */
?>
<form method="post" action="options.php">
	<?php
	settings_fields( $page->get_slug() );
	do_settings_sections( $page->get_slug() );
	submit_button();
	?>
</form>
