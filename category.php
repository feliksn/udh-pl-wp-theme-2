<?php get_header(); ?>

<div class="container">
    <h1><?php single_cat_title(); ?></h1>
    <?php $query = [ 'post_type' => 'product'] ?>
    <?php get_template_part( 'loop', null, $query ); ?>
</div>

<?php get_footer(); ?>