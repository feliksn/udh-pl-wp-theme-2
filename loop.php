<div class="row row-cols-4">
	<?php if ( have_posts() ) { ?>
		<?php while ( have_posts() ) { ?>
			<?php the_post(); ?>
		
		<?php } ?>
	<?php } else { ?>
		<p>Wpisów nie odnaleziono!</p>;
	<?php } ?>
</div>