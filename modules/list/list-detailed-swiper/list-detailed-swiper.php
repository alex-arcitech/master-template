<?php
$arrow_svg = get_stylesheet_directory() . '/assets/svgs/arrow.svg';
$rows_raw = get_field('sections');
$rows_raw = is_array($rows_raw) ? $rows_raw : [];

$rows = [];
foreach ($rows_raw as $row) {
    $image = isset($row['image']) && is_array($row['image']) ? $row['image'] : [];
    $content = isset($row['content']) && is_array($row['content']) ? $row['content'] : [];
    $image_url = isset($image['url']) ? (string) $image['url'] : '';
    $image_alt = isset($image['alt']) && $image['alt'] !== '' ? (string) $image['alt'] : '';
    $title = isset($content['title']) ? (string) $content['title'] : '';
    $text = isset($content['text']) ? (string) $content['text'] : '';

    $rows[] = [
        'image_url' => $image_url,
        'image_alt' => $image_alt,
        'title' => $title,
        'text' => $text,
    ];
}

$slides = $rows;
?>

<section class="section bg-themelight list-detailed-swiper-module">
    <div class="container content">
        <div class="swiper list-detailed-swiper relative" data-list-detailed-swiper>
            <div class="swiper-wrapper">
                <?php foreach ($slides as $row): ?>
                    <div class="swiper-slide">
                        <div class="list-detailed-swiper-card">
                            <img class="list-detailed-swiper-image" src="<?php echo esc_url($row['image_url']); ?>"
                                alt="<?php echo esc_attr($row['image_alt']); ?>">

                            <div class="list-detailed-swiper-controls-inline" aria-hidden="true">
                                <button class="list-detailed-swiper-btn list-detailed-swiper-prev-inline" type="button"
                                    aria-label="Previous slide">
                                    <span class="list-detailed-swiper-arrow"
                                        aria-hidden="true"><?php include $arrow_svg; ?></span>
                                </button>
                                <button class="list-detailed-swiper-btn list-detailed-swiper-next-inline" type="button"
                                    aria-label="Next slide">
                                    <span class="list-detailed-swiper-arrow"
                                        aria-hidden="true"><?php include $arrow_svg; ?></span>
                                </button>
                            </div>

                            <div class="list-detailed-swiper-content max-w-sm">
                                <h2><?php echo esc_html($row['title']); ?></h2>
                                <p><?php echo wp_kses_post($row['text']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="list-detailed-swiper-controls">
                <button class="list-detailed-swiper-btn list-detailed-swiper-prev" type="button"
                    aria-label="Previous slide">
                    <span class="list-detailed-swiper-arrow" aria-hidden="true"><?php include $arrow_svg; ?></span>
                </button>
                <button class="list-detailed-swiper-btn list-detailed-swiper-next" type="button"
                    aria-label="Next slide">
                    <span class="list-detailed-swiper-arrow" aria-hidden="true"><?php include $arrow_svg; ?></span>
                </button>
            </div>
        </div>

    </div>
</section>