<?php

/**
 * Theme setup.
 */

namespace App;

use function Roots\bundle;

/**
 * Register the theme assets.
 *
 * @return void
 */
add_action('wp_enqueue_scripts', function () {
    bundle('app')->enqueue();
}, 100);

/**
 * Register the theme assets with the block editor.
 *
 * @return void
 */
add_action('enqueue_block_editor_assets', function () {
    bundle('editor')->enqueue();
}, 100);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {

    load_theme_textdomain('zah', get_template_directory() . '/resources/lang');


    /**
     * Enable features from the Soil plugin if activated.
     *
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil', [
        'clean-up',
        'nav-walker',
        'nice-search',
        'relative-urls',
    ]);

    //add_theme_support('wc-product-gallery-zoom');
    //add_theme_support('wc-product-gallery-lightbox');
    //add_theme_support('wc-product-gallery-slider');


    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'main_menu' => __('Main navigation', 'sage'),
        'second_menu_right' => __('Header second navigation ', 'sage'),
        'announce_bar_menu' => __('Announcebar Menu ', 'sage'),
        'footer_pages1' => __('Footer Problems', 'sage'),
        'footer_pages2' => __('Footer Categories', 'sage'),
        'footer_pages5' => __('Footer Categories2', 'sage'),
        'footer_pages3' => __('Footer Pages', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

   

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Primary', 'sage'),
        'id' => 'sidebar-primary',
    ] + $config);

    register_sidebar([
        'name' => __('Shop sidebar', 'sage'),
        'id' => 'sidebar-shop',
    ] + $config);

    register_sidebar([
        'name' => __('Footer', 'sage'),
        'id' => 'sidebar-footer',
    ] + $config);
});



/**
 * Custom AJAX to apply coupon to cart
 */
// add_action('wp_ajax_apply_coupon_to_cart', function () {
//     $coupon = $_POST['coupon'];
//     if(!$coupon) {
//         return false;
//     }

//     WC()->cart->remove_coupons();
//     WC()->cart->add_discount( $coupon );
//     $notices = wc_get_notices();
//     wc_clear_notices();
//     echo json_encode($notices);
// });

// add_action('wp_ajax_nopriv_apply_coupon_to_cart', function () {
//     $coupon = $_POST['coupon'];
//     if(!$coupon) {
//         return false;
//     }

//     WC()->cart->remove_coupons();
//     WC()->cart->add_discount( $coupon );
//     $notices = wc_get_notices();
//     wc_clear_notices();
//     echo json_encode($notices);
// });

// add_action('rest_api_init', function () {
//     register_rest_route('blinds-calculator/v1', '/calculate', [
//         'methods' => 'POST',
//         'callback' => [new \App\Calculator(), 'calculate'],
//         'permission_callback' => '__return_true',
//     ]);
// });

// add_action('wp_enqueue_scripts', function () {
//     if (is_page_template('views/template-calculator.blade.php')) {
//         wp_enqueue_script('blinds-calculator', asset('scripts/calculator.js')->uri(), ['jquery'], null, true);
//     }
// });

// add_action('wp_enqueue_scripts', function () {
//     if (is_product()) {
//         wp_enqueue_script('atlas-fence-calculator', asset('scripts/atlas-fence-calculator.js')->uri(), ['jquery'], null, true);
//     }
// });

add_action('wp_enqueue_scripts', function () {
    bundle('app')->enqueue();
    
    if (is_page_template('views/template-calculator.blade.php')) {
        bundle('calculator')->enqueue();
    }
}, 100);