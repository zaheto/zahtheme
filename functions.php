<?php


/* Disable WordPress Admin Bar for all users */
add_filter( 'show_admin_bar', '__return_false' );

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/

if (! file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'sage'));
}

require $composer;

/*
|--------------------------------------------------------------------------
| Register The Bootloader
|--------------------------------------------------------------------------
|
| The first thing we will do is schedule a new Acorn application container
| to boot when WordPress is finished loading the theme. The application
| serves as the "glue" for all the components of Laravel and is
| the IoC container for the system binding all of the various parts.
|
*/

if (! function_exists('\Roots\bootloader')) {
    wp_die(
        __('You need to install Acorn to use this theme.', 'sage'),
        '',
        [
            'link_url' => 'https://roots.io/acorn/docs/installation/',
            'link_text' => __('Acorn Docs: Installation', 'sage'),
        ]
    );
}

\Roots\bootloader()->boot();

/*
|--------------------------------------------------------------------------
| Register Sage Theme Files
|--------------------------------------------------------------------------
|
| Out of the box, Sage ships with categorically named theme files
| containing common functionality and setup to be bootstrapped with your
| theme. Simply add (or remove) files from the array below to change what
| is registered alongside Sage.
|
*/

collect(['setup', 'filters'])
    ->each(function ($file) {
        if (! locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(
                /* translators: %s is replaced with the relative file path */
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file)
            );
        }
    });

add_filter('wp_nav_menu_items', 'do_shortcode');

/**
 * Enqueue product gallery script only on single product pages
 */
function zah_enqueue_product_gallery_script() {
    if (is_product()) {
        wp_enqueue_style('swiper-bundle', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), null);
        wp_enqueue_script('swiper-bundle', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true);
        
        wp_enqueue_script(
            'zah-product-gallery', 
            get_template_directory_uri() . '/resources/scripts/product-gallery.js',
            array('swiper-bundle', 'jquery'),
            filemtime(get_template_directory() . '/resources/scripts/product-gallery.js'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'zah_enqueue_product_gallery_script');


function zah_enqueue_calculator_scripts() {


    // Check if script file exists
    $script_path = get_template_directory() . '/resources/scripts/subcategories-slider.js';

    // For the calculator template page
    if (is_page_template('template-calculator.blade.php')) {
        wp_enqueue_script('zah-calculator', 
            get_template_directory_uri() . '/resources/scripts/calculator.js', 
            array('jquery'), 
            null, 
            true
        );
    }

    // For product archive pages
    if (is_product_category() || is_shop()) {  // Add is_shop() check here
        //error_log('Attempting to enqueue subcategories slider');
        
        // Enqueue Swiper first
        wp_enqueue_script('swiper-js',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            array(),
            '11.0.5',
            true
        );

        // Enqueue slider script
        wp_enqueue_script('zah-subcategories-slider', 
            get_template_directory_uri() . '/resources/scripts/subcategories-slider.js', 
            array('swiper-js', 'jquery'), 
            filemtime($script_path), // Add version based on file modification time
            true
        );

        // Add test console log
        // wp_add_inline_script('zah-subcategories-slider', 
        //     'console.log("Subcategories slider script enqueued at: " + new Date().toISOString());', 
        //     'before'
        // );

        // Enqueue Swiper CSS
        wp_enqueue_style('swiper-css',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
            array(),
            '11.0.5'
        );

        //error_log('Enqueue complete');
    }


    if (is_product()) {
        if (has_term('siding', 'product_tag')) {
            wp_enqueue_script('zah-siding-calculator', 
                get_template_directory_uri() . '/resources/scripts/siding-calculator.js', 
                array('jquery'), 
                null, 
                true
            );
        }

        if (has_term('atlas', 'product_tag')) {
            wp_enqueue_script('zah-atlas-calculator', 
                get_template_directory_uri() . '/resources/scripts/atlas-calculator.js', 
                array('jquery'), 
                null, 
                true
            );
        }
        if (has_term('sigma', 'product_tag')) {
            wp_enqueue_script('zah-sigma-calculator', 
                get_template_directory_uri() . '/resources/scripts/sigma-calculator.js', 
                array('jquery'), 
                null, 
                true
            );
        }
        if (has_term('gamma', 'product_tag')) {
            wp_enqueue_script('zah-gamma-calculator', 
                get_template_directory_uri() . '/resources/scripts/gamma-calculator.js', 
                array('jquery'), 
                null, 
                true
            );
        }
        if (has_term('piramida', 'product_tag')) {
            wp_enqueue_script('zah-piramida-calculator', 
                get_template_directory_uri() . '/resources/scripts/piramida-calculator.js', 
                array('jquery'), 
                null, 
                true
            );
        }
        if (has_term('terra', 'product_tag')) {
            wp_enqueue_script('zah-terra-calculator', 
                get_template_directory_uri() . '/resources/scripts/terra-calculator.js', 
                array('jquery'), 
                null, 
                true
            );
        }
    }

}
add_action('wp_enqueue_scripts', 'zah_enqueue_calculator_scripts');

// function custom_enqueue_woocommerce_scripts() {
//     if (function_exists('is_woocommerce')) {
//         wp_enqueue_script('wc-add-to-cart');
//         wp_enqueue_script('woocommerce');
//         wp_enqueue_script('wc-cart-fragments');
//         wp_enqueue_script('wc-add-to-cart-variation');
//     }
// }
// add_action('wp_enqueue_scripts', 'custom_enqueue_woocommerce_scripts', 999);


/**
 * Produces nice safe html for presentation.
 *
 * @param $input - accepts a string.
 * @return string
 */
function zah_safe_html( $input ) {

	$args = array(
		// formatting.
		'span'   => array(
			'class' => array(),
		),
		'h1'     => array(
			'class' => array(),
		),
		'h2'     => array(
			'class' => array(),
		),
		'h3'     => array(
			'class' => array(),
		),
		'h4'     => array(
			'class' => array(),
		),
		'del'    => array(),
		'ins'    => array(),
		'strong' => array(),
		'em'     => array(),
		'b'      => array(),
		'hr'     => array(),
		'i'      => array(
			'class' => array(),
		),
		'img'      => array(
			'href'        => array(),
			'alt'         => array(),
			'class'       => array(),
			'scale'       => array(),
			'width'       => array(),
			'height'      => array(),
			'src'         => array(),
			'srcset'      => array(),
			'sizes'       => array(),
			'data-src'    => array(),
			'data-srcset' => array(),
		),
		'p'     => array(
			'class' => array(),
		),
		'figure'     => array(
			'class' => array(),
		),
		'div'     => array(
			'class' => array(),
			'style' => array(),
		),
		'ul'     => array(
			'class' => array(),
		),
		'li'     => array(
			'class' => array(),
		),
		'mark'   => array(
			'class' => array(),
		),

		// links.
		'a'        => array(
			'href'            => array(),
			'data-product-id' => array(),
			'data-type'       => array(),
			'data-wpage'      => array(),
			'class'           => array(),
			'aria-label'      => array(),
			'target'          => array(),
		),
	);

	return wp_kses( $input, $args );
}

// Additional Custom Field For Product Taxonomy
if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title' 	=> 'Page Custom Settings',
        'menu_title'	=> 'Theme settings',
        'menu_slug' 	=> 'theme-general-settings',
        'capability'	=> 'edit_posts',
        'redirect'		=> false
    ));
}

function codeless_file_types_to_uploads($file_types){
    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $file_types = array_merge($file_types, $new_filetypes );
    return $file_types;
}
add_filter('upload_mimes', 'codeless_file_types_to_uploads');

if (!function_exists('hs_sample_setup_header')) :
    function hs_sample_setup_header()
    {
        register_nav_menus(
            array(
                'main_menu' => esc_html__('Main navigation', 'sage'),            )
        );
    }
    add_action('after_setup_theme', 'hs_sample_setup_header');
endif;

add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);
function special_nav_class($classes, $item){
     if( in_array('current-menu-item', $classes) ){
             $classes[] = 'active';
     }
     return $classes;
}

/**
 * @snippet       WooCommerce User Registration Shortcode
 */

 add_shortcode( 'wc_reg_form_bbloomer', 'bbloomer_separate_registration_form' );

 function bbloomer_separate_registration_form() {
    if ( is_user_logged_in() ) return '<p>You are already registered</p>';
    ob_start();
    do_action( 'woocommerce_before_customer_login_form' );
    $html = wc_get_template_html( 'myaccount/form-login.php' );
    $dom = new DOMDocument();
    $dom->encoding = 'utf-8';
    $dom->loadHTML( utf8_decode( $html ) );
    $xpath = new DOMXPath( $dom );
    $form = $xpath->query( '//form[contains(@class,"register")]' );
    $form = $form->item( 0 );
    echo '<div class="woocommerce">';
    echo $dom->saveXML( $form );
    echo '</div>';
    wp_enqueue_script( 'wc-password-strength-meter' );
    return ob_get_clean();
 }

 /**
 * @snippet       WooCommerce User Login Shortcode
 */

add_shortcode( 'wc_login_form_bbloomer', 'bbloomer_separate_login_form' );

function bbloomer_separate_login_form() {
    if ( is_user_logged_in() ) return '<p>You are already logged in</p>';
    ob_start();
    do_action( 'woocommerce_before_customer_login_form' );
    echo '<div class="woocommerce">';
    echo woocommerce_login_form( array( 'redirect' => wc_get_page_permalink( 'myaccount' ) ) );
    echo '</div>';

   return ob_get_clean();
}

/**
 * @snippet Redirect Login/Registration to My Account
 */

 add_action( 'template_redirect', 'bbloomer_redirect_login_registration_if_logged_in' );

 function bbloomer_redirect_login_registration_if_logged_in() {
     if ( is_page() && is_user_logged_in() && ( has_shortcode( get_the_content(), 'wc_login_form_bbloomer' ) || has_shortcode( get_the_content(), 'wc_reg_form_bbloomer' ) ) ) {
         wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
         exit;
     }
 }

 /**
 * @snippet       Custom Redirect for Registrations @ WooCommerce My Account
 */

add_filter( 'woocommerce_registration_redirect', 'bbloomer_customer_register_redirect' );

function bbloomer_customer_register_redirect( $redirect_url ) {
   $redirect_url = '/shop';
   return $redirect_url;
}

add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );


function by_wrap_title_open() {
	echo '<div id="buy-bottom" class="inside-product--top-wrap">';
}
add_action( 'woocommerce_before_single_product_summary', 'by_wrap_title_open', 1 );


function by_wrap_title_close() {
	echo '</div>';
}
add_action( 'woocommerce_after_single_product_summary', 'by_wrap_title_close', 10 );

/**
* WooCommerce Display Stock Availablity
*/

add_filter( 'woocommerce_get_availability', 'njengah_display_stock_availability', 1, 2);

function njengah_display_stock_availability( $availability, $_product ) {

   global $product;

   // Change In Stock Text
    if ( $_product->is_in_stock() ) {
        $availability['availability'] = __('In stock', 'zah');
    }

    // Change Out of Stock Text
    if ( ! $_product->is_in_stock() ) {
    	$availability['availability'] = __('Out of stock', 'zah');
    }

    return $availability;
}

//Product page breadcrumb
function zah_breadcumb() {
    static $displayed = false;
    if (!$displayed && function_exists('yoast_breadcrumb')) {
        $displayed = true;
        yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
        //error_log('Breadcrumb displayed.');
    } else {
        //error_log('Breadcrumb not displayed. Function exists: ' . (function_exists('yoast_breadcrumb') ? 'yes' : 'no'));
    }
}

// Hook the breadcrumb function to an appropriate action
add_action('woocommerce_before_main_content', 'zah_breadcumb', 5);

//Product loop open main container
add_action( 'woocommerce_before_shop_loop_item', 'zah_product_loop_main_container_open' );
function zah_product_loop_main_container_open() {
  echo '<div class="product-loop--container">';
}

//Product loop open div
add_action( 'woocommerce_shop_loop_item_title', 'zah_product_loop_open' );
function zah_product_loop_open() {
  echo '<div class="product-loop-title-container">';
}

//Product loop close div
add_action( 'woocommerce_after_shop_loop_item', 'zah_product_loop_close' );
function zah_product_loop_close() {
  echo '</div></div>';
}


//Product loop open div
add_action( 'woocommerce_before_add_to_cart_quantity', 'zah_product_button_open' );
function zah_product_button_open() {
  echo '<section class="inside-product--buy-buttons">';
}

//Product loop open div
add_action( 'woocommerce_after_add_to_cart_button', 'zah_product_button_close' );
function zah_product_button_close() {
  echo '</section>';
}

/**
 * @snippet       Hide ALL shipping rates in ALL zones when Free Shipping is available
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 6
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */

 add_filter( 'woocommerce_package_rates', 'bbloomer_unset_shipping_when_free_is_available_all_zones', 9999, 2 );

 function bbloomer_unset_shipping_when_free_is_available_all_zones( $rates, $package ) {
    $all_free_rates = array();
    foreach ( $rates as $rate_id => $rate ) {
       if ( 'free_shipping' === $rate->method_id ) {
          $all_free_rates[ $rate_id ] = $rate;
          break;
       }
    }
    if ( empty( $all_free_rates )) {
       return $rates;
    } else {
       return $all_free_rates;
    }
 }

 add_filter( 'woocommerce_checkout_fields' , 'quadlayers_remove_checkout_fields' );
 function quadlayers_remove_checkout_fields( $fields ) {

    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['shipping']['shipping_state']);

    return $fields;

}

/**
 * @snippet       Removes shipping method labels @ WooCommerce Cart / Checkout
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.9
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */

 add_filter( 'woocommerce_cart_shipping_method_full_label', 'bbloomer_remove_shipping_label', 9999, 2 );

 function bbloomer_remove_shipping_label( $label, $method ) {
     $new_label = preg_replace( '/^.+:/', '', $label );
     return $new_label;
 }

/**
 * @snippet       Product Images @ Woo Checkout
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 5
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */

add_filter( 'woocommerce_cart_item_name', 'bbloomer_product_image_review_order_checkout', 9999, 3 );

function bbloomer_product_image_review_order_checkout( $name, $cart_item, $cart_item_key ) {
     if ( ! is_checkout() ) return $name;
     $product = $cart_item['data'];
     $thumbnail = $product->get_image( array( '50', '50' ), array( 'class' => 'alignleft' ) );
     return $thumbnail . $name;
}

// function uwc_new_address_one_placeholder( $fields ) {
//     $fields['address_1']['label'] = 'Адрес за доставка';
//     $fields['address_1']['placeholder'] = 'Моля, въведете желания адрес за доставка или офис на ЕКОНТ';

//     return $fields;
// }
// add_filter( 'woocommerce_default_address_fields', 'uwc_new_address_one_placeholder' );


/**
 * Remove password strength check.
 */
function iconic_remove_password_strength() {
    wp_dequeue_script( 'wc-password-strength-meter' );
}
add_action( 'wp_print_scripts', 'iconic_remove_password_strength', 10 );

