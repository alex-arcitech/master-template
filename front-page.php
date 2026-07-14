<?php
get_header();
?>

<main class="w-full h-full bg-white" id="site-content" role="main">
    <div class="h-screen w-full bg-theme flex justify-center items-center">
        <h1 class="text-white">Dummy Hero</h1>
    </div>
    <?php
    // Render editor-inserted blocks, filtered by an allowed list for this template
    $allowed_blocks = allowed_blocks(get_post());

    // Parse the post content and render only allowed blocks
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
