<?php get_header(); ?>

<div class="container">
    <?php $query = [ 'post_type' => 'brand' ] ?>
    <?php get_template_part( 'loop', null, $query ); ?>
</div>

<?php get_footer(); ?>