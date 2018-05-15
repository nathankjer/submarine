<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package submarine
 */

update_option( 'woocommerce_thumbnail_cropping', '2:1' );

remove_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title',10);
add_action('woocommerce_shop_loop_item_title','change_product_title',10);
function change_product_title() {
   echo '<h4 class="d-flex justify-content-center text-dark">' . get_the_title() . '</h4>';
}

add_filter( 'get_product_search_form' , 'change_product_search_form' );

function change_product_search_form( $form ) {
	$form = '<form role="search" method="get" class="woocommerce-product-search" action="' . esc_url( home_url( '/'  ) )  . '">
				<div class="input-group">
					<input type="search" class="form-control" placeholder="' . esc_attr_x( 'Search Products&hellip;', 'placeholder', 'woocommerce' ) . '" value="' . get_search_query() . '" name="s" title="' . esc_attr_x( 'Search for:', 'label', 'woocommerce' ) . '" />
					<input type="submit" class="btn btn-primary" value="' . esc_attr_x( 'Search', 'submit button', 'woocommerce' ) . '" />
					<input type="hidden" name="post_type" value="product" />
				</div><!-- .input-group -->
			</form>';
	return $form;
}

remove_action('woocommerce_shop_loop_subcategory_title','woocommerce_template_loop_category_title',10);
add_action('woocommerce_shop_loop_subcategory_title','change_category_title',10);
function change_category_title( $category ) {
   echo '<h4 class="d-flex justify-content-center text-dark">' . $category->name . '</h4>';
}

if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}
}

add_filter('woocommerce_sale_flash', 'change_sale_flash', 10, 3);
function change_sale_flash($content, $post, $product){
   $content = '<span class="badge badge-pill badge-primary" style="position:absolute;top:0;right:0;left:auto;margin:-.5em -.5em 0 0;">' . __( 'Sale', 'submarine' ) . '</span>';
   return $content;
}

add_action('woocommerce_before_main_content', 'add_category_image', 10 );
function add_category_image(){

	if ( is_product_category() ) {
		echo '<div class="wrapper" id="wrapper-post-image">';
		global $wp_query;
		$cat = $wp_query->get_queried_object();
		$thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
		$image = wp_get_attachment_url( $thumbnail_id );
		if ( $image ) {
			echo '<img src="' . $image . '" class="img-responsive" alt="' . $cat->name . '" />';
		}
		echo '</div>';
	}
}

add_action('woocommerce_after_main_content', 'add_featured_carousel', 10 );
function add_featured_carousel(){

	if( is_shop() ) {
		$args = array(
		    'post_type' => 'product',
		    'posts_per_page' => 12,
		    'tax_query' => array(
		            array(
		                'taxonomy' => 'product_visibility',
		                'field'    => 'name',
		                'terms'    => 'featured',
		            ),
		        ),
		    );
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			echo '<h1>Specials</h1>';
			echo '<div class="products owl-carousel owl-theme">';
		    while ( $loop->have_posts() ) : $loop->the_post();
		    	echo '<div class="item">';
		    	echo '<a href="' . get_permalink() . '">';
		        echo woocommerce_get_product_thumbnail('full');
		        echo '<h4 class="text-dark text-center">' . get_the_title() . '</h4>';
		        echo '</a>';
		        echo '</div>';
		    endwhile;
			echo '</div><!--/.products-->';
		} else {
		}
		wp_reset_postdata();
	}
}

add_action('woocommerce_before_shop_loop', 'add_shop_search', 10 );
function add_shop_search(){
	the_widget( 'WC_Widget_Product_Search' );
}

function remove_downloads($items) {
    unset( $items['downloads'] );
    return $items;
}
add_filter('woocommerce_account_menu_items', 'remove_downloads', 10, 1);

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

add_action( 'after_setup_theme', 'submarine_woocommerce_support' );
if ( ! function_exists( 'submarine_woocommerce_support' ) ) {
	/**
	 * Declares WooCommerce theme support.
	 */
	function submarine_woocommerce_support() {
		add_theme_support( 'woocommerce' );
		
		// Add New Woocommerce 3.0.0 Product Gallery support
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-slider' );

		// hook in and customizer form fields.
		add_filter( 'woocommerce_form_field_args', 'submarine_wc_form_field_args', 10, 3 );
	}
}


