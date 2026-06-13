<?php

namespace Poeticsoft\Heart\Admin;

use Poeticsoft\Heart\Campus;

class Meta
{
    protected $metas;


    public function __construct()
    {

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

    public function init()
    {

        add_action('init', function () {

            foreach ($this->metas as $meta) {
                register_post_meta(
                    $meta['post_type'],
                    Campus::PREFIX . $meta['key'],
                    [
                        'show_in_rest' => true,
                        'single'       => true,
                        'label'        => $meta['label'],
                        'type'         => $meta['type'],
                        'default'      => $meta['default'],
                        'auth_callback' => function () use ($meta) {
                            return current_user_can($meta['cap']);
                        }
                    ]
                );
            }
        });
    }
}
