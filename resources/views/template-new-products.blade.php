{{--
  Template Name: New products
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
            'orderby' => 'date',
            'order' => 'DESC',
        );
        
        $new_products = new WP_Query($args);
        @endphp

        @if($new_products->have_posts())
            <ul class="products columns-4 ">
                @while($new_products->have_posts()) 
                    @php($new_products->the_post())
                    @include('woocommerce.content-product')
                @endwhile
            </ul>
           
            @php(wp_reset_postdata())
        @endif

        
    </div>
  @endwhile
@endsection