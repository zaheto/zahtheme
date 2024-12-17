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
            )
        );
        
        $sale_products = new WP_Query($args);
        @endphp

        @if($sale_products->have_posts())
            <ul class="products columns-4 ">
                @while($sale_products->have_posts())
                    @php($sale_products->the_post())
                    @include('woocommerce.content-product')
                @endwhile
            </ul>
            @php(wp_reset_postdata())
        @endif
    </div>
  @endwhile
@endsection