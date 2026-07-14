<?php
$section_title = (string) get_field('section_title');
$section_text = (string) get_field('section_text');
$fluentform_shortcode = trim((string) get_field('fluentform_shortcode'));
$has_shortcode = $fluentform_shortcode !== '';
?>

<section class="section">
    <div class="container flex flex-col items-center">
        <div class="text-center flex flex-col gap-5 mb-6">
            <h2><?php echo esc_html($section_title); ?></h2>
            <p><?php echo nl2br(esc_html($section_text)); ?></p>
        </div>
        <?php if ($has_shortcode): ?>
            <?php echo do_shortcode($fluentform_shortcode); ?>
        <?php endif; ?>
    </div>
</section>