<?php
$quote_svg = get_stylesheet_directory() . '/assets/svgs/quote-single.svg';

$section_title = (string) get_field('section_title');
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

<section class="section">
    <div class="container content">
        <div class="rounded-md bg-theme text-white flex flex-col-reverse md:flex-row gap-8 p-8 sm:p-14 relative">
            <div class="flex gap-6 w-full">
                <div class="w-12 text-accent opacity-20 md:-scale-x-100 absolute md:relative md:right-auto md:top-auto right-3 top-3" aria-hidden="true">
                    <?php include $quote_svg; ?>
                </div>
                <div class="pt-4 flex flex-col gap-8">
                    <p><?php echo wp_kses_post($testimonial_text); ?></p>
                    <p class="text-sm">
                        <span class="font-normal"><?php echo esc_html($testimonial_author); ?></span>
                        <?php if ($has_testimonial_button): ?>
                            <a class="underline"
                            href="<?php echo esc_url($testimonial_button_url); ?>"
                                target="<?php echo esc_attr($testimonial_button_target); ?>">
                                <?php echo esc_html($testimonial_button_title); ?>
                            </a>
                        <?php endif; ?>
                    </p>
                </div>

            </div>
            <div class="w-60 md:pl-10 md:border-l border-solid border-white/30 flex flex-col gap-4">
                <h2><?php echo esc_html($section_title); ?></h2>
                <?php if ($has_section_button): ?>
                    <a class="theme btn" href="<?php echo esc_url($section_button_url); ?>"
                        target="<?php echo esc_attr($section_button_target); ?>">
                        <?php echo esc_html($section_button_title); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>


    </div>
</section>