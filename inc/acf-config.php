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
                    'acf/accordion',
                    'acf/accordion-feature',
                    'acf/accordion-float-right',
                    'acf/accordion-grid',
                    'acf/cta-01',
                    'acf/cta-02',
                    'acf/cta-03',
                    'acf/anchor',
                    'acf/contact-form',
                    'acf/map',
                    'acf/content-page-hero',
                    'acf/front-page-hero',
                    'acf/list-detailed-image-scroll',
                    'acf/list-detailed-sticky',
                    'acf/list-detailed-swiper',
                    'acf/list-detailed-text-scroll',
                    'acf/list-icons-01',
                    'acf/list-icons-02',
                    'acf/list-numbers',
                    'acf/list-row-01',
                    'acf/list-row-02',
                    'acf/list-row-03',
                    'acf/comparison-table',
                    'acf/testimonial-01',
                    'acf/testimonial-02',
                    'acf/testimonials-grid',
                    'acf/testimonials-swiper'
                ];
            case 'page-contact.php':
                return [
                    'acf/contact-form',
                    'acf/map',
                ];
            case 'page-content.php':
                return [
                    'acf/accordion-feature',
                    'acf/accordion-float-right',
                    'acf/accordion-grid',
                    'acf/cta-01',
                    'acf/cta-02',
                    'acf/cta-03',
                    'acf/anchor',
                    'acf/contact-form',
                    'acf/map',
                    'acf/content-page-hero',
                    'acf/front-page-hero',
                    'acf/list-detailed-image-scroll',
                    'acf/list-detailed-sticky',
                    'acf/list-detailed-swiper',
                    'acf/list-detailed-text-scroll',
                    'acf/list-icons-01',
                    'acf/list-icons-02',
                    'acf/list-numbers',
                    'acf/list-row-01',
                    'acf/list-row-02',
                    'acf/list-row-03',
                    'acf/comparison-table',
                    'acf/testimonial-01',
                    'acf/testimonial-02',
                    'acf/testimonials-grid',
                    'acf/testimonials-swiper'
                ];
            case 'page-seo.php':
                return [
                    'core/heading',
                    'core/paragraph',
                    'core/image',
                    'core/list',
                    'core/buttons',
                    'core/spacer',
                    'core/shortcode',
                    'core/html',
                    'acf/accordion-grid',
                    'acf/cta-01',
                    'acf/cta-02',
                    'acf/cta-03',
                    'acf/anchor',
                    'acf/list-icons-01',
                    'acf/list-row-02',
                    'acf/list-row-03',
                    'acf/comparison-table',
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
            $style_file_slug = $block_dir . '/' . $block_slug . '.css';
            $script_file = $block_dir . '/script.js';
            $script_file_slug = $block_dir . '/' . $block_slug . '.js';

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
            $title = preg_replace('/\bCta\b/', 'CTA', $title);

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

            if (is_file($style_file) || is_file($style_file_slug)) {
                $style_name = is_file($style_file) ? 'style.css' : $block_slug . '.css';
                $args['enqueue_style'] = $theme_uri . '/modules/' . $type_slug . '/' . $block_slug . '/' . $style_name;
            }
            if (is_file($script_file) || is_file($script_file_slug)) {
                $script_name = is_file($script_file) ? 'script.js' : $block_slug . '.js';
                $args['enqueue_script'] = $theme_uri . '/modules/' . $type_slug . '/' . $block_slug . '/' . $script_name;
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

// SEO page metadata fields used by SEO templates.
add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_seo_page_fields',
        'title' => 'Cluster Meta',
        'fields' => [
            [
                'key' => 'field_seo_page_cluster',
                'label' => 'Cluster',
                'name' => 'cluster',
                'type' => 'text',
                'required' => 0
            ],
            [
                'key' => 'field_seo_page_label',
                'label' => 'Label',
                'name' => 'label',
                'type' => 'text',
                'required' => 0
            ],
            [
                'key' => 'field_seo_page_position_in_cluster',
                'label' => 'Position',
                'name' => 'position_in_cluster',
                'type' => 'number',
                'required' => 0,
                'min' => 1,
                // 'step' => 1
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'page-seo.php',
                ],
            ],
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => 'seo-page.php',
                ],
            ],
        ],
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => [],
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
    ]);
});