// Show only lowest prices in WooCommerce variable products

add_filter( 'woocommerce_variable_sale_price_html', 'wpglorify_variation_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'wpglorify_variation_price_format', 10, 2 );

function wpglorify_variation_price_format( $price, $product ) {

// Main Price
$prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
$price = $prices[0] !== $prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

// Sale Price
$prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
sort( $prices );
$saleprice = $prices[0] !== $prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

if ( $price !== $saleprice ) {
$price = '<del>' . $saleprice . $product->get_price_suffix() . '</del> <ins>' . $price . $product->get_price_suffix() . '</ins>';
}
return $price;
}


add_action( 'woocommerce_before_shop_loop_item_title', 'action_template_loop_product_thumbnail', 9 );
function action_template_loop_product_thumbnail() {
    global $product;

    $file = get_field('archive_video', $product->get_id());

    if( isset($file['url']) && ! empty($file['url']) ) {
        remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

        echo '<video width="200" muted loop autoplay src="' . $file['url'] . '"></video>';
    }
}


function custom_table_size_modal() {

    // Get the table size link from the ACF custom field
    $table_size_link = get_field('table_size_link', 'options');

    if ($table_size_link) {
        // Output the link and modal HTML
        echo '<a href="#" id="openTableSizeModal" class="link table-size">Таблица с размери</a>';


        echo '<div id="tableSizeModal" class="modal">
                <div class="modal-wrap">
                    <div class="modal-content">
                        <div class="modal-head">
                            <h4>Таблица с размери</h4>
                            <button id="closeTableSizeModal" class="close-btn"> &times; </button>
                        </div>
                        <div class="modal-middle">
                            <img src="' . esc_url($table_size_link) . '"  />
                        </div>
                    </div>
                </div>
              </div>';
    }
}

add_action('woocommerce_product_meta_start', 'custom_table_size_modal', 31);


function custom_features_product() {
    $feature_bar = get_field('feature_bar', 'options');

    if ($feature_bar) {
        echo '<section class="feature-bar "><div class="container">';
        echo '<ul>';
        foreach ($feature_bar as $item) {
            echo '<li>';
            echo '<span><img src="' . esc_html($item['feature_icon']) . '" /></span>';
            echo '<h3>' . esc_html($item['feature_text']) . '</h3>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</div></section>';
    }

}

add_action('before_footer_content', 'custom_features_product', 31);


add_filter( 'woocommerce_single_product_carousel_options', 'cuswoo_update_woo_flexslider_options' );
/**
 * Filer WooCommerce Flexslider options - Add Navigation Arrows
 */
function cuswoo_update_woo_flexslider_options( $options ) {

    $options['directionNav'] = true;

    return $options;
}


//Add class to body when cart is empty
function rp_woo_empty_cart_classes( $classes ){
	global $woocommerce;
    if( is_cart() && WC()->cart->cart_contents_count == 0){
		$classes[] = 'woocommerce-cart-empty';
    }
    return $classes;
}
add_filter( 'body_class', 'rp_woo_empty_cart_classes' );


add_filter('woocommerce_is_purchasable', 'filter_is_purchasable_callback', 10, 2 );
add_filter('woocommerce_variation_is_purchasable', 'filter_is_purchasable_callback', 10, 2 );
function filter_is_purchasable_callback( $purchasable, $product ) {
    if ( $product->get_stock_status() === 'out_of_stock' ) {
        return false;
    }

    return $purchasable;
}



// Adjust price based on options
function adjust_price_based_on_options($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    foreach ($cart->get_cart() as $cart_item) {
        if (isset($cart_item['selected_options'])) {
            $price_adjustment = calculate_wpcpo_price_adjustment($cart_item['selected_options']);
            $cart_item['data']->set_price($cart_item['data']->get_price() + $price_adjustment);
        }
    }
}
add_action('woocommerce_before_calculate_totals', 'adjust_price_based_on_options', 10, 1);



function calculate_wpcpo_price_adjustment($options) {
    $adjustment = 0;

    if (is_array($options) || is_object($options)) {
        foreach ($options as $option_name => $option_value) {
            // Placeholder function call, replace with actual function from the plugin
            $adjustment += WPCPO()->get_option_price($option_name, $option_value);
        }
    } else {
        //error_log('Options are not an array or object: ' . print_r($options, true));
    }

    return $adjustment;
}

function filter_woocommerce_product_cross_sells_products_heading( $string ) {
    // New text
    $string = __( 'My new text', 'zah' );

    return $string;
}
add_filter( 'woocommerce_product_cross_sells_products_heading', 'filter_woocommerce_product_cross_sells_products_heading', 10, 1 );

function add_product_tags_to_body_class( $classes ) {
    if ( is_singular( 'product' ) ) {
        global $post;
        $product = wc_get_product( $post->ID );
        if ( $product ) {
            $tags = get_the_terms( $post->ID, 'product_tag' );
            if ( $tags && ! is_wp_error( $tags ) ) {
                // Loop through each tag and add its slug to the body class
                foreach ( $tags as $tag ) {
                    $classes[] = 'product-tag-' . sanitize_html_class( $tag->slug );
                }
            }
        }
    }
    return $classes;
}
add_filter( 'body_class', 'add_product_tags_to_body_class' );



add_filter('wc_get_template_part', function($template, $slug, $name) {
    $template_path = 'woocommerce/' . $slug . '-' . $name . '.blade.php';

    if (file_exists(get_stylesheet_directory() . '/' . $template_path)) {
        return get_stylesheet_directory() . '/' . $template_path;
    }

    return $template;
}, 10, 3);


/////NEW CODE

if ( ! function_exists( 'zah_woo_cart_available' ) ) {
	/**
	 * Validates whether the Woo Cart instance is available in the request
	 *
	 * @since 2.6.0
	 * @return bool
	 */
	function zah_woo_cart_available() {
		$woo = WC();
		return $woo instanceof \WooCommerce && $woo->cart instanceof \WC_Cart;
	}
}

if ( ! function_exists( 'zah_is_woocommerce_activated' ) ) {
	/**
	 * Query WooCommerce activation
	 */
	function zah_is_woocommerce_activated() {
		$activated = class_exists( 'WooCommerce' ) ? true : false;
		if (!$activated) {
			//error_log('WooCommerce is not activated');
		}
		return $activated;
	}
}

/**
 * Enqueue quantity.js script only on single product and cart pages.
 */
function zah_enqueue_quantity_script() {
    if ( is_product() || is_cart() ) {
        wp_enqueue_script( 'zah-quantity', get_template_directory_uri() . '/resources/scripts/quantity.js', array(), '1.1.4', true );
    }
}
add_action( 'wp_enqueue_scripts', 'zah_enqueue_quantity_script' );



/**
* Checks if ACF is active.
*
* @return boolean
*/
if ( ! function_exists( 'zah_is_acf_activated' ) ) {
	/**
	 * Query ACF activation.
	 */
	function zah_is_acf_activated() {
		return class_exists( 'acf' ) ? true : false;
	}
}

if ( ! function_exists( 'zah_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments
	 * Ensure cart contents update when products are added to the cart via AJAX
	 *
	 * @param  array $fragments Fragments to refresh via AJAX.
	 * @return array            Fragments to refresh via AJAX
	 */
	function zah_cart_link_fragment( $fragments ) {
		global $woocommerce;

		ob_start();
		zah_cart_link();
		$fragments['div.cart-click'] = ob_get_clean();

		return $fragments;
	}
}

if ( ! function_exists( 'zah_cart_link' ) ) {
	/**
	 * Cart Link
	 * Displayed a link to the cart including the number of items present and the cart total
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function zah_cart_link() {


		if ( ! zah_woo_cart_available() ) {
			//error_log('WooCommerce cart is not available');
			return;
		}

        $cart_subtotal = WC()->cart->get_cart_subtotal();
		$cart_contents_count = WC()->cart->get_cart_contents_count();
		// //error_log('Cart Subtotal: ' . $cart_subtotal);
		// //error_log('Cart Contents Count: ' . $cart_contents_count);

		?>

        <div class="cart-click">
            <a class="cart-contents" href="#" title="<?php esc_attr_e( 'View your shopping cart', 'zah' ); ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8.5 14.25C8.5 16.17 10.08 17.75 12 17.75C13.92 17.75 15.5 16.17 15.5 14.25" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8.81 2L5.19 5.63" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15.19 2L18.81 5.63" stroke="#292D32" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 7.84998C2 5.99998 2.99 5.84998 4.22 5.84998H19.78C21.01 5.84998 22 5.99998 22 7.84998C22 9.99998 21.01 9.84998 19.78 9.84998H4.22C2.99 9.84998 2 9.99998 2 7.84998Z" stroke="#292D32" stroke-width="1.5"/>
                    <path d="M3.5 10L4.91 18.64C5.23 20.58 6 22 8.86 22H14.89C18 22 18.46 20.64 18.82 18.76L20.5 10" stroke="#292D32" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <!-- <span class="amount"> -->
                    <?php //echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?>
                <!-- </span> -->
                <span class="count"><?php echo wp_kses_post( /* translators: cart count */ sprintf( _n( '%d', '%d', WC()->cart->get_cart_contents_count(), 'zah' ), WC()->cart->get_cart_contents_count() ) ); ?></span>
            </a>
        </div>
		<?php
	}
}

if ( ! function_exists( 'zah_header_cart' ) ) {
	/**
	 * Display Header Cart
	 *
	 * @since  1.0.0
	 * @uses  zah_is_woocommerce_activated() check if WooCommerce is activated
	 * @return void
	 */
	function zah_header_cart() {
			?>
		<ul class="site-header-cart menu">
			<li><?php zah_cart_link(); ?></li>
		</ul>
			<?php
	}
}

// Hook the header cart function to an action hook
add_action( 'zah_minicart_header', 'zah_header_cart' );




if ( ! function_exists( 'zah_header_cart_drawer' ) ) {
	/**
	 * Display Header Cart Drawer
	 *
	 * @since  1.0.0
	 * @uses  zah_is_woocommerce_activated() check if WooCommerce is activated
	 * @return void
	 */
	function zah_header_cart_drawer() {

        ?>



		<div tabindex="-1" id="zahCartDrawer" class="cart-popup" role="dialog" aria-label="Cart drawer">

			<div id="ajax-loading">
				<div class="zah-loader">
					<div class="spinner">
					<div class="bounce1"></div>
					<div class="bounce2"></div>
					<div class="bounce3"></div>
					</div>
				</div>
			</div>

            <?php do_action( 'zah_before_cart_popup' ); ?>
			<div class="cart-heading"><?php echo __('Your cart', 'zah'); ?></div>
			<button type="button" aria-label="Close drawer" class="close-drawer">
				<span aria-hidden="true"><svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 19L19 7" stroke="#292D32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M19 19L7 7" stroke="#292D32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
			</button>

			<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>

			</div>

        <?php
    }
}

// Function to fetch the ACF settings for the mini cart.
function get_mini_cart_settings() {
    return get_field('mini_cart_settings', 'option');
}

/**
 * Output the announcement bar at the end of the mini cart.
 */
function zah_add_announce_bar_to_mini_cart() {
    // Check if the cart is not empty
    if (!WC()->cart->is_empty() && get_field('add_announce_to_mini_cart', 'option')) {
        if (get_field('announce_bar_header', 'option')) : ?>
            <section class="announce-bar">
                <ul>
                    <?php foreach (get_field('announce_bar_header', 'option') as $item) : ?>
                        <li><?php echo $item['annonce_text']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif;
    }
}

// Hook into the mini cart to add the announcement bar at the end.
add_action('woocommerce_before_mini_cart', 'zah_add_announce_bar_to_mini_cart');



/**
 * Display cross-sell products in the mini cart.
 */
function zah_minicart_cross_sells() {
    $cross_sells = WC()->cart->get_cross_sells();
    if (empty($cross_sells)) {
        return;
    }

    $args = array(
        'posts_per_page' => apply_filters('woocommerce_cross_sells_total', 4),
        'orderby'        => 'rand',
        'post_type'      => 'product',
        'post__in'       => $cross_sells,
    );

    $cross_sells_query = new WP_Query($args);
    if (!$cross_sells_query->have_posts()) {
        return;
    }

    echo '<div class="cross-sells">';
    echo '<h2>' . __('You may be interested in', 'zah') . '</h2>';
    echo '<ul class="products">';

    while ($cross_sells_query->have_posts()) {
        $cross_sells_query->the_post();
        wc_get_template_part('content', 'product');
    }

    echo '</ul>';
    echo '</div>';

    wp_reset_postdata();
}

// Hook cross-sell display into the mini cart.
add_action('woocommerce_mini_cart_contents', 'zah_minicart_cross_sells', 20);

/**
 * Remove view cart button from mini cart.
 */
// function zah_remove_view_cart_minicart() {

//         remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );

// }
// add_action( 'woocommerce_widget_shopping_cart_buttons', 'zah_remove_view_cart_minicart', 1 );

if ( class_exists( 'WooCommerce' ) ) {
	/**
	 * Adds a body class to just the Shop landing page.
	 */
	function zah_shop_body_class( $classes ) {
		if ( is_shop() ) {
			$classes[] = 'shop';
		}
		return $classes;
	}

	add_filter( 'body_class', 'zah_shop_body_class' );
}



/**
 * Ajax get variable product sale label prices.
 */
function zah_get_sale_prices() {
	$product_id = isset( $_POST['product_id'] ) ? sanitize_text_field( $_POST['product_id'] ) : 0;
	$ajax       = array();
	$percents   = array();
	if ( $product_id ) {
		$_product = wc_get_product( $product_id );
		if ( $_product && $_product->is_type( 'variable' ) ) {
			$prices = $_product->get_variation_prices();
			if ( count( $prices ) ) {
				foreach ( $prices['price'] as $variation_id => $price ) {
					$sale_price    = $prices['sale_price'][ $variation_id ];
					$regular_price = $prices['regular_price'][ $variation_id ];
					if ( $regular_price !== $price ) {
						$percentage = round( 100 - ( $sale_price / $regular_price * 100 ) );
						if ( $percentage ) {
							$percents[ $variation_id ] = '-' . $percentage . '%';
						}
					}
				}
			}
		}
	}
	$ajax['percents'] = $percents;

	wp_send_json( $ajax );
}
add_action( 'wp_ajax_zah_get_sale_prices', 'zah_get_sale_prices' );
add_action( 'wp_ajax_nopriv_zah_get_sale_prices', 'zah_get_sale_prices' );

/**
 * Get variable product sale label prices script.
 */
function zah_get_sale_prices_script(){
	global $product;
	if ( ! is_product() ) {
		return;
	}
	if ( ! $product ) {
		return;
	}
	if ( ! $product->is_type( 'variable' ) ) {
		return;
	}
	if ( ! $product->is_on_sale() ) {
		return;
	}


		return;

	?>
<script type="text/javascript">
var zah_sales = null;
jQuery( document ).ready( function( $ ) {
	var zah_sale_lbl = $( '.summary .sale-item.product-label' );
	zah_sale_lbl.css( 'visibility', 'hidden' );
	$.ajax( {
		type: 'POST',
		url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
		data: { 'action': 'zah_get_sale_prices', 'product_id': <?php echo esc_attr( $product->get_id() ); ?> },
		success: function( json ) {
			zah_sales = json.percents;
			zah_update_variable_sale_badge();
		}
	} );
	$( '.summary input.variation_id' ).change( function() {
		zah_update_variable_sale_badge();
	} );
	function zah_update_variable_sale_badge() {
		var variation_id = $( '.summary input.variation_id' ).val();
		if ( '' != variation_id && zah_sales && zah_sales.hasOwnProperty( variation_id ) ) {
			zah_sale_lbl.html( zah_sales[variation_id] ).css( 'visibility', 'visible' );
		} else {
			zah_sale_lbl.css( 'visibility', 'hidden' );
		}
	}
} );
</script>
	<?php
}
add_action( 'wp_footer', 'zah_get_sale_prices_script', 999 );

/**
 * Single Product - exclude from Jetpack's Lazy Load.
 */
function is_lazyload_activated() {
	$condition = is_product();
	if ( $condition ) {
		return false;
	} return true;
}

add_filter( 'lazyload_is_enabled', 'is_lazyload_activated', 10, 3 );


/**
 * Show cart widget on all pages.
 */
add_filter( 'woocommerce_widget_cart_is_hidden', 'zah_always_show_cart', 40, 0 );

/**
 * Function to always show cart.
 */
function zah_always_show_cart() {
	return false;
}

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 7 );

