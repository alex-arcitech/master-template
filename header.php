<?php
$header_left_ctas = get_field('header_left_ctas', 'option');
$header_right_ctas = get_field('header_right_ctas', 'option');

$prepare_header_cta = function ($cta) {
    $link = $cta['link'];
    if (!$link) {
        return null;
    }

    return [
        'url' => $link['url'],
        'target' => !empty($link['target']) ? $link['target'] : '_self',
        'title' => $link['title'],
    ];
};

$header_left_ctas = $header_left_ctas ? array_filter(array_map($prepare_header_cta, $header_left_ctas)) : [];
$header_right_ctas = $header_right_ctas ? array_filter(array_map($prepare_header_cta, $header_right_ctas)) : [];

$header_cta_class = "text-theme relative pl-4 pr-4 pt-3 pb-3 before:content-[''] before:pointer-events-none before:absolute before:inset-0 before:[background:url('../assets/images/pencil-line-light-horizontal.png')_repeat-x_left_top/auto_5px,url('../assets/images/pencil-line-light-horizontal.png')_repeat-x_left_bottom/auto_5px] after:content-[''] after:pointer-events-none after:absolute after:inset-0 after:[background:url('../assets/images/pencil-line-light.png')_repeat-y_left_top/5px_auto,url('../assets/images/pencil-line-light.png')_repeat-y_right_top/5px_auto]";
?>

<!doctype html>
<html class="colorScheme" <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php wp_body_open(); ?>
    <div id="page" class="site">
        <header id="site-header" class="fixed w-full h-24 top-0 left-0 bg-clay border border-red-500">
            <div class="container flex items-center justify-between h-full">
                <?php if ($header_left_ctas) : ?>
                    <div class="flex items-center gap-4">
                        <?php foreach ($header_left_ctas as $cta) : ?>
                            <a class="<?php echo esc_attr($header_cta_class); ?>" href="<?php echo esc_url($cta['url']); ?>" target="<?php echo esc_attr($cta['target']); ?>">
                                <?php echo esc_html($cta['title']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($header_right_ctas) : ?>
                    <div class="flex items-center gap-4">
                        <?php foreach ($header_right_ctas as $cta) : ?>
                            <a class="<?php echo esc_attr($header_cta_class); ?>" href="<?php echo esc_url($cta['url']); ?>" target="<?php echo esc_attr($cta['target']); ?>">
                                <?php echo esc_html($cta['title']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php include get_template_directory() . '/mobile-nav.php'; ?>
        </header>
