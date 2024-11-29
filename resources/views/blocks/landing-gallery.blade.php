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
                        <a href="{{ $image['url'] }}" class="gallery-link" data-fancybox="gallery">
                            <img src="{{ $image['sizes']['thumbnail'] ?? $image['url'] }}" 
                                 alt="{{ $image['alt'] }}"
                                 class="gallery-thumbnail" 
                                 loading="lazy" />
                        </a>
                    </div>
                @endforeach
            </div>
            
      
        </div>
    </section>
  
@endif