/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
* Then hook in your own functions to display the wrappers your theme requires
*/
add_action('woocommerce_before_main_content', 'submarine_woocommerce_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'submarine_woocommerce_wrapper_end', 10);
if ( ! function_exists( 'submarine_woocommerce_wrapper_start' ) ) {
	function submarine_woocommerce_wrapper_start() {
		$container   = get_theme_mod( 'submarine_container_type' );
		echo '<div class="wrapper" id="woocommerce-wrapper">';
	  echo '<div class="' . esc_attr( $container ) . '" id="content" tabindex="-1">';
		echo '<div class="row">';
		get_template_part( 'global-templates/left-sidebar-check' );
		echo '<main class="site-main" id="main">';
	}
}
if ( ! function_exists( 'submarine_woocommerce_wrapper_end' ) ) {
function submarine_woocommerce_wrapper_end() {
	echo '</main><!-- #main -->';
	echo '</div><!-- #primary -->';
	get_template_part( 'global-templates/right-sidebar-check' );
  echo '</div><!-- .row -->';
	echo '</div><!-- Container end -->';
	echo '</div><!-- Wrapper end -->';
	}
}


/**
 * Filter hook function monkey patching form classes
 * Author: Adriano Monecchi http://stackoverflow.com/a/36724593/307826
 *
 * @param string $args Form attributes.
 * @param string $key Not in use.
 * @param null   $value Not in use.
 *
 * @return mixed
 */
if ( ! function_exists ( 'submarine_wc_form_field_args' ) ) {
	function submarine_wc_form_field_args( $args, $key, $value = null ) {
		// Start field type switch case.
		switch ( $args['type'] ) {
			/* Targets all select input type elements, except the country and state select input types */
			case 'select' :
				// Add a class to the field's html element wrapper - woocommerce
				// input types (fields) are often wrapped within a <p></p> tag.
				$args['class'][] = 'form-group';
				// Add a class to the form input itself.
				$args['input_class']       = array( 'form-control', 'input-lg' );
				$args['label_class']       = array( 'control-label' );
				$args['custom_attributes'] = array(
					'data-plugin'      => 'select2',
					'data-allow-clear' => 'true',
					'aria-hidden'      => 'true',
					// Add custom data attributes to the form input itself.
				);
				break;
			// By default WooCommerce will populate a select with the country names - $args
			// defined for this specific input type targets only the country select element.
			case 'country' :
				$args['class'][]     = 'form-group single-country';
				$args['label_class'] = array( 'control-label' );
				break;
			// By default WooCommerce will populate a select with state names - $args defined
			// for this specific input type targets only the country select element.
			case 'state' :
				// Add class to the field's html element wrapper.
				$args['class'][] = 'form-group';
				// add class to the form input itself.
				$args['input_class']       = array( '', 'input-lg' );
				$args['label_class']       = array( 'control-label' );
				$args['custom_attributes'] = array(
					'data-plugin'      => 'select2',
					'data-allow-clear' => 'true',
					'aria-hidden'      => 'true',
				);
				break;
			case 'password' :
			case 'text' :
			case 'email' :
			case 'tel' :
			case 'number' :
				$args['class'][]     = 'form-group';
				$args['input_class'] = array( 'form-control', 'input-lg' );
				$args['label_class'] = array( 'control-label' );
				break;
			case 'textarea' :
				$args['input_class'] = array( 'form-control', 'input-lg' );
				$args['label_class'] = array( 'control-label' );
				break;
			case 'checkbox' :
				$args['label_class'] = array( 'custom-control custom-checkbox' );
				$args['input_class'] = array( 'custom-control-input', 'input-lg' );
				break;
			case 'radio' :
				$args['label_class'] = array( 'custom-control custom-radio' );
				$args['input_class'] = array( 'custom-control-input', 'input-lg' );
				break;
			default :
				$args['class'][]     = 'form-group';
				$args['input_class'] = array( 'form-control', 'input-lg' );
				$args['label_class'] = array( 'control-label' );
				break;
		} // end switch ($args).
		return $args;
	}
}


/**
* Change loop add-to-cart button class to Bootstrap
*/
add_filter( 'woocommerce_loop_add_to_cart_args', 'submarine_woocommerce_add_to_cart_args', 10, 2 );

if ( ! function_exists ( 'submarine_woocommerce_add_to_cart_args' ) ) {
	function submarine_woocommerce_add_to_cart_args( $args, $product ) {
		$args['class'] = str_replace('button','btn btn-outline-primary', 'button');
		return $args;
	}
}
