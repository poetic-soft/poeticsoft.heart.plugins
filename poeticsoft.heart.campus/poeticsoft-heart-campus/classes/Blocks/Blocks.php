<?php

namespace Poeticsoft\Heart\Blocks;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Utils\Utils;

/**
 * Blocks Register & Customizations
 */
class Blocks
{

    /**
     * Available blocks.
     */
    private $available_blocks;

    /**
     * Initialize block register.
     */
    public function init()
    {
        
        $this->available_blocks = [
           'breadcrumbs',
            'columntools',
            'containerchildren',
            'mytools',
            'relatedcontent',
            'treenav'
        ];
        
        $this->register_category();
        $this->register_blocks();
    }
    
    private function register_category() {
        
        add_filter(
            'block_categories_all', 
            function (
                $categories, 
                $post 
            ) {

                return array_merge(
                    [
                        [
                            'slug'  => Campus::PLUGIN_SLUG,
                            'title' => Campus::PLUGIN_NAME,
                            'icon'  => 'superhero'
                        ],
                    ],
                    $categories      
                );
            }, 
            10, 
            2 
        );
    }
    
    private function register_blocks() {        
         

        add_filter(
            'enqueue_block_editor_assets',
            function() {     

                wp_enqueue_script(
                    Campus::PREFIX . 'coreblocks-configs',
                    Utils::url('ui/edit/coreconfigs/main.js'),
                    [
                        'jquery'
                    ],
                    filemtime(Utils::path('ui/edit/coreconfigs/main.js')),
                    true
                );      

                wp_enqueue_style(
                    Campus::PREFIX . 'coreblocks-configs',
                    Utils::url('ui/edit/coreconfigs/main.css'),
                    [],
                    filemtime(Utils::path('ui/edit/coreconfigs/main.css'))
                );   
            }
        );
        
        add_action(
            'init',
            function() {
        
                $blocks_path = Utils::path('blocks');
                $block_paths = glob($blocks_path . '/*', GLOB_ONLYDIR);
                $block_names = array_map('basename', $block_paths);
                
                foreach($block_names as $block_name) {

                    if(!in_array($block_name, $this->available_blocks)) { continue; } 
                    
                    $block_json_dir = $blocks_path . '/' . $block_name;
                    
                    register_block_type($block_json_dir);
                }   
            }
        );
    }
}