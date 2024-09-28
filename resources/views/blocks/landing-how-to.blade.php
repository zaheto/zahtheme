@php
  $landing_how_to_section_background = $block_data['landing_how_to_section_background'];
  $landing_how_to_subheading = $block_data['landing_how_to_subheading'];
  $landing_how_to_heading = $block_data['landing_how_to_heading'];
  $landing_how_to_add_step = $block_data['landing_how_to_add_step'];
@endphp

{{-- <section id="reviews" class="reviews">
  <div class="container">

    {!! do_shortcode('[site_reviews assigned_posts="post_id" hide="assigned_links,title" ]') !!}

  </div>
</section> --}}

<section class="landing-how-to" style="background-color: {{ $landing_how_to_section_background }};">

  <div class="container">
    <div class="flex flex-col items-center gap-4 md:gap-8">

      <div class="content-top ">
        <h4 class="landing-subtitle">{{ $landing_how_to_subheading }}</h4>
        <h2 class="landing-title">{{ $landing_how_to_heading }} </h2>
      </div>


      @if($landing_how_to_add_step)
      <div class="swiper how-to-list">
        <div class="swiper-wrapper">
          @foreach($landing_how_to_add_step as $step)
            <div class="swiper-slide">
              <div class="content">
                <img src="{{ $step['landing_how_to_step_image']['url'] }}" alt="{{ $step['landing_how_to_step_image']['alt'] }}">
                <h3>{{ $step['landing_how_to_step_heading'] }}</h3>
                <p>{{ $step['landing_how_to_step_text'] }}</p>
              </div>
            </div>
          @endforeach
        </div>
        <div class="swiper-pagination"></div>
      </div>
    @endif



    </div>
  </div>
</section>