/**
 * Single Product Page - Add a section wrapper start.
 */
add_action( 'woocommerce_before_single_product_summary', 'zah_product_content_wrapper_start', 5 );
function zah_product_content_wrapper_start() {
	echo '<div class="product-details-wrapper">';
}

/**
 * Single Product Page - Add a section wrapper end.
 */
add_action( 'woocommerce_single_product_summary', 'zah_product_content_wrapper_end', 60 );
function zah_product_content_wrapper_end() {
	echo '</div><!--/product-details-wrapper-end-->';
}

add_action( 'woocommerce_after_single_product_summary', 'zah_related_content_wrapper_start', 10 );
add_action( 'woocommerce_after_single_product_summary', 'zah_related_content_wrapper_end', 60 );

/**
 * Single Product Page - Related products section wrapper start.
 */
function zah_related_content_wrapper_start() {
	echo '<section class="related-wrapper">';
}


/**
 * Single Product Page - Related products section wrapper end.
 */
function zah_related_content_wrapper_end() {
	echo '</section>';
}

/**
 * Override WooCommerce related products function
 */
function woocommerce_related_products($args = array()) {
    global $product;

    // error_log("\n\n=== Custom Related Products Function Start ===");
    // error_log("Processing product: " . $product->get_id() . " - " . $product->get_name());

    // Get all categories for current product
    $terms = get_the_terms($product->get_id(), 'product_cat');
    
    if ($terms && !is_wp_error($terms)) {
        //error_log("Found " . count($terms) . " categories");
        
        // Find deepest category
        $deepest_term = null;
        $max_depth = -1;
        
        foreach ($terms as $term) {
            $ancestors = get_ancestors($term->term_id, 'product_cat');
            $depth = count($ancestors);
            
           // error_log("Category: {$term->name} (ID: {$term->term_id}) - Depth: {$depth}");
            
            if ($depth > $max_depth) {
                $max_depth = $depth;
                $deepest_term = $term;
            }
        }
        
        if ($deepest_term) {
            //error_log("Selected deepest category: " . $deepest_term->name);
            
            // Set up custom arguments for related products query
            $args = array(
                'posts_per_page' => 4,
                'columns'        => 4,
                'orderby'        => 'rand',
                'post__not_in'   => array($product->get_id()),
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'term_id',
                        'terms'    => $deepest_term->term_id
                    )
                )
            );
        }
    }

   // error_log("Final query args: " . print_r($args, true));
    
    // Get the products
    $related_products = new WP_Query($args);
    
   // error_log("Found " . $related_products->post_count . " related products");
    //error_log("=== Custom Related Products Function End ===\n");

    if ($related_products->have_posts()) {
        echo '<div class="related products">';
        
        echo '<h2>' . esc_html__('Related products', 'zah') . '</h2>';
        
        woocommerce_product_loop_start();
        
        while ($related_products->have_posts()) : $related_products->the_post();
            wc_get_template_part('content', 'product');
        endwhile;
        
        woocommerce_product_loop_end();
        
        echo '</div>';
    }
    
    wp_reset_postdata();
}



if ( ! function_exists( 'zah_pdp_ajax_atc' ) ) {
	/**
	 * PDP/Single product ajax add to cart.
	 */
	function zah_pdp_ajax_atc() {
		$sku = '';
		if ( isset( $_POST['variation_id'] ) ) {
			$sku = $_POST['variation_id'];
		}
		$product_id = $_POST['add-to-cart'];
		if ( empty( $sku ) ) {
			$sku = $product_id;
		}

		ob_start();
		wc_print_notices();
		$notices = ob_get_clean();
		ob_start();
		woocommerce_mini_cart();
		$zah_mini_cart = ob_get_clean();
		$zah_atc_data  = array(
			'notices'   => $notices,
			'fragments' => apply_filters(
				'woocommerce_add_to_cart_fragments',
				array(
					'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $zah_mini_cart . '</div>',
				)
			),
			'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() ),
		);
		// if GA Pro is installed, send an atc event.
		//if ( class_exists( 'WC_Google_Analytics_Pro' ) ) {
		//	wc_google_analytics_pro()->get_integration()->ajax_added_to_cart( $sku );
		//}
		do_action( 'woocommerce_ajax_added_to_cart', $sku );

		wp_send_json( $zah_atc_data );
		die();
	}
}

add_action( 'wc_ajax_zah_pdp_ajax_atc', 'zah_pdp_ajax_atc' );
add_action( 'wc_ajax_nopriv_zah_pdp_ajax_atc', 'zah_pdp_ajax_atc' );


if ( ! function_exists( 'zah_pdp_ajax_atc_enqueue' ) ) {

    /**
     * Enqueue assets for PDP/Single product ajax add to cart.
     */
    function zah_pdp_ajax_atc_enqueue() {
        if ( is_product() ) {
            wp_enqueue_script( 'zah-ajax-script', get_template_directory_uri() . '/resources/scripts/single-product-ajax.js', array( 'jquery' ), '1.0.0', true );
            wp_localize_script(
                'zah-ajax-script',
                'zah_ajax_obj',
                array(
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'nonce'   => wp_create_nonce( 'ajax-nonce' ),
                )
            );
        }
    }
}

// Hook the function to enqueue scripts
add_action( 'wp_enqueue_scripts', 'zah_pdp_ajax_atc_enqueue' );




/**
 * Custom markup around cart field.
 */
// function zah_cart_custom_field() {

// 	if ( is_active_sidebar( 'cart-field' ) ) :
// 		echo '<div class="cart-custom-field">';
// 		echo 'TEST TEST TEST CART TEXT';
// 		echo '</div>';
// 	endif;

// }



/**
 *  Quantity selectors for zah mini cart
 *
 * @package zah
 *
 * Description: Adds quantity buttons for the zah mini cart
 * Version: 1.0
 */


/**
 * Add minicart quantity fields
 *
 * @param  string $html          cart html.
 * @param  string $cart_item     cart item.
 * @param  string $cart_item_key cart item key.
 */
function add_minicart_quantity_fields( $html, $cart_item, $cart_item_key ) {

	$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $cart_item['data'] ), $cart_item, $cart_item_key );
	$_product      = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
	$max_qty       = $_product->get_max_purchase_quantity();

	$out = '<div class="zah-custom-quantity-mini-cart_container">
				<div class="zah-custom-quantity-mini-cart">
				<span tabindex="0" role="button" aria-label="Reduce quantity" class="zah-custom-quantity-mini-cart_button quantity-down">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6">
					  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
					</svg>
				</span>
				<input aria-label="' . esc_attr( __( 'Quantity input', 'zah' ) ) . '" class="zah-custom-quantity-mini-cart_input" data-cart_item_key="' . $cart_item_key . '" type="number" min="1" ' . ( -1 !== $max_qty ? 'max="' . $max_qty . '"' : '' ) . ' step="1" value="' . $cart_item['quantity'] . '">
				<span tabindex="0" role="button" aria-label="Increase quantity" class="zah-custom-quantity-mini-cart_button quantity-up">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6">
  						<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
					</svg>
				</span>
			</div></div>';

	return sprintf(
		'%2$s %1$s',
		$out,
		$product_price
	);

	}


	add_filter( 'woocommerce_widget_cart_item_quantity', 'add_minicart_quantity_fields', 10, 3 );


if ( ! function_exists( 'minicart_zah_update_mini_cart' ) ) {




		/**
		 * Minicart zah update mini cart.
		 */
		function minicart_zah_update_mini_cart() {

			$data = $_POST['data']; // phpcs:ignore
			if ( ! WC()->cart->is_empty() ) {
				foreach ( $data as $item_key => $item_qty ) {
					$_cart_item = WC()->cart->get_cart_item( $item_key );
					if ( ! empty( $_cart_item ) ) {
						$_product = apply_filters( 'woocommerce_cart_item_product', $_cart_item['data'], $_cart_item, $item_key );
						$max_qty  = $_product->get_max_purchase_quantity();
						if ( -1 !== $max_qty && $item_qty > $max_qty ) {
							$item_qty = $max_qty;
						}
						if ( $item_qty > 0 ) {
							WC()->cart->set_quantity( $item_key, $item_qty, true );
						}
					}
				}
			}
			wp_send_json_success();
		}

}

add_action( 'wp_ajax_cg_zah_update_mini_cart', 'minicart_zah_update_mini_cart' );
add_action( 'wp_ajax_nopriv_cg_zah_update_mini_cart', 'minicart_zah_update_mini_cart' );


if ( ! function_exists( 'minicart_zah_get_styles' ) ) {
	/**
	 * Enqueue scripts
	 */
	function minicart_zah_get_scripts() {



			wp_enqueue_script( 'custom-zah-quantity-js', get_theme_file_uri( '/resources/scripts/minicart-quantity.js' ), array( 'jquery' ), time(), true );

	}
}
add_action( 'wp_enqueue_scripts', 'minicart_zah_get_scripts', 30 );


/**
* Option to automatically update the cart page quantity without clicking "Update".
*
* @since 2.6.6
*/
add_action( 'wp_footer', 'zah_cart_ajax_update_quantity' );

