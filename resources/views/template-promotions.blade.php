{{--
  Template Name: Promotional products
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) 
    @php
    the_post();
    @endphp

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">{{ get_the_title() }}</h1>

        @php
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 12,
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => '_sale_price',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'NUMERIC'
                ),
                array(
                    'key' => '_sale_price',
                    'value' => '',
                    'compare' => '!=',
                )
            ),
            'paged' => get_query_var('paged') ? get_query_var('paged') : 1, // Add this line for pagination
        );
        
        $sale_products = new WP_Query($args);
        @endphp

        @if($sale_products->have_posts())
            <ul class="products columns-4">
                @while($sale_products->have_posts())
                    @php
                    $sale_products->the_post();
                    @endphp
                    @include('woocommerce.content-product')
                @endwhile
            </ul>

            {{-- Include WooCommerce pagination --}}
            @php
            wc_get_template('loop/pagination.php', array(
                'total'   => $sale_products->max_num_pages,
                'current' => max(1, get_query_var('paged')),
                'base'    => esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false)))),
                'format'  => '',
            ));
            @endphp

            @php
            wp_reset_postdata();
            @endphp
        @endif
    </div>
  @endwhile
@endsection