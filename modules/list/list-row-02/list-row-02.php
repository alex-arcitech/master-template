<?php
$mode_raw = (string) get_field('mode');
$mode = in_array($mode_raw, ['light', 'dark'], true) ? $mode_raw : 'dark';

$page_context_raw = (string) get_field('page_context');
$is_seo_page = $page_context_raw === 'minimal';
if ($is_seo_page) {
    $mode = 'light';
}

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

$section_class = 'section dark:bg-theme dark:text-themelight mb-12' . ($is_seo_page ? ' seo-section' : '');
?>
<div class="<?php echo esc_attr($mode); ?>">
    <section class="<?php echo esc_attr($section_class); ?>">
        <div class="container">
            <div class="flex flex-col gap-8 items-start">
                <?php if ($section_title !== ''): ?>
                    <h2><?php echo esc_html($section_title); ?></h2>
                <?php endif; ?>
                <div class="w-full grid md:grid-cols-3 gap-10 sm:gap-6">

                    <?php foreach ($items as $index => $item): ?>
                        <?php if ($item['has_link']): ?>
                            <a class="dark:border-white dark:border p-8 rounded-md dark:first:border-l-4 dark:first:border-solid dark:first:border-t-0 dark:first:border-b-0 dark:first:border-r-0 dark:first:border-accent first:border-l-4 border-solid border-accent flex flex-col gap-4 first:text-themedark bg-theme text-themelight hover:-translate-y-2 duration-300"
                                href="<?php echo esc_url($item['item_link_url']); ?>"
                                target="<?php echo esc_attr($item['item_link_target']); ?>"
                                rel="<?php echo esc_attr($item['item_link_target'] === '_blank' ? 'noopener noreferrer' : ''); ?>"
                                <?php if ($index === 0): ?>style="background-color: #F1F5FF; color: #111827;" <?php endif; ?>>
                            <?php else: ?>
                                <div class="dark:border-white dark:border p-8 rounded-md dark:first:border-l-4 dark:first:border-solid dark:first:border-t-0 dark:first:border-b-0 dark:first:border-r-0 dark:first:border-accent first:border-l-4 border-solid border-accent flex flex-col gap-4 first:text-themedark bg-theme text-themelight hover:-translate-y-2 duration-300"
                                    <?php if ($index === 0): ?>style="background-color: #F1F5FF; color: #111827;" <?php endif; ?>>
                                <?php endif; ?>
                                <p class="font-bold"><?php echo esc_html($item['item_title']); ?></p>
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
</div>