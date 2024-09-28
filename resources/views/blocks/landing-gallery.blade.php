@php
    // Get the gallery images
    $gallery_images = $block_data['landing_gallery'];
    // Check if there are images
    $has_images = !empty($gallery_images);
@endphp
@if($has_images)
<section id="gallery" class="overflow-hidden">
  <div class="swiper landing-gallery">
    <div class="swiper-wrapper">
      @foreach($gallery_images as $image)
          <div class="swiper-slide">
              <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" />
          </div>
      @endforeach
    </div>
  </div>
</section>
@endif
