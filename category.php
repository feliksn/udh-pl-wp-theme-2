<?php get_header(); ?>

<div class="container">
    <h1><?php single_cat_title(); ?></h1>
    <?php get_template_part('loop'); ?>
</div>

<?php get_footer(); ?>