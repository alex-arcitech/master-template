<?php
$arrow_svg = get_stylesheet_directory() . '/assets/svgs/arrow.svg';
$five_stars_svg = get_stylesheet_directory() . '/assets/svgs/five-stars.svg';

$section_title = (string) get_field('section_title');
$section_text = (string) get_field('section_text');
$testimonials_raw = get_field('testimonials');
$testimonials_raw = is_array($testimonials_raw) ? $testimonials_raw : [];

$testimonials = [];
foreach ($testimonials_raw as $row) {
    $text = isset($row['text']) ? (string) $row['text'] : '';
    $author = isset($row['author']) ? (string) $row['author'] : '';
    $button_raw = isset($row['button']) && is_array($row['button']) ? $row['button'] : [];
    $button_url = isset($button_raw['url']) ? (string) $button_raw['url'] : '';
    $button_title = isset($button_raw['title']) ? (string) $button_raw['title'] : '';
    $button_target = isset($button_raw['target']) && $button_raw['target'] !== '' ? (string) $button_raw['target'] : '_self';

    $testimonials[] = [
        'text' => $text,
        'author' => $author,
        'button_url' => $button_url,
        'button_title' => $button_title,
        'button_target' => $button_target,
        'has_button' => $button_url !== '' && $button_title !== '',
    ];
}
?>

<style>
    .testimonials-swiper .swiper-slide-active > div{
        opacity:1;
    }
</style>

<section class="section bg-themelight testimonials-swiper overflow-hidden">
    <div class="container content grid gap-10 md:items-start lg:grid-cols-2">
        <div class="flex max-w-[90vw] sm:max-w-sm flex-col gap-8">
            <h2><?php echo esc_html($section_title); ?></h2>
            <p><?php echo wp_kses_post($section_text); ?></p>
            <div class="flex gap-3">
                <button class="testimonial-prev w-12 h-12 rounded-md bg-white flex justify-center items-center hover:shadow-sm">
                    <div class="w-2 text-themedark"><?php include $arrow_svg; ?></div>
                </button>
                <button class="testimonial-next w-12 h-12 rounded-md bg-white flex justify-center items-center hover:shadow-sm">
                    <div class="w-2 rotate-180 text-themedark"><?php include $arrow_svg; ?></div>
                </button>
            </div>
        </div>

        <div
            class="relative overflow-visible w-[calc(100%+(50vw-50%))] max-w-none mr-[calc(50%-50vw)] min-h-[18rem]">
            <div
                class="swiper w-full absolute left-0 right-0 top-0">
                <div class="swiper-wrapper">
                    <?php foreach ($testimonials as $item): ?>
                        <div class="swiper-slide min-h-80 !h-80 max-h-80 min-w-72 max-w-72">
                            <div class="duration-300 bg-white opacity-45 text-themedark h-full w-full p-4 rounded-md shadow-sm flex flex-col justify-between">
                                <div>
                                    <p class="font-medium pb-2 border-b mb-3 border-grey border-solid">Review</p>
                                    <p class="overflow-hidden [display:-webkit-box] [-webkit-box-orient:vertical] [-webkit-line-clamp:6]">
                                        <?php echo wp_kses_post($item['text']); ?>
                                    </p>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <p class="text-sm font-normal"><?php echo esc_html($item['author']); ?></p>
                                    <?php if ($item['has_button']): ?>
                                        <a class="text-sm underline" href="<?php echo esc_url($item['button_url']); ?>"
                                            target="<?php echo esc_attr($item['button_target']); ?>">
                                            <?php echo esc_html($item['button_title']); ?>
                                        </a>
                                    <?php endif; ?>
                                    <div class="w-16 text-accent" aria-hidden="true">
                                        <?php include $five_stars_svg; ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</section>