function zah_cart_ajax_update_quantity() {

		if ( is_cart() || ( is_cart() && is_checkout() ) ) {
	    	wc_enqueue_js('
				var timeout;
				jQuery("div.woocommerce").on("change keyup mouseup", "input.qty, select.qty", function(){
					if (timeout != undefined) clearTimeout(timeout);
					if (jQuery(this).val() == "") return;
					timeout = setTimeout(function() {
						jQuery("[name=\"update_cart\"]").trigger("click");
					}, );
				});

			');
		}
}

add_filter( 'body_class', 'zah_cart_ajax_update_quantity_class');
function zah_cart_ajax_update_quantity_class( $classes ) {

	    if ( is_cart() || ( is_cart() && is_checkout() ) ) {
	          $classes[] = 'zah-ajax-cart';
		}

    return $classes;
}




/**
 * Add free shipping notification to mini cart.
 */
function custom_fsn_add_mini_cart() {
    // Fetch the ACF settings for mini cart
    $mini_cart_settings = get_mini_cart_settings();

    if (!WC()->cart->is_empty()) {
        // Check if the free shipping notification should be shown
        $show_free_shipping_notification = isset($mini_cart_settings['onoff_free_shipping_notification_-_mini_cart']) ? $mini_cart_settings['onoff_free_shipping_notification_-_mini_cart'] : false;

        if ($show_free_shipping_notification) {
            custom_free_shipping_notification('mini-cart');
        }
    } else {
        zah_empty_mini_cart($mini_cart_settings);
    }
}
add_action('woocommerce_before_mini_cart', 'custom_fsn_add_mini_cart', 20);

if ( ! function_exists( 'zah_upsell_display' ) ) {
	/**
	 * Upsells
	 * Replace the default upsell function with our own which displays the correct number product columns
	 *
	 * @since   1.0.0
	 * @return  void
	 * @uses    woocommerce_upsell_display()
	 */
	function zah_upsell_display() {
		$columns = apply_filters( 'zah_upsells_columns', 4 );
		woocommerce_upsell_display( -1, $columns );
	}
}

/**
 * Free shipping notification.
 *
 * @param string $type Type of notification.
 */
function custom_free_shipping_notification($type) {
    if (WC()->cart->is_empty()) {
        return;
    }

    $packages = WC()->cart->get_shipping_packages();
    $package = reset($packages);

    $min_amount = 0;
    $progressPercentage = 0;
    $free_shipping_available = false;

    if ($package) {
        $zone = wc_get_shipping_zone($package);
        if ($zone) {
            $shippingMethods = $zone->get_shipping_methods(true);
            $shippingCartTotal = WC()->cart->shipping_total ?? 0;
            $cartTotal = WC()->cart->total ?? 0;

            foreach ($shippingMethods as $method) {
                if ($method->id === 'free_shipping') {
                    $min_amount = $method->get_option('min_amount') ?? 0;
                    $awayFromFreeDelivery = $min_amount - (floatval($cartTotal) - floatval($shippingCartTotal));
                    $free_shipping_available = true;
                    break;
                }
            }
        }
    }

    if ($min_amount > 0) {
        $progressPercentage = (WC()->cart->total / $min_amount) * 100;
        if ($progressPercentage > 100) {
            $progressPercentage = 100;
        }
    } else {
        $awayFromFreeDelivery = 0;
    }

    if ($free_shipping_available) {
        ?>
        <div class="free-delivery-bar--cart">
            <?php if (WC()->cart->total <= $min_amount) : ?>
                <div class="bar-body">
                    <span style="width: <?php echo esc_attr($progressPercentage); ?>%;"></span>
                </div>
                <p>👋 <?php esc_html_e('You are ', 'zah'); ?> <span data-min="<?php echo esc_attr($min_amount); ?>" data-total="<?php echo esc_attr(WC()->cart->total); ?>" data-shipping="<?php echo esc_attr(WC()->cart->shipping_total); ?>"><?php echo wc_price($awayFromFreeDelivery); ?></span> <?php esc_html_e(' away from free delivery', 'zah'); ?></p>
            <?php else : ?>
                <div class="bar-body">
                    <span style="width: 100%;"></span>
                </div>
                <p class="unlocked flex gap-2 items-center"><span class="icon icon-tick-circle"></span> <?php esc_html_e('Congrats! You\'ve reached free shipping.', 'zah'); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }
}

/**
 * Display custom content when mini cart is empty.
 */
function zah_empty_mini_cart($mini_cart_settings) {
    if (WC()->cart->is_empty()) {
        echo '<div class="zah-empty-mini-cart">';
        echo '<h4>'. __('Your bag is empty', 'zah') .'</h4>';

        // Display the selected categories
        if (!empty($mini_cart_settings['choose_emtpy_cart_category'])) {
            echo '<ul class="empty-cart-categories">';
            foreach ($mini_cart_settings['choose_emtpy_cart_category'] as $category_id) {
                $category = get_term($category_id, 'product_cat');
                if ($category) {
                    echo '<li><a class="btn btn-main btn-block" href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a></li>';
                }
            }
            echo '</ul>';
        }

        // Display the custom button
        if (!empty($mini_cart_settings['add_custom_button'])) {
            echo '<a href="' . esc_url($mini_cart_settings['add_custom_button']['url']) . '" class="custom-button btn btn-main-o btn-block">' . esc_html($mini_cart_settings['add_custom_button']['title']) . '</a>';
        }

        echo '</div>';
    }
}

/**
 * JavaScript to dynamically update the free shipping notification bar.
 */
function custom_fsn_enqueue_scripts() {
    if (is_cart()) {
        wp_enqueue_script('custom-fsn-scripts', get_template_directory_uri() . '/resources/scripts/free-shipping.js', array('jquery'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'custom_fsn_enqueue_scripts');


/**
 * Remove default WooCommerce product link open
 *
 * @see get_the_permalink()
 */
function zah_remove_woocommerce_template_loop_product_link_open() {
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
}
add_action( 'wp_head', 'zah_remove_woocommerce_template_loop_product_link_open' );


/**
 * Remove default WooCommerce product link close
 *
 * @see get_the_permalink()
 */
function zah_remove_woocommerce_template_loop_product_link_close() {
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
}
add_action( 'wp_head', 'zah_remove_woocommerce_template_loop_product_link_close' );


/**
 * Open link before the product thumbnail image
 *
 * @see get_the_permalink()
 */
function zah_template_loop_image_link_open() {
	echo '<a href="' . get_the_permalink() . '" title="' . get_the_title() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'zah_template_loop_image_link_open', 5 );


/**
 * Close link after the product thumbnail image
 *
 * @see get_the_permalink()
 */
function zah_template_loop_image_link_close() {
	echo '</a>';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'zah_template_loop_image_link_close', 20 );

add_action( 'woocommerce_shop_loop_item_title', 'zah_loop_product_content_header_open', 5 );

function zah_loop_product_content_header_open() {
	echo '<div class="woocommerce-card__header">';
}

add_action( 'woocommerce_after_shop_loop_item', 'zah_loop_product_content_header_close', 60 );

function zah_loop_product_content_header_close() {
	echo '</div>';
}

/**
 * Within Product Loop - remove title hook and create a new one with the category displayed above it.
 */
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'zah_loop_product_title', 10 );

function zah_loop_product_title() {
    global $product;
    
    // Get SKU
    $sku = $product->get_sku();
    $sku_html = '';
    
    // Only display SKU if it exists
    if ($sku) {
        $sku_html = '<div class="product-sku">' . esc_html($sku) . '</div>';
    }

    echo '<div class="woocommerce-loop-product__title">' . 
         $sku_html . 
         '<a tabindex="0" href="' . get_the_permalink() . '" aria-label="' . get_the_title() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">' . 
         get_the_title() . 
         '</a></div>';
}

/**
 * Single Product Page - Display upsells before related.
 */
// add_action( 'after_setup_theme', 'cg_upsells_related', 99 );

// function cg_upsells_related() {
//     remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 25 );
//     add_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 18 );
// }

/**
 * Display discounted % on product loop.
 */
// add_action( 'woocommerce_before_shop_loop_item_title', 'zah_change_displayed_sale_price_html', 7 );
// add_action( 'woocommerce_single_product_summary', 'zah_change_displayed_sale_price_html', 10 );
add_action( 'woocommerce_single_product_summary', 'zah_clear_product_price', 11 );

if ( ! function_exists( 'zah_clear_product_price' ) ) {
	/**
	 * Clear product price
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	function zah_clear_product_price() {
		echo '<div class="clear"></div>';
	}
}

/**
 * Shop page - Out of Stock
 */
if ( ! function_exists( 'zah_shop_out_of_stock' ) ) :
	/**
	 * Add Out of Stock to the Shop page
	 *
	 * @hooked woocommerce_before_shop_loop_item_title - 8
	 *
	 * @since 1.8.5
	 */
	function zah_shop_out_of_stock() {
		$out_of_stock        = get_post_meta( get_the_ID(), '_stock_status', true );
		$out_of_stock_string = apply_filters( 'zah_shop_out_of_stock_string', __( 'Out of stock', 'zah' ) );

		if ( 'outofstock' === $out_of_stock && ! empty( $out_of_stock_string ) ) {
			?>
			<span class="product-out-of-stock"><?php echo esc_html( $out_of_stock_string ); ?></span>
			<?php
		}
	}

endif;

function zah_change_displayed_sale_price_html() {
    global $product;
    $zah_sale_badge = '';

    if ($product && $product->is_on_sale() && !$product->is_type('grouped') && !$product->is_type('bundle')) {
        $percentage = 0;

        if ($product->is_type('variable')) {
            $available_variations = $product->get_available_variations();
            $percentages = array();

            // Convert product data to JSON for console logging
            $product_data = array(
                'id' => $product->get_id(),
                'type' => $product->get_type(),
                'variations' => array()
            );

            foreach ($available_variations as $variation) {
                $variation_obj = wc_get_product($variation['variation_id']);
                $regular_price = (float) $variation_obj->get_regular_price();
                $sale_price = (float) $variation_obj->get_sale_price();

                $product_data['variations'][] = array(
                    'id' => $variation['variation_id'],
                    'regular_price' => $regular_price,
                    'sale_price' => $sale_price
                );

                if ($regular_price > 0 && $sale_price > 0) {
                    $percentages[] = round(100 - ($sale_price / $regular_price * 100));
                }
            }

            //echo '<script>console.log("Product Data:", ' . json_encode($product_data) . ');</script>';

            if (!empty($percentages)) {
                $percentage = max($percentages);
            }
        }

        if ($percentage > 0) {
            $zah_sale_badge = sprintf(
                __('<div class="product-badges"><span class="product-badge sale">-%s%%</span></div>', 'zah'),
                $percentage
            );
        }
    }

    echo wp_kses_post($zah_sale_badge);
}

// Add to archive pages
add_action('woocommerce_before_shop_loop_item_title', 'zah_change_displayed_sale_price_html', 10);

/**
 * Variation selected highlight
 *
 * @since 1.6.1
 */
add_action( 'woocommerce_before_add_to_cart_quantity', 'zah_highlight_selected_variation' );

function zah_highlight_selected_variation() {

	global $product;

	if ( $product->is_type( 'variable' ) ) {

		?>
	 <script>
document.addEventListener( 'DOMContentLoaded', function() {
	var vari_labels = document.querySelectorAll('.variations .label label');
	vari_labels.forEach( function( vari_label ) {
		vari_label.innerHTML = '<span>' + vari_label.innerHTML + '</span>';
	} );

	var vari_values = document.querySelectorAll('.value');
	vari_values.forEach( function( vari_value ) {
		vari_value.addEventListener( 'change', function( event ) {
			var $this = event.target;
			if ( $this.selectedIndex != 0 ) {
				$this.closest( 'tr' ).classList.add( 'selected-variation' );
			} else {
				$this.closest( 'tr' ).classList.remove( 'selected-variation' );
			}
		} );
	} );

	document.addEventListener('click', function( event ){
		if ( event.target.classList.contains( 'reset_variations' ) ) {
			var vari_classs = document.querySelectorAll('.variations tr.selected-variation');
			vari_classs.forEach( function( vari_class ) {
				vari_class.classList.remove( 'selected-variation' );
			} );
		}
	} );
} );
</script>
		<?php

	}

}


/**
 * Single Product Page - Added to cart message.
 */
add_filter( 'wc_add_to_cart_message_html', 'zah_add_to_cart_message_filter', 10, 2 );

function zah_add_to_cart_message_filter( $message ) {

	$zah_message = sprintf(
		'<div class="message-inner"><div class="message-content">%s </div><div class="buttons-wrapper"><a href="%s" class="button checkout"><span>%s</span></a> <a href="%s" class="button cart"><span>%s</span></a></div></div>',
		zah_safe_html( $message ),
		esc_url( wc_get_page_permalink( 'checkout' ) ),
		esc_html__( 'Checkout', 'zah' ),
		esc_url( wc_get_page_permalink( 'cart' ) ),
		esc_html__( 'View Cart', 'zah' )
	);

	return $zah_message;

}



/**
 * Cart wrapper open.
 */
function zah_cart_wrapper_open() {
	echo '<section class="zah-cart-wrapper">';
}

/**
 * Cart wrapper close.
 */

function zah_cart_wrapper_close() {
	echo '</section>';
}

add_action( 'woocommerce_before_cart', 'zah_cart_wrapper_open', 20 );
add_action( 'woocommerce_after_cart', 'zah_cart_wrapper_close', 10 );


/**
 * Add Progress Bar to the Cart and Checkout pages.
 */
add_action( 'woocommerce_before_cart', 'zah_cart_progress' );
add_action( 'woocommerce_before_checkout_form', 'zah_cart_progress', 5 );

if ( ! function_exists( 'zah_cart_progress' ) ) {

	/**
	 * More product info
	 * Link to product
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function zah_cart_progress() {



			?>

			<div class="checkout-wrap">
			<ul class="checkout-bar">
				<li class="active first"><span>
				<a href="<?php echo get_permalink( wc_get_page_id( 'cart' ) ); ?>"><?php esc_html_e( 'Shopping Cart', 'zah' ); ?></a></span>
				</li>
				<li class="next">
				<span>
				<a href="<?php echo get_permalink( wc_get_page_id( 'checkout' ) ); ?>"><?php esc_html_e( 'Shipping and Checkout', 'zah' ); ?></a></span></li>
				<li><span><?php esc_html_e( 'Confirmation', 'zah' ); ?></span></li>

			</ul>
			</div>
			<?php


		?>
		<?php

	}
}// End if().

/**
 * Single Product Page - Reorder sale message.
 */
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_show_product_sale_flash', 3 );


/**
* Remove "Description" heading from WooCommerce tabs.
*
* @since 1.0.0
*/
//add_filter( 'woocommerce_product_description_heading', '__return_null' );

// Change add to cart text on product archives page
add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_add_to_cart_button_text_archives' );
function woocommerce_add_to_cart_button_text_archives() {
    return __( 'Add to cart', 'zah' );
}

// Rename the coupon field on the cart page
add_filter( 'gettext', 'woocommerce_rename_coupon_field_on_cart', 10, 3 );
function woocommerce_rename_coupon_field_on_cart( $translated_text, $text, $domain ) {
    if ( $domain === 'woocommerce' ) {
        switch ( $text ) {
            case 'Apply coupon':
                $translated_text = __( 'Apply Coupon', 'zah' );
                break;
            case 'Coupon code':
                $translated_text = __( 'Enter Coupon Code', 'zah' );
                break;
        }
    }
    return $translated_text;
}



add_filter('woocommerce_sale_flash', 'ds_change_sale_text');
function ds_change_sale_text() {
return '<span class="onsale">Промоция</span>';
}

// Remove default SKU location
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

// Add new location for SKU and balloon text
add_action('woocommerce_single_product_summary', 'custom_product_subheading', 4); // 4 to place before title (5)
function custom_product_subheading() {
    global $product;
    
    if (!$product) {
        return;
    }

    // Get ACF balloon text
    $balloon_text = get_field('product_balloon_text');
    
    // Start subheading div
    echo '<div class="product-subheading">';
    
    // Add balloon text if exists
    if ($balloon_text) {
        echo '<span class="product-balloon-text">' . esc_html($balloon_text) . '</span>';
    }
    
    // Add SKU
    if ($product->get_sku()) {
        echo '<span class="sku_wrapper">' . esc_html__('SKU', 'zah') . ' <span class="sku">' . esc_html($product->get_sku()) . '</span></span>';
    }

    // Add stock status
    if ($product->is_in_stock()) {
        echo '<span class="stock in-stock">' . __('In stock', 'zah') . '</span>';
    } else {
        echo '<span class="stock out-of-stock">' . __('Out of stock', 'zah') . '</span>';
    }
    
    echo '</div>';
}


// Handle CSV import for product specifications
add_action('acf/save_post', function($post_id) {
    // Check if this is a product
    if (get_post_type($post_id) !== 'product') {
        return;
    }

    // Get the CSV file field
    $csv_file = get_field('product_specificatoins_import_product_specificaition', $post_id);
    
    if (!$csv_file) {
        return;
    }

    // Read CSV file
    $file_path = get_attached_file($csv_file['ID']);
    if (!$file_path || !file_exists($file_path)) {
        return;
    }

    $specifications = [];
    if (($handle = fopen($file_path, "r")) !== false) {
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            if (count($data) >= 2) {
                $specifications[] = [
                    'column1' => $data[0],
                    'column2' => $data[1]
                ];
            }
        }
        fclose($handle);
    }

    // Update the repeater field with CSV data
    update_field('product_specificatoins_add_product_specification', $specifications, $post_id);
    
    // Clear the CSV file field
    update_field('product_specificatoins_import_product_specificaition', '', $post_id);
}, 20);


/**
 * Add product badges (sale, new) to product gallery
 */

// Remove existing sale flash hooks
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 7);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_show_product_sale_flash', 3);

if (!function_exists('zah_get_product_badges')) {
    /**
     * Get product badges HTML
     * 
     * @param WC_Product $product Product object
     * @return string Badges HTML
     */
    function zah_get_product_badges($product) {
        if (!$product) {
            return '';
        }

        $badges = '';

        // Check if the product is an Atlas product
        if (has_term('atlas', 'product_tag', $product->get_id())) {
            // Get the total price and discounted price from the calculator
            $total_price = 0;
            $discounted_price = 0;

            // Check if the calculator has set the total and discounted prices
            if (isset($_POST['total_price']) && isset($_POST['discounted_price'])) {
                $total_price = (float) $_POST['total_price'];
                $discounted_price = (float) $_POST['discounted_price'];
            } else {
                // Fallback to base and sale prices if calculator data is not available
                $total_price = (float) $product->get_regular_price();
                $discounted_price = (float) $product->get_sale_price();
            }

            // Calculate the discount percentage
            if ($total_price > 0 && $discounted_price > 0) {
                $percentage = round(100 - ($discounted_price / $total_price * 100));
                
                if ($percentage > 0) {
                    $badges .= sprintf(
                        '<span class="product-badge sale">-%d%%</span>',
                        $percentage
                    );
                }
            }
        } else {
            // For non-Atlas products, use the default logic
            $total_price = (float) $product->get_regular_price();
            $discounted_price = (float) $product->get_sale_price();

            if ($total_price > 0 && $discounted_price > 0) {
                $percentage = round(100 - ($discounted_price / $total_price * 100));
                
                if ($percentage > 0) {
                    $badges .= sprintf(
                        '<span class="product-badge sale">-%d%%</span>',
                        $percentage
                    );
                }
            }
        }

        // New product badge (products less than 30 days old)
        $days_as_new = apply_filters('zah_new_product_days', 30);
        $created_date = strtotime($product->get_date_created());
        
        if ($created_date && (time() - $created_date) < ($days_as_new * 24 * 60 * 60)) {
            $badges .= '<span class="product-badge new">' . esc_html__('New', 'zah') . '</span>';
        }

        // Out of stock badge
        if (!$product->is_in_stock()) {
            $badges .= '<span class="product-badge out-of-stock">' . esc_html__('Out of stock', 'zah') . '</span>';
        }

        if ($badges) {
            return sprintf('<div class="product-badges">%s</div>', $badges);
        }

        return '';
    }
}


// Add badges to single product gallery
add_action('woocommerce_before_single_product_summary', function() {
    global $product;
    echo zah_get_product_badges($product);
}, 8);

// Add badges to product loop items
add_action('woocommerce_before_shop_loop_item_title', function() {
    global $product;
    echo zah_get_product_badges($product);
}, 7);


/**
 * Add video modal functionality for product pages
 */

// Add video modal to page footer
add_action('wp_footer', 'zah_product_video_modal');
function zah_product_video_modal() {
    if (!is_product()) {
        return;
    }
    ?>
    <div id="videoModal" class="video-modal fixed inset-0 z-[9999] hidden">
        <div class="modal-overlay absolute inset-0 bg-black/90"></div>
        <div class="modal-content relative z-[10000] w-full h-full flex items-center justify-center">
            <button class="modal-close absolute top-4 right-4 text-white text-4xl z-[10001]">&times;</button>
            <div class="video-container w-full max-w-60 mx-auto px-4 flex items-center justify-center">
                <div class="relative  w-full pb-[56.25%] h-0 flex items-center justify-center">
                    <iframe id="youtubeFrame" style="max-width: 680px;"  width="100%" height="315"  title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        function openVideoModal(videoId) {
            $('#youtubeFrame').attr('src', 'https://www.youtube.com/embed/' + videoId);
            $('#videoModal').removeClass('hidden');
            $('body').addClass('modal-open');
        }

        function closeVideoModal() {
            $('#youtubeFrame').attr('src', '');
            $('#videoModal').addClass('hidden');
            $('body').removeClass('modal-open');
        }

        $('.product-video-btn').on('click', function(e) {
            e.preventDefault();
            var videoId = $(this).data('video');
            openVideoModal(videoId);
        });

        $('.product-video-btn-desiption').on('click', function(e) {
            e.preventDefault();
            var videoId = $(this).data('video');
            openVideoModal(videoId);
        });

        $('.video-modal .modal-close, .video-modal .modal-overlay').on('click', function() {
            closeVideoModal();
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && !$('#videoModal').hasClass('hidden')) {
                closeVideoModal();
            }
        });
    });
    </script>

   
    <?php
}

