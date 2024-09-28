@if(!is_page('checkout'))
<div class="page-header">
  <h1 class="text-32 font-bold mb-4">{!! $title !!}</h1>
</div>
@else

<div class="container py-4 lg:py-8 flex justify-between items-center content-center">
  <a class="logo mr-0 lg:mr-4 block lg:flex-auto w-full max-w-[140px] max-h-auto" href="{{ get_site_url('/') }}">
    @if(get_field('logo_checkout', 'options'))
    <img src="{{ get_field('logo_checkout', 'options') }}" class=" w-full  block " alt="{{ get_bloginfo('name', 'display') }}">
    @endif
  </a>
  <h1 class="hidden lg:block font-semibold text-18 uppercase m-0 p-0 text-black/80">{!! $title !!}</h1>
</div>

@endif

