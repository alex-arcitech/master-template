<?php
$section_title = (string) get_field('section_title');
$section_text = (string) get_field('section_text');

$page_context_raw = (string) get_field('page_context');
$is_seo_page = $page_context_raw === 'minimal';

$mode_raw = (string) get_field('mode');
$mode = in_array($mode_raw, ['dark', 'light'], true) ? $mode_raw : 'dark';
if ($is_seo_page) {
    $mode = 'light';
}

$style_raw = (string) get_field('style');
$style = in_array($style_raw, ['tile', 'divider'], true) ? $style_raw : 'tile';

$faq_items_raw = get_field('faq_items');
$faq_items_raw = is_array($faq_items_raw) ? $faq_items_raw : [];
$id_prefix = function_exists('wp_unique_id') ? wp_unique_id('faq-1-item-') : uniqid('faq-1-item-', false);

$faq_items = [];
foreach ($faq_items_raw as $index => $item) {
    $faq_items[] = [
        'id' => $id_prefix . $index,
        'title' => isset($item['title']) ? (string) $item['title'] : '',
        'text' => isset($item['text']) ? (string) $item['text'] : '',
    ];
}
$faq_columns = [[], []];
foreach ($faq_items as $index => $item) {
    $faq_columns[$index % 2][] = $item;
}

$arrow_svg = get_stylesheet_directory() . '/assets/svgs/arrow.svg';

$wrapper_class = $mode;
$section_class = 'section dark:bg-theme dark:text-themelight' . ($is_seo_page ? ' seo-section' : '');

$item_style_class = $style === 'divider'
    ? 'border-b border-solid border-accent2'
    : 'rounded-md bg-themelight dark:bg-themelight/10';

$arrow_style_class = $style === 'divider' ? 'dark:text-white' : 'dark:text-accent';
?>

<div class="<?php echo esc_attr($wrapper_class); ?>">
    <section class="<?php echo esc_attr($section_class); ?>">
        <div class="container">
            <div class="faq-1-accordion flex w-full flex-col gap-6 items-center text-center">
                <?php if ($section_title !== '' || $section_text !== ''): ?>
                    <?php if ($section_title !== ''): ?>
                        <h2><?php echo esc_html($section_title); ?></h2>
                    <?php endif; ?>
                    <?php if ($section_text !== ''): ?>
                        <p class="mb-8"><?php echo nl2br(esc_html($section_text)); ?></p>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="grid w-full gap-8 md:grid-cols-2 max-w-4xl text-left">
                    <?php foreach ($faq_columns as $column): ?>
                        <div class="flex flex-col gap-6 ">
                            <?php foreach ($column as $item): ?>
                                <div class="faq-accordion-item py-7 px-5 <?php echo esc_attr($item_style_class); ?>">
                                    <button type="button" aria-expanded="false"
                                        aria-controls="<?php echo esc_attr($item['id']); ?>"
                                        class="faq-accordion-trigger w-full flex justify-between items-center">
                                        <p class=" font-light"><?php echo esc_html($item['title']); ?></p>
                                        <div
                                            class="faq-accordion-arrow w-[6px] text-themedark <?php echo esc_attr($arrow_style_class); ?> -rotate-90">
                                            <?php if (file_exists($arrow_svg)): ?>
                                                <?php include $arrow_svg; ?>
                                            <?php endif; ?>
                                        </div>
                                    </button>
                                    <div id="<?php echo esc_attr($item['id']); ?>" class="faq-accordion-content">
                                        <p class="pt-5"><?php echo nl2br(esc_html($item['text'])); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</div>