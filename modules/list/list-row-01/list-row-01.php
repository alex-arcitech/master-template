<?php
$section_title = (string) get_field('section_title');

$items_raw = get_field('items');
$items_raw = is_array($items_raw) ? $items_raw : [];

$items = [];
foreach ($items_raw as $row) {
    $item_title = isset($row['item_title']) ? (string) $row['item_title'] : '';
    $item_description = isset($row['item_description']) ? (string) $row['item_description'] : '';
    $item_link = isset($row['item_link']) && is_array($row['item_link']) ? $row['item_link'] : [];
    $item_link_url = isset($item_link['url']) ? (string) $item_link['url'] : '';
    $item_link_target_raw = isset($item_link['target']) && $item_link['target'] !== '' ? (string) $item_link['target'] : '_self';
    $item_link_target = in_array($item_link_target_raw, ['_self', '_blank', '_parent', '_top'], true) ? $item_link_target_raw : '_self';

    $items[] = [
        'item_title' => $item_title,
        'item_description' => $item_description,
        'item_link_url' => $item_link_url,
        'item_link_target' => $item_link_target,
        'has_link' => $item_link_url !== '',
    ];
}
?>

<section class="section">
    <div class="container">
        <div class="bg-theme flex flex-col gap-8 items-center rounded-md px-12 py-20 text-themelight">
            <h2 class="text-center max-w-sm"><?php echo esc_html($section_title); ?></h2>

            <div class="grid md:grid-cols-3 gap-10 sm:gap-6">

                <?php foreach ($items as $item): ?>
                    <?php if ($item['has_link']): ?>
                        <a class="pl-8 border-l-4 border-solid border-accent flex flex-col gap-4 hover:-translate-y-2 duration-300"
                            href="<?php echo esc_url($item['item_link_url']); ?>"
                            target="<?php echo esc_attr($item['item_link_target']); ?>"
                            rel="<?php echo esc_attr($item['item_link_target'] === '_blank' ? 'noopener noreferrer' : ''); ?>">
                        <?php else: ?>
                            <div class="pl-8 border-l-4 border-solid border-accent flex flex-col gap-4 hover:-translate-y-2 duration-300">
                            <?php endif; ?>
                            <p class="text-lg font-semibold"><?php echo esc_html($item['item_title']); ?></p>
                            <p><?php echo wp_kses_post($item['item_description']); ?></p>
                            <?php if ($item['has_link']): ?>
                        </a>
                    <?php else: ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
        </div>


    </div>
    </div>
</section>