// Add free sample modal to page footer
add_action('wp_footer', 'zah_free_sample_modal');
function zah_free_sample_modal() {
    if (!is_product()) {
        return;
    }
    ?>
    <div id="sampleModal" class="sample-modal free-sample-modal fixed inset-0 z-[9999] hidden">
        <div class="modal-overlay absolute inset-0 bg-black/90"></div>
        <div class="modal-content relative z-[10000] w-full h-full flex items-center justify-center">
            <button class="modal-close absolute top-4 right-4 text-white text-4xl z-[10001]">&times;</button>
            <div class="form-container ">
                <h2 >Поръчай Безплатна мостра</h2>
                <?php 
                    // Get the current product
                    global $product;
                    
                    // Set up the dynamic data
                    add_filter('wpcf7_form_tag_data_option', function($value, $option, $args) use ($product) {
                        if ($option === 'get-post-title') {
                            return $product->get_title();
                        }
                        if ($option === 'get-custom-field' && $args === 'sku') {
                            return $product->get_sku();
                        }
                        return $value;
                    }, 10, 3);
                    
                    // Output the form
                    echo do_shortcode('[contact-form-7 id="db605c4" title="Безплатна мостра"]'); 
                    ?>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        function openSampleModal() {
            $('#sampleModal').removeClass('hidden');
            $('body').addClass('modal-open');
        }

        function closeSampleModal() {
            $('#sampleModal').addClass('hidden');
            $('body').removeClass('modal-open');
        }

        $('.free-sample-btn').on('click', function(e) {
            e.preventDefault();
            openSampleModal();
        });

        $('.sample-modal .modal-close, .sample-modal .modal-overlay').on('click', function() {
            closeSampleModal();
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && !$('#sampleModal').hasClass('hidden')) {
                closeSampleModal();
            }
        });
    });
    </script>
    <?php
}

// Modify the product description tab to include video button
add_filter('woocommerce_product_tabs', function($tabs) {
    $tabs['description']['callback'] = function() {
        echo '<div class="inside-description">';
        // Get the default description
        wc_get_template('single-product/tabs/description.php');
        
        // Add video button inside description
        global $product;
        $video_id = get_field('archive_video', $product->get_id());
        if ($video_id) {
            echo '<div class="description-video-wrapper mt-4">';
            echo '<button class="product-video-btn-desiption" data-video="' . esc_attr($video_id) . '">';
            echo '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M0.666748 9.99993V5.84659C0.666748 0.689928 4.31841 -1.42174 8.78675 1.1566L12.3917 3.23326L15.9967 5.30993C20.4651 7.88826 20.4651 12.1116 15.9967 14.6899L12.3917 16.7666L8.78675 18.8433C4.31841 21.4216 0.666748 19.3099 0.666748 14.1533V9.99993Z" fill="white"/>
</svg>
';
            echo esc_html__('Watch Video', 'zah');
            echo '</button>';
            echo '</div>';
        }
        echo '</div>';
        
        // Get and display specifications
        if (have_rows('product_specificatoins_add_product_specification')) {
            echo '<div class="product-specifications">';
            echo '<h3>Спецификации</h3>';
            echo '<table class="specifications-table">';
            
            while (have_rows('product_specificatoins_add_product_specification')) {
                the_row();
                $column1 = get_sub_field('column1');
                $column2 = get_sub_field('column2');
                
                echo '<tr>';
                echo '<th>' . esc_html($column1) . '</th>';
                echo '<td>' . wp_kses_post($column2) . '</td>';
                echo '</tr>';
            }
            
            echo '</table>';
            echo '</div>';
        }
    };
    
    return $tabs;
});


//-------------------- custom calculator pass to cart -------------------------


// Change "Add to Cart" button text and URL for Atlas products on the archive page
add_filter('woocommerce_loop_add_to_cart_link', function($button, $product) {
    if (has_term(['atlas', 'sigma', 'gamma', 'piramida', 'terra'], 'product_tag', $product->get_id())) {
        $product_url = get_permalink($product->get_id());
        $button_text = __('Calculate', 'zah');
        $button = sprintf('<a href="%s" class="button">%s</a>', esc_url($product_url), esc_html($button_text));
    }
    return $button;
}, 10, 2);

// Hook the calculator form after product title
add_action('woocommerce_single_product_summary', function() {
    $models = ['atlas', 'sigma', 'gamma', 'piramida', 'terra']; // Add other models here as needed
    foreach ($models as $model) {
        if (has_term($model, 'product_tag') && function_exists('get_field')) {
            $pricing = [
               // 'price_panels_lin_meter' => get_field('price_panels_lin_meter'),
                'price_u_profile_left' => get_field('price_u_profile_left'),
                'price_u_profile_right' => get_field('price_u_profile_right'),
                'price_u_horizontal_panel' => get_field('price_u_horizontal_panel'),
                'price_reinforcing_profile' => get_field('price_reinforcing_profile'),
                'price_rivets' => get_field('price_rivets'),
                'price_self_tapping_screw' => get_field('price_self_tapping_screw'),
                'price_dowels' => get_field('price_dowels'),
                'price_corners' => get_field('price_corners')
            ];

            // Add Terra-specific pricing fields
            if ($model === 'terra') {
                $pricing['base_distance'] = get_field('price_base_distance') ?: 2; // Default 2cm
                $pricing['distance_cassettes'] = get_field('price_distance_cassettes') ?: 2; // Default 2cm
            }

            $data = [
                'pricing' => $pricing,
                'panel_height' => explode("\r\n", get_field('panel_height')),
                'width_min' => get_field('width_min'),
                'width_max' => get_field('width_max')
            ];

            echo view("partials.product.{$model}-calculator-form", ["{$model}Data" => $data])->render();
        }
    }
}, 6); // After title (5), before price (10)

// Hook the calculator results after product meta
add_action('woocommerce_after_add_to_cart_form', function() {
    $models = ['atlas', 'sigma', 'gamma', 'piramida', 'terra']; // Add other models here as needed
    foreach ($models as $model) {
        if (has_term($model, 'product_tag')) {
            echo view("partials.product.{$model}-calculator-results")->render();
        }
    }
});

// Modify the price before adding to cart
add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id) {
    $models = ['atlas', 'sigma', 'gamma', 'piramida', 'terra'];
    foreach ($models as $model) {
        if (has_term($model, 'product_tag', $product_id)) {
            // Get base values for all models
            $calculated_price = isset($_POST['calculated_price']) ? floatval($_POST['calculated_price']) : 0;
            $panel_width = isset($_POST["{$model}_panel_width"]) ? floatval($_POST["{$model}_panel_width"]) : 0;
            $panel_height = isset($_POST["{$model}_panel_height"]) ? floatval($_POST["{$model}_panel_height"]) : 0;
            $number_of_panels = isset($_POST["{$model}_number_of_panels"]) ? intval($_POST["{$model}_number_of_panels"]) : 0;

            // Only add if we have valid data
            if ($calculated_price > 0) {
                $cart_item_data['custom_price'] = $calculated_price;
                $cart_item_data["{$model}_panel_width"] = $panel_width;
                $cart_item_data["{$model}_panel_height"] = $panel_height;
                $cart_item_data["{$model}_number_of_panels"] = $number_of_panels;

                // Add Terra-specific fields
                if ($model === 'terra') {
                    $distance_cassettes = isset($_POST['terra_panel_distance_cassettes']) ? floatval($_POST['terra_panel_distance_cassettes']) : 0;
                    $base_distance = isset($_POST['terra_panel_base_distance']) ? floatval($_POST['terra_panel_base_distance']) : 0;
                    
                    $cart_item_data['terra_panel_distance_cassettes'] = $distance_cassettes;
                    $cart_item_data['terra_panel_base_distance'] = $base_distance;
                }
            }
        }
    }
    return $cart_item_data;
}, 10, 2);

// Add the calculated price to the add to cart form
add_action('woocommerce_before_add_to_cart_button', function() {
    $models = ['atlas', 'sigma', 'gamma', 'piramida', 'terra'];
    foreach ($models as $model) {
        if (has_term($model, 'product_tag')) {
            ?>
            <input type="hidden" name="calculated_price" id="calculated_price" value="">
            <input type="hidden" name="<?php echo $model; ?>_panel_width" id="<?php echo $model; ?>_panel_width" value="">
            <input type="hidden" name="<?php echo $model; ?>_panel_height" id="<?php echo $model; ?>_panel_height" value="">
            <input type="hidden" name="<?php echo $model; ?>_number_of_panels" id="<?php echo $model; ?>_number_of_panels" value="">
            
            <?php if ($model === 'terra'): ?>
            <input type="hidden" name="terra_panel_distance_cassettes" id="terra_panel_distance_cassettes" value="">
            <input type="hidden" name="terra_panel_base_distance" id="terra_panel_base_distance" value="">
            <?php endif; ?>

            <script>
                jQuery(document).ready(function($) {
                    $('#<?php echo $model; ?>-calculate').on('click', function() {
                        // Get base values
                        let calculatedPrice = $('.single_add_to_cart_button').attr('data-calculated-price');
                        let panelWidth = $('#<?php echo $model; ?>-panel-width').val();
                        let panelHeight = $('#<?php echo $model; ?>-panel-height').val();
                        let numberOfPanels = $('#<?php echo $model; ?>-number-of-panels').val();
                        
                        // Convert to numbers
                        calculatedPrice = parseFloat(calculatedPrice) || 0;
                        panelWidth = parseFloat(panelWidth) || 0;
                        panelHeight = parseFloat(panelHeight) || 0;
                        numberOfPanels = parseInt(numberOfPanels) || 0;
                        
                        // Update base fields
                        $('#calculated_price').val(calculatedPrice.toFixed(2));
                        $('#<?php echo $model; ?>_panel_width').val(panelWidth.toFixed(2));
                        $('#<?php echo $model; ?>_panel_height').val(panelHeight.toFixed(2));
                        $('#<?php echo $model; ?>_number_of_panels').val(numberOfPanels);

                        <?php if ($model === 'terra'): ?>
                        // Get and update Terra-specific fields
                        let distanceCassettes = $('#terra-panel-distance-cassettes').val();
                        let baseDistance = $('#terra-panel-base-distance').val();
                        
                        distanceCassettes = parseFloat(distanceCassettes) || 0;
                        baseDistance = parseFloat(baseDistance) || 0;
                        
                        $('#terra_panel_distance_cassettes').val(distanceCassettes.toFixed(2));
                        $('#terra_panel_base_distance').val(baseDistance.toFixed(2));
                        <?php endif; ?>
                        
                        console.log('Values set:', {
                            price: calculatedPrice,
                            width: panelWidth,
                            height: panelHeight,
                            panels: numberOfPanels
                            <?php if ($model === 'terra'): ?>
                            ,
                            distanceCassettes: distanceCassettes,
                            baseDistance: baseDistance
                            <?php endif; ?>
                        });
                    });
                });
            </script>
            <?php
        }
    }
});

// Display width, height, and number of panels in the mini cart
add_filter('woocommerce_get_item_data', function($item_data, $cart_item) {
    $models = ['atlas', 'sigma', 'gamma', 'piramida', 'terra'];
    foreach ($models as $model) {
        if (isset($cart_item["{$model}_panel_width"])) {
            $item_data[] = [
                'key' => __('Panel Width', 'zah'),
                'value' => wc_clean($cart_item["{$model}_panel_width"]) . 'm',
            ];
            $item_data[] = [
                'key' => __('Panel Height', 'zah'),
                'value' => wc_clean($cart_item["{$model}_panel_height"]) . 'm',
            ];
            $item_data[] = [
                'key' => __('Number of Panels', 'zah'),
                'value' => wc_clean($cart_item["{$model}_number_of_panels"]),
            ];

            // Add Terra-specific fields
            if ($model === 'terra' && isset($cart_item['terra_panel_distance_cassettes'])) {
                $item_data[] = [
                    'key' => __('Distance Between Cassettes', 'zah'),
                    'value' => wc_clean($cart_item['terra_panel_distance_cassettes']) . 'cm'
                ];
                $item_data[] = [
                    'key' => __('Base Distance', 'zah'),
                    'value' => wc_clean($cart_item['terra_panel_base_distance']) . 'cm'
                ];
            }
        }
    }
    return $item_data;
}, 10, 2);

