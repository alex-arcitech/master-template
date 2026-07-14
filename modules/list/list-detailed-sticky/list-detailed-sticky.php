<?php
$rows_raw = get_field('sections');
$rows_raw = is_array($rows_raw) ? $rows_raw : [];

$rows = [];
foreach ($rows_raw as $row) {
    $image = isset($row['image']) && is_array($row['image']) ? $row['image'] : [];
    $content = isset($row['content']) && is_array($row['content']) ? $row['content'] : [];
    $image_url = isset($image['url']) ? (string) $image['url'] : '';
    $image_alt = isset($image['alt']) && $image['alt'] !== '' ? (string) $image['alt'] : '';
    $title = isset($content['title']) ? (string) $content['title'] : '';
    $text = isset($content['text']) ? (string) $content['text'] : '';

    $rows[] = [
        'image_url' => $image_url,
        'image_alt' => $image_alt,
        'title' => $title,
        'text' => $text,
    ];
}
?>

<section class="section list-detailed-sticky-module">
    <div class="container content space-y-20 md:space-y-32">
        <?php foreach ($rows as $index => $row): ?>
            <?php $is_odd_row = ($index % 2) === 1; ?>
            <div class="grid items-start gap-10 py-0 md:min-h-0 md:grid-cols-2 md:gap-20 md:py-0">
                <div
                    class="mx-auto w-full max-w-[500px] <?php echo esc_attr($is_odd_row ? 'md:order-2 md:ml-auto md:mr-0' : 'md:order-1 md:mr-auto md:ml-0'); ?>">
                    <img class="block aspect-square w-full overflow-hidden rounded-[20px] object-cover"
                        src="<?php echo esc_url($row['image_url']); ?>" alt="<?php echo esc_attr($row['image_alt']); ?>">
                </div>

                <div
                    class="mx-auto flex w-full max-w-sm flex-col gap-8 self-start md:sticky md:top-28 md:mx-0 <?php echo esc_attr($is_odd_row ? 'md:order-1' : 'md:order-2'); ?>">
                    <h2><?php echo esc_html($row['title']); ?></h2>
                    <p><?php echo wp_kses_post($row['text']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>