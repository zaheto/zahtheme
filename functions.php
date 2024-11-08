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
    }
}
// function zah_breadcumb() {
//     // Check if breadcrumb is already displayed
//     if ( function_exists('yoast_breadcrumb') && !did_action('woocommerce_breadcrumb') ) {
//         yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
//     }
// }

//Product page form
// add_action( 'woocommerce_single_product_summary', 'zah_quick_order_form' );
// function zah_quick_order_form() {
//   echo '<div class="quick-form-product"><h5 >БЪРЗА ПОРЪЧКА С ЕДИН КЛИК</h5>';
//   echo do_shortcode( '[contact-form-7 id="269" title="Product Quick Order"]' );
//   echo '</div>';
// }

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
        error_log('Options are not an array or object: ' . print_r($options, true));
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
			error_log('WooCommerce is not activated');
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
			error_log('WooCommerce cart is not available');
			return;
		}

        $cart_subtotal = WC()->cart->get_cart_subtotal();
		$cart_contents_count = WC()->cart->get_cart_contents_count();
		// error_log('Cart Subtotal: ' . $cart_subtotal);
		// error_log('Cart Contents Count: ' . $cart_contents_count);

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

	global $post;

	?>
		<?php
		echo '<div class="woocommerce-loop-product__title"><a tabindex="0" href="' . get_the_permalink() . '" aria-label="' . get_the_title() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">' . get_the_title() . '</a></div>';
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
add_action( 'woocommerce_before_shop_loop_item_title', 'zah_change_displayed_sale_price_html', 7 );
add_action( 'woocommerce_single_product_summary', 'zah_change_displayed_sale_price_html', 10 );
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

	global $product, $price;
	$zah_sale_badge = '';


	if ( $product->is_on_sale() && ! $product->is_type( 'grouped' ) && ! $product->is_type( 'bundle' ) ) {

		if ( $product->is_type( 'variable' ) ) {
			$percentages = array();

			// Get all variation prices.
			$prices = $product->get_variation_prices();

			// Loop through variation prices.
			foreach ( $prices['price'] as $key => $price ) {
				// Only on sale variations.
				if ( $prices['regular_price'][ $key ] !== $price && $prices['regular_price'][ $key ] > 0.005) {
					// Calculate and set in the array the percentage for each variation on sale.
					$percentages[] = round( 100 - ( $prices['sale_price'][ $key ] / $prices['regular_price'][ $key ] * 100 ) );
				}
			}
			// Keep the highest value.
			if ( ! empty( $percentages ) ) {
				$percentage = max( $percentages ) . '%';
			}
		} else {
			$percentage = 0;
			$regular_price = (float) $product->get_regular_price();
			if ( $regular_price > 0.005 ) {
				$sale_price    = (float) $product->get_price();
				$percentage = round( 100 - ( $sale_price / $regular_price * 100 ), 0 ) . '%';
			}
		}

		if ( isset( $percentage ) && $percentage > 0 ) {


				$zah_sale_badge .= sprintf( __( '<span class="sale-item product-label type-rounded">-%s</span>', 'zah' ), $percentage );

		}
	}


		echo zah_safe_html( $zah_sale_badge );


}

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


// Modify the price before adding to cart
add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id) {
    if (isset($_POST['calculated_price']) && has_term('atlas', 'product_tag', $product_id)) {
        $cart_item_data['custom_price'] = floatval($_POST['calculated_price']);
    }
    return $cart_item_data;
}, 10, 2);

// Apply the custom price
add_action('woocommerce_before_calculate_totals', function($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    foreach ($cart->get_cart() as $cart_item) {
        if (isset($cart_item['custom_price'])) {
            $cart_item['data']->set_price($cart_item['custom_price']);
        }
    }
}, 10, 1);

// Add the calculated price to the add to cart form
add_action('woocommerce_before_add_to_cart_button', function() {
    if (has_term('atlas', 'product_tag')) {
        echo '<input type="hidden" name="calculated_price" id="calculated_price" value="">';
        ?>
        <script>
            jQuery(document).ready(function($) {
                $('#atlas-calculate').on('click', function() {
                    let calculatedPrice = $('.single_add_to_cart_button').attr('data-calculated-price');
                    $('#calculated_price').val(calculatedPrice);
                });
            });
        </script>
        <?php
    }
});

// Prevent add to cart if price hasn't been calculated
add_filter('woocommerce_add_to_cart_validation', function($passed, $product_id) {
    if (has_term('atlas', 'product_tag', $product_id) && empty($_POST['calculated_price'])) {
        wc_add_notice('Please use the calculator before adding to cart.', 'error');
        return false;
    }
    return $passed;
}, 10, 2);


// Hook the calculator form after product title
add_action('woocommerce_single_product_summary', function() {
    if (has_term('atlas', 'product_tag') && function_exists('get_field')) {
        $atlas_pricing = [
            'price_panels_lin_meter' => get_field('price_panels_lin_meter'),
            'price_u_profile_left' => get_field('price_u_profile_left'),
            'price_u_profile_right' => get_field('price_u_profile_right'),
            'price_u_horizontal_panel' => get_field('price_u_horizontal_panel'),
            'price_reinforcing_profile' => get_field('price_reinforcing_profile'),
            'price_rivets' => get_field('price_rivets'),
            'price_self_tapping_screw' => get_field('price_self_tapping_screw'),
            'price_dowels' => get_field('price_dowels'),
            'price_corners' => get_field('price_corners')
        ];

        $atlas_data = [
            'pricing' => $atlas_pricing,
            'panel_height' => explode("\r\n", get_field('panel_height')),
            'width_min' => get_field('width_min'),
            'width_max' => get_field('width_max')
        ];

        echo view('partials.product.atlas-calculator-form', ['atlasData' => $atlas_data])->render();
    }
}, 6); // After title (5), before price (10)

// Hook the calculator results after product meta
add_action('woocommerce_product_meta_end', function() {
    if (has_term('atlas', 'product_tag')) {
        echo view('partials.product.atlas-calculator-results')->render();
    }
});

// Enqueue the calculator script
add_action('wp_enqueue_scripts', function() {
    if (is_product() && has_term('atlas', 'product_tag')) {
        wp_enqueue_script(
            'atlas-calculator', 
            get_stylesheet_directory_uri() . '/resources/scripts/atlas-calculator.js', 
            ['jquery'], 
            '1.0.0', 
            true
        );
    }
});


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
        echo '<span class="sku_wrapper">' . esc_html__('SKU:', 'woocommerce') . ' <span class="sku">' . esc_html($product->get_sku()) . '</span></span>';
    }

    // Add stock status
    if ($product->is_in_stock()) {
        echo '<span class="stock in-stock">' . __('In stock', 'woocommerce') . '</span>';
    } else {
        echo '<span class="stock out-of-stock">' . __('Out of stock', 'woocommerce') . '</span>';
    }
    
    echo '</div>';
}