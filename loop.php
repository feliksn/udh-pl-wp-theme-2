<?php if( $args ) $query = new WP_Query( $args ) ?>

<div class="row row-cols-4 g-0">
	<?php if ( isset( $query ) && $query->have_posts() || have_posts() ) { ?>
		<?php while ( isset( $query ) && $query->have_posts() || have_posts() ) { ?>
			<?php isset( $query ) ? $query->the_post() : the_post(); ?>
			
			<!-- getPost() checks which current post type is in the loop -->
			<!-- Don't use $post!!! It is a global var. It could cause an error; -->
			<?php $_post = getPost(); ?>
			
			<!-- Post in the loop -->
			<div id="post-<?php the_ID(); ?>" <?php post_class('col'); ?>>
				<a href="<?php the_permalink(); ?>">
					<img class="w-100" src="<?php echo $post_img_url ?>" alt="">
				</a>
				<div class="p-3">
					<a class="d-block mb-3" href="<?php the_permalink(); ?>">
						<img src="<?php echo $logo_img_url ?>" alt="">
					</a>
					<?php echo $description; ?>
				</div>
			</div>

		<?php } ?>
	<?php } else { ?>
		<p>Wpisów nie odnaleziono!</p>
	<?php } ?>
	<?php wp_reset_postdata(); ?>
</div>