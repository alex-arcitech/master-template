<?php
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

$image_slides = $rows;
$text_items = $rows;
$mobile_items = $rows;
?>

<section class="section list-detailed-image-scroll-module" data-list-detailed-image-scroll>
    <div class="container content">
        <div class="iis-layout">
            <div class="iis-media-col">
                <div class="iis-media-sticky">
                    <div class="iis-media-viewport">
                        <div class="iis-media-track" data-iis-track>
                            <?php foreach ($image_slides as $row): ?>
                                <div class="iis-media-slide">
                                    <img src="<?php echo esc_url($row['image_url']); ?>"
                                        alt="<?php echo esc_attr($row['image_alt']); ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="iis-text-col">
                <?php foreach ($text_items as $index => $row): ?>
                    <div class="iis-text-item max-w-sm" data-iis-item="<?php echo esc_attr((string) $index); ?>">
                        <h2><?php echo esc_html($row['title']); ?></h2>
                        <p><?php echo wp_kses_post($row['text']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="iis-mobile-layout">
            <?php foreach ($mobile_items as $row): ?>
                <div class="iis-mobile-item">
                    <img class="iis-mobile-image" src="<?php echo esc_url($row['image_url']); ?>"
                        alt="<?php echo esc_attr($row['image_alt']); ?>">
                    <div class="iis-mobile-text max-w-sm">
                        <h2><?php echo esc_html($row['title']); ?></h2>
                        <p><?php echo wp_kses_post($row['text']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>