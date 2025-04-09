<?php get_header(); ?>

<div class="container">
    <?php $query = new WP_Query('post_type=brand'); ?>
    <?php get_template_part('loop', null, $query); ?>
</div>

<?php get_footer(); ?>