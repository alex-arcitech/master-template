<?php
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

<section class="section">
    <div class="container">
        <div class="bg-theme flex flex-col gap-8 items-center rounded-md px-12 py-20 text-themelight">
            <h2 class="text-center max-w-sm"><?php echo esc_html($section_title); ?></h2>

            <div class="grid md:grid-cols-3 gap-10 sm:gap-6">

                <?php foreach ($items as $item): ?>
                    <div class="pl-8 border-l-4 border-solid border-accent flex flex-col gap-4">
                        <p class="text-lg font-semibold"><?php echo esc_html($item['item_title']); ?></p>
                        <p><?php echo wp_kses_post($item['item_description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>


        </div>
    </div>
</section>