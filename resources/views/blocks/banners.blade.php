@if($add_banner_block)
  <section class="banners-section bg-third">
    <div class="container flex flex-col md:flex-row items-center gap-5 py-10">
      @foreach($add_banner_block as $banner)
        <div class="banner-item">
          @if($banner['banner_link_and_text_block'])
            <a 
              class="overflow-hidden rounded-md relative block"
              href="{{ $banner['banner_link_and_text_block']['url'] }}" 
              title="{{ $banner['banner_link_and_text_block']['title'] }}"
              @if($banner['banner_link_and_text_block']['target'])
                target="{{ $banner['banner_link_and_text_block']['target'] }}"
              @endif
            >
              @if($banner['banner_image_block'])
                <img 
                  src="{{ $banner['banner_image_block']['url'] }}" 
                  alt="{{ $banner['banner_image_block']['alt'] }}"
                  @if(isset($banner['banner_image_block']['width']))
                    width="{{ $banner['banner_image_block']['width'] }}"
                  @endif
                  @if(isset($banner['banner_image_block']['height']))
                    height="{{ $banner['banner_image_block']['height'] }}"
                  @endif
                >
              @endif
              @if($banner['banner_link_and_text_block']['title'])
                <span class="banner-title text-white text-center text-18 md:text-24 font-bold absolute left-0 top-0 right-0 bottom-0 flex items-end justify-center p-4 md:p-10 bg-gradient-to-b from-black/0 to-black/90">{{ $banner['banner_link_and_text_block']['title'] }}</span>
              @endif
            </a>
          @endif
        </div>
      @endforeach
    </div>
  </section>
@endif