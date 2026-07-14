<?php

// This loads the theme's main CSS and JavaScript files onto the site.
add_action('wp_enqueue_scripts', function () {
    $dir = get_stylesheet_directory();
    $uri = get_stylesheet_directory_uri();

    // This creates a version number from the file date so browsers refresh changed files.
    $ver = function (string $rel_path) use ($dir) {
        $full = $dir . $rel_path;
        return file_exists($full) ? filemtime($full) : null;
    };

    $styles = [
        ['handle' => 'theme-style', 'src' => $uri . '/css/output.css', 'deps' => [], 'ver' => $ver('/css/output.css')],
        ['handle' => 'animations-css', 'src' => $uri . '/css/animations.css', 'deps' => ['theme-style'], 'ver' => $ver('/css/animations.css')],
        ['handle' => 'swiper-css', 'src' => 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', 'deps' => [], 'ver' => null],
    ];

    foreach ($styles as $style) {
        wp_enqueue_style($style['handle'], $style['src'], $style['deps'], $style['ver']);
    }

    $scripts = [
        ['handle' => 'swiper-js', 'src' => 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', 'deps' => [], 'ver' => null, 'in_footer' => true],
        ['handle' => 'site-js', 'src' => $uri . '/scripts/site.js', 'deps' => [], 'ver' => $ver('/scripts/site.js'), 'in_footer' => true],
    ];

    foreach ($scripts as $script) {
        wp_enqueue_script($script['handle'], $script['src'], $script['deps'], $script['ver'], $script['in_footer']);
    }
});
