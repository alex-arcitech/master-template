<?php
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
foreach ($items_raw as $item) {
    $items[] = [
        'title' => isset($item['title']) ? (string) $item['title'] : '',
        'text' => isset($item['text']) ? (string) $item['text'] : '',
    ];
}
?>

<section class="section list-detailed-text-scroll-module bg-themelight md:pt-28 lg:pt-36"
    data-list-detailed-text-scroll>
    <div class="container content grid gap-10 md:grid-cols-2 md:items-start">

        <div class="mx-auto flex max-w-sm flex-col gap-8 self-start text-left md:sticky md:top-[30vh] md:mx-0">
            <h2><?php echo esc_html($section_title); ?></h2>
            <p><?php echo wp_kses_post($section_text); ?></p>
            <a class="btn theme" href="<?php echo esc_url($button_url); ?>"
                target="<?php echo esc_attr($button_target); ?>"><?php echo esc_html($button_label); ?></a>
        </div>
        <div class="grid justify-items-center gap-4 md:justify-items-start">

            <?php foreach ($items as $item): ?>
                <div class="list-detailed-text-scroll-card flex max-w-md flex-col gap-8 rounded-md bg-white p-8 text-left"
                    data-list-detailed-text-scroll-card>
                    <p class="text-lg font-medium"><?php echo esc_html($item['title']); ?></p>
                    <p><?php echo wp_kses_post($item['text']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>



    </div>
</section>