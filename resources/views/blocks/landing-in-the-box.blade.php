@php
  $landing_in_the_box_heading = $block_data['landing_in_the_box_heading'];
  $landing_in_the_box_description = $block_data['landing_in_the_box_description'];
  $landing_in_the_box_image = $block_data['landing_in_the_box_image'];
@endphp

<section id="box" class="landing-in-the-box" style="background: url('{{ $landing_in_the_box_image['url'] }}') no-repeat right center; background-size:cover;">
  <div class="container">
    <div class="content">
        <h2 class="landing-title">{{ $landing_in_the_box_heading }} </h2>
        <div class="description">{!! $landing_in_the_box_description !!}</div>
    </div>
  </div>
</section>

