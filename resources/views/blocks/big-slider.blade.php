@if($big_slider)
  <section class="swiper slider-home">

    <div class="swiper-wrapper">
    @foreach($big_slider as $slide)
      <div class="swiper-slide">
          <a href="{{ $slide['slider_link_big_slider']['url'] }}" title="{{ $slide['slider_link_big_slider']['title'] }}">
              <img src="{{ $slide['desktop_image_big_slider']['url'] }}" alt="{{ $slide['desktop_image_big_slider']['alt'] }}" class="desktop">
              <img src="{{ $slide['mobile_image_big_slider']['url'] }}" alt="{{ $slide['mobile_image_big_slider']['alt'] }}" class="mobile">
          </a>
      </div>
    @endforeach
    </div>

    <div class="swiper-pagination"></div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>

  </section>
@endif
