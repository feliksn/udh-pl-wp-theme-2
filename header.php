<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body>
	<header class="mb-3 border-bottom">
		<div class="container">
			<?php
				wp_nav_menu([
					'container' => '',
					'menu_id' => 'menu-list',
					'menu_class' => 'nav list-unstyled'
				]); 
			?>
		</div>
	</header>