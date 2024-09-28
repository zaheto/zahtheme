<div class="container">
  <div class="flex align-center content-center justify-between py-14">
    <a href="{{ home_url('/') }}">
        @if(get_field('logo_footer', 'options'))
        <img src="{{ get_field('logo_footer', 'options') }}" alt="{{ get_bloginfo('name', 'display') }}">
        @endif
    </a>

     @if(get_field('social_links', 'option'))
    <ul class="flex items-center content-end gap-4">
        @foreach(get_field('social_links', 'option') as $item)
        <li><a href="{{ $item['link']['url'] }}" target="{{ $item['link']['target'] }}" class="mx-1 flex"><img src="{{ $item['icon'] }}" alt=""></a></li>
        @endforeach
    </ul>
    @endif
  </div>

  <div class="flex flex-col lg:flex-row mb-6 md:mb-14 w-full justify-between gap-10">
    @php
      dynamic_sidebar('sidebar-footer');
    @endphp
  </div>

  @if(get_field('footer_copy', 'options'))
  <div class="footer-copy">
    <p>{{ get_field('footer_copy', 'options') }} </p>
  </div>
  @endif
</div>
