<?php
$mode_raw = (string) get_field('mode');
$mode = $mode_raw === 'dark' ? 'dark' : '';

$section_title = (string) get_field('section_title');
$section_text = (string) get_field('section_text');

$button_raw = get_field('button');
$button = is_array($button_raw) ? $button_raw : [];
$button_url = isset($button['url']) ? (string) $button['url'] : '';
$button_label = isset($button['title']) ? (string) $button['title'] : '';
$button_target = isset($button['target']) && $button['target'] !== '' ? (string) $button['target'] : '_self';
$has_button = $button_url !== '' && $button_label !== '';

$items_raw = get_field('items');
$items_raw = is_array($items_raw) ? $items_raw : [];

$items = [];
foreach ($items_raw as $row) {
    $icon = isset($row['icon']) && is_array($row['icon']) ? $row['icon'] : [];
    $icon_url = isset($icon['url']) ? (string) $icon['url'] : '';
    $icon_alt = isset($icon['alt']) && $icon['alt'] !== '' ? (string) $icon['alt'] : '';
    $item_heading = isset($row['item_heading']) ? (string) $row['item_heading'] : '';
    $item_text = isset($row['item_text']) ? (string) $row['item_text'] : '';
    $item_link = isset($row['item_link']) && is_array($row['item_link']) ? $row['item_link'] : [];
    $item_link_url = isset($item_link['url']) ? (string) $item_link['url'] : '';
    $item_link_target = isset($item_link['target']) && $item_link['target'] !== '' ? (string) $item_link['target'] : '_self';

    $items[] = [
        'icon_url' => $icon_url,
        'icon_alt' => $icon_alt,
        'item_heading' => $item_heading,
        'item_text' => $item_text,
        'item_link_url' => $item_link_url,
        'item_link_target' => $item_link_target,
    ];
}
?>


<div class="<?php echo esc_attr($mode); ?>">
    <section class="section dark:bg-theme dark:text-white">
        <div class="container content grid gap-10 lg:gap-0 lg:grid-cols-2 md:items-start">

            <div class=" flex max-w-sm flex-col gap-8">
                <h2><?php echo esc_html($section_title); ?></h2>
                <p><?php echo wp_kses_post($section_text); ?></p>
                <?php if ($has_button): ?>
                    <a class="btn theme" href="<?php echo esc_url($button_url); ?>"
                        target="<?php echo esc_attr($button_target); ?>"><?php echo esc_html($button_label); ?></a>
                <?php endif; ?>
            </div>

            <div class="grid gap-x-6 gap-y-12 md:grid-cols-3">
                <?php foreach ($items as $item): ?>
                    <a class="group flex flex-col gap-2" href="<?php echo esc_url($item['item_link_url']); ?>"
                        target="<?php echo esc_attr($item['item_link_target']); ?>"
                        rel="<?php echo esc_attr($item['item_link_target'] === '_blank' ? 'noopener noreferrer' : ''); ?>">
                        <img class="w-12 mb-4 group-hover:-translate-y-2 duration-300" src="<?php echo esc_url($item['icon_url']); ?>"
                            alt="<?php echo esc_attr($item['icon_alt']); ?>">
                        <h3 class=" leading-tight"><?php echo esc_html($item['item_heading']); ?></h3>
                        <p><?php echo wp_kses_post($item['item_text']); ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>