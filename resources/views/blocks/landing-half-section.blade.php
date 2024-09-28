@php

  $image_half_section = $block_data['image_half_section'];
  $landings_video_file = $block_data['landings_video_file'];
@endphp

<div class="landing-half-section {{ $block_data['text_position_half_section'] == 'Left' ? 'left' : 'right' }}">
  <div class="container">
    <div class="cell">
      @if(is_array($image_half_section) && isset($image_half_section['url']))
        <img src="{{ $image_half_section['url'] }}" alt="">
      @endif
      @if(is_array($landings_video_file) && isset($landings_video_file['url']))
        <video  webkit-playsinline="true" playsinline="true" muted="muted" loop="loop" autoplay="" muted><source src="{{ $landings_video_file['url'] }}"></video>
      @endif
    </div>
    <div class="cell">
      <div class="content">
        <h2 class="landing-title">{{ $block_data['heading_half_section'] }}</h2>
        <div class="description">{!! $block_data['text_half_section'] !!}</div>
        <a href="{{ $block_data['link_half_section']['url'] }}" class="btn btn-green" target="{{ $block_data['link_half_section']['target'] }}">{{ $block_data['link_half_section']['title'] }}</a>
      </div>
    </div>
  </div>
</div>
