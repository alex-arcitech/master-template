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

$header_cta_class = "relative pl-4 pt-3 pb-1 pr-1 before:content-[''] before:pointer-events-none before:absolute before:inset-x-0 before:top-0 before:h-[3px] before:bg-[url('../assets/images/pencil-line-light-horizontal.png')] before:bg-repeat-x before:bg-left-top before:[background-size:auto_100%] after:content-[''] after:pointer-events-none after:absolute after:inset-y-0 after:left-0 after:w-[3px] after:bg-[url('../assets/images/pencil-line-light.png')] after:bg-repeat-y after:bg-left-top after:[background-size:100%_auto]";
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
        <header id="site-header" class="fixed w-full h-24 top-0 left-0 border border-red-500">
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
