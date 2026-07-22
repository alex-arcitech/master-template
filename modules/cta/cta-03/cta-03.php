<?php
$mode_raw = (string) get_field('mode');
$mode = in_array($mode_raw, ['light', 'dark'], true) ? $mode_raw : 'light';

$page_context_raw = (string) get_field('page_context');
$is_seo_page = $page_context_raw === 'minimal';

$section_title = (string) get_field('section_title');

$button_raw = get_field('button');
$button = is_array($button_raw) ? $button_raw : [];
$button_url = isset($button['url']) ? (string) $button['url'] : '';
$button_label = isset($button['title']) ? (string) $button['title'] : '';
$button_target_raw = isset($button['target']) && $button['target'] !== '' ? (string) $button['target'] : '_self';
$button_target = in_array($button_target_raw, ['_self', '_blank', '_parent', '_top'], true) ? $button_target_raw : '_self';
$has_button = $button_url !== '' && $button_label !== '';
$button_class = $mode === 'dark' ? 'third' : 'theme';
$section_class = 'section dark:bg-theme' . ($is_seo_page ? ' seo-section' : '');
?>

<div class="<?php echo esc_attr($mode); ?>">
    <section class="<?php echo esc_attr($section_class); ?>">
        <div class="container">
            <div
                class=" bg-theme dark:bg-accent rounded-md text-themelight p-10 md:p-20 text-left flex flex-col items-left gap-10 lg:flex-row lg:items-center justify-between">
                <h2 class="text-4xl leading-[3rem] md:text-5xl max-w-2xl md:leading-[4rem]">
                    <?php echo esc_html($section_title); ?></h2>
                <?php if ($has_button): ?>
                    <a href="<?php echo esc_url($button_url); ?>" target="<?php echo esc_attr($button_target); ?>"
                        class="btn <?php echo esc_attr($button_class); ?>"><?php echo esc_html($button_label); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>