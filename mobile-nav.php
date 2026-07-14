<?php
$mobile_nav_mode_raw = (string) get_field('mobile_nav_mode', 'option');
$mobile_nav_mode = in_array($mobile_nav_mode_raw, ['light', 'dark'], true) ? $mobile_nav_mode_raw : 'light';
?>

<div id="mobile-nav-overlay"
    class="fixed top-0 left-0 w-full h-full bg-black opacity-0 bg-opacity-50 invisible z-[60] transform transition-all duration-500">
</div>

<div class="<?php echo esc_attr($mobile_nav_mode); ?>">
    <section id="mobile-nav-section"
        class=" dark:bg-theme bg-white fixed top-0 right-0 w-96 max-w-[85%] h-full z-[70] transform transition-all duration-500 translate-x-full">
        <div class="w-full h-full p-6 flex items-center justify-start text-left relative">
            <nav class="mobile w-full">
                <?php
                wp_nav_menu([
                    'theme_location' => 'theme',
                    'container' => false
                ]);
                ?>
            </nav>
            <a href="/contact"
                class="font-normal absolute bottom-5 left-1/2 -translate-x-1/2 w-[90%] max-w-xs bg-accent text-white dark:text-themedark dark:bg-white  py-5 box-border flex items-center justify-center">Book
                a Call</a>
            </a>

        </div>
    </section>
</div>