add_filter('woocommerce_get_cart_item_from_session', function($cart_item, $values) {
    $models = ['atlas', 'sigma', 'gamma', 'piramida', 'terra'];
    foreach ($models as $model) {
        if (isset($values['custom_price'])) {
            $cart_item['custom_price'] = $values['custom_price'];
            $cart_item['data']->set_price($values['custom_price']);
        }
        if (isset($values["{$model}_panel_width"])) {
            $cart_item["{$model}_panel_width"] = $values["{$model}_panel_width"];
            $cart_item["{$model}_panel_height"] = $values["{$model}_panel_height"];
            $cart_item["{$model}_number_of_panels"] = $values["{$model}_number_of_panels"];

            // Restore Terra-specific fields
            if ($model === 'terra' && isset($values['terra_panel_distance_cassettes'])) {
                $cart_item['terra_panel_distance_cassettes'] = $values['terra_panel_distance_cassettes'];
                $cart_item['terra_panel_base_distance'] = $values['terra_panel_base_distance'];
            }
        }
    }
    return $cart_item;
}, 10, 2);

// Save the custom data to the order items
add_action('woocommerce_checkout_create_order_line_item', function($item, $cart_item_key, $values, $order) {
    $models = ['atlas', 'sigma', 'gamma', 'piramida', 'terra'];
    foreach ($models as $model) {
        if (isset($values["{$model}_panel_width"])) {
            $item->add_meta_data(__('Panel Width', 'zah'), $values["{$model}_panel_width"] . 'm');
            $item->add_meta_data(__('Panel Height', 'zah'), $values["{$model}_panel_height"] . 'm');
            $item->add_meta_data(__('Number of Panels', 'zah'), $values["{$model}_number_of_panels"]);

            // Add Terra-specific fields
            if ($model === 'terra' && isset($values['terra_panel_distance_cassettes'])) {
                $item->add_meta_data(__('Distance Between Cassettes', 'zah'), $values['terra_panel_distance_cassettes'] . ' cm');
                $item->add_meta_data(__('Base Distance', 'zah'), $values['terra_panel_base_distance'] . ' cm');
            }
        }
    }
}, 10, 4);


add_filter('woocommerce_get_price_html', function($price, $product) {
    if (has_term(['atlas', 'sigma', 'gamma', 'piramida', 'terra'], 'product_tag', $product->get_id())) {
        return ''; // Return an empty string to hide the price
    }
    return $price;
}, 10, 2);


// Populate the height field with values from the product
add_filter('acf/load_field/key=field_67364f8510685', 'populate_height_field');
function populate_height_field($field) {
    // Reset the choices
    $field['choices'] = [];

    // Query for products with the "Atlas" or "Sigma" tag
    $args = [
        'post_type' => 'product',
        'tax_query' => [
            [
                'taxonomy' => 'product_tag',
                'field' => 'slug',
                'terms' => ['atlas', 'sigma', 'gamma', 'piramida', 'terra'],
            ],
        ],
        'posts_per_page' => 6, // Assuming the heights are the same for all "Atlas" and "Sigma" products
    ];
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Fetch the panel_height field
            $panel_height = get_field('panel_height');
            if ($panel_height) {
                // Split textarea values into an array
                $heights = explode("\r\n", $panel_height);

                // Populate choices
                foreach ($heights as $height) {
                    $field['choices'][$height] = $height;
                }
            }
        }
        wp_reset_postdata();
    }

    return $field;
}

// Apply the custom price
add_action('woocommerce_before_calculate_totals', function($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        //error_log('Calculating totals for item: ' . print_r($cart_item, true));
        if (isset($cart_item['custom_price'])) {
            $cart_item['data']->set_price($cart_item['custom_price']);
        }
    }
}, 10, 1);

// Save the calculated price in the session
add_filter('woocommerce_add_cart_item', function($cart_item) {
    if (isset($cart_item['custom_price'])) {
        $cart_item['data']->set_price($cart_item['custom_price']);
    }
    return $cart_item;
}, 10, 1);

// add_action('woocommerce_add_to_cart', function($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {
//     ////error_log('Add to Cart Event:');
//     ////error_log('POST Data: ' . print_r($_POST, true));
//     ////error_log('Cart Item Data: ' . print_r($cart_item_data, true));
// }, 10, 6);


if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title'    => 'Fence Product Settings',
        'menu_title'    => 'Fence Settings',
        'menu_slug'     => 'fence-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
}

add_action('woocommerce_after_shop_loop_item_title', function() {
    global $product;

    // Check if product has relevant tags
    $has_atlas = has_term('atlas', 'product_tag', $product->get_id());
    $has_sigma = has_term('sigma', 'product_tag', $product->get_id());
    $has_gamma = has_term('gamma', 'product_tag', $product->get_id());
    $has_piramida = has_term('piramida', 'product_tag', $product->get_id());
    $has_terra = has_term('terra', 'product_tag', $product->get_id());
    $has_siding = has_term('siding', 'product_tag', $product->get_id());


        // Inside your existing action hook, in the siding section
        if ($has_siding) {
            $panel_siding_sqm = floatval(get_field('panel_siding_sqm', $product->get_id()) ?: 0);
            $base_price = floatval($product->get_regular_price() ?: 0);
            $sale_price = floatval($product->get_sale_price() ?: 0);
        
            // Show the base price directly (27.48 лв.)
            if ($product->is_on_sale() && $sale_price > 0 && $sale_price < $base_price) {
                echo '<div class="price">';
                echo '<span class="woocommerce-Price-amount amount">';
                echo '<del><bdi>' . number_format($base_price, 2) . '&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi></del> ';
                echo '<ins><bdi>' . number_format($sale_price, 2) . '&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi></ins>';
                echo '<span class="custom-text-after-price">/м²</span>';
                echo '</span>';
                echo '</div>';
            } else {
                echo '<div class="price">';
                echo '<span class="woocommerce-Price-amount amount">';
                echo '<bdi>' . number_format($base_price, 2) . '&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>';
                echo '<span class="custom-text-after-price">/м²</span>';
                echo '</span>';
                echo '</div>';
            }
        
            return;
        }

    if (!$has_atlas && !$has_sigma && !$has_gamma && !$has_piramida && !$has_terra && !$has_siding) {
        //echo "<script>console.log('No relevant tags found');</script>";
        return;
    }

    $models = get_field('models', 'option');

   if (!$models || !is_array($models)) {
       //echo "<script>console.log('No models found or invalid models');</script>";
       return;
   }

    foreach ($models as $model) {
        // Get tag info
        $tag_id = isset($model['tag'][0]) ? $model['tag'][0] : null;
        $term = get_term($tag_id, 'product_tag');
        $tag_slug = $term ? $term->slug : '';

        if (($has_sigma && $tag_slug === 'sigma') || 
            ($has_atlas && $tag_slug === 'atlas') || 
            ($has_gamma && $tag_slug === 'gamma') || 
            ($has_piramida && $tag_slug === 'piramida') ||
            ($has_terra && $tag_slug === 'terra') ||
            ($has_siding && $tag_slug === 'siding')) {
            
            $predefined_sizes = $model['predefined_sizes'];

            if (is_array($predefined_sizes) && count($predefined_sizes) > 0) {
                $first_size = $predefined_sizes[0];

                // Get dimensions
                $width = isset($first_size['width']) ? floatval($first_size['width']) : 0;
                $height = isset($first_size['height']) ? floatval($first_size['height']) : 0;
                $panels = isset($first_size['number_of_panels']) ? intval($first_size['number_of_panels']) : 0;

                // Set up base variables
                $prices = [
                    'base_price' => $product->get_regular_price() ?: 0,
                    'sale_price' => $product->get_sale_price() ?: 0,
                    'u_profile_left' => floatval(get_field('price_u_profile_left', $product->get_id()) ?: 0),
                    'u_profile_right' => floatval(get_field('price_u_profile_right', $product->get_id()) ?: 0),
                    'u_horizontal_panel' => floatval(get_field('price_u_horizontal_panel', $product->get_id()) ?: 0),
                    'reinforcing_profile' => floatval(get_field('price_reinforcing_profile', $product->get_id()) ?: 0),
                    'rivets' => floatval(get_field('price_rivets', $product->get_id()) ?: 0),
                    'self_tapping_screw' => floatval(get_field('price_self_tapping_screw', $product->get_id()) ?: 0),
                    'dowels' => floatval(get_field('price_dowels', $product->get_id()) ?: 0),
                    'corners' => floatval(get_field('price_corners', $product->get_id()) ?: 0),
                ];

                if (($has_siding && $tag_slug === 'siding')) {
                    
                    // Get the siding-specific fields directly
                    $panel_siding_sqm = floatval(get_field('panel_siding_sqm', $product->get_id()) ?: 0);
                    $panel_siding_useful = floatval(get_field('panel_siding_useful', $product->get_id()) ?: 0);
                    $base_price = floatval($product->get_regular_price() ?: 0);
                    $sale_price = floatval($product->get_sale_price() ?: 0);
                
                    // Calculate example price for 1m²
                    $total_price = $base_price;
                    $discounted_price = $sale_price > 0 ? $sale_price : $total_price;
                
                    // Display price per square meter
                    if ($product->is_on_sale() && $sale_price > 0 && $sale_price < $base_price) {
                        echo '<div class="price">';
                        echo '<del>' . wc_price($total_price) . '</del> ';
                        echo '<ins>' . wc_price($discounted_price) . '</ins>';
                        echo '<span class="price-suffix">' . __('/м²', 'zah') . '</span>';
                        echo '</div>';
                    } else {
                        echo '<div class="price">';
                        echo wc_price($total_price);
                        echo '<span class="price-suffix">' . __('/м²', 'zah') . '</span>';
                        echo '</div>';
                    }
                
                    break;
                } elseif ($tag_slug === 'terra') {
                    // Terra calculations (existing code)
                    $cassetteDistance = 2;
                    $baseDistance = 2;
                    
                    $G15 = floor(($height - $baseDistance/100) / (0.108 + $cassetteDistance/100));
                    $G16 = ceil(($height - $baseDistance/100) / (0.108 + $cassetteDistance/100));
                    $H15 = $G15 * 0.108 + ($G15 - 1) * ($cassetteDistance/100) + ($baseDistance/100);
                    $H16 = $G16 * 0.108 + ($G16 - 1) * ($cassetteDistance/100) + ($baseDistance/100);
                    $G17 = abs($height - $H15);
                    $H17 = abs($height - $H16);
                    
                    $numCassettes = ($G17 <= $H17) ? $G15 : $G16;
                    $profileCassettesPcs = $numCassettes * $panels;
                    $profileCassettesLm = max(($width - 0.01) * $profileCassettesPcs, 0);
                    
                    $uProfilePcs = $panels * 2;
                    $uProfileLm = ($G17 <= $H17 ? $H15 : $H16) * $panels * 2;
                    $rivetsPcs = round($profileCassettesPcs * 8) >= 101 ? $panels * 200 : $panels * 100;
                    $selfTappingScrewPcs = $panels * 10;
                    $dowelsPcs = $panels * 10;

                    $total_price = $profileCassettesLm * $prices['base_price'] +
                                 $uProfileLm * $prices['u_profile_left'] +
                                 $rivetsPcs * $prices['rivets'] +
                                 $selfTappingScrewPcs * $prices['self_tapping_screw'] +
                                 $dowelsPcs * $prices['dowels'];

                    $discounted_price = $profileCassettesLm * ($prices['sale_price'] ?: $prices['base_price']) +
                                      $uProfileLm * $prices['u_profile_left'] +
                                      $rivetsPcs * $prices['rivets'] +
                                      $selfTappingScrewPcs * $prices['self_tapping_screw'] +
                                      $dowelsPcs * $prices['dowels'];

                } else {
                    // Other models calculations (existing code)
                    $F20 = 0;
                    if ($width > 1.29 && $width < 2.1) {
                        $F20 = 1;
                    } else if ($width > 2.09) {
                        $F20 = 2;
                    }

                    $dowelsPcs = $panels * 10 + $F20 * $panels;
                    $cornerPcs = $F20 * $panels;

                    $blindsProfilePcs = 0;

                    if ($tag_slug === 'atlas') {
                        $blindsProfilePcs = max(($height - 0.045) / 0.1 * $panels, 0);
                    } elseif ($tag_slug === 'sigma') {
                        $blindsProfilePcs = max(($height - 0.06) / 0.08 * $panels, 0);
                    } elseif ($tag_slug === 'gamma') {
                        $blindsProfilePcs = max(($height - 0.05) / 0.16 * $panels, 0);
                    } elseif ($tag_slug === 'piramida') {
                        $blindsProfilePcs = max(($height - 0.06) / 0.065 * $panels, 0);
                    }

                    $blindsProfileLm = max(($width - 0.01) * $blindsProfilePcs, 0);
                    $uProfileLeftLm = $height * $panels;
                    $uProfileRightLm = $height * $panels;
                    $horizontalUProfileLm = $width * $panels;
                    $reinforcingProfileLm = $height * $panels;
                    $rivetsQty = 100;
                    $selfTappingScrewQty = 10;

                    $total_price = $blindsProfileLm * $prices['base_price'] +
                                 $uProfileLeftLm * $prices['u_profile_left'] +
                                 $uProfileRightLm * $prices['u_profile_right'] +
                                 $horizontalUProfileLm * $prices['u_horizontal_panel'] +
                                 $reinforcingProfileLm * $prices['reinforcing_profile'] +
                                 $rivetsQty * $prices['rivets'] +
                                 $selfTappingScrewQty * $prices['self_tapping_screw'] +
                                 $dowelsPcs * $prices['dowels'] +
                                 $cornerPcs * $prices['corners'];

                    $discounted_price = $blindsProfileLm * ($prices['sale_price'] ?: $prices['base_price']) +
                                      $uProfileLeftLm * $prices['u_profile_left'] +
                                      $uProfileRightLm * $prices['u_profile_right'] +
                                      $horizontalUProfileLm * $prices['u_horizontal_panel'] +
                                      $reinforcingProfileLm * $prices['reinforcing_profile'] +
                                      $rivetsQty * $prices['rivets'] +
                                      $selfTappingScrewQty * $prices['self_tapping_screw'] +
                                      $dowelsPcs * $prices['dowels'] +
                                      $cornerPcs * $prices['corners'];
                }

                echo '<div class="predefined-size">';
                if ($product->is_on_sale() && $prices['sale_price'] > 0 && $prices['sale_price'] < $prices['base_price']) {
                    echo '<p class="fence-price">';
                    echo '<del>' . wc_price($total_price) . '</del> ';
                    echo '<ins>' . wc_price($discounted_price) . '</ins>';
                    echo '</p>';
                } else {
                    echo '<p class="fence-price">' . wc_price($total_price) . '</p>';
                }

                echo '<span class="fence-data">' . __('Panel price for', 'zah') . ' ' .
                     number_format($width, 2) . '(ш) x ' . number_format($height, 3) . '(в)' . '</span>';
                echo '</div>';

                break;
            }
        }
    }
});

