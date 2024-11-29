@php
  $landing_introduction_heading = $block_data['landing_introduction_heading'];
  $landing_introduction_description = $block_data['landing_introduction_description'];
  $landing_introduction_video = $block_data['landing_introduction_video'];
  $features_with_image = $block_data['landing_introduction_features_with_image'];
@endphp

<section class="landing-introduction-section" id="overview">
  <div class="container">
    <div class="flex flex-col items-center gap-4">
      
      @if(!empty($landing_introduction_heading) || !empty($landing_introduction_description))
        <div class="content">
          @if(!empty($landing_introduction_heading))
            <h2 class="landing-title">{{ $landing_introduction_heading }}</h2>
          @endif
          @if(!empty($landing_introduction_description))
            <div class="description">{!! $landing_introduction_description !!}</div>
          @endif
        </div>
      @endif

      @if(!empty($landing_introduction_video))
        <!-- Modal Structure -->
        <div id="videoModal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-100">
          <div class="modal-content p-10 w-full mx-auto mt-32">
            <span class="close-modal text-white text-3xl font-bold float-right cursor-pointer">&times;</span>
            <div id="videoContainer" class="mt-8"></div>
          </div>
        </div>

        <div class="video-box" id="video">
          <a href="" id="play-video"></a>
          <div class="play">
            <x-iconsax-bol-play-circle class="text-white w-[108px] h-[108px] md:w-[128px] md:h-[128px] transition-all duration-200 scale-100 hover:scale-95 transform" />
          </div>
          <img src="https://img.youtube.com/vi/{{ $landing_introduction_video }}/maxresdefault.jpg" class="thumb" alt="">
        </div>
      @endif

      @if(!empty($features_with_image))
        <section class="features-center-with-image">
          @if(!empty($features_with_image['landing_introduction_add_feature']))
            <ul class="features-list left">
              @foreach($features_with_image['landing_introduction_add_feature'] as $feature)
                @if(!empty($feature['landing_introduction_feature_heading']) || !empty($feature['landing_introduction_feature_description']))
                  <li>
                    @if(!empty($feature['landing_introduction_feature_heading']))
                      <h3>{{ $feature['landing_introduction_feature_heading'] }}</h3>
                    @endif
                    @if(!empty($feature['landing_introduction_feature_description']))
                      <p>{{ $feature['landing_introduction_feature_description'] }}</p>
                    @endif
                  </li>
                @endif
              @endforeach
            </ul>
          @endif

          @if(!empty($features_with_image['landing_introduction_product_image']))
            <img src="{{ $features_with_image['landing_introduction_product_image']['url'] }}" alt="">
          @endif

          @if(!empty($features_with_image['landing_introduction_add_feature_right']))
            <ul class="features-list right">
              @foreach($features_with_image['landing_introduction_add_feature_right'] as $feature)
                @if(!empty($feature['landing_introduction_feature_heading']) || !empty($feature['landing_introduction_feature_description']))
                  <li>
                    @if(!empty($feature['landing_introduction_feature_heading']))
                      <h3>{{ $feature['landing_introduction_feature_heading'] }}</h3>
                    @endif
                    @if(!empty($feature['landing_introduction_feature_description']))
                      <p>{{ $feature['landing_introduction_feature_description'] }}</p>
                    @endif
                  </li>
                @endif
              @endforeach
            </ul>
          @endif
        </section>
      @endif

    </div>
  </div>
</section>

@if(!empty($landing_introduction_video))
  <script>
    var videoSrc = "https://www.youtube.com/embed/{{ $landing_introduction_video }}?autoplay=1";
  </script>
@endif