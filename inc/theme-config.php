<?php

// This sets up the main menu location for the theme.
register_nav_menus([
    'primary' => 'Primary Menu',
]);

// This allows admins to upload SVG files.
add_filter('upload_mimes', function ($mimes) {
    if (current_user_can('manage_options')) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
});

// This cleans uploaded SVG code so unsafe content is removed.
function ac_studio_sanitize_svg_content($svg)
{
    if (!is_string($svg) || $svg === '') {
        return false;
    }

    $dom = new DOMDocument('1.0', 'UTF-8');
    $options = LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_COMPACT;

    try {
        if (@$dom->loadXML($svg, $options) === false) {
            return false;
        }
    } catch (Throwable $e) {
        return false;
    }

    if ($dom->doctype) {
        $dom->removeChild($dom->doctype);
    }

    $xpath = new DOMXPath($dom);

    foreach ($xpath->query('//comment()') as $comment) {
        $comment->parentNode->removeChild($comment);
    }

    foreach ($dom->childNodes as $child) {
        if ($child->nodeType === XML_PI_NODE) {
            $dom->removeChild($child);
        }
    }

    $dangerous = ['script', 'foreignObject', 'iframe', 'object', 'embed', 'link', 'base'];
    foreach ($dangerous as $tag) {
        foreach ($xpath->query('//' . $tag) as $node) {
            $node->parentNode->removeChild($node);
        }
    }

    foreach ($xpath->query('//*') as $element) {
        if (!($element instanceof DOMElement) || !$element->hasAttributes()) {
            continue;
        }
        $to_remove = [];
        foreach (iterator_to_array($element->attributes) as $attr) {
            $name = strtolower($attr->name);
            $value = trim($attr->value);

            if (strpos($name, 'on') === 0) {
                $to_remove[] = $name;
                continue;
            }

            if ($name === 'href' || $name === 'xlink:href') {
                $lower = strtolower($value);
                if (preg_match('/^(javascript:|data:|https?:)/', $lower)) {
                    $to_remove[] = $name;
                    continue;
                }
            }

            if ($name === 'style') {
                $lower = strtolower($value);
                if (preg_match('/url\(\s*(javascript:|data:|https?:)/', $lower)) {
                    $to_remove[] = $name;
                    continue;
                }
            }
        }

        foreach ($to_remove as $attr_name) {
            $element->removeAttribute($attr_name);
        }
    }

    $output = $dom->saveXML($dom->documentElement);
    return is_string($output) ? $output : false;
}

// This checks SVG uploads before saving them and blocks unsafe ones.
add_filter('wp_handle_upload_prefilter', function ($file) {
    $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
    if ($ext !== 'svg') {
        return $file;
    }

    if (!current_user_can('manage_options')) {
        $file['error'] = 'SVG uploads are restricted to administrators.';
        return $file;
    }

    $tmp = $file['tmp_name'] ?? '';
    if ($tmp && is_readable($tmp)) {
        $raw = @file_get_contents($tmp);
        if ($raw !== false) {
            $clean = ac_studio_sanitize_svg_content($raw);
            if ($clean !== false) {
                @file_put_contents($tmp, $clean);
            } else {
                $file['error'] = 'Invalid or unsafe SVG content.';
            }
        } else {
            $file['error'] = 'Unable to read uploaded SVG.';
        }
    }

    return $file;
});

// This hides the default Posts menu in the WordPress admin.
add_action('admin_menu', function () {
    remove_menu_page('edit.php');
}, 999);

// This turns on basic theme features like featured images and page titles.
add_action('after_setup_theme', function () {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
});

// This removes legacy page metaboxes from the block editor to avoid an empty bottom Meta boxes panel.
add_action('add_meta_boxes', function ($post_type) {
    if ($post_type !== 'page') {
        return;
    }

    if (function_exists('use_block_editor_for_post_type') && !use_block_editor_for_post_type('page')) {
        return;
    }

    $legacy_boxes = [
        'pageparentdiv',
        'slugdiv',
        'authordiv',
        'commentstatusdiv',
        'commentsdiv',
        'trackbacksdiv',
        'postcustom',
        'revisionsdiv',
    ];

    foreach ($legacy_boxes as $box_id) {
        remove_meta_box($box_id, 'page', 'side');
        remove_meta_box($box_id, 'page', 'normal');
        remove_meta_box($box_id, 'page', 'advanced');
    }
}, 99);
