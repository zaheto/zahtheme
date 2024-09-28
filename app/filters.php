<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

//Ajax Custom Fragments
// add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
//     $fragments['cart-custom-blade-html'] = \Roots\view('woocommerce.cart.cart-custom')->render();
//     $fragments['cart-totals-custom-blade-html'] = \Roots\view('woocommerce.cart.cart-totals-custom')->render();
//     return $fragments;
// }, 10, 1);

