<?php get_header(); ?>

<div class="container p-3">
	<?php while( have_posts() ) { ?>
		<?php the_post(); ?>
		<?php $product = getProduct(); ?>
		
		<!-- A product title -->
		<h2 class="mb-5"><?php the_title(); ?></h2>
		<div class="row">
			<!-- A product image -->
			<div class="col">
				<img
					class="img-fluid p-3 shadow-sm border border-light"
					src="<?php echo $product['volumes'][0]['single_image_url'] ?>"
					alt="">
			</div>

			<!-- Product contents and product volumes data -->
			<div class="col ps-5">
				<p class="fw-semibold">
					<?php echo $product['category']->name; ?><br>
					<?php echo $product['subcategory']->name; ?>
				</p>
				<p>
					Zawartość alkoholu: <strong><?php echo $product['content_1']; ?></strong><br>
					Zawartość ekstraktu: <strong><?php echo $product['content_2']; ?></strong>
				</p>
				<p>
					<!-- Run a loop for volumes array ??? -->
					<?php echo $product['volumes'][0]['shape_type']; ?>
					<?php echo $product['volumes'][0]['volume_val']; ?>
					x <?php echo $product['volumes'][0]['pcs_in_pack']; ?>
					szt. w opakowaniu<br>
					<?php echo $product['volumes'][0]['shape_type']; ?>
					<?php echo $product['volumes'][0]['volume_val']; ?>
					x <?php echo $product['volumes'][0]['pcs_on_pallete']; ?>
					szt. na palecie
				</p>
			</div>
		</div>
	
	<?php } ?>
</div>

<?php get_footer(); ?>
