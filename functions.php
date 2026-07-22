<?php

require get_template_directory() . '/inc/enqueue.php';
require get_template_directory() . '/inc/theme-config.php';
require get_template_directory() . '/inc/acf-config.php';
require_once get_template_directory() . '/inc/schema/bootstrap.php';


// Dequeue/deregister unwanted Fluent Forms public styles
add_action('wp_enqueue_scripts', function () {
    $handles = [
        'fluent-forms-public',
        'fluentform-public-default',
        // Also block potential RTL variants
        'fluent-forms-public-rtl',
        'fluentform-public-default-rtl',
    ];

    foreach ($handles as $handle) {
        if (wp_style_is($handle, 'enqueued')) {
            wp_dequeue_style($handle);
        }
        if (wp_style_is($handle, 'registered')) {
            wp_deregister_style($handle);
        }
    }
}, 99999);

// As a failsafe, strip the link tags by URL before output
add_filter('style_loader_tag', function ($html, $handle, $href, $media) {
    if (strpos($href, 'fluent-forms-public') !== false || strpos($href, 'fluentform-public-default') !== false) {
        return '';
    }
    return $html;
}, 99999, 4);

// Extra guard right before styles print
add_action('wp_print_styles', function () {
    foreach (['fluent-forms-public', 'fluentform-public-default', 'fluent-forms-public-rtl', 'fluentform-public-default-rtl'] as $h) {
        if (wp_style_is($h, 'enqueued')) {
            wp_dequeue_style($h);
        }
        if (wp_style_is($h, 'registered')) {
            wp_deregister_style($h);
        }
    }
}, 99999);
