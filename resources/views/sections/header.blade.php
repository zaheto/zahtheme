<a href="tel:{{ get_field('footer_phone', 'options') }}" class="scroll-phone-number"> <x-iconsax-lin-call-calling class="icon" /></a>
@php
do_action( 'zah_before_site' );
do_action( 'zah_before_header' );
@endphp
<header id="header" class="header header-white header-2">
  @if(!is_page('checkout'))
    @php do_action( 'zah_announce_bar' ); @endphp

    @if(get_field('announce_bar_header', 'option'))
      <section class="announce-bar2">
        <div class="container">
          <div class="accounce-bar2-inner">
            @if(get_field('social_links', 'option'))
          <ul class="annonce-social flex items-center content-end gap-4">
              @foreach(get_field('social_links', 'option') as $item)
              <li><a href="{{ $item['link']['url'] }}" target="{{ $item['link']['target'] }}" class="mx-1 flex"><img src="{{ $item['icon'] }}" alt=""></a></li>
              @endforeach
          </ul>
          @endif
          <ul class="announce-text">
              @foreach(get_field('announce_bar_header', 'option') as $item)
              <li>{!! $item['annonce_text'] !!}</li>
              @endforeach
          </ul>
          @if (has_nav_menu('announce_bar_menu'))

            <nav class="navbar">
              {!!
                wp_nav_menu(array(
                    'theme_location'    => 'announce_bar_menu',
                    'container'         => 'div',
                    'depth'				      => "3",
                    'menu_class'        => 'nav announce-bar-menu',
                ));
              !!}
            </nav>

            @endif
          </div>
        </div>
      </section>
    @endif

    @include('partials.headers.header2')

    @endif
  </header>


<div class="mobile-nav">
  <div class="icon-wrap">
      <a id="close-cart-popup"><x-iconsax-lin-add class="icon" /></a>
  </div>
  <div class="mobile-nav--inner">
  @if (has_nav_menu('main_menu'))

    <nav class="navbar">
      {!!
        wp_nav_menu(array(
            'theme_location'    => 'main_menu',
            'container'         => 'div',
            'depth'				      => "3",
            'menu_class'        => 'nav flex-col flex header-main-nav',
        ));
      !!}
    </nav>

    @endif

    @if (has_nav_menu('second_menu_right'))

    <nav class="navbar">
      {!!
        wp_nav_menu(array(
            'theme_location'    => 'second_menu_right',
            'container'         => 'div',
            'depth'				      => "3",
            'menu_class'        => 'nav flex flex-col header-right-nav',
        ));
      !!}
    </nav>

    @endif
  </div>
  <ul class="header--my-account hidden">
    @if(is_user_logged_in())
    <li>
      <a href="{{ get_permalink( wc_get_page_id( 'myaccount' ) ) }}">
        <x-iconsax-lin-user-octagon class="icon" />
        {{ __('Your account', 'zah') }}
      </a>
    </li>
    @else
    <li>
      <a href="{{ home_url('/login') }}">
      <x-iconsax-lin-user-octagon class="icon" />
      {{ __('Your account', 'zah') }}
      </a>
    </li>
    @endif
  </ul><!-- account -->
</div>

@if(!is_page('cart'))
{{-- <section class="cart-popup">
  <div class="cart-popup--inner">
    <div class="cart-popup-info">
      @include('woocommerce.cart.cart-custom')
    </div>
  </div>
</section>

<section id="quick-view-modal" class="quick-view-modal">
  @include('woocommerce.cart.quick-view-modal')
</section> --}}
@endif
