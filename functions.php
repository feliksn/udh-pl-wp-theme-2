<?php

// Custom functions write with "_" at the beginning of the name to separate from other functions
// Display data in the var
function _raw( $var ){
    echo "<pre>" . print_r( $var, true ) . "</pre>";
}

// Add a menu in an admin panel
add_theme_support("menus");

// Add a title tag support in a browser tab
add_theme_support('title-tag');

// Add post thumbnails support to the theme
add_theme_support('post-thumbnails');
//set_post_thumbnail_size(250, 150);
//add_image_size('big-thumb', 400, 400, true);

// Change a class for a nav item
add_filter( 'nav_menu_css_class', 'change_menu_item_css_classes', 10, 1 );
function change_menu_item_css_classes( $classes ) {
	$classes = ['nav-item'];
	return $classes;
}

// Change a class for a nav link
add_filter( 'nav_menu_link_attributes', 'filter_nav_menu_link_attributes', 10, 1 );
function filter_nav_menu_link_attributes( $atts ) {
	$atts['class'] = 'nav-link';
	return $atts;
}

// styles and scripts
add_action('wp_enqueue_scripts', 'add_scripts');
function add_scripts() {
	// Theme sytles
	wp_enqueue_style( 'bs', get_template_directory_uri() .'/lib/bootstrap/bootstrap.min.css' );
	wp_enqueue_style( 'bs-icons', get_template_directory_uri() . '/lib/bootstrap/bootstrap-icons.min.css' );
	wp_enqueue_style( 'main', get_template_directory_uri() . '/css/main.css');
	// Theme scripts
	wp_enqueue_script('jq', get_template_directory_uri() . '/lib/jquery/jquery-3.7.1.min.js',[],'',true);
	wp_enqueue_script('bootstrap', get_template_directory_uri().'/lib/bootstrap/bootstrap.bundle.min.js',[],'',true);
	wp_enqueue_script('main', get_template_directory_uri() . '/js/main.js', [], '', true);
}

// Disable comments for media files
add_filter( 'comments_open', 'filter_media_comment_status', 10 , 2 );
function filter_media_comment_status( $open, $post_id ) {
   $post = get_post( $post_id );
   if( $post->post_type == 'attachment' ) return false;
   return $open;
}

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
   remove_menu_page('edit-comments.php');
});

// Disable support for comments and trackbacks in post types
add_action('admin_init', 'disable_comments_post_types_support');
function disable_comments_post_types_support() {
   $post_types = get_post_types();
   foreach ($post_types as $post_type) {
       if(post_type_supports($post_type, 'comments')) {
           remove_post_type_support($post_type, 'comments');
           remove_post_type_support($post_type, 'trackbacks');
       }
   }
}

// Remove comments metabox from dashboard
add_action('admin_init', 'disable_comments_dashboard');
function disable_comments_dashboard() {
   remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}

// Remove comments links from admin bar
add_action('admin_init', function () {
   if (is_admin_bar_showing()) {
       remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
   }
});

// Redirect any user trying to access comments page
add_action('admin_init', 'disable_comments_admin_menu_redirect');
function disable_comments_admin_menu_redirect() {
   global $pagenow;
   if ($pagenow === 'edit-comments.php') {
       wp_redirect(admin_url());
		exit;
   }
}

?>
