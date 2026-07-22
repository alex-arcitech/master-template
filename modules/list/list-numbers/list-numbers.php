<?php
$mode_raw = (string) get_field('mode');
$mode = in_array($mode_raw, ['light', 'dark'], true) ? $mode_raw : 'dark';

$section_title = (string) get_field('section_title');
$section_text = (string) get_field('section_text');

$button_raw = get_field('button');
$button = is_array($button_raw) ? $button_raw : [];
$button_url = isset($button['url']) ? (string) $button['url'] : '';
$button_label = isset($button['title']) ? (string) $button['title'] : '';
$button_target_value = isset($button['target']) && $button['target'] !== '' ? (string) $button['target'] : '_self';
$button_target = in_array($button_target_value, ['_self', '_blank', '_parent', '_top'], true) ? $button_target_value : '_self';

$items_raw = get_field('items');
$items_raw = is_array($items_raw) ? $items_raw : [];

$items = [];
foreach ($items_raw as $index => $item) {
    $item_link = isset($item['item_link']) && is_array($item['item_link']) ? $item['item_link'] : [];
    $item_link_url = isset($item_link['url']) ? (string) $item_link['url'] : '';
    $item_link_target_raw = isset($item_link['target']) && $item_link['target'] !== '' ? (string) $item_link['target'] : '_self';
    $item_link_target = in_array($item_link_target_raw, ['_self', '_blank', '_parent', '_top'], true) ? $item_link_target_raw : '_self';

    $items[] = [
        'title' => isset($item['title']) ? (string) $item['title'] : '',
        'text' => isset($item['text']) ? (string) $item['text'] : '',
        'number' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
        'item_link_url' => $item_link_url,
        'item_link_target' => $item_link_target,
        'has_link' => $item_link_url !== '',
    ];
}

?>

<div class="<?php echo esc_attr($mode); ?>">
    <section class="section dark:bg-theme dark:text-white">
        <div class="container content grid gap-10 lg:grid-cols-2 md:items-start">

            <div class="lg:mt-20 flex max-w-sm flex-col gap-8">
                <h2><?php echo esc_html($section_title); ?></h2>
                <p><?php echo wp_kses_post($section_text); ?></p>
                <a class="btn theme" href="<?php echo esc_url($button_url); ?>"
                    target="<?php echo esc_attr($button_target); ?>"><?php echo esc_html($button_label); ?></a>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <?php foreach ($items as $item): ?>
                    <?php if ($item['has_link']): ?>
                        <a href="<?php echo esc_url($item['item_link_url']); ?>"
                            target="<?php echo esc_attr($item['item_link_target']); ?>"
                            rel="<?php echo esc_attr($item['item_link_target'] === '_blank' ? 'noopener noreferrer' : ''); ?>">
                        <?php else: ?>
                            <div>
                            <?php endif; ?>
                            <div class="text-[150px] font-heading font-bold -mb-[86px] text-accent2 opacity-10">
                                <?php echo esc_html($item['number']); ?>
                            </div>
                            <h3><?php echo esc_html($item['title']); ?></h3>
                            <p><?php echo wp_kses_post($item['text']); ?></p>
                            <?php if ($item['has_link']): ?>
                        </a>
                    <?php else: ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
        </div>

</div>
</section>
</div>