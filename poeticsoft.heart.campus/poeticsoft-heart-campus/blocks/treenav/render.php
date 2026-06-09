<?php

/**
 * - $attributes: atributos del bloque
 * - $content: contenido interno, si aplica
 * - $block: array con info completa del bloque
 */

defined('ABSPATH') || exit;

use Poeticsoft\Heart\Campus;
use Poeticsoft\Heart\Validation\Access;
use Poeticsoft\Heart\Validation\Validation;
use Poeticsoft\Heart\Utils\Utils;

(function ($attributes, $content, $block) {

    global $wpdb;
    global $post;

    if (!$post) {
        return;
    }

    // 1. Validar y Sanitizar Atributos
    $validator = Campus::get(Validation::class);
    $schema = [
        'blockId'           => ['type' => 'text',   'required' => false],
        'refClientId'       => ['type' => 'text',   'required' => false],
        'ignoreRoot'        => ['type' => 'bool',   'required' => false],
        'onlySubscriptions' => ['type' => 'bool',   'required' => false],
        'maxDeep'           => ['type' => 'number', 'required' => false],
        'showLegend'        => ['type' => 'bool',   'required' => false],
    ];

    $attrs = $validator->validate_schema($attributes, $schema);

    if (is_wp_error($attrs)) {
        $attrs = $attributes;
    }

    $campus_root_id_option_name = Campus::PREFIX . 'root_post_id';
    $campus_root_id = get_option($campus_root_id_option_name);

    if (!$campus_root_id) {
        return;
    }

    $only_subscriptions = $attrs['onlySubscriptions'] ?? true;
    $show_legend = $attrs['showLegend'] ?? true;
    $ignore_root = (bool) ($attrs['ignoreRoot'] ?? false);
    $max_deep = intval($attrs['maxDeep'] ?? 0);

    $first_visible_level = $ignore_root ? 1 : 0;
    $max_allowed_level = $first_visible_level + $max_deep - 1;

    $campus_pages = get_pages([
        'sort_column' => 'menu_order',
        'sort_order'  => 'ASC',
        'post_status' => 'publish',
        'child_of'    => $campus_root_id,
        'post_type'   => 'page',
    ]);

    $valid_user_email = Campus::get(Access::class)->validate_email();

    $block_id = $attrs['blockId'] ?? '';
    $cache_enabled = (bool) get_option(Campus::PREFIX . 'block_cache_enabled', true);
    $cache_key = ($block_id && $cache_enabled) ? 'poeticsoft_heart_campus_' . md5($block_id . '_' . $post->ID . '_' . $valid_user_email) : '';
    $dom = '';

    if ($cache_key) {
        $dom = get_transient($cache_key);
    }

    if (false === $dom || empty($cache_key)) {

        $admin_access_option_name = Campus::PREFIX . 'admin_access';
        $admin_access = get_option($admin_access_option_name);

        $is_admin_and_can_view_all = current_user_can('manage_options') && $admin_access;

        $user_contents = [];
        if ($valid_user_email) {
            $table_name = $wpdb->prefix . Campus::PREFIX . 'access';
            $query = "SELECT post_id FROM {$table_name} WHERE user_mail = %s";
            $user_contents = array_map(
                'intval',
                $wpdb->get_col($wpdb->prepare($query, $valid_user_email))
            );
        }

        $build_page_tree = function ($parent = 0, $level = -1)
        use (
            $campus_root_id, 
            $user_contents, 
            $post, 
            $campus_pages, 
            &$build_page_tree
        ) {

            $list = [];

            if ($parent == 0) {
                $page = get_page($campus_root_id);
                if (!$page) {
                    return $list;
                }

                $is_user_contents = in_array($campus_root_id, $user_contents);
                $type = get_post_meta($campus_root_id, Campus::PREFIX . 'access', true);
                $type = is_string($type) ? trim($type) : '';

                $root_level = $level + 1;

                $list[] = [
                    'id'               => $campus_root_id,
                    'level'            => $root_level,
                    'type'             => $type,
                    'current'          => $campus_root_id == $post->ID,
                    'is_user_contents' => $is_user_contents,
                    'is_free'          => $type == '1',
                    'title'            => $page->post_title,
                    'pages'            => $build_page_tree($campus_root_id, $root_level)
                ];
            } else {
                $child_level = $level + 1;
                foreach ($campus_pages as $page) {
                    if ($page->post_parent == $parent) {
                        $is_user_contents = in_array($page->ID, $user_contents);
                        $type = get_post_meta($page->ID, Campus::PREFIX . 'access', true);
                        $type = is_string($type) ? trim($type) : '';

                        $list[] = [
                            'id'               => $page->ID,
                            'type'             => $type,
                            'level'            => $child_level,
                            'title'            => $page->post_title,
                            'current'          => $page->ID == $post->ID,
                            'is_user_contents' => $is_user_contents,
                            'is_free'          => $type == '1',
                            'pages'            => $build_page_tree($page->ID, $child_level)
                        ];
                    }
                }
            }

            return $list;
        };

        $build_object_tree = function ($pages, $parent_is_user = false, $parent_is_free = false)
        use ($is_admin_and_can_view_all, &$build_object_tree) {

            $page_data = [];
            $branch_has_user_content = false;
            $branch_has_free = false;

            foreach ($pages as $page) {
                $is_this_node_user = $page['is_user_contents'];
                $is_this_node_free = $page['is_free'];

                $inherited_user = $parent_is_user || $is_this_node_user;
                $inherited_free = $parent_is_free || $is_this_node_free;

                $children_pages = $build_object_tree($page['pages'], $inherited_user, $inherited_free);

                $has_within_user = $is_this_node_user || $children_pages['has_user_content'];
                $has_within_free = $is_this_node_free || $children_pages['has_free'];

                if ($has_within_user) $branch_has_user_content = true;
                if ($has_within_free) $branch_has_free = true;

                $page_path = get_permalink($page['id']);
                if (!$page_path) {
                    continue;
                }

                $has_children = isset($page['pages']) && count($page['pages']) > 0;
                $visible = $is_admin_and_can_view_all || $parent_is_user || $is_this_node_user || $has_within_user || $has_within_free;

                $page_data[] = [
                    'page_id'          => $page['id'],
                    'level'            => $page['level'],
                    'title'            => $page['title'],
                    'page_path'        => $page_path,
                    'current'          => $page['current'],
                    'has_children'     => $has_children,
                    'is_free'          => $page['is_free'],
                    'is_user_contents' => $page['is_user_contents'],
                    'has_within_user'  => $has_within_user,
                    'has_within_free'  => $has_within_free,
                    'parent_is_user'   => $parent_is_user,
                    'parent_is_free'   => $parent_is_free,
                    'visible'          => $visible,
                    'pages'            => $children_pages['children']
                ];
            }

            return [
                'children'         => $page_data,
                'has_user_content' => $branch_has_user_content,
                'has_free'         => $branch_has_free
            ];
        };

        $build_dom_tree = function ($pages) use (&$build_dom_tree, $only_subscriptions, $max_deep, $max_allowed_level) {
            $dom = '';

            foreach ($pages as $page) {
                if ($only_subscriptions && !$page['visible']) {
                    continue;
                }

                $has_children = $page['has_children'];
                if ($max_deep > 0 && $page['level'] >= $max_allowed_level) {
                    $has_children = false;
                }

                $inner_dom = $has_children ? $build_dom_tree($page['pages']) : '';

                $dom .= sprintf(
                    '<div id="%d" class="Page Level_%d%s%s%s">
                        <div class="Title%s">
                            %s
                            <a class="TitleLink" href="%s">
                                <span class="Text">%s</span>
                                <span class="Icon%s%s"></span>
                            </a>
                        </div>
                        %s
                    </div>',
                    $page['page_id'],
                    $page['level'],
                    $page['is_user_contents'] ? ' IsUserContents' : '',
                    $has_children ? ' HasChildren' : '',
                    $page['is_free'] ? ' IsFree' : '',
                    $page['current'] ? ' Current' : '',
                    $has_children ? '<div class="OpenClose"></div>' : '<div class="Indent"></div>',
                    esc_url($page['page_path']),
                    esc_html($page['title']),
                    $page['is_user_contents'] ? ' Paid' : '',
                    $page['is_free'] ? ' Free' : '',
                    $has_children ? '<div class="Pages">' . $inner_dom . '</div>' : ''
                );
            }

            return $dom;
        };

        $pages_tree = $build_page_tree();
        $object_tree = $build_object_tree($pages_tree, false, false);

        $root_children = [];
        if ($ignore_root && !empty($object_tree['children'])) {
            $root_children = $object_tree['children'][0]['pages'] ?? [];
        }

        $dom_tree_html = $build_dom_tree($ignore_root ? $root_children : $object_tree['children']);

        $legend = $show_legend ?
            '<div class="Legend">
                <div class="Type ShouldPay"><span class="Icon ShouldPay"></span><span class="Text">Privado</span></div>
                <div class="Type Free"><span class="Icon Free"></span><span class="Text">Abierto</span></div>
                <div class="Type Paid"><span class="Icon Paid"></span><span class="Text">Tu contenido</span></div>
            </div>' : '';

        $wrapper_attributes = get_block_wrapper_attributes([
            'id' => $block_id,
        ]);

        $dom = sprintf(
            '<div %s><div class="Nav">%s</div>%s</div>',
            $wrapper_attributes,
            $dom_tree_html,
            $legend
        );

        if ($cache_key) {
            set_transient($cache_key, $dom, 0);
        }
    }

    echo $dom;

})($attributes, $content, $block);
