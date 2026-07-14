<?php
$section_title = (string) get_field('section_title');

$style_raw = (string) get_field('style');
$style = in_array($style_raw, ['tile', 'divider'], true) ? $style_raw : 'divider';

$faq_items_raw = get_field('faq_items');
$faq_items_raw = is_array($faq_items_raw) ? $faq_items_raw : [];
$id_prefix = function_exists('wp_unique_id') ? wp_unique_id('faq-3-item-') : uniqid('faq-3-item-', false);

$faq_items = [];
foreach ($faq_items_raw as $index => $item) {
    $faq_items[] = [
        'id' => $id_prefix . $index,
        'title' => isset($item['title']) ? (string) $item['title'] : '',
        'text' => isset($item['text']) ? (string) $item['text'] : '',
    ];
}

$arrow_svg = get_stylesheet_directory() . '/assets/svgs/arrow.svg';

$item_style_class = $style === 'tile'
    ? 'bg-themelight/10 rounded-md px-5 py-8'
    : 'border-b border-solid border-themelight/60 pb-10';
?>

<section class="section">
    <div class="container flex justify-center">
        <div
            class="faq-3-accordion w-full max-w-6xl flex flex-col items-center bg-theme rounded-md text-themelight py-20 md:py-40 px-5 md:px-12">
            <h2 class="text-center mb-20 max-w-lg"><?php echo esc_html($section_title); ?></h2>
            <div class="w-full max-w-4xl flex flex-col gap-7">
                <?php foreach ($faq_items as $item): ?>
                    <div class="faq-accordion-item w-full  <?php echo esc_attr($item_style_class); ?>">
                        <button type="button" aria-expanded="false" aria-controls="<?php echo esc_attr($item['id']); ?>"
                            class="faq-accordion-trigger w-full flex justify-between items-center">
                            <p class="text-lg"><?php echo esc_html($item['title']); ?></p>
                            <div class="faq-accordion-arrow w-2 text-accent dark:text-themelight -rotate-90">
                                <?php if (file_exists($arrow_svg)): ?>
                                    <?php include $arrow_svg; ?>
                                <?php endif; ?>
                            </div>
                        </button>
                        <div id="<?php echo esc_attr($item['id']); ?>" class="faq-accordion-content">
                            <p class="pt-8"><?php echo nl2br(esc_html($item['text'])); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>