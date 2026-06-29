<?php

use Poeticsoft\Heart\Heart;

?>
<div class="wrap">
	<h1><?php echo esc_html($page->page_title); ?></h1>
	<hr>
	<div class="<?php echo esc_attr(Heart::PLUGIN_SLUG); ?>-page-content">
		<?php $page->render_content(); ?>
	</div>
</div>