// Optionally, add this filter to hide the default price
add_filter('woocommerce_get_price_html', function($price, $product) {
    if (has_term(['atlas', 'sigma', 'gamma', 'piramida', 'terra', 'siding'], 'product_tag', $product->get_id())) {
        return ''; // Return empty string to hide default price
    }
    return $price;
}, 100, 2);



// Change "Add to Cart" button text for siding products
add_filter('woocommerce_loop_add_to_cart_link', function($button, $product) {
    if (has_term('siding', 'product_tag', $product->get_id())) {
        $product_url = get_permalink($product->get_id());
        $button = sprintf('<a href="%s" class="button">%s</a>', esc_url($product_url), esc_html__('Calculate', 'zah'));
    }
    return $button;
}, 10, 2);

// Add calculator form in product page

add_action('woocommerce_single_product_summary', function() {
    if (has_term('siding', 'product_tag') && function_exists('get_field')) {
        global $product;  // Get the product object
        
        $data = [
            'panel_siding_sqm' => get_field('panel_siding_sqm'),
            'panel_siding_useful' => get_field('panel_siding_useful'),
            'base_price' => $product->get_regular_price()
        ];
        
        echo view("partials.product.siding-calculator-form", [
            'sidingData' => $data,
            'product' => $product  // Pass the product object
        ])->render();
    }
}, 6);

// Handle cart item data
add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id) {
    if (has_term('siding', 'product_tag', $product_id)) {
        $calculated_price = isset($_POST['calculated_price']) ? floatval($_POST['calculated_price']) : 0;
        $siding_width = isset($_POST['siding_width']) ? floatval($_POST['siding_width']) : 0;
        $panel_number = isset($_POST['siding_panel_number']) ? intval($_POST['siding_panel_number']) : 0;

        if ($calculated_price > 0) {
            $cart_item_data['custom_price'] = $calculated_price;
            $cart_item_data['siding_width'] = $siding_width;
            $cart_item_data['panel_number'] = $panel_number;
        }
    }
    return $cart_item_data;
}, 10, 2);

// Add hidden fields for add to cart
add_action('woocommerce_before_add_to_cart_button', function() {
    if (has_term('siding', 'product_tag')) {
        ?>
        <input type="hidden" name="calculated_price" id="calculated_price" value="">
        <input type="hidden" name="siding_width" id="siding_width" value="">
        <input type="hidden" name="siding_panel_number" id="siding_panel_number" value="">
        <script>
            jQuery(document).ready(function($) {
                $('#siding-calculate').on('click', function() {
                    let calculatedPrice = $('.single_add_to_cart_button').attr('data-calculated-price');
                    let sidingWidth = $('#siding-width').val();
                    let panelNumber = $('#siding-panel-number').val();
                    
                    $('#calculated_price').val(calculatedPrice);
                    $('#siding_width').val(sidingWidth);
                    $('#siding_panel_number').val(panelNumber);
                });
            });
        </script>
        <?php
    }
});

// Display siding data in cart
add_filter('woocommerce_get_item_data', function($item_data, $cart_item) {
    if (isset($cart_item['siding_width'])) {
        $item_data[] = [
            'key' => __('Width', 'zah'),
            'value' => wc_clean($cart_item['siding_width']) . 'm'
        ];
        $item_data[] = [
            'key' => __('Number of Panels', 'zah'),
            'value' => wc_clean($cart_item['panel_number'])
        ];
    }
    return $item_data;
}, 10, 2);

// Save siding data in order
add_action('woocommerce_checkout_create_order_line_item', function($item, $cart_item_key, $values, $order) {
    if (isset($values['siding_width'])) {
        $item->add_meta_data(__('Width', 'zah'), $values['siding_width'] . 'm');
        $item->add_meta_data(__('Number of Panels', 'zah'), $values['panel_number']);
    }
}, 10, 4);

// Hide default price for siding products
add_filter('woocommerce_get_price_html', function($price, $product) {
    if (has_term('siding', 'product_tag', $product->get_id())) {
        return '';
    }
    return $price;
}, 10, 2);


/**
 * Modify ACF relationship field query to include SKU in search for product fields
 */
function modify_relationship_query($args, $field, $post_id) {

    // Check for both field names
    $product_fields = ['product_list_builder', 'related_products'];
    
    if (in_array($field['name'], $product_fields) && !empty($args['s'])) {
        
        $search_term = $args['s'];
        
        // Store original 's' parameter
        $original_search = $args['s'];
        unset($args['s']);
        
        // Modify the query to include SKU search
        $args['meta_query'] = array(
            'relation' => 'OR',
            array(
                'key'     => '_sku',
                'value'   => $search_term,
                'compare' => 'LIKE'
            )
        );
        
        // Add custom filter for title search
        $where_filter = function($where) use ($original_search) {
            global $wpdb;
            $search = '%' . $wpdb->esc_like($original_search) . '%';
            $where .= $wpdb->prepare(
                " OR ({$wpdb->posts}.post_title LIKE %s)",
                $search
            );
            return $where;
        };
        add_filter('posts_where', $where_filter, 10, 1);
        
        // Add custom filter to group results
        $groupby_filter = function($groupby) use ($wpdb) {
            if(!$groupby) {
                $groupby = "{$wpdb->posts}.ID";
            }
            return $groupby;
        };
        add_filter('posts_groupby', $groupby_filter);

        // Remove the filters after the query
        add_action('pre_get_posts', function() use ($where_filter, $groupby_filter) {
            remove_filter('posts_where', $where_filter);
            remove_filter('posts_groupby', $groupby_filter);
        }, 999);
    }
    
    if (WP_DEBUG) {
        error_log('Modified args: ' . print_r($args, true));
    }
    return $args;
}
add_filter('acf/fields/relationship/query', 'modify_relationship_query', 10, 3);

/**
 * Add debug information to AJAX responses
 */
function debug_ajax_response($response) {
    if (is_admin() && defined('DOING_AJAX') && DOING_AJAX) {
        error_log('AJAX Response: ' . print_r($response, true));
    }
    return $response;
}
add_filter('acf/fields/relationship/result', 'debug_ajax_response', 10, 1);

/**
 * Modify the query join to include SKU from postmeta
 */
function modify_relationship_join($join) {
    global $wpdb;
    
    // Check if we're in the correct context
    if (doing_action('acf/fields/relationship/query')) {
        $join .= " LEFT JOIN {$wpdb->postmeta} as sku_meta ON ({$wpdb->posts}.ID = sku_meta.post_id AND sku_meta.meta_key = '_sku')";
    }
    
    return $join;
}
add_filter('posts_join', 'modify_relationship_join', 10, 1);












// Modify catalog ordering arguments for SKU sorting
add_filter('woocommerce_get_catalog_ordering_args', 'custom_woocommerce_sku_ordering');

function custom_woocommerce_sku_ordering($args) {
    // If no orderby is set or it's explicitly set to 'sku'
    if (!isset($_GET['orderby']) || $_GET['orderby'] === 'sku') {
        $args['orderby'] = 'meta_value';
        $args['meta_key'] = '_sku';
        $args['meta_type'] = 'CHAR';
        
        // Add custom sorting through posts_clauses
        add_filter('posts_clauses', 'custom_sku_sorting_clauses', 10, 2);
    }

    return $args;
}

// Custom sorting clauses for natural SKU ordering
function custom_sku_sorting_clauses($clauses, $query) {
    global $wpdb;
    
    // Only modify if we're sorting by SKU and on a WooCommerce page
    if (is_woocommerce() && isset($clauses['join']) && strpos($clauses['join'], '_sku') !== false) {
        // Original orderby
        $original_orderby = $clauses['orderby'];
        
        // New orderby with natural sorting
        $clauses['orderby'] = "
            CASE 
                WHEN pm.meta_value REGEXP '^[0-9]' THEN 1
                ELSE 2
            END ASC,
            CASE 
                WHEN pm.meta_value REGEXP '^[0-9]' 
                THEN CAST(pm.meta_value AS UNSIGNED)
                ELSE pm.meta_value
            END ASC,
            " . $original_orderby;
    }
    
    return $clauses;
}

// Remove the filter after the query is complete
add_action('wp', function() {
    remove_filter('posts_clauses', 'custom_sku_sorting_clauses', 10);
});

// Add SKU sorting option to the dropdown
add_filter('woocommerce_default_catalog_orderby_options', 'custom_woocommerce_sku_orderby_options');
add_filter('woocommerce_catalog_orderby', 'custom_woocommerce_sku_orderby_options');

function custom_woocommerce_sku_orderby_options($sortby) {
    $sortby['sku'] = 'Сортиране по Арт. №';
    return $sortby;
}

// Set SKU as default sorting option
add_filter('woocommerce_default_catalog_orderby', 'custom_default_catalog_orderby');

function custom_default_catalog_orderby($default_orderby) {
    return 'sku';
}

add_filter('woocommerce_get_price_html', 'custom_text_after_price', 10, 2);

function custom_text_after_price($price, $product) {
    // Check if we're on a single product page
    if (is_product()) {
        // Your custom text here
        $custom_text = ' <span class="custom-text-after-price">(вкл. ДДС)</span>';
        
        // Append the custom text to the price
        return $price . $custom_text;
    }

    // Return the original price if not on a product page
    return $price;
}

add_filter( 'econt_create_loading_result', 'mrejanet_alter_shipping_price876876', 10, 2 );

function mrejanet_alter_shipping_price876876 ($result, $loading_data) {
global $woocommerce;

$free_shipping_products = false;
$free_shipping_class_name = 'free-shipping';

if($loading_data['order_id'] < 0){
    if( isset($woocommerce->cart) ){
        $items = $woocommerce->cart->get_cart();

        foreach($items as $item => $values) {
            $_product = wc_get_product( $values['data']->get_id() );
            $shipping_class = $_product->get_shipping_class();

            if( $shipping_class == $free_shipping_class_name ){
                $free_shipping_products = true;
                break;
            }
        }

    }
    if( $free_shipping_products === true ){
    $result['customer_shipping_cost'] = __('Безплатна доставка', 'woocommerce-econt');
        if(!isset($_SESSION)) {
            session_start();
        }
    $_SESSION['econt_shipping_cost'] = 0.00;

    }
}else{
$order = wc_get_order( $loading_data['order_id'] );

if( $order ){
    foreach( $order->get_items() as $item_id => $item ) {
        $_product = wc_get_product( $item->get_product_id() );
        $shipping_class = $_product->get_shipping_class();
        $quantity = $item->get_quantity();

        if( $shipping_class == $free_shipping_class_name ){
            $free_shipping_products = true;
            break;
            }
        }
    }
    if( $free_shipping_products === true ){
        $result['customer_shipping_cost'] = __('free shipping', 'woocommerce-econt');
    }

}

return $result;

}


// In functions.php or theme's ACF-related file
add_action('woocommerce_after_single_product_summary', function() {
    global $product;
    
    // Get related products and free sample data
    $related_products = get_field('related_products');
    $free_sample = get_field('free_sample');
    
    if ($related_products || ($free_sample && !empty($free_sample['sample_heading']))) {
        echo '<div class="product-additional-sections">';
        
        // Related Products Section
        if ($related_products) {
            $products_count = count($related_products);
            $slider_class = $products_count >= 5 ? 'is-slider' : 'is-grid';
            
            echo '<section class="product-list-builder2">';
            echo '<h2 class="title">' . esc_html__('Connected Products', 'zah') . '</h2>';
            echo '<section class="connected-products">';
            echo '<div class="swiper more-products-slider products ' . $slider_class . '" data-products-count="' . $products_count . '">';
            echo '<div class="swiper-wrapper">';
            
            foreach ($related_products as $related_product) {
                echo '<div class="swiper-slide">';

                // Store original globals
                $original_post = $GLOBALS['post'];
                $original_product = $GLOBALS['product'];

                // Set up new post data
                $GLOBALS['post'] = get_post($related_product->ID);
                $related_wc_product = wc_get_product($related_product->ID);
                $GLOBALS['product'] = $related_wc_product;
                setup_postdata($GLOBALS['post']);

                // Let content-product.blade.php handle the structure
                wc_get_template_part('content', 'product');

                // Restore original globals
                $GLOBALS['post'] = $original_post;
                $GLOBALS['product'] = $original_product;
                wp_reset_postdata();

                echo '</div>';
            }
            
            echo '</div>'; // .swiper-wrapper
            
            // Navigation for 6+ products
            if ($products_count >= 6) {
                // ... navigation code ...
            }
            
            echo '</div></section>';
            echo '</section>';
        }

        // Free Sample Section
        if ($free_sample && !empty($free_sample['sample_heading'])) {
            echo '<section class="free-sample-block">';
           
            echo '<div class="free-sample-content">';
            
            echo '<div class="sample-text">';
            if (!empty($free_sample['sample_heading'])) {
                echo '<h2>' . esc_html($free_sample['sample_heading']) . '</h2>';
            }
            
            if (!empty($free_sample['sample_description'])) {
                echo '<div class="sample-description">';
                echo wp_kses_post($free_sample['sample_description']);
                echo '</div>';

                echo '<button class="free-sample-btn ">Поръчай Безплатна мостра</button>';
            }
            echo '</div>'; // .sample-text

            if (!empty($free_sample['sample_image'])) {
                echo '<div class="sample-image">';
                echo wp_get_attachment_image($free_sample['sample_image']['ID'], 'medium_large', false, [
                    'class' => 'sample-featured-image',
                    'alt' => esc_attr($free_sample['sample_heading'])
                ]);
                echo '</div>';
            }
            
            echo '</div>'; // .free-sample-content
    
            echo '</section>';
        }
        
        echo '</div>'; // .product-additional-sections
    }
}, 15); // Priority 15 to ensure it comes after tabs



function get_atlas_pokritie_products() {
    $args = array(
        'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_tag',
                'field' => 'slug',
                'terms' => 'atlas'
            )
        ),
        'posts_per_page' => -1
    );
    
    $products = new WP_Query($args);
    $pokritie_products = array();
    
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            $product = wc_get_product(get_the_ID());
            
            $pokritie_terms = wc_get_product_terms(get_the_ID(), 'pa_pokritie', array('fields' => 'all'));
            
            if (!empty($pokritie_terms)) {
                $pokritie_term = $pokritie_terms[0];
                // Create the archive URL with swoof filter
                $archive_url = home_url('/product-category/ogradni-sthori-i-lamarini/ogradni-sthori-atlas/swoof/pokritie-' . $pokritie_term->slug . '/');
                
                $pokritie_products[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'pokritie' => $pokritie_term->name,
                    'price' => floatval($product->get_regular_price()),
                    'sale_price' => floatval($product->get_sale_price()),
                    'link' => $archive_url, // Using archive URL instead of product URL
                    'component_prices' => array(
                        'u_profile_left' => floatval(get_field('price_u_profile_left')),
                        'u_profile_right' => floatval(get_field('price_u_profile_right')),
                        'u_horizontal_panel' => floatval(get_field('price_u_horizontal_panel')),
                        'reinforcing_profile' => floatval(get_field('price_reinforcing_profile')),
                        'rivets' => floatval(get_field('price_rivets')),
                        'self_tapping_screw' => floatval(get_field('price_self_tapping_screw')),
                        'dowels' => floatval(get_field('price_dowels')),
                        'corners' => floatval(get_field('price_corners'))
                    )
                );
            }
        }
        wp_reset_postdata();
    }
    
    return $pokritie_products;
}

