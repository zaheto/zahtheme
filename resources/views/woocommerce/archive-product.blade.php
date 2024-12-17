{{--
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */
--}}

@extends('layouts.app')

@section('content')
  @php
    do_action('get_header', 'shop');
    do_action('woocommerce_before_main_content');
  @endphp

  <main id="primary-content" class="main">
   
      {{-- Breadcrumb (added via hook) --}}
      
      {{-- Products Header Section --}}
      <header class="woocommerce-products-header ">
        @if (apply_filters('woocommerce_show_page_title', true))
          <h1 class="woocommerce-products-header__title page-title text-24 lg:text-40 text-black text-left font-bold font-Inter">{!! woocommerce_page_title(false) !!}</h1>
        @endif

        @php
          do_action('woocommerce_archive_description')
        @endphp

        {{-- Archive Product Template - Subcategories Section --}}
        @if (is_product_category())
        @php
          $current_category = get_queried_object();
          $args = array(
              'taxonomy' => 'product_cat',
              'parent' => $current_category->term_id,
              'hide_empty' => false,
          );
          $subcategories = get_terms($args);
          $no_thumbnail_url = get_template_directory_uri() . '/resources/images/no-image.svg';
        @endphp
      
        @if (!empty($subcategories))
          <div class="relative w-full mb-2  md:mb-8">
            {{-- Important: Remove static-subcategories class when there are more than 7 items --}}
            <div class="subcategories-slider">
              <div class="swiper-wrapper">
                @foreach ($subcategories as $subcategory)
                  @php
                    $category_link = get_term_link($subcategory->term_id, 'product_cat');
                    $thumbnail_id = get_term_meta($subcategory->term_id, 'thumbnail_id', true);
                    $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : $no_thumbnail_url;
                  @endphp
                  <div class="swiper-slide w-full">
                    <a href="{{ esc_url($category_link) }}" class="block w-full">
                      @if ($image_url)
                        <img src="{{ esc_url($image_url) }}" 
                             alt="{{ esc_attr($subcategory->name) }}"
                             class="w-full h-auto object-cover rounded-lg">
                      @endif
                      <h3 class="mt-0 md:mt-2 text-center text-sm md:text-base font-medium">
                        {{ esc_html($subcategory->name) }}
                      </h3>
                    </a>
                  </div>
                @endforeach
              </div>
      
              {{-- Navigation Buttons --}}
              <div class="subcategories-prev absolute -left-2 top-[82px] md:top-[88px] z-10 w-14 h-14 bg-white/80 hover:bg-white rounded-md shadow-lg flex items-center justify-center focus:outline-none cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
              </div>
      
              <div class="subcategories-next absolute -right-2 top-[82px] md:top-[88px] z-10 w-14 h-14 bg-white/80 hover:bg-white rounded-md shadow-lg flex items-center justify-center focus:outline-none cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </div>
            </div>
          </div>
        @endif
      @endif
      </header>

      {{-- Shop Section --}}
      <div id="shop-section">
        {{-- Filters/Ordering Top Bar --}}
        @if (woocommerce_product_loop())
          @php
            do_action('woocommerce_before_shop_loop');
          @endphp
        @endif

        {{-- Shop Layout --}}
        <div class="shop-layout">
          {{-- Sidebar/Filters --}}
          <aside id="shop-sidebar" class="left shadow-lg lg:shadow-none transition-all duration-150">
            <h2 class="hidden text-18 text-black font-bold font-Inter pb-2 mb-2 border-b border-black/10">{{ __('Filters','zah') }}</h2>
            @php
              dynamic_sidebar('sidebar-shop');
            @endphp
          </aside>

          {{-- Products Area --}}
          <div id="shop-products" class="content-area">
            @if (woocommerce_product_loop())
              {{-- Products Loop --}}
              @php
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
          </div>
        </div>
      </div>

  </main>

  @php
    do_action('woocommerce_after_main_content');
    do_action('get_footer', 'shop');
  @endphp
@endsection