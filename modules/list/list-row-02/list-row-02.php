<?php
$mode_raw = (string) get_field('mode');
$mode = in_array($mode_raw, ['light', 'dark'], true) ? $mode_raw : 'dark';

$show_title = (bool) get_field('show_title');
$section_title = (string) get_field('section_title');

$items_raw = get_field('items');
$items_raw = is_array($items_raw) ? $items_raw : [];

$items = [];
foreach ($items_raw as $row) {
    $item_title = isset($row['item_title']) ? (string) $row['item_title'] : '';
    $item_description = isset($row['item_description']) ? (string) $row['item_description'] : '';

    $items[] = [
        'item_title' => $item_title,
        'item_description' => $item_description,
    ];
}
?>
<div class="<?php echo esc_attr($mode); ?>">
    <section class="section dark:bg-theme dark:text-themelight mb-12">
        <div class="container">
            <div class="flex flex-col gap-8 items-start">
                <?php if ($show_title && $section_title !== ''): ?>
                    <h2><?php echo esc_html($section_title); ?></h2>
                <?php endif; ?>
                <div class="grid md:grid-cols-3 gap-10 sm:gap-6">

                    <?php foreach ($items as $index => $item): ?>
                        <div class="dark:border-white dark:border p-8 rounded-md dark:first:border-l-4 dark:first:border-solid dark:first:border-t-0 dark:first:border-b-0 dark:first:border-r-0 dark:first:border-accent first:border-l-4 border-solid border-accent flex flex-col gap-4 first:text-themedark bg-theme text-themelight"
                            <?php if ($index === 0): ?>style="background-color: #F1F5FF; color: #111827;" <?php endif; ?>>
                            <p class="font-bold"><?php echo esc_html($item['item_title']); ?></p>
                            <p><?php echo wp_kses_post($item['item_description']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>


            </div>
        </div>
    </section>
</div>