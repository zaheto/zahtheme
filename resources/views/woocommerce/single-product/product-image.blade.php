@php
defined('ABSPATH') || exit;

global $product;
$attachment_ids = $product->get_gallery_image_ids();
$post_thumbnail_id = $product->get_image_id();
$video_id = get_field('archive_video', $product->get_id());
@endphp

<section class="woocommerce-product-gallery">
  {!! zah_get_product_badges($product) !!}
  
  
  
  <!-- Main Image Slider -->
  <div class="swiper product-main-images">
    <div class="swiper-wrapper">
      @if($video_id)
        <div class="gallery-video-btn-wrapper">
          <button class="product-video-btn" data-video="{{ esc_attr($video_id) }}">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M0.666748 9.99993V5.84659C0.666748 0.689928 4.31841 -1.42174 8.78675 1.1566L12.3917 3.23326L15.9967 5.30993C20.4651 7.88826 20.4651 12.1116 15.9967 14.6899L12.3917 16.7666L8.78675 18.8433C4.31841 21.4216 0.666748 19.3099 0.666748 14.1533V9.99993Z" fill="white"/>
              </svg>
              
            {{ __('Video', 'zah') }}
        </button>
        </div>
      @endif

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