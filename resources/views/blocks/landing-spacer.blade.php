@php
    $spacerDesktop = $block_data['landing_spacer_desktop'];
    $spacerTablet = $block_data['landing_spacer_tablet'];
    $spacerMobile = $block_data['landing_spacer_mobile'];
@endphp

<style>
  :root {
      --spacer-desktop: {{ $spacerDesktop }}px;
      --spacer-tablet: {{ $spacerTablet }}px;
      --spacer-mobile: {{ $spacerMobile }}px;
  }
</style>

<div class="spacer-section">
</div>
