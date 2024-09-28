<div class="half-section  {{ $block_data['text_position_half_section'] == 'Left' ? 'left' : 'right' }}" style="background: url({{ $block_data['image_half_section']['url'] }}) center no-repeat; background-size:cover;">
  <div class="content">
      <h2>{{ $block_data['heading_half_section'] }}</h2>
      <div class="text">{!! $block_data['text_half_section'] !!}</div>
      @if(isset($block_data['link_half_section']) && is_array($block_data['link_half_section']))
        @php
            $url = $block_data['link_half_section']['url'] ?? '#'; // Fallback to '#' or any default URL if not set
            $target = $block_data['link_half_section']['target'] ?? '_self'; // Fallback to '_self' or any default target if not set
            $title = $block_data['link_half_section']['title'] ?? ''; // Fallback to empty string or any default title if not set
        @endphp

        <a href="{{ $url }}" class="btn btn-main btn-block" target="{{ $target }}">{{ $title }}</a>
    @endif

  </div>
</div>


