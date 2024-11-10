@php
defined('ABSPATH') || exit;

global $product;
$attachment_ids = $product->get_gallery_image_ids();
$post_thumbnail_id = $product->get_image_id();
$video_id = get_field('archive_video', $product->get_id());
@endphp

<section class="woocommerce-product-gallery">
  {!! zah_get_product_badges($product) !!}
  
  @if($video_id)
    <div class="gallery-video-btn-wrapper">
        <button class="product-video-btn" data-video="{{ esc_attr($video_id) }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polygon points="10 8 16 12 10 16 10 8"></polygon>
            </svg>
            {{ __('Video', 'zah') }}
        </button>
    </div>
  @endif
  
  <!-- Main Image Slider -->
  <div class="swiper product-main-images">
    <div class="swiper-wrapper">
      <!-- Main product image -->
      <div class="swiper-slide">
        <div class="swiper-zoom-container">
          {!! wp_get_attachment_image($post_thumbnail_id, 'full', false, [
              'class' => 'product-main-image',
              'data-zoom' => wp_get_attachment_image_url($post_thumbnail_id, 'full')
          ]) !!}
        </div>
      </div>

      <!-- Gallery images -->
      @foreach($attachment_ids as $attachment_id)
        <div class="swiper-slide">
          <div class="swiper-zoom-container">
            {!! wp_get_attachment_image($attachment_id, 'full', false, [
                'class' => 'product-gallery-image',
                'data-zoom' => wp_get_attachment_image_url($attachment_id, 'full')
            ]) !!}
          </div>
        </div>
      @endforeach
    </div>
    
    <div class="swiper-button-next main-next"></div>
    <div class="swiper-button-prev main-prev"></div>
  </div>

  <!-- Thumbnails Slider -->
  <div class="swiper product-thumbnails">
    <div class="swiper-wrapper">
      <!-- Main product thumbnail -->
      <div class="swiper-slide">
        {!! wp_get_attachment_image($post_thumbnail_id, 'thumbnail') !!}
      </div>
      
      <!-- Gallery thumbnails -->
      @foreach($attachment_ids as $attachment_id)
        <div class="swiper-slide">
          {!! wp_get_attachment_image($attachment_id, 'thumbnail') !!}
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- Fullscreen Modal -->
<div id="gallery-modal" class="gallery-modal fixed inset-0 z-[9999] hidden">
  <div class="modal-overlay absolute inset-0 bg-black/90"></div>
  <div class="modal-content relative z-[10000] w-full h-full flex items-center justify-center">
    <button class="modal-close absolute top-4 right-4 text-white text-4xl z-[10001]">&times;</button>
    <div class="modal-swiper w-full h-full px-4">
      <div class="swiper-wrapper"></div>
      <div class="swiper-button-next modal-next"></div>
      <div class="swiper-button-prev modal-prev"></div>
    </div>
  </div>
</div>

<!-- Video Modal -->
<div id="videoModal" class="video-modal fixed inset-0 z-[9999] hidden">
  <div class="modal-overlay absolute inset-0 bg-black/90"></div>
  <div class="modal-content relative z-[10000] w-full h-full flex items-center justify-center">
    <button class="modal-close absolute top-4 right-4 text-white text-4xl z-[10001]">&times;</button>
    <div class="video-container w-full max-w-4xl mx-auto px-4">
      <div class="relative pb-[56.25%] h-0">
        <iframe id="youtubeFrame" class="absolute top-0 left-0 w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      </div>
    </div>
  </div>
</div>