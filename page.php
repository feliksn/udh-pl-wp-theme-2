<?php get_header(); ?>

<h1>Strona pojedyncza</h1>
<?php while( have_posts() ) { ?>
	<?php the_post(); ?>
	<h2><?php the_title(); ?></h2>
	<div><?php the_content(); ?></div>			
<?php } ?>

<?php get_footer(); ?>