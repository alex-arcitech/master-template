<?php
$items_raw = get_field('items');
$items_raw = is_array($items_raw) ? $items_raw : [];

$id_prefix = function_exists('wp_unique_id') ? wp_unique_id('accordion-') : uniqid('accordion-', false);

$items = [];
foreach ($items_raw as $index => $item) {
    $items[] = [
        'id' => $id_prefix . $index,
        'title' => isset($item['title']) ? (string) $item['title'] : '',
        'text' => isset($item['text']) ? (string) $item['text'] : '',
    ];
}

$arrow_svg = get_stylesheet_directory() . '/assets/svgs/arrow.svg';
?>

<section class="accordion-block mb-12">
    <div class="accordion-list">
        <?php foreach ($items as $item): ?>
            <div class="accordion-item">
                <button class="accordion-trigger" type="button" aria-expanded="false"
                    aria-controls="<?php echo esc_attr($item['id']); ?>">
                    <span class="accordion-title"><?php echo esc_html($item['title']); ?></span>
                    <span class="accordion-arrow" aria-hidden="true">
                        <?php if (file_exists($arrow_svg)): ?>
                            <?php include $arrow_svg; ?>
                        <?php endif; ?>
                    </span>
                </button>
                <div class="accordion-panel" id="<?php echo esc_attr($item['id']); ?>" hidden>
                    <div class="accordion-panel-inner">
                        <div class="accordion-text"><?php echo wp_kses_post($item['text']); ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>