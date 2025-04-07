<div class="row row-cols-4">
	<?php if ( have_posts() ) { ?>
		<?php while ( have_posts() ) { ?>
			<?php the_post(); ?>
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
			?>

		<?php } ?>
	<?php } else { ?>
		<p>Wpisów nie odnaleziono!</p>;
	<?php } ?>
</div>