@php

  $image_contacts = get_field('image_contacts');
  $heading_contacts = get_field('heading_contacts');
  $text_contacts = get_field('text_contacts');
  $contactFormShortcode = get_field('contact_form_shortcode');

@endphp

<section class="contacts-section" >
  <div class="container">
    <div class="flex flex-col md:flex-row justify-between items-center py-4 md:py-10">
      <div class="content">
        <h2 class="text-black text-h2 mb-4">{{ $heading_contacts }}</h2>
        {!! $text_contacts  !!}
      </div>
      @if(!empty($image_contacts))
      <div class="contacts-image">
        <img src=" {{ $image_contacts['url'] }}" alt="">
      </div>
      @endif
    </div>
  </div>
  <div class="contact-information bg-black/10 w-full">
    <div class="container  py-10">
      @if(have_rows('contact_information'))
        <ul class="flex flex-col md:flex-row w-full gap-4">
          @while(have_rows('contact_information')) @php(the_row())
            <li class="w-full md:w-1/3 flex flex-col items-center bg-white rounded-xl py-6">
              <span><img src="{{ get_sub_field('icon_contact_information') }}" alt=""></span>
              <h3 class="text-18 text-center font-bold text-black">{{ get_sub_field('heading_contact_information') }}</h3>
              <p class="text-15 text-center text-black/80 m-0">{{ get_sub_field('text_contact_information') }}</p>
            </li>
          @endwhile
        </ul>
      @endif
    </div>
  </div>

  <div class="contact-form bg-black w-full">
    <div class="container flex items-center">
      @if (!empty($contactFormShortcode))
        {!! do_shortcode($contactFormShortcode) !!}
      @endif
    </div>
  </div>

</section>
