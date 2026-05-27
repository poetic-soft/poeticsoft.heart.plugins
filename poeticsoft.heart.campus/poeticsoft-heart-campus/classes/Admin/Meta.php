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
                'key' => 'status',
                'type' => 'boolean',
                'cap' => 'edit_posts',
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
                        'type'         => $meta['type'],
                        'auth_callback' => function() {
                            return current_user_can($meta['cap']);
                        }
                    ] 
                );
            }
        });
    }
}