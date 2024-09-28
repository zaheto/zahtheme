@php
    $no_thumbnail_url = get_template_directory_uri() . '/resources/images/no-image.svg'; // Path to your default no-thumbnail image
@endphp

@if($category_list_builder)
    <section class=" category-list-builder swiper">

        <div class="swiper-wrapper"  data-slider="true">
            @foreach($category_list_builder as $category_id)
                @php
                    $category = get_term($category_id, 'product_cat');
                    $category_link = get_term_link($category);
                    $thumbnail_id = get_term_meta($category_id, 'thumbnail_id', true);
                    $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : $no_thumbnail_url;
                @endphp
                <div class="swiper-slide">
                    <a href="{{ $category_link }}">
                        @if($image_url)
                            <img src="{{ $image_url }}" alt="{{ $category->name }}">
                        @endif
                        <h3>{{ $category->name }}</h3>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>

    </section>
@endif
