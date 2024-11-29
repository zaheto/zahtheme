@php
    $phone_second = get_field('footer_phone_second', 'options');
@endphp

<div class="inner-header ">
  
        <div class="header-left">
          <a href="javascript:;" id="desktop-menu" class="icon-wrap hidden lg:flex"><x-iconsax-lin-menu class="icon"/></a>
          <a href="javascript:;" id="mobile-menu" class="icon-wrap flex items-center content-center lg:hidden absolute top-1/2 -translate-y-1/2 left-1 z-50"><x-iconsax-lin-menu class="icon "/></a>
          <div class="search-box">
              <?php aws_get_search_form( true ); ?>
          </div><!-- search-box -->
        </div>
        <!-- END OF HEADER LEFT -->

        <div class="header-center">
          <a class="logo min-w-[174px] max-w-[174px] md:min-w-[240px] md:max-w-[240px] lg:min-w-[660px] lg:max-w-[660px] flex items-center justify-center" href="{{ get_site_url('/') }}">
            @if(get_field('logo_header', 'options'))
            <img src="{{ get_field('logo_header', 'options') }}" alt="{{ get_bloginfo('name', 'display') }}">
            @endif
          </a>
        </div>
        <!-- END OF HEADER center -->

        <div class="header-right">
          @if(get_field('footer_phone', 'options'))
          <div class="header-info">
            <x-iconsax-bol-call-calling class="info-icon text-main" />
            <div class="header-info--wrap">
              

                <div class="flex items-center gap-2">
                    <a href="call:{{ get_field('footer_phone', 'options') }}">
                      {{ get_field('footer_phone', 'options') }}
                    </a>

                    @if(!empty($phone_second))
                      <a href="tel:{{ $phone_second }}">
                          {{ $phone_second }}
                      </a>
                  @endif
                </div>

            </div>
          </div>
          @endif

              @php global $woocommerce; @endphp


              @php do_action( 'zah_minicart_header' ); @endphp



        </div>
        <!-- END OF HEADER RIGHT -->
    </div><!-- container -->

    <nav class="main-nav">
      <div class="container ">
        @if (has_nav_menu('main_menu'))

        <nav class="navbar">
          {!!
            wp_nav_menu(array(
                'theme_location'    => 'main_menu',
                'container'         => 'div',
                'depth'				      => "3",
                'menu_class'        => 'nav flex -ml-4 header-main-nav',
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
                'menu_class'        => 'nav flex -mr-2 header-right-nav',
            ));
          !!}
        </nav>

        @endif

      </div>
    </nav>




