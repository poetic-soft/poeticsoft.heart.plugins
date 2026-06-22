<?php
/**
 * Dashboard Template.
 *
 * @var \Poeticsoft\Heart\Babel\Admin\AbstractPage $page
 * @var string $welcome_message
 * @var bool   $chroma_connected
 * @var string $chroma_collection
 * @var int    $chroma_count
 * @var bool   $ollama_connected
 * @var array  $ollama_models
 * @var string $active_model
 * @var array|null $search_results
 */

use Poeticsoft\Heart\Babel\Babel;
?>
<div class="wrap">
        <p style="font-size: 1.1em; color: #555; margin-bottom: 20px;"><?php echo esc_html( $welcome_message ); ?></p>

        <!-- Main Status Cards Container -->
        <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
                
                <!-- ChromaDB Card -->
                <div class="card" style="flex: 1; min-width: 300px; margin: 0; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 12px; margin-bottom: 15px;">
                                <h2 style="margin: 0; font-size: 1.3em;"><?php esc_html_e( 'ChromaDB Status', Babel::TEXT_DOMAIN ); ?></h2>
                                <span style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600; font-size: 0.9em; padding: 4px 10px; border-radius: 12px; <?php echo $chroma_connected ? 'background-color: #e6f6ec; color: #0d5423;' : 'background-color: #fbeae5; color: #761c19;'; ?>">
                                        <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; <?php echo $chroma_connected ? 'background-color: #22c55e;' : 'background-color: #ef4444;'; ?>"></span>
                                        <?php echo $chroma_connected ? esc_html__( 'Connected', Babel::TEXT_DOMAIN ) : esc_html__( 'Disconnected', Babel::TEXT_DOMAIN ); ?>
                                </span>
                        </div>
                        
                        <p style="margin: 8px 0; font-size: 1.05em;">
                                <strong><?php esc_html_e( 'Active Collection:', Babel::TEXT_DOMAIN ); ?></strong> 
                                <code style="font-size: 0.95em;"><?php echo esc_html( $chroma_collection ); ?></code>
                        </p>
                        
                        <p style="margin: 8px 0; font-size: 1.05em;">
                                <strong><?php esc_html_e( 'Total Indexed Chunks:', Babel::TEXT_DOMAIN ); ?></strong> 
                                <span style="font-size: 1.2em; font-weight: bold; color: #1e3a8a; margin-left: 5px;"><?php echo number_format_i18n( $chroma_count ); ?></span>
                        </p>
                </div>

                <!-- Ollama Card -->
                <div class="card" style="flex: 1; min-width: 300px; margin: 0; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 12px; margin-bottom: 15px;">
                                <h2 style="margin: 0; font-size: 1.3em;"><?php esc_html_e( 'Ollama Server Status', Babel::TEXT_DOMAIN ); ?></h2>
                                <span style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600; font-size: 0.9em; padding: 4px 10px; border-radius: 12px; <?php echo $ollama_connected ? 'background-color: #e6f6ec; color: #0d5423;' : 'background-color: #fbeae5; color: #761c19;'; ?>">
                                        <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; <?php echo $ollama_connected ? 'background-color: #22c55e;' : 'background-color: #ef4444;'; ?>"></span>
                                        <?php echo $ollama_connected ? esc_html__( 'Connected', Babel::TEXT_DOMAIN ) : esc_html__( 'Disconnected', Babel::TEXT_DOMAIN ); ?>
                                </span>
                        </div>

                        <p style="margin: 8px 0; font-size: 1.05em;">
                                <strong><?php esc_html_e( 'Configured Model:', Babel::TEXT_DOMAIN ); ?></strong> 
                                <code style="font-size: 0.95em;"><?php echo esc_html( $active_model ); ?></code>
                        </p>

                        <div style="margin-top: 15px;">
                                <strong><?php esc_html_e( 'Installed Models:', Babel::TEXT_DOMAIN ); ?></strong>
                                <?php if ( empty( $ollama_models ) ) : ?>
                                        <p style="color: #777; font-style: italic; margin: 5px 0 0 0;"><?php esc_html_e( 'No models detected or offline.', Babel::TEXT_DOMAIN ); ?></p>
                                <?php else : ?>
                                        <ul style="margin: 5px 0 0 20px; list-style-type: disc; padding: 0;">
                                                <?php foreach ( $ollama_models as $model ) : 
                                                        $is_active = ( $model === $active_model || strpos( $model, $active_model . ':' ) === 0 );
                                                ?>
                                                        <li style="margin: 3px 0; font-size: 0.95em; <?php echo $is_active ? 'font-weight: bold; color: #1e3a8a;' : ''; ?>">
                                                                <?php echo esc_html( $model ); ?>
                                                                <?php if ( $is_active ) : ?>
                                                                        <span style="font-size: 0.85em; background-color: #dbeafe; color: #1e40af; padding: 1px 6px; border-radius: 4px; margin-left: 5px;"><?php esc_html_e( 'Active Embedder', Babel::TEXT_DOMAIN ); ?></span>
                                                                <?php endif; ?>
                                                        </li>
                                                <?php endforeach; ?>
                                        </ul>
                                <?php endif; ?>
                        </div>
                </div>

        </div>

        <!-- Semantic Search Test Box -->
        <div class="card" style="max-width: 100%; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h2 style="border-bottom: 1px solid #eee; padding-bottom: 12px; margin-top: 0; font-size: 1.3em;"><?php esc_html_e( 'Semantic Search Test (RAG)', Babel::TEXT_DOMAIN ); ?></h2>
                <p style="color: #666; margin-bottom: 15px;"><?php esc_html_e( 'Test the sovereign vector engine by performing a real-time semantic query across your indexed knowledge.', Babel::TEXT_DOMAIN ); ?></p>
                
                <form method="post" action="" style="display: flex; gap: 10px; max-width: 800px;">
                        <input type="hidden" name="action" value="test_search">
                        <?php $page->nonce_field(); ?>
                        <input type="text" name="test_query" placeholder="<?php esc_attr_e( 'Type something to search...', Babel::TEXT_DOMAIN ); ?>" required style="flex: 1; padding: 8px 12px; font-size: 1em; border-radius: 4px; border: 1px solid #ccc;">
                        <button type="submit" class="button button-primary" style="padding: 4px 20px; font-size: 1em; height: auto;" <?php echo ! $chroma_connected || ! $ollama_connected ? 'disabled' : ''; ?>>
                                <?php esc_html_e( 'Search Context', Babel::TEXT_DOMAIN ); ?>
                        </button>
                </form>

                <?php if ( ! empty( $search_results ) ) : ?>
                        <div style="margin-top: 25px; border-top: 1px solid #eee; padding-top: 20px;">
                                <h3 style="margin-top: 0; font-size: 1.15em;"><?php esc_html_e( 'Search Results (Top 3):', Babel::TEXT_DOMAIN ); ?></h3>
                                
                                <div style="display: flex; flex-direction: column; gap: 15px;">
                                        <?php foreach ( $search_results as $index => $res ) : ?>
                                                <div style="background-color: #fafafa; border-left: 4px solid #1e3a8a; padding: 15px; border-radius: 0 6px 6px 0; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px; flex-wrap: wrap; gap: 10px;">
                                                                <span style="font-weight: bold; color: #1e3a8a; font-size: 0.95em;">
                                                                        #<?php echo ( $index + 1 ); ?> - <?php echo esc_html( $res['metadata']['title'] ?? __( 'Untitled Document', Babel::TEXT_DOMAIN ) ); ?>
                                                                </span>
                                                                <span style="background-color: #f1f5f9; color: #475569; font-size: 0.85em; padding: 2px 8px; border-radius: 4px; font-family: monospace;">
                                                                        ID: <?php echo esc_html( $res['id'] ); ?> | Distance: <?php echo esc_html( number_format( $res['distance'], 4 ) ); ?>
                                                                </span>
                                                        </div>
                                                        
                                                        <p style="margin: 0 0 10px 0; color: #334155; font-size: 0.95em; line-height: 1.5; font-style: italic;">
                                                                "<?php echo esc_html( wp_trim_words( $res['document'], 80, '...' ) ); ?>"
                                                        </p>
                                                        
                                                        <div style="font-size: 0.8em; color: #64748b; border-top: 1px dashed #e2e8f0; padding-top: 8px;">
                                                                <strong><?php esc_html_e( 'Source:', Babel::TEXT_DOMAIN ); ?></strong> <?php echo esc_html( strtoupper( $res['metadata']['source'] ?? 'unknown' ) ); ?>
                                                                <?php if ( ! empty( $res['metadata']['url'] ) ) : ?>
                                                                        | <strong>URL:</strong> <a href="<?php echo esc_url( $res['metadata']['url'] ); ?>" target="_blank" style="color: #2563eb;"><?php echo esc_html( $res['metadata']['url'] ); ?></a>
                                                                <?php endif; ?>
                                                                <?php if ( ! empty( $res['metadata']['path'] ) ) : ?>
                                                                        | <strong>Path:</strong> <code><?php echo esc_html( $res['metadata']['path'] ); ?></code>
                                                                <?php endif; ?>
                                                        </div>
                                                </div>
                                        <?php endforeach; ?>
                                </div>
                        </div>
                <?php endif; ?>
        </div>

        <!-- Actions panel -->
        <div style="margin-top: 20px; display: flex; gap: 15px; align-items: center;">
                <form method="post" action="">
                        <input type="hidden" name="action" value="refresh_status">
                        <?php $page->nonce_field(); ?>
                        <?php submit_button( __( 'Force Refresh System Status', Babel::TEXT_DOMAIN ), 'secondary', 'submit', false ); ?>
                </form>
        </div>

        <!-- Danger Zone Panel -->
        <div class="card" style="max-width: 100%; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-top: 30px; border-left: 4px solid #dc2626; background-color: #fef2f2;">
                <h2 style="margin-top: 0; font-size: 1.3em; color: #991b1b;"><?php esc_html_e( 'Danger Zone (Testing Period)', Babel::TEXT_DOMAIN ); ?></h2>
                <p style="color: #7f1d1d; margin-bottom: 15px; font-size: 0.95em;">
                        <?php esc_html_e( 'Warning: This action will permanently delete all processed, raw, failed, and converted Markdown files on the server, and will completely destroy and recreate the ChromaDB vector database collection. This cannot be undone.', Babel::TEXT_DOMAIN ); ?>
                </p>
                
                <form method="post" action="" onsubmit="return confirm('<?php esc_attr_e( 'Are you absolutely sure you want to delete all files and empty the vector database? This action is irreversible.', Babel::TEXT_DOMAIN ); ?>');">
                        <input type="hidden" name="action" value="reset_system">
                        <?php $page->nonce_field(); ?>
                        <button type="submit" class="button button-link" style="background-color: #dc2626; color: #ffffff; padding: 10px 20px; font-size: 1.05em; border-radius: 4px; border: none; cursor: pointer; text-decoration: none; font-weight: bold; line-height: 1;">
                                <?php esc_html_e( 'Reset System & Empty Vector Database', Babel::TEXT_DOMAIN ); ?>
                        </button>
                </form>
        </div>
</div>
