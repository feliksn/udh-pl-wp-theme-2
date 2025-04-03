<?php get_header(); ?>

<h1><?php printf('Wynik: %s', get_search_query()); ?></h1>
<?php get_template_part("loop"); ?>				

<?php get_footer(); ?>