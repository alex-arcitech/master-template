<?php
$mode_raw = (string) get_field('mode');
$wrapper_class = $mode_raw === 'dark' ? 'dark' : '';

$show_title = (bool) get_field('show_title');
$section_title = (string) get_field('section_title');
$section_text = (string) get_field('section_text');

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

<div class="<?php echo esc_attr($wrapper_class); ?>">
    <section class="section dark:bg-themelight">
        <div class="container">
            <?php if ($show_title): ?>
                <div class="w-full flex-col gap-4 mb-8 text-center items-center flex">
                    <?php if ($section_title !== ''): ?>
                        <h2><?php echo esc_html($section_title); ?></h2>
                    <?php endif; ?>
                    <?php if ($section_text !== ''): ?>
                        <p><?php echo wp_kses_post($section_text); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($items as $item): ?>
                    <a class="hover:-translate-y-2 duration-300 bg-theme text-white rounded-md p-8 first:bg-themelight first:text-themedark dark:bg-white dark:text-themedark flex flex-col gap-2"
                        href="<?php echo esc_url($item['item_link_url']); ?>"
                        target="<?php echo esc_attr($item['item_link_target']); ?>"
                        rel="<?php echo esc_attr($item['item_link_target'] === '_blank' ? 'noopener noreferrer' : ''); ?>">
                        <?php if ($item['icon_url'] !== ''): ?>
                            <img class="mb-4 w-12" src="<?php echo esc_url($item['icon_url']); ?>"
                                alt="<?php echo esc_attr($item['icon_alt']); ?>">
                        <?php endif; ?>
                        <?php if ($item['item_heading'] !== ''): ?>
                            <h3 class="leading-tight">
                                <?php echo esc_html($item['item_heading']); ?>
                            </h3>
                        <?php endif; ?>
                        <?php if ($item['item_text'] !== ''): ?>
                            <p>
                                <?php echo wp_kses_post($item['item_text']); ?>
                            </p>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>