<?php
$arrow_svg = get_stylesheet_directory() . '/assets/svgs/arrow-long.svg';
$is_seo_template = is_page_template('page-seo.php') || is_page_template('seo-page.php');
?>

<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&family=Libre+Baskerville:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php wp_body_open(); ?>
    <div id="page" class="site">
        <header id="site-header" class=" site-header fixed top-0 left-0 w-full z-50">
            <div
                class="container <?php echo $is_seo_template ? 'max-w-none px-6' : ''; ?> flex justify-between h-20 md:h-24 items-center z-0">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php
                    $header_logo = get_field('header_logo', 'option');

                    if ($header_logo && isset($header_logo['ID'])):
                        $logo_url = wp_get_attachment_image_url($header_logo['ID'], 'full');
                        $alt_text = get_post_meta($header_logo['ID'], '_wp_attachment_image_alt', true) ?: get_bloginfo('name');
                        ?>
                        <img class="header-logo w-32 md:w-44" src="<?php echo esc_url($logo_url); ?>"
                            alt="<?php echo esc_attr($alt_text); ?>" />
                    <?php endif; ?>
                </a>

                <div class="nav-right flex gap-2 md:gap-7 items-center h-full justify-center">
                    <nav class="hidden md:flex h-full items-center desktop" aria-label="Primary">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'theme',
                            'container' => false,
                            'menu_class' => '',
                            'fallback_cb' => false,
                        ]);
                        ?>
                    </nav>
                    <div class="btn-container flex gap-2">
                        <a href="tel:" class="secondary btn hidden md:flex">Call Us</a>
                        <a href="/contact" class="theme btn">Contact Us</a>
                    </div>
                    <div class="md:hidden scale-75 relative z-[80]">
                        <button id="menu-button" class=" menu-button fx-squeeze" type="button" aria-label="Menu">
                            <span class="bars">
                                <span class="bar bar--top"></span>
                                <span class="bar bar--middle"></span>
                                <span class="bar bar--bottom"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <?php include get_template_directory() . '/mobile-nav.php'; ?>
        </header>