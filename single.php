<?php get_header(); ?>
<main class="wrap">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
            get_template_part('template-parts/content', get_post_type());
            // comments_template(); // enable if needed
        endwhile;
    endif;
    ?>
</main>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
