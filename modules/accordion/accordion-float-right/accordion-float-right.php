<?php
$mode_raw = (string) get_field('mode');
$mode = in_array($mode_raw, ['light', 'dark'], true) ? $mode_raw : 'light';

$section_title = (string) get_field('section_title');
$section_text = (string) get_field('section_text');

$button_raw = get_field('button');
$button = is_array($button_raw) ? $button_raw : [];
$button_url = isset($button['url']) ? (string) $button['url'] : '';
$button_label = isset($button['title']) ? (string) $button['title'] : '';
$button_target_value = isset($button['target']) && $button['target'] !== '' ? (string) $button['target'] : '_self';
$button_target = in_array($button_target_value, ['_self', '_blank', '_parent', '_top'], true) ? $button_target_value : '_self';

$accordion_items_raw = get_field('accordion_items');
$accordion_items_raw = is_array($accordion_items_raw) ? $accordion_items_raw : [];

$accordion_items = [];
foreach ($accordion_items_raw as $item) {
    $accordion_items[] = [
        'title' => isset($item['title']) ? (string) $item['title'] : '',
        'text' => isset($item['text']) ? (string) $item['text'] : '',
    ];
}

$snippet_id = 'accordion-' . get_the_ID();

$arrow_svg = get_stylesheet_directory() . '/assets/svgs/arrow.svg';

$accordion_columns = [[], []];
foreach ($accordion_items as $index => $item) {
    $accordion_columns[$index % 2][] = [
        'index' => $index,
        'item' => $item,
    ];
}
?>


<div class="<?php echo esc_attr($mode); ?>">
    <section class="section dark:bg-theme dark:text-white flex items-center">
        <div class="container content grid gap-10 lg:grid-cols-2 md:items-start">

            <div class="flex max-w-sm flex-col gap-8">
                <h2><?php echo esc_html($section_title); ?></h2>
                <p><?php echo nl2br(esc_html($section_text)); ?></p>
                <a class="btn theme" href="<?php echo esc_url($button_url); ?>"
                    target="<?php echo esc_attr($button_target); ?>"><?php echo esc_html($button_label); ?></a>
            </div>

            <div class="grid gap-6 md:grid-cols-2" id="<?php echo esc_attr($snippet_id); ?>">
                <?php foreach ($accordion_columns as $column): ?>
                    <div class="flex flex-col gap-6">
                        <?php foreach ($column as $entry): ?>
                            <?php
                            $index = $entry['index'];
                            $item = $entry['item'];
                            $item_id = $snippet_id . '-item-' . $index;
                            $title = $item['title'];
                            $text = $item['text'];
                            ?>
                            <div class="accordion-item border-b border-solid border-grey py-5" data-accordion-item>
                                <button class="accordion-title flex justify-between w-full items-center" type="button"
                                    aria-expanded="false" aria-controls="<?php echo esc_attr($item_id); ?>"
                                    data-accordion-trigger>
                                    <p class="text-lg"><?php echo esc_html($title); ?></p>
                                    <div class="w-[6px] text-themedark dark:text-themelight -rotate-90"
                                        data-accordion-arrow>
                                        <?php if (file_exists($arrow_svg)): ?>
                                            <?php include $arrow_svg; ?>
                                        <?php endif; ?>
                                    </div>
                                </button>
                                <div class="accordion-tray" id="<?php echo esc_attr($item_id); ?>" data-accordion-content>
                                    <div></div>
                                    <p class="pt-5"><?php echo wp_kses_post($text); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>