function get_gamma_pokritie_products() {
    $args = array(
        'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_tag',
                'field' => 'slug',
                'terms' => 'gamma'
            )
        ),
        'posts_per_page' => -1
    );
    
    $products = new WP_Query($args);
    error_log('Gamma query results: ' . print_r($products->posts, true));
    $pokritie_products = array();
    
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            $product = wc_get_product(get_the_ID());
            error_log('Processing product: ' . get_the_title());
            
            $pokritie_terms = wc_get_product_terms(get_the_ID(), 'pa_pokritie', array('fields' => 'all'));
            error_log('Pokritie terms: ' . print_r($pokritie_terms, true));
            
            if (!empty($pokritie_terms)) {
                $pokritie_term = $pokritie_terms[0];
                $archive_url = home_url('/product-category/ogradni-sthori-i-lamarini/ogradni-sthori-gamma/swoof/pokritie-' . $pokritie_term->slug . '/');
                
                $pokritie_products[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'pokritie' => $pokritie_term->name,
                    'price' => floatval($product->get_regular_price()),
                    'sale_price' => floatval($product->get_sale_price()),
                    'link' => $archive_url,
                    'component_prices' => array(
                        'u_profile_left' => floatval(get_field('price_u_profile_left')),
                        'u_profile_right' => floatval(get_field('price_u_profile_right')),
                        'u_horizontal_panel' => floatval(get_field('price_u_horizontal_panel')),
                        'reinforcing_profile' => floatval(get_field('price_reinforcing_profile')),
                        'rivets' => floatval(get_field('price_rivets')),
                        'self_tapping_screw' => floatval(get_field('price_self_tapping_screw')),
                        'dowels' => floatval(get_field('price_dowels')),
                        'corners' => floatval(get_field('price_corners'))
                    )
                );
            }
        }
        wp_reset_postdata();
    }
    
    return $pokritie_products;
}

function get_sigma_pokritie_products() {
    $args = array(
        'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_tag',
                'field' => 'slug',
                'terms' => 'sigma'
            )
        ),
        'posts_per_page' => -1
    );
    
    $products = new WP_Query($args);
    $pokritie_products = array();
    
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            $product = wc_get_product(get_the_ID());
            
            $pokritie_terms = wc_get_product_terms(get_the_ID(), 'pa_pokritie', array('fields' => 'all'));
            
            if (!empty($pokritie_terms)) {
                $pokritie_term = $pokritie_terms[0];
                $archive_url = home_url('/product-category/ogradni-sthori-i-lamarini/ogradni-sthori-sigma/swoof/pokritie-' . $pokritie_term->slug . '/');
                
                $pokritie_products[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'pokritie' => $pokritie_term->name,
                    'price' => floatval($product->get_regular_price()),
                    'sale_price' => floatval($product->get_sale_price()),
                    'link' => $archive_url,
                    'component_prices' => array(
                        'u_profile_left' => floatval(get_field('price_u_profile_left')),
                        'u_profile_right' => floatval(get_field('price_u_profile_right')),
                        'u_horizontal_panel' => floatval(get_field('price_u_horizontal_panel')),
                        'reinforcing_profile' => floatval(get_field('price_reinforcing_profile')),
                        'rivets' => floatval(get_field('price_rivets')),
                        'self_tapping_screw' => floatval(get_field('price_self_tapping_screw')),
                        'dowels' => floatval(get_field('price_dowels')),
                        'corners' => floatval(get_field('price_corners'))
                    )
                );
            }
        }
        wp_reset_postdata();
    }
    
    return $pokritie_products;
}

function get_piramida_pokritie_products() {
    $args = array(
        'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_tag', 
                'field' => 'slug',
                'terms' => 'piramida'
            )
        ),
        'posts_per_page' => -1
    );
    
    $products = new WP_Query($args);
    $pokritie_products = array();
    
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            $product = wc_get_product(get_the_ID());
            
            $pokritie_terms = wc_get_product_terms(get_the_ID(), 'pa_pokritie', array('fields' => 'all'));
            
            if (!empty($pokritie_terms)) {
                $pokritie_term = $pokritie_terms[0];
                $archive_url = home_url('/product-category/ogradni-sthori-i-lamarini/ogradni-sthori-piramida/swoof/pokritie-' . $pokritie_term->slug . '/');
                
                $pokritie_products[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'pokritie' => $pokritie_term->name,
                    'price' => floatval($product->get_regular_price()),
                    'sale_price' => floatval($product->get_sale_price()),
                    'link' => $archive_url,
                    'component_prices' => array(
                        'u_profile_left' => floatval(get_field('price_u_profile_left')),
                        'u_profile_right' => floatval(get_field('price_u_profile_right')),
                        'u_horizontal_panel' => floatval(get_field('price_u_horizontal_panel')),
                        'reinforcing_profile' => floatval(get_field('price_reinforcing_profile')),
                        'rivets' => floatval(get_field('price_rivets')),
                        'self_tapping_screw' => floatval(get_field('price_self_tapping_screw')),
                        'dowels' => floatval(get_field('price_dowels')),
                        'corners' => floatval(get_field('price_corners'))
                    )
                );
            }
        }
        wp_reset_postdata();
    }
    
    return $pokritie_products;
 }
 
 function get_terra_pokritie_products() {
    $args = array(
        'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_tag',
                'field' => 'slug',
                'terms' => 'terra'
            )
        ),
        'posts_per_page' => -1
    );
    
    $products = new WP_Query($args);
    $pokritie_products = array();
    
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            $product = wc_get_product(get_the_ID());
            
            $pokritie_terms = wc_get_product_terms(get_the_ID(), 'pa_pokritie', array('fields' => 'all'));
            
            if (!empty($pokritie_terms)) {
                $pokritie_term = $pokritie_terms[0];
                $archive_url = home_url('/product-category/ogradni-sthori-i-lamarini/ogradni-sthori-terra/swoof/pokritie-' . $pokritie_term->slug . '/');
                
                $pokritie_products[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'pokritie' => $pokritie_term->name,
                    'price' => floatval($product->get_regular_price()),
                    'sale_price' => floatval($product->get_sale_price()),
                    'link' => $archive_url,
                    'component_prices' => array(
                        'u_profile_left' => floatval(get_field('price_u_profile_left')),
                        'u_profile_right' => floatval(get_field('price_u_profile_right')),
                        'u_horizontal_panel' => floatval(get_field('price_u_horizontal_panel')),
                        'reinforcing_profile' => floatval(get_field('price_reinforcing_profile')),
                        'rivets' => floatval(get_field('price_rivets')),
                        'self_tapping_screw' => floatval(get_field('price_self_tapping_screw')),
                        'dowels' => floatval(get_field('price_dowels')),
                        'corners' => floatval(get_field('price_corners'))
                    )
                );
            }
        }
        wp_reset_postdata();
    }
    
    return $pokritie_products;
 }



// Add custom fields to checkout
add_filter('woocommerce_checkout_fields', 'add_custom_company_checkout_fields');
function add_custom_company_checkout_fields($fields) {
    // Add invoice checkbox after phone field
    $fields['billing']['billing_invoice'] = array(
        'type'        => 'checkbox',
        'label'       => __('Искам фактура', 'woocommerce'),
        'required'    => false,
        'class'       => array('form-row-wide'),
        'priority'    => 51, // After phone (50)
        'clear'       => true
    );

    // Add entity type radio buttons
    $fields['billing']['billing_entity_type'] = array(
        'type'        => 'radio',
        'options'     => array(
            'juridical' => __('Юридическо лице', 'woocommerce'),
            'physical'  => __('Физическо лице', 'woocommerce')
        ),
        'required'    => false,
        'class'       => array('form-row-wide', 'hide-company-field'),
        'priority'    => 52 // Right after invoice checkbox
    );
    
    // Company name (using default WooCommerce field)
    $fields['billing']['billing_company']['label'] = __('Име на фирмата', 'woocommerce');
    $fields['billing']['billing_company']['class'] = array('form-row-wide', 'hide-company-field');
    $fields['billing']['billing_company']['priority'] = 53;
    
    // EIK field
    $fields['billing']['billing_eik'] = array(
        'type'        => 'text',
        'label'       => __('ЕИК', 'woocommerce'),
        'placeholder' => __('Въведете ЕИК номер', 'woocommerce'),
        'required'    => false,
        'class'       => array('form-row-wide', 'hide-company-field'),
        'priority'    => 54
    );
    
    // VAT number
    $fields['billing']['billing_vat_number'] = array(
        'type'        => 'text',
        'label'       => __('ДДС Номер', 'woocommerce'),
        'placeholder' => __('Въведете ДДС номер', 'woocommerce'),
        'required'    => false,
        'class'       => array('form-row-wide', 'hide-company-field'),
        'priority'    => 55
    );
    
    // MOL field
    $fields['billing']['billing_mol'] = array(
        'type'        => 'text',
        'label'       => __('МОЛ', 'woocommerce'),
        'placeholder' => __('Въведете име на МОЛ', 'woocommerce'),
        'required'    => false,
        'class'       => array('form-row-wide', 'hide-company-field'),
        'priority'    => 56
    );
    
    // Company address
    $fields['billing']['billing_company_address'] = array(
        'type'        => 'textarea',
        'label'       => __('Адрес на фирмата', 'woocommerce'),
        'placeholder' => __('Въведете адрес на фирмата', 'woocommerce'),
        'required'    => false,
        'class'       => array('form-row-wide', 'hide-company-field'),
        'priority'    => 57
    );

    return $fields;
}

// Add invoice checkbox and modify fields
add_filter('woocommerce_checkout_fields', 'add_invoice_checkbox_and_modify_company_fields');
function add_invoice_checkbox_and_modify_company_fields($fields) {
    // Add invoice checkbox after phone field
    $fields['billing']['billing_invoice'] = array(
        'type'        => 'checkbox',
        'label'       => __('Искам фактура', 'woocommerce'),
        'required'    => false,
        'class'       => array('form-row-wide'),
        'priority'    => 51, // Set priority after phone (which is 50)
        'clear'       => true
    );

    return $fields;
}

// Save custom fields to order meta
add_action('woocommerce_checkout_create_order', 'save_custom_company_checkout_fields', 10, 2);
function save_custom_company_checkout_fields($order, $data) {
    $fields_to_save = array(
        'billing_invoice',
        'billing_entity_type',
        'billing_eik',
        'billing_vat_number',
        'billing_mol',
        'billing_company_address'
    );

    foreach ($fields_to_save as $field) {
        if (!empty($_POST[$field])) {
            $order->update_meta_data('_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}

// Display custom fields in admin order details
add_action('woocommerce_admin_order_data_after_billing_address', 'display_custom_company_fields_in_admin', 10, 1);
function display_custom_company_fields_in_admin($order) {
    $fields = array(
        'billing_entity_type' => __('Тип лице', 'woocommerce'),
        'billing_eik' => __('ЕИК', 'woocommerce'),
        'billing_vat_number' => __('ДДС Номер', 'woocommerce'),
        'billing_mol' => __('МОЛ', 'woocommerce'),
        'billing_company_address' => __('Адрес на фирмата', 'woocommerce')
    );

    foreach ($fields as $field_key => $label) {
        $value = $order->get_meta('_' . $field_key);
        if ($value) {
            if ($field_key === 'billing_entity_type') {
                $value = $value === 'juridical' ? 'Юридическо лице' : 'Физическо лице';
            }
            echo '<p><strong>' . $label . ':</strong> ' . esc_html($value) . '</p>';
        }
    }
}

// Add CSS and JavaScript
add_action('wp_footer', 'add_company_fields_scripts');
function add_company_fields_scripts() {
    if (!is_checkout()) return;
    ?>
    <style>
        .hide-company-field {
            display: none;
        }

        /* Style for radio buttons */
        .woocommerce-input-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Style for radio buttons */
        .woocommerce-input-wrapper input[type="radio"] {
            margin-right: 8px;
        }
        .woocommerce-input-wrapper label {
            margin-right: 15px;
        }
    </style>
    <script>
    jQuery(function($){
        function toggleCompanyFields() {
            var isChecked = $('#billing_invoice').is(':checked');
            $('.hide-company-field').toggle(isChecked);
        }

        // Initial state
        toggleCompanyFields();

        // Handle checkbox changes
        $(document).on('change', '#billing_invoice', function() {
            toggleCompanyFields();
        });

        // Handle form updates
        $(document.body).on('updated_checkout', function() {
            toggleCompanyFields();
        });
    });
    </script>
    <?php
}

// Add custom checkbox field to checkout
add_action('woocommerce_review_order_before_submit', 'add_custom_checkout_field');
function add_custom_checkout_field($checkout) {
    woocommerce_form_field('no_contact_needed', array(
        'type' => 'checkbox',
        'class' => array('form-row-wide'),
        'label' => 'Потвръждавам поръчката, не желая потвръждение по телефона.',
        'required' => false,
    ));
}

// Save checkbox value to order
add_action('woocommerce_checkout_create_order', 'save_custom_checkout_field');
function save_custom_checkout_field($order) {
    if (!empty($_POST['no_contact_needed'])) {
        $order->update_meta_data('no_contact_needed', 'yes');
    } else {
        $order->update_meta_data('no_contact_needed', 'no');
    }
}

// Display the field value on admin order page
add_action('woocommerce_admin_order_data_after_billing_address', 'display_custom_field_in_admin');
function display_custom_field_in_admin($order) {
    $no_contact = $order->get_meta('no_contact_needed');
    if ($no_contact) {
        echo '<p><strong>Не е нужен контакт:</strong> ' . 
             ($no_contact === 'yes' ? 'Да' : 'Не') . '</p>';
    }
}

// Add the field to order emails
add_action('woocommerce_email_after_order_table', 'add_custom_field_to_emails', 10, 4);
function add_custom_field_to_emails($order, $sent_to_admin, $plain_text, $email) {
    $no_contact = $order->get_meta('no_contact_needed');
    if ($no_contact) {
        if ($plain_text) {
            echo "\nНе е нужен контакт: " . ($no_contact === 'yes' ? 'Да' : 'Не') . "\n";
        } else {
            echo '<p><strong>Не е нужен контакт:</strong> ' . 
                 ($no_contact === 'yes' ? 'Да' : 'Не') . '</p>';
        }
    }
}