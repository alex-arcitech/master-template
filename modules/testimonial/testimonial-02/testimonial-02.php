<?php
$quote_svg = get_stylesheet_directory() . '/assets/svgs/quote-single.svg';

$section_title = (string) get_field('section_title');
$section_text = (string) get_field('section_text');
$section_button_raw = get_field('section_button');
$section_button = is_array($section_button_raw) ? $section_button_raw : [];
$section_button_url = isset($section_button['url']) ? (string) $section_button['url'] : '';
$section_button_title = isset($section_button['title']) ? (string) $section_button['title'] : '';
$section_button_target = isset($section_button['target']) && $section_button['target'] !== '' ? (string) $section_button['target'] : '_self';
$has_section_button = $section_button_url !== '' && $section_button_title !== '';

$testimonial_text = (string) get_field('testimonial_text');
$testimonial_author = (string) get_field('testimonial_author');
$testimonial_button_raw = get_field('testimonial_button');
$testimonial_button = is_array($testimonial_button_raw) ? $testimonial_button_raw : [];
$testimonial_button_url = isset($testimonial_button['url']) ? (string) $testimonial_button['url'] : '';
$testimonial_button_title = isset($testimonial_button['title']) ? (string) $testimonial_button['title'] : '';
$testimonial_button_target = isset($testimonial_button['target']) && $testimonial_button['target'] !== '' ? (string) $testimonial_button['target'] : '_self';
$has_testimonial_button = $testimonial_button_url !== '' && $testimonial_button_title !== '';

?>

<section class="section bg-themelight">
    <div class="container content">
        <div class="bg-theme grid grid-cols-1 gap-12 md:grid-cols-2 md:items-start  text-white rounded-md p-8 sm:p-14">
            <div class="flex flex-col gap-4 max-w-lg">
                <h1 class="leading-tight text-3xl md:text-5xl"><?php echo esc_html($section_title); ?></h1>
                <p><?php echo wp_kses_post($section_text); ?></p>
                <?php if ($has_section_button): ?>
                    <a class="btn theme mt-6" href="<?php echo esc_url($section_button_url); ?>"
                        target="<?php echo esc_attr($section_button_target); ?>">
                        <?php echo esc_html($section_button_title); ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="w-full h-full relative flex lg:block justify-center items-center">
                <div
                    class="relative lg:absolute lg:-top-20 lg:left-1/2 lg:-translate-x-1/2 rounded-md bg-white w-80 shadow-lg text-themedark p-8 ">
                    <div class="absolute right-4 top-4">
                        <div class="w-12 text-accent opacity-20" aria-hidden="true">
                            <?php include $quote_svg; ?>
                        </div>
                    </div>
                    <div>
                        <p class="font-normal pb-2 mb-4 border-b border-grey">Client Testimonial</p>
                        <p
                            class="overflow-hidden [display:-webkit-box] [-webkit-box-orient:vertical] [-webkit-line-clamp:7]">
                            <?php echo wp_kses_post($testimonial_text); ?>
                        </p>
                    </div>
                    <div>
                        <div class="flex flex-col gap mt-4">
                            <p class="text-sm font-normal">
                                <?php echo esc_html($testimonial_author); ?>
                            </p>
                            <?php if ($has_testimonial_button): ?>
                                <a class="text-sm underline" href="<?php echo esc_url($testimonial_button_url); ?>"
                                    target="<?php echo esc_attr($testimonial_button_target); ?>">
                                    <?php echo esc_html($testimonial_button_title); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>