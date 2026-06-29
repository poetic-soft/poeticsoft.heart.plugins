<?php
?>
<form method="post" action="options.php">
	<?php
	settings_fields($page->get_slug());
	do_settings_sections($page->get_slug());
	submit_button();
	?>
</form>
