<?php
$content = (string) get_field('content');
?>

<section class="wysiwyg-block section">
    <div class="container content">
        <div class="wysiwyg-block__content">
            <?php echo wp_kses_post($content); ?>
        </div>
    </div>
</section>