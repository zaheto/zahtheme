{{--
The Template for displaying all single products

This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.

HOWEVER, on occasion WooCommerce will need to update template files and you
(the theme developer) will need to copy the new files to your theme to
maintain compatibility. We try to do this as little as possible, but it does
happen. When this occurs the version of the template file will be bumped and
the readme will list any important changes.

@see         https://docs.woocommerce.com/document/template-structure/
@package     WooCommerce\Templates
@version     1.6.4
--}}


@extends('layouts.app')

@section('content')
    @php
        do_action('get_header', 'shop');
        do_action('woocommerce_before_main_content');
    @endphp

    @while(have_posts())
        @php
            the_post();
            do_action('woocommerce_before_single_product');

           // Move this inside the loop to ensure we have the correct post ID
           if (has_term('atlas', 'product_tag', get_the_ID())) {
            $product = wc_get_product(get_the_ID());
            $atlas_pricing = [
                'base_price' => $product->get_regular_price() ?: 0,
                'price_panels_lin_meter' => get_field('price_panels_lin_meter', get_the_ID()) ?: 0,
                'price_u_profile_left' => get_field('price_u_profile_left', get_the_ID()) ?: 0,
                'price_u_profile_right' => get_field('price_u_profile_right', get_the_ID()) ?: 0,
                'price_u_horizontal_panel' => get_field('price_u_horizontal_panel', get_the_ID()) ?: 0,
                'price_reinforcing_profile' => get_field('price_reinforcing_profile', get_the_ID()) ?: 0,
                'price_rivets' => get_field('price_rivets', get_the_ID()) ?: 0,
                'price_self_tapping_screw' => get_field('price_self_tapping_screw', get_the_ID()) ?: 0,
                'price_dowels' => get_field('price_dowels', get_the_ID()) ?: 0,
                'price_corners' => get_field('price_corners', get_the_ID()) ?: 0
            ];

            $atlas_data = [
                'pricing' => $atlas_pricing,
                'panel_height' => explode("\r\n", get_field('panel_height', get_the_ID())),
                'width_min' => get_field('width_min', get_the_ID()),
                'width_max' => get_field('width_max', get_the_ID())
            ];
            }
        @endphp

        

    @if (has_term('atlas', 'product_tag', get_the_ID()))
    <script>
        var atlas_pricing = @json($atlas_pricing);
        
        //console.log('Initial Atlas Pricing:', atlas_pricing);
    </script>
    @endif

        <div id="product-{{ get_the_ID() }}" @php wc_product_class('', get_the_ID()); @endphp>
            <div class="summary entry-summary">
   

                @php
                    wc_get_template_part('content', 'single-product');
                @endphp

        
            
            </div>
        </div>

    @endwhile

    @php
        do_action('woocommerce_after_main_content');
        do_action('get_sidebar', 'shop');
        do_action('get_footer', 'shop');
    @endphp
@endsection