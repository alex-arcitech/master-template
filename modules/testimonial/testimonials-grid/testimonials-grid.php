<?php
$quote_svg = get_stylesheet_directory() . '/assets/svgs/quote-single.svg';
$five_stars_svg = get_stylesheet_directory() . '/assets/svgs/five-stars.svg';

$show_title = (bool) get_field('show_title');
$section_title = (string) get_field('section_title');
$section_text = (string) get_field('section_text');
$google_profile_link = (string) get_field('google_profile_link');
$google_profile_href = $google_profile_link !== '' ? $google_profile_link : '#';
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

$testimonials = array_slice($testimonials, 0, 5);

?>



<section class="section">
    <div class="container content">
        <?php if ($show_title): ?>
            <div class="w-full flex flex-col items-center text-center mb-10 gap-4">
                <?php if ($section_title !== ''): ?>
                    <h2><?php echo esc_html($section_title); ?></h2>
                <?php endif; ?>
                <?php if ($section_text !== ''): ?>
                    <p><?php echo wp_kses_post($section_text); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($testimonials as $item): ?>
                <div
                    class="relative aspect-square flex h-full flex-col justify-between bg-theme pt-16 pb-4 px-10 rounded-md text-white">
                    <div class="absolute right-4 top-4">
                        <div class="w-12 text-accent opacity-40" aria-hidden="true">
                            <?php include $quote_svg; ?>
                        </div>
                    </div>
                    <p class="overflow-hidden [display:-webkit-box] [-webkit-box-orient:vertical] [-webkit-line-clamp:7]">
                        <?php echo wp_kses_post($item['text']); ?>
                    </p>
                    <div class="flex flex-col gap-2">
                        <p class="text-sm font-normal">
                            <?php echo esc_html($item['author']); ?>
                        </p>
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
            <?php endforeach; ?>

            <div
                class="relative aspect-square flex h-full flex-col justify-between bg-accent pt-10  pb-6 px-10 rounded-md text-white">
                <h2 class="text-5xl lg:text-3xl">Leave us a review</h2>
                <p>Our customers' feedback helps us improve. Leave a review by clicking the link below.</p>
                <a class="btn third" href="<?php echo esc_url($google_profile_href); ?>">Review Us</a>
            </div>
        </div>
    </div>
</section>