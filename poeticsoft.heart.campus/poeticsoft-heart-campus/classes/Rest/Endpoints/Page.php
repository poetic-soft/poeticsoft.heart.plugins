<?php

namespace Poeticsoft\Heart\Rest\Endpoints;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Rest\Endpoint;
use Poeticsoft\Heart\Utils\Utils;

/**
 * System Endpoint Section.
 * Example of modular API implementation with security levels.
 */
class Page extends Endpoint
{

    /**
     * Define routes for this section.
     */
    public function get_routes()
    {
        return [
            '/page/access/get' => [
                'methods'  => 'GET',
                'callback' => 'access_get',
                'auth'     => self::AUTH_ADMIN,
            ],
            '/page/access/get/(?P<pageid>\d+)' => [
                'methods'  => 'GET',
                'callback' => 'access_get_pageid',
                'auth'     => self::AUTH_ADMIN,
            ],
            '/page/access/update' => [
                'methods'  => 'POST',
                'callback' => 'access_update',
                'auth'     => self::AUTH_ADMIN,
            ],
        ];
    }

    /**
     * Get access of pages.
     */
    public function access_get($request)
    {      
              
        $campus_root_id_option_name = sprintf('%sroot_post_id', Campus::PREFIX);
        $campus_root_id = intval(get_option($campus_root_id_option_name));
      
        if(!$campus_root_id) { 

            throw new Exception('Campus root id not provided', 404); 
        }
      
        $post = get_post($campus_root_id);
        if(!$post) { 

            throw new Exception('Campus root page not found', 404); 
        }
      
        $descendants = get_pages(array(
            'child_of'     => $campus_root_id,
            'post_type'    => get_post_type($campus_root_id),
            'post_status'  => [
                'publish',
                'pending', 
                'draft', 
                'private', 
                'future'          
            ],
        ));
      
        $ids = wp_list_pluck($descendants, 'ID');
        
        $ids[] = (int)$campus_root_id;
        
        
        $posts_status = [];      
        foreach($ids as $id) {
            
            $post_status = get_post_meta(
                $id,
                Campus::PREFIX . 'access',
                true
            );
            
            $posts_status[$id] = $post_status;
        }
            
        return $this->send_success([
            'pages' => $posts_status,
            'time'   => current_time('mysql'),
        ]);
    }

    /**
     * Get access of page.
     */
    public function access_get_pageid($request)
    {      
              
        $page_id = $request->get_param('pageid');
      
        if(!$page_id) { 

            throw new Exception('Page id not provided', 404); 
        }
      
        $page = get_post($page_id);
        if(!$page) { 

            throw new Exception('Page not found', 404); 
        }

        $page_is_in_campus = Utils::post_is_in_campus($page_id);
        
        $page_access = get_post_meta(
            $page_id,
            Campus::PREFIX . 'access',
            true
        );
            
        return $this->send_success([
            'in_campus' => $page_is_in_campus,
            'access' => $page_access ?: 'restringida',
            'time'   => current_time('mysql'),
        ]);
    }


    /**
     * Update access of page 
     */
    public function access_update($request)
    {
        $post_id = $request->get_param('postid');
        $access = $request->get_param('isopen') ? 'abierta' : 'restringida';
      
        if(!$post_id) { 

            throw new Exception('Post id not provided', 404); 
        }
        
        $post = get_post($post_id);
        if(!$post) { 

            throw new Exception('Post not found', 404); 
        }

        $update = update_post_meta(
            $post_id,
            Campus::PREFIX . 'access',
            $access
        );
        
        return $this->send_success([
            'postid' => $post_id,
            'updated' => $update ? 'ok' : 'ko',
            'access' => $access
        ]);
    }
}
