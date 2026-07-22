<?php
$section_title = (string) get_field('section_title');

$page_context_raw = (string) get_field('page_context');
$is_seo_page = $page_context_raw === 'minimal';

$items_raw = get_field('items');
$items_raw = is_array($items_raw) ? $items_raw : [];

$items = [];
foreach ($items_raw as $row) {
    $icon_raw = isset($row['icon']) && is_array($row['icon']) ? $row['icon'] : [];
    $icon_url = isset($icon_raw['url']) ? (string) $icon_raw['url'] : '';
    $icon_alt = isset($icon_raw['alt']) ? (string) $icon_raw['alt'] : '';

    $button_raw = isset($row['button']) && is_array($row['button']) ? $row['button'] : [];
    $button_url = isset($button_raw['url']) ? (string) $button_raw['url'] : '';
    $button_title = isset($button_raw['title']) ? (string) $button_raw['title'] : '';
    $button_target_raw = isset($button_raw['target']) ? (string) $button_raw['target'] : '_self';
    $button_target = in_array($button_target_raw, ['_self', '_blank', '_parent', '_top'], true) ? $button_target_raw : '_self';

    $items[] = [
        'icon_url' => $icon_url,
        'icon_alt' => $icon_alt,
        'title' => isset($row['title']) ? (string) $row['title'] : '',
        'text' => isset($row['text']) ? (string) $row['text'] : '',
        'button_url' => $button_url,
        'button_title' => $button_title,
        'button_target' => $button_target,
        'has_button' => $button_url !== '',
        'has_icon' => $icon_url !== '',
    ];
}

$section_class = 'section dark:bg-theme dark:text-themelight my-12 list-row-03' . ($is_seo_page ? ' seo-section' : '');
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container list-row-03__container">
        <?php if ($section_title !== ''): ?>
            <h3 class="list-row-03__title mb-8 text-2xl font-bold"><?php echo esc_html($section_title); ?></h3>
        <?php endif; ?>

        <div class="list-row-03__grid grid md:grid-cols-3 gap-8 sm:gap-6">
            <?php foreach ($items as $item): ?>
                <?php if ($item['has_button']): ?>
                    <a style="background-color: #F1F5FF;"
                        class="list-row-03__card p-8 rounded-md flex flex-col gap-4 text-themedark hover:-translate-y-2 duration-300"
                        href="<?php echo esc_url($item['button_url']); ?>"
                        target="<?php echo esc_attr($item['button_target']); ?>"
                        rel="<?php echo esc_attr($item['button_target'] === '_blank' ? 'noopener noreferrer' : ''); ?>">
                    <?php else: ?>
                        <article style="background-color: #F1F5FF;"
                            class="list-row-03__card p-8 rounded-md flex flex-col gap-4 text-themedark hover:-translate-y-2 duration-300">
                        <?php endif; ?>
                        <?php if ($item['has_icon']): ?>
                            <figure class="list-row-03__icon-wrap">
                                <img class="w-6 h-6 list-row-03__icon" src="<?php echo esc_url($item['icon_url']); ?>"
                                    alt="<?php echo esc_attr($item['icon_alt']); ?>">
                            </figure>
                        <?php endif; ?>

                        <p class="list-row-03__card-title font-bold"><?php echo esc_html($item['title']); ?></p>
                        <p class="list-row-03__card-text"><?php echo nl2br(esc_html($item['text'])); ?></p>

                        <?php if ($item['button_title'] !== ''): ?>
                            <span class="list-row-03__card-button mt-auto underline font-semibold">
                                <?php echo esc_html($item['button_title']); ?>
                            </span>
                        <?php endif; ?>
                        <?php if ($item['has_button']): ?>
                    </a>
                <?php else: ?>
                    </article>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>