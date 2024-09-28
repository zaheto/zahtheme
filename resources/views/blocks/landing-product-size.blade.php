@php
  $landings_product_size_subheading = $block_data['landings_product_size_subheading'];
  $landings_product_size_heading = $block_data['landings_product_size_heading'];
  $landings_product_size_image_desktop = $block_data['landings_product_size_image_desktop'];
  $landings_product_size_image_mobile = $block_data['landings_product_size_image_mobile'];
@endphp

<section class="landing-product-size">
  <div class="container">
    <div class="content">
        <h4 class="landing-subtitle">{{ $landings_product_size_subheading }}</h4>
        <h2 class="landing-title">{{ $landings_product_size_heading }} </h2>
        @if(is_array($landings_product_size_image_desktop) && isset($landings_product_size_image_desktop['url']))
          <img src="{{ $landings_product_size_image_desktop['url'] }}" class="desktop " alt="{{ $landings_product_size_image_desktop['alt'] }}">
        @endif

        @if(is_array($landings_product_size_image_mobile) && isset($landings_product_size_image_mobile['url']))
        <img src="{{ $landings_product_size_image_mobile['url'] }}" class="mobile " alt="{{ $landings_product_size_image_mobile['alt'] }}">
        @endif
    </div>
  </div>
</section>

