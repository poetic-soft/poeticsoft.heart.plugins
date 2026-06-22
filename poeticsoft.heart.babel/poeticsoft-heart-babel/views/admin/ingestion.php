<?php
/**
 * Ingestion Page Template (React/Webpack Mount Root).
 * 
 * @var \Poeticsoft\Heart\Babel\Admin\Pages\IngestionPage $page
 * @var array  $pending_files
 * @var string $raw_dir_path
 * @var string $api_upload_url
 * @var string $nonce
 * @var int    $tagged_posts_count
 * @var string $converted_posts_dir
 */

use Poeticsoft\Heart\Babel\Babel;
?>

<div id="babel-ingestor-root"
     data-pending-files="<?php echo esc_attr( json_encode( $pending_files ) ); ?>"
     data-raw-dir-path="<?php echo esc_attr( $raw_dir_path ); ?>"
     data-api-upload-url="<?php echo esc_attr( $api_upload_url ); ?>"
     data-api-ingest-url="<?php echo esc_attr( get_rest_url( null, Babel::API_NAMESPACE . '/ingest-file' ) ); ?>"
     data-nonce="<?php echo esc_attr( $nonce ); ?>"
     data-tagged-posts-count="<?php echo esc_attr( $tagged_posts_count ); ?>"
     data-converted-posts-dir="<?php echo esc_attr( $converted_posts_dir ); ?>">
</div>
