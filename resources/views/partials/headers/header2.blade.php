<div class="inner-header ">
  <div class="search-box">
    <?php aws_get_search_form( true ); ?>
</div><!-- search-box -->
        <div class="header-left">
          <a href="javascript:;" id="desktop-menu" class="icon-wrap hidden lg:flex"><x-iconsax-lin-menu class="icon"/></a>
          <a href="javascript:;" id="mobile-menu" class="icon-wrap flex items-center content-center lg:hidden absolute top-1/2 -translate-y-1/2 left-1 z-50"><x-iconsax-lin-menu class="icon "/></a>
          <a href="javascript:;" id="search-menu" class="icon-wrap "><x-iconsax-lin-search-normal-1 class="icon"/></a>
        </div>
        <!-- END OF HEADER LEFT -->

        <div class="header-center">
          <a class="logo min-w-[102px] max-w-[102px] lg:min-w-[138px] lg:max-w-[138px]" href="{{ get_site_url('/') }}">
            @if(get_field('logo_header', 'options'))
            <img src="{{ get_field('logo_header', 'options') }}" alt="{{ get_bloginfo('name', 'display') }}">
            @endif
          </a>
        </div>
        <!-- END OF HEADER center -->

        <div class="header-right">
              <ul class="header--my-account">
                @if(is_user_logged_in())
                <li>
                  <a href="{{ get_permalink( wc_get_page_id( 'myaccount' ) ) }}" class="icon-wrap">
                    <x-iconsax-lin-user-octagon class="icon" />
                  </a>
                </li>
                @else
                <li>
                  <a href="{{ home_url('/login') }}"  class="icon-wrap">
                    <x-iconsax-lin-user-octagon class="icon" />
                  </a>
                </li>
                @endif
              </ul><!-- account -->

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




