<?php if( $args ) $query = new WP_Query( $args ) ?>

<div class="row row-cols-4 g-0">
	<?php if ( isset( $query ) && $query->have_posts() || have_posts() ) { ?>
		<?php while ( isset( $query ) && $query->have_posts() || have_posts() ) { ?>
			<?php isset( $query ) ? $query->the_post() : the_post(); ?>
			
			<!-- getPost() checks which current post type is in the loop -->
			<!-- Don't use $post!!! It is a global var. It could cause an error; -->
			<?php $_post = getPost(); ?>

			<!-- A brand in a loop -->
			<div id="post-<?php the_ID(); ?>" <?php post_class('col'); ?>>
				
				<!-- A brand image as a link -->
				<a href="<?php the_permalink(); ?>">
					<img class="w-100" src="<?php echo $_post['image_url'] ?>" alt="">
				</a>
				
				<div class="p-3">
					<!-- A brand name if a brand logo doesn't exist -->
					<a class="d-block mb-3" href="<?php the_permalink(); ?>">
						<?php if( isset( $_post['logo_url'] ) ){ ?>
							<img src="<?php echo $_post['logo_url'] ?>" alt="">
						<?php } else { ?>
							<h2><?php the_title(); ?></h2>
						<?php } ?> 
					</a>
					<!-- A brand description -->
					<?php echo $_post['description']; ?>
				</div>

			</div>
		<?php } ?>
	<?php } else { ?>
		
		<h4>Wpisów nie odnaleziono!</h4>
	
	<?php } ?>
	<?php wp_reset_postdata(); ?>
</div>