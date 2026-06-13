<?php

/**
 * Master Admin Page Layout.
 *
 * @var \Poeticsoft\Heart\Admin\AbstractPage $page
 */
?>
<div class="wrap">
    <h1><?php echo esc_html($page->get_page_title()); ?></h1>
    <hr>
    <div class="<?php echo esc_attr(\Poeticsoft\Heart\Campus::PLUGIN_SLUG); ?>-page-content">
        <?php $page->render_content_internal(); ?>
    </div>
</div>