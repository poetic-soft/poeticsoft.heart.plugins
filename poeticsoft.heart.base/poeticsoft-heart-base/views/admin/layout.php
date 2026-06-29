<?php
/**
 * Master Admin Page Layout.
 * 
 * @var \Poeticsoft\Heart\Admin\AbstractPage $page
 */

use Poeticsoft\Heart\Base\Base;
?>
<div class="wrap">
	<h1><?php echo esc_html($page->page_title); ?></h1>
	<hr>
	<div class="<?php echo esc_attr(Base::PLUGIN_SLUG); ?>-page-content">
		<?php $page->render_content_internal(); ?>
	</div>
</div>
