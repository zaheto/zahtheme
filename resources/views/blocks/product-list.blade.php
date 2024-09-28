@php
    $heading_color = $block_data['heading_color'] ?? '';
    $section_color = $block_data['section_color'] ?? '';
    $is_slider = $block_data['is_slider'] ?? false;
    $section_heading_product_list = $block_data['section_heading_product_list'] ?? '';
    $product_list_builder = $block_data['product_list_builder'] ?? [];
@endphp

@if($product_list_builder)
  <section class="product-list-builder px-4 lg:px-6 @if($is_slider)  pt-8 lg:pt-16  @else   py-8 lg:py-16 @endif" style="background-color: {{ $section_color }};">
    <div class="container flex flex-col items-center gap-6">

      <h2 class="title"><span style="background-color: {{ $heading_color }}"></span>{{ $section_heading_product_list }}</h2>

      @if($is_slider)
      <div class="swiper swiper-container more-products-slider" data-slider="true">
        <div class="swiper-products swiper-wrapper">
      @else
        <div class="products columns-6">
      @endif
          @foreach($product_list_builder as $product_id)
              @if($is_slider)<div class="swiper-slide">@endif

              @php
                  global $post, $product;
                  $post = get_post($product_id);
                  $product = wc_get_product($product_id);
                  setup_postdata($post);
              @endphp
              @include('woocommerce.content-product')
              @php
                  wp_reset_postdata();
              @endphp

              @if($is_slider)</div>@endif
          @endforeach
        </div> <!-- Close the swiper-wrapper or products div -->
      @if($is_slider)
            <div class="mt-6 gap-2 flex w-full h-6 relative items-center content-center justify-center">
              <div class="small-swiper-button-prev"><x-iconsax-lin-arrow-left class="text-black w-[24px] h-[24px] hover:text-main transition-all duration-200 scale-100 hover:scale-95 transform " /></div>
              <div class="swiper-pagination"></div>
              <div class="small-swiper-button-next"><x-iconsax-lin-arrow-right class="text-black w-[24px] h-[24px] hover:text-main transition-all duration-200 scale-100 hover:scale-95 transform " /></div>
            </div>
          </div> <!-- Close the swiper-container div -->
      @else
          </div> <!-- Close the products div -->
      @endif
    </div>
  </section>
@endif


