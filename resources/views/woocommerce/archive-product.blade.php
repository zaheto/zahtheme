{{--
The Template for displaying product archives, including the main shop page which is a post type archive

This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.

HOWEVER, on occasion WooCommerce will need to update template files and you
(the theme developer) will need to copy the new files to your theme to
maintain compatibility. We try to do this as little as possible, but it does
happen. When this occurs the version of the template file will be bumped and
the readme will list any important changes.

@see https://docs.woocommerce.com/document/template-structure/
@package WooCommerce/Templates
@version 3.4.0
--}}

@extends('layouts.app')

<header class="woocommerce-products-header bg-[#D0F1F1] flex flex-col items-center content-center justify-center py-6 md:py-12 px-2">
  @if (apply_filters('woocommerce_show_page_title', true))
    <h1 class="woocommerce-products-header__title page-title text-24 lg:text-32 text-black text-center font-bold font-Inter">{!! woocommerce_page_title(false) !!}</h1>
  @endif

  @php
    do_action('woocommerce_archive_description')
  @endphp

@if (is_product_category())
@php
    $current_category = get_queried_object();
    $args = array(
        'taxonomy' => 'product_cat',
        'parent' => $current_category->term_id,
        'hide_empty' => false,
    );
    $subcategories = get_terms($args);
    $no_thumbnail_url = get_template_directory_uri() . '/resources/images/no-image.svg'; // Path to your default no-thumbnail image
@endphp

@if (!empty($subcategories))
    <div class="swiper subcategories-slider">
      <div class="swiper-wrapper">
        @foreach ($subcategories as $subcategory)
            @php
                $category_link = get_term_link($subcategory->term_id, 'product_cat');
                $thumbnail_id = get_term_meta($subcategory->term_id, 'thumbnail_id', true);
                $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : $no_thumbnail_url;
            @endphp
            <div class="swiper-slide">
                <a href="{{ esc_url($category_link) }}">
                    @if ($image_url)
                        <img src="{{ esc_url($image_url) }}" alt="{{ esc_attr($subcategory->name) }}">
                    @endif
                    <h3>{{ esc_html($subcategory->name) }}</h3>
                </a>
            </div>
        @endforeach
      </div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>
@endif
@endif

</header>

@section('content')
<aside id="aside" class="left shadow-lg lg:shadow-none transition-all duration-150">
  <h2 class="hidden text-18 text-black font-bold font-Inter pb-2 mb-2 border-b border-black/10 "> {{ __('Filters','zah') }}</h2>
  @php
    dynamic_sidebar('sidebar-shop');
  @endphp
</aside>
  @php
    do_action('get_header', 'shop');
    do_action('woocommerce_before_main_content');
  @endphp



  @if (woocommerce_product_loop())
    @php
      do_action('woocommerce_before_shop_loop');
      woocommerce_product_loop_start();
    @endphp

    @if (wc_get_loop_prop('total'))
      @while (have_posts())
        @php
          the_post();
          do_action('woocommerce_shop_loop');
          wc_get_template_part('content', 'product');
        @endphp
      @endwhile
    @endif

    @php
      woocommerce_product_loop_end();
      do_action('woocommerce_after_shop_loop');
    @endphp
  @else
    @php
      do_action('woocommerce_no_products_found')
    @endphp
  @endif



  @php
    do_action('woocommerce_after_main_content');

    do_action('get_footer', 'shop');
  @endphp
@endsection
