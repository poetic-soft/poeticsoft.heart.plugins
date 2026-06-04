<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Campus;

/**
 * Register Meta Fields
 */
class Meta
{

    /**
     * Meta FIelds definition.
     * 
     * @var array
     */
    protected $metas;

    /**
     * Constructor.
     */
    public function __construct() {        
        
        $this->metas = [
            [
                'post_type' => 'page',
                'type' => 'string',
                'label' => 'Page Access [abierta, restringida]',
                'key' => 'access',
                'cap' => 'edit_posts',
                'default' => 'restringida'
            ]
        ];
    }
    
    public function init() {   
        
        add_action('init', function () {
        
            foreach($this->metas as $meta) {
                
                register_post_meta(
                    $meta['post_type'], 
                    Campus::PREFIX . $meta['key'], 
                    [
                        'show_in_rest' => true,
                        'single'       => true,
                        'label'        => $meta['label'],
                        'type'         => $meta['type'],
                        'default'      => $meta['default'],
                        'auth_callback' => function() {
                            return current_user_can($meta['cap']);
                        }
                    ] 
                );
            }
        });
    }
}