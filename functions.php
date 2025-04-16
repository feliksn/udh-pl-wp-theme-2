<?php
// Add a composer packages path
include_once 'vendor/autoload.php';
use simplehtmldom\HtmlDocument;
const PARSER = new HtmlDocument();

// Display data in the var
function showVarData( $var ){
    echo "<pre>" . print_r( $var, true ) . "</pre>";
}

// Get a parsed content from WP function
function getParsedContent(){
	return PARSER->load( get_the_content() );
}

// Get a category an a subcategory from a product
function getCategories(){
	foreach( get_the_category() as $category){
		if( $category->parent == 0) $categories['category'] = $category;			
		else						$categories['subcategory'] = $category;
	}
	return $categories;
}

// Get an image url from parsed html <table> in a <tr> tag
function getImageUrlFromTd( $tr, $td_index, $size='thumbnail' ){
	$img = $tr->find( 'td', $td_index )->find( 'img', 0 );
	if($img){
		$img_id  = str_replace( 'wp-image-', '', $img->class );
		$img_url = wp_get_attachment_image_url( $img_id, $size );
		return $img_url;
	}
}

// Get brand data from a parsed content using a brand wp pattern
function getBrand(){
	$parsedContent		= getParsedContent();
	$brand['description']= $parsedContent->find( "#brand_description", 0 )->outertext;
	$brand_images		= $parsedContent->find( '#brand_images tbody tr', 0 );
	$brand['logo_url']  = getImageUrlFromTd( $brand_images, 0 );
	$brand['image_url'] = getImageUrlFromTd( $brand_images, 1, 'medium' );
	$brand['cover_url'] = getImageUrlFromTd( $brand_images, 2, 'full' );
	return $brand;
}

// Get product data from a parsed content using a product wp pattern
function getProduct(){
	$categories 			= getCategories();
	$product['category'] 	= $categories['category'];
	$product['subcategory'] = $categories['subcategory'];
	$parsedContent 			= getParsedContent();
	$product['description'] = $parsedContent->find( "#product_description", 0 )->outertext; 
	$product_contents 		= $parsedContent->find( '#product_contents tbody tr', 0 );
	$product['content_1'] 	= $product_contents->find( 'td', 0 );
	$product['content_2'] 	= $product_contents->find( 'td', 1 );
	$product_volumes 		= $parsedContent->find( '#product_volumes tbody tr' );
	foreach( $product_volumes as $product_volume ){
		$product['volumes'][] = [
			'single_image_url'  => getImageUrlFromTd( $product_volume, 0, 'medium_large' ),
			'image_type'        => $product_volume->find( 'td', 1 ),
			'pack_image_url'    => getImageUrlFromTd( $product_volume, 2, 'medium' ),
			'pallete_image_url' => getImageUrlFromTd( $product_volume, 3, 'medium' ),
			'shape_type'        => $product_volume->find( 'td', 4 ),
			'volume_val'        => $product_volume->find( 'td', 5 ),
			'pcs_in_pack'       => $product_volume->find( 'td', 6 ),
			'pcs_on_pallete'    => $product_volume->find( 'td', 7 ),
			'thubmnail_height'  => $product_volume->find( 'td', 8 )
		];	
	}
	$product['image_url'] = $product['volumes'][0]['single_image_url'];
	return $product;
}

// Get post data by post type
function getPost(){
	$post_type = get_post_type();
	if( $post_type == 'brand' ) return getBrand();
	if( $post_type == 'product' ) return getProduct();
}

// Add a menu in an admin panel
add_theme_support("menus");

// Add a title tag support in a browser tab
add_theme_support('title-tag');

// Add post thumbnails support to the theme
add_theme_support('post-thumbnails');

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
   remove_menu_page('edit.php');
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

// Register custom post types
add_action( 'init', 'register_post_types' );
function register_post_types(){
	// brand
	register_post_type( 'brand', [
		'label'  => null,
		'labels' => [
			'name'               => 'Marki',
			'singular_name'      => 'Marka',
			'add_new'            => 'Dodaj markę',
			'add_new_item'       => 'Dodaj markę',
			'edit_item'          => 'Edytuj markę',
			'new_item'           => 'Nowa marka',
			'view_item'          => 'Oglądaj markę',
			'search_items'       => 'Szukaj marki',
			'not_found'          => 'Nie znaleziono',
			'not_found_in_trash' => 'Nie znaleziono w koszu',
			'menu_name'          => 'Marka',
		],
		'public'              => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => false,
		'show_in_rest'        => true,
		'menu_position'       => 5,
		'menu_icon'           => null,
		'hierarchical'        => true,
		'supports'            => array('title', 'editor', 'page-attributes'),
		'taxonomies'          => [ 'post_tag' ],
		'has_archive'         => false,
		'rewrite'             => true,
	] );
	// product
	register_post_type( 'product', [
		'label'  => null,
		'labels' => [
			'name'               => 'Produkty',
			'singular_name'      => 'Produkt',
			'add_new'            => 'Dodaj produkt',
			'add_new_item'       => 'Dodaj produkt',
			'edit_item'          => 'Edytuj produkt',
			'new_item'           => 'Nowy produkt',
			'view_item'          => 'Oglądaj produkt',
			'search_items'       => 'Szukaj produkt',
			'not_found'          => 'Nie znaleziono',
			'not_found_in_trash' => 'Nie znaleziono w koszu',
			'menu_name'          => 'Produkt',
		],
		'public'              => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => false,
		'show_in_rest'        => true,
		'menu_position'       => 6,
		'menu_icon'           => null,
		'hierarchical'        => false,
		'supports'            => array('title', 'editor', 'page-attributes'),
		'taxonomies'          => [ 'category', 'post_tag' ],
		'has_archive'         => true,
		'rewrite'             => true,
	] );
}


?>
