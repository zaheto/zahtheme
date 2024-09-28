@php
  $image_about = get_field('image_about');
  $heading_about = get_field('heading_about');
  $text_about = get_field('text_about');
@endphp


<section class="about-section" >
  <div class="container">
    <div class="flex flex-col items-center">
      @if(!empty($image_about))
      <div class="about-image">
        <img src=" {{ $image_about['url'] }}" alt="">
      </div>
      @endif

      <div class="content">
        <h2 class="text-black text-h2 mb-4">{{ $heading_about }}</h2>
        {!! $text_about  !!}
      </div>
    </div>
  </div>
</section>
