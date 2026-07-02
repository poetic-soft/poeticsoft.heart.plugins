<?php

namespace Poeticsoft\Heart\UI;

use Poeticsoft\Heart\Heart;
use Poeticsoft\Heart\UI\UI as HeartUI;

class Blocks {

    protected $available_blocks = [];

	public function init() {

        add_filter(
            'block_categories_all',
            [$this, 'block_categories_all'],
            10,
            2
        );

        add_action(
            'enqueue_block_editor_assets',
            [$this, 'enqueue_block_editor_assets'],
            10,
            2
        );        

        add_filter(
            'register_block_type_args',
            [$this, 'register_block_type_args'],
            10,
            2
        );        

        add_action(
            'init',
            [$this, 'blocks_init']
        );
	}



    public function block_categories_all($categories, $post) {

        $plugin_categories = [
            [
                'slug'  => Heart::PLUGIN_SLUG,
                'title' => Heart::PLUGIN_NAME,
                'icon'  => 'heart'
            ],
        ];

        // Plugins categories +

        return array_merge(
            $plugin_categories,
            $categories
        );
    }

    public function enqueue_block_editor_assets() {

        $assets = [
            [
                'class' => Heart::class,
                'ui_path' => '/ui/edit/coreconfigs',
                'js_deps' => [
                    'jquery'
                ],
                'css_deps' => [

                ]
            ]
        ];

        // Plugins blocks editor assets +

        foreach($assets as $asset_data) {

            Heart::get(HeartUI::class)->enqueue($asset_data);
        }
    }

    public function register_block_type_args($args, $block_type) {

        $block_types_args = [
            [
                'type' => 'core/post-content',
                'attributes' => [
                    'attr' => ['type' => 'string', 'default' => 'Valor']
                ]
            ]
        ];

        // Plugins blocks types attrs +

        foreach($block_types_args as $block_type_args) {

            if ($block_type === $block_type_args['type']) {

                if (! isset($args['attributes'])) {
                    $args['attributes'] = [];
                }

                foreach($block_type_args['attributes'] as $attribute => $values) {

                    $args['attributes'][$attribute] = $values;
                }
            }
        }

        return $args;
    }

    public function blocks_init() {

        $plugin_blocks = [
            [
                'path' => Heart::PLUGIN_DIR . '/blocks',
                'available_blocks' => [
                    'base'
                ]
            ]
        ];

        // Plugins block paths +

       foreach($plugin_blocks as $plugin_block) {
        
            $block_paths = glob($plugin_block['path'] . '/*', GLOB_ONLYDIR);
            $block_names = array_map('basename', $block_paths);

            foreach ($block_names as $block_name) {

                if (!in_array($block_name, $plugin_block['available_blocks'])) {
                    continue;
                }

                $block_json_dir = $plugin_block['path'] . '/' . $block_name;
                register_block_type($block_json_dir);
            }
       } 
    }
}
