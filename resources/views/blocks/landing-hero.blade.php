@php
global $product;

// If the global product object is not available, get it using the product ID
if (!$product) {
    $product_id = get_the_ID();
    $product = wc_get_product($product_id);
}

// Additional check to ensure $product is a valid product object
if ( ! $product instanceof WC_Product ) {
    echo '<p>Invalid product.</p>';
    return; // Exit if not a valid product
}

  $sections_background = $block_data['sections_background'];
  $image_landing_hero = $block_data['image_landing_hero'];
  $image_mobile_landing_hero = $block_data['image_mobile_landing_hero'];
  $text_landing_hero = $block_data['text_landing_hero'];
  $subheading_landing_hero = $block_data['subheading_landing_hero'];
  $heading_landing_hero = $block_data['heading_landing_hero'];
  $is_dark = $block_data['is_dark'];

@endphp

<section class="landing-header">
  <div class="container">
      @if(get_field('footer_email', 'options'))
      <div class="header-corners flex items-center">
        <a href="mailto:{{ get_field('footer_email', 'options') }}"><x-iconsax-lin-sms class="text-main mr-2 w-[32px] h-[32px]" /></a>
        <div class="flex flex-col text-12 text-black/60 font-medium whitespace-nowrap">

          {{-- {{ __('Email address','zah') }} --}}

            <a href="mailto:{{ get_field('footer_email', 'options') }}" class="hidden lg:flex text-black text-15 font-semibold whitespace-nowrap">
                {{ get_field('footer_email', 'options') }}
            </a>

        </div>
      </div>
    @endif

    @if(get_field('logo_checkout', 'options'))
      <img src="{{ get_field('logo_checkout', 'options') }}"  alt="{{ get_bloginfo('name', 'display') }}">
    @endif

    @if(get_field('footer_phone', 'options'))
      <div class="header-corners right flex items-center">

        <div class="flex flex-col text-12 text-black/60 font-medium whitespace-nowrap">
          {{-- {{ __('Call us now','zah') }} --}}
            <a href="callto:{{ get_field('footer_phone', 'options') }}" class="hidden lg:flex text-black text-15 font-semibold whitespace-nowrap">
                {{ get_field('footer_phone', 'options') }}
            </a>
        </div>
        <a href="callto:{{ get_field('footer_phone', 'options') }}"><x-iconsax-lin-call-calling class="text-main ml-2 w-[32px] h-[32px]" /></a>
      </div>
    @endif
  </div>
</section>

<section id="buy-scroll" class="buy-scroll">
  <div class="container">
    <span class="heading">{{ $heading_landing_hero }} </span>
    <div class="right">
      <ul>
        <li><a href="#overview">{{ __('Information', 'zah') }}</a></li>
        <li><a href="#video">{{ __('Video', 'zah') }}</a></li>
        <li><a href="#gallery">{{ __('Gallery', 'zah') }}</a></li>
        <li><a href="#reviews">{{ __('Reviews', 'zah') }}</a></li>
        <li><a href="#box">{{ __('In the box', 'zah') }}</a></li>
      </ul>
      <a href="#buy-bottom" class="btn btn-green btn-small"> {{ __('BUY NOW', 'zah') }} </a>
    </div>
  </div>
</section>





<section class="landing-hero-section {{ $is_dark ? ' is-dark' : '' }}" @if(is_array($sections_background) && isset($sections_background['url']))style="background: url({{ $sections_background['url'] }}) no-repeat center center; background-size: cover;"@endif >

  <div class="container">
    <div class="flex flex-col items-center">

      <div class="content">
        <h3>{{ $subheading_landing_hero }}</h3>
        <h2>{{ $heading_landing_hero }} </h2>
        <div class="description ">{!! $text_landing_hero  !!}</div>
        @if(!empty($image_landing_hero))
          <img src="{{ $image_landing_hero['url'] }}" class="hero-desktop " alt="">
        @endif

        @if(!empty($image_mobile_landing_hero))
          <img src="{{ $image_mobile_landing_hero['url'] }}" class="hero-mobile " alt="">
        @endif


        <div class="bottom-cta">

          @if($product->is_type('variable'))

            @php
                $prices = $product->get_variation_prices(true);
            @endphp

            @if(!empty($prices['price']))
                @php
                    $min_price = current($prices['price']);
                    $max_price = end($prices['price']);
                @endphp

                <div class="price-from">{{ __('Prices from:', 'zah') }} <p class="price"> {!! wc_price($min_price) !!}</div>
            @endif

          @else
          <div class="price-from">{{ __('Prices from:', 'zah') }} <p class="price"> {!! wc_price($product->get_price()) !!}</p></div>
          @endif

          <a href="#buy-bottom" class="btn btn-green"> <x-iconsax-lin-bag-happy class="text-white w-[28px]  h-[28px] transition-all duration-200 scale-100 hover:scale-95 transform" />  {{ __('BUY NOW', 'zah') }} </a>
        </div>
      </div>

    </div>
  </div>
</section>

<section class="features-boxes">
  <div class="container">
    <div class="swiper features-landing">
      <div class="swiper-wrapper">

        <div class="swiper-slide">
          <div class="box">
            <x-iconsax-lin-shield-tick class="text-green w-[28px] md:w-[40px] min-w-[28px] md:min-w-[40px] h-[40px] transition-all duration-200 scale-100 hover:scale-95 transform" />
            <p>{{ __('Landing Feature 1', 'zah') }}</p>
          </div>
        </div>

        <div class="swiper-slide">
          <div class="box">
            <x-iconsax-lin-empty-wallet-tick class="text-green w-[28px] md:w-[40px] min-w-[28px] md:min-w-[40px] h-[40px] transition-all duration-200 scale-100 hover:scale-95 transform" />
            <p>{{ __('Landing Feature 2', 'zah') }}</p>
          </div>
        </div>

        <div class="swiper-slide">
          <div class="box">
            <x-iconsax-bro-truck-fast class="text-green w-[28px] md:w-[40px] min-w-[28px] md:min-w-[40px] h-[40px] transition-all duration-200 scale-100 hover:scale-95 transform" />
            <p>{{ __('Landing Feature 3', 'zah') }}</p>
          </div>
        </div>

        <div class="swiper-slide">
          <div class="box">
            <x-iconsax-lin-box-time class="text-green w-[28px] md:w-[40px] min-w-[28px] md:min-w-[40px] h-[40px] transition-all duration-200 scale-100 hover:scale-95 transform" />
            <p>{{ __('Landing Feature 4', 'zah') }}</p>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
