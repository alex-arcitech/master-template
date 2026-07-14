<?php
get_header();
?>

<main class="bg-white w-full h-full" id="site-content" role="main">
    <?php
    // Render only allowed editor blocks for generic pages
    $allowed_blocks = allowed_blocks(get_post());

    $post = get_post();
    if ($post) {
        $blocks = parse_blocks($post->post_content);
        foreach ($blocks as $block) {
            $name = isset($block['blockName']) ? $block['blockName'] : null;
            if ($name && in_array($name, $allowed_blocks, true)) {
                echo render_block($block);
            }
        }
    }
    ?>

</main>

<?php
get_footer();
