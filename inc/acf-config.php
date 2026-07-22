<?php

/**
 * Arcitech 2026 block setup.
 */

if (!defined('ABSPATH')) {
    exit;
}

// This tells ACF to save field group JSON files inside this theme.
add_filter('acf/settings/save_json', function ($path) {
    return get_stylesheet_directory() . '/acf-json';
});

// This tells ACF to load field group JSON files from this theme.
add_filter('acf/settings/load_json', function ($paths) {
    if (isset($paths[0])) {
        unset($paths[0]);
    }
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
});

// This fills in missing ACF field group settings so imported groups still work properly.
add_filter('acf/load_field_group', function ($field_group) {
    if (!isset($field_group['ID'])) {
        $field_group['ID'] = 'group_' . md5($field_group['key']);
    }
    if (!isset($field_group['active'])) {
        $field_group['active'] = 1;
    }
    if (!isset($field_group['description'])) {
        $field_group['description'] = '';
    }
    if (!isset($field_group['show_in_rest'])) {
        $field_group['show_in_rest'] = 0;
    }
    return $field_group;
});

if (!function_exists('allowed_blocks')) {
    // This returns the list of blocks each page type is allowed to use.
    function allowed_blocks($post)
    {
        if (!$post) {
            return [];
        }

        $front_id = (int) get_option('page_on_front');
        $slug = function_exists('get_page_template_slug') ? (string) get_page_template_slug($post->ID) : '';
        if ($front_id && (int) $post->ID === $front_id) {
            $slug = 'front-page.php';
        }

        switch (basename($slug)) {
            case 'front-page.php':
                return [
                    'core/heading',
                    'core/paragraph',
                    'core/image',
                    'core/buttons',
                    'core/spacer',
                    'acf/accordion'
                ];
            case 'page-contact.php':
                return [
                    'core/heading',
                    'core/paragraph',
                    'core/shortcode',
                    'core/html',
                    'acf/accordion'
                ];
            case 'page-content.php':
                return [
                    'core/heading',
                    'core/paragraph',
                    'core/image',
                    'core/list',
                    'core/buttons',
                    'acf/accordion'
                ];
        }

        return [
            'core/heading',
            'core/paragraph',
            'core/image',
            'core/list',
            'core/buttons',
            'core/spacer',
        ];
    }
}

if (!function_exists('arc_allowed_blocks_for_post')) {
    // This gives the same block list through an older helper name.
    function arc_allowed_blocks_for_post($post)
    {
        return allowed_blocks($post);
    }
}

// This limits the block inserter so editors only see allowed blocks for that page.
add_filter('allowed_block_types_all', function ($allowed, $context) {
    $post = null;
    if (is_object($context) && isset($context->post)) {
        $post = $context->post;
    } elseif (is_array($context) && isset($context['post'])) {
        $post = $context['post'];
    }

    if (!$post || !function_exists('allowed_blocks')) {
        return $allowed;
    }

    $list = allowed_blocks($post);
    return is_array($list) ? $list : $allowed;
}, 10, 2);

// This automatically finds and registers custom ACF blocks from the modules folder.
add_action('acf/init', function () {
    if (!function_exists('acf_register_block_type')) {
        return;
    }

    $theme_dir = get_template_directory();
    $theme_uri = get_template_directory_uri();
    $modules_root = $theme_dir . '/modules';

    if (!is_dir($modules_root)) {
        return;
    }

    foreach (glob($modules_root . '/*', GLOB_ONLYDIR) as $type_dir) {
        $type_slug = basename($type_dir);
        foreach (glob($type_dir . '/*', GLOB_ONLYDIR) as $block_dir) {
            $block_slug = basename($block_dir);
            $render_file = $block_dir . '/' . $block_slug . '.php';
            $json_file = $block_dir . '/' . $block_slug . '.json';
            $php_group = $block_dir . '/' . $block_slug . '-fields.php';
            $style_file = $block_dir . '/style.css';
            $script_file = $block_dir . '/script.js';

            if (!is_file($render_file)) {
                continue;
            }

            if (function_exists('acf_add_local_field_group')) {
                if (is_file($json_file)) {
                    $json = file_get_contents($json_file);
                    $data = json_decode($json, true);
                    if (is_array($data)) {
                        if (isset($data['key'])) {
                            acf_add_local_field_group($data);
                        } else {
                            foreach ($data as $group) {
                                if (is_array($group) && isset($group['key'])) {
                                    acf_add_local_field_group($group);
                                }
                            }
                        }
                    }
                } elseif (is_file($php_group)) {
                    include $php_group;
                }
            }

            $title = ucwords(str_replace(['-', '_'], ' ', $block_slug));

            $args = [
                'name' => $block_slug,
                'title' => $title,
                'render_template' => $render_file,
                'category' => 'layout',
                'mode' => 'edit',
                'supports' => [
                    'align' => true,
                    'jsx' => true,
                ],
            ];

            if (is_file($style_file)) {
                $args['enqueue_style'] = $theme_uri . '/modules/' . $type_slug . '/' . $block_slug . '/style.css';
            }
            if (is_file($script_file)) {
                $args['enqueue_script'] = $theme_uri . '/modules/' . $type_slug . '/' . $block_slug . '/script.js';
            }

            acf_register_block_type($args);
        }
    }
});

// This adds the Global Content settings page in the WordPress admin.
add_action('acf/init', function () {
    if (!function_exists('acf_add_options_page')) {
        return;
    }
    acf_add_options_page([
        'page_title' => 'Global Content',
        'menu_title' => 'Global Content',
        'menu_slug' => 'global-content',
        'capability' => 'manage_options',
        'redirect' => false,
        'position' => 5,
        'icon_url' => 'dashicons-admin-appearance',
    ]);
});