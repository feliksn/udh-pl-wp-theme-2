<?php
	$post_type = $args;
	$posts = get_posts('post_type='. $post_type);
?>

<div class="row row-cols-4 g-0">
	<?php if ( $args->have_posts() ) { ?>
		<?php while ( $args->have_posts() ) { ?>
			<?php $args->the_post(); ?>
			
			<?php
				$parsed_content = PARSER->load( get_the_content() );
				$tbody_rows = $parsed_content->find('#brand_images tbody tr');
				foreach($tbody_rows as $tr){
					$logo_img     = $tr->find('td', 0)->find('img', 0);
					$logo_img_id  = str_replace('wp-image-', '', $logo_img->class);
					$logo_img_url = wp_get_attachment_image_url($logo_img_id);

					$post_img     = $tr->find('td', 1)->find('img', 0);
					$post_img_id  = str_replace('wp-image-', '', $post_img->class);
					$post_img_url = wp_get_attachment_image_url($post_img_id, 'medium');
				}
				$description = $parsed_content->getElementById("#brand_description");
			?>
			
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