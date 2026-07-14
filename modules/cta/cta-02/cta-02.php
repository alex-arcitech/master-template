<?php
$section_title = (string) get_field('section_title');

$button_raw = get_field('button');
$button = is_array($button_raw) ? $button_raw : [];
$button_url = isset($button['url']) ? (string) $button['url'] : '';
$button_label = isset($button['title']) ? (string) $button['title'] : '';
$button_target_raw = isset($button['target']) && $button['target'] !== '' ? (string) $button['target'] : '_self';
$button_target = in_array($button_target_raw, ['_self', '_blank', '_parent', '_top'], true) ? $button_target_raw : '_self';
$has_button = $button_url !== '' && $button_label !== '';
?>

<section class="section">
    <div class="container">
        <div class=" bg-theme rounded-md text-themelight p-10 md:p-20 text-left flex flex-col items-start gap-10">
            <h2 class="text-3xl leading-[3rem]  max-w-xl"><?php echo esc_html($section_title); ?></h2>
            <?php if ($has_button): ?>
                <a href="<?php echo esc_url($button_url); ?>" target="<?php echo esc_attr($button_target); ?>"
                    class="theme btn"><?php echo esc_html($button_label); ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>