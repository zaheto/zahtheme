<div class="container pt-14">


  <div class="flex  flex-wrap lg:flex-nowrap flex-row mb-6 md:mb-14 w-full justify-between gap-6 md:gap-0 lg:gap-10">

    <div class="footer-company-info">
      <a href="{{ home_url('/') }}">
          @if(get_field('logo_footer', 'options'))
          <img src="{{ get_field('logo_footer', 'options') }}" alt="{{ get_bloginfo('name', 'display') }}">
          @endif
      </a>

      @if(get_field('footer_text', 'options'))
          <div>{!! get_field('footer_text', 'options') !!} </div>
      @endif

       @if(get_field('social_links', 'option'))
      <ul class="flex items-center content-end gap-4">
          @foreach(get_field('social_links', 'option') as $item)
          <li><a href="{{ $item['link']['url'] }}" target="{{ $item['link']['target'] }}" class="mx-1 flex"><img src="{{ $item['icon'] }}" alt=""></a></li>
          @endforeach
      </ul>
      @endif
    </div>
    @php
      dynamic_sidebar('sidebar-footer');
    @endphp

    <div class="subscribe-footer-right">
      <h3>{{ get_field('subscribe_title', 'options') }}</h3>
      <div class="newsletter-form--wrap">{!! get_field('klaviyo_element', 'options') !!}</div>
      <p class="text-small">{{ get_field('subscribe_text', 'options') }}</p>
    </div>
  </div>


  @if(get_field('footer_copy', 'options'))
  <div class="footer-copy">
    <p>{{ get_field('footer_copy', 'options') }} </p>
  </div>
  @endif
</div>
