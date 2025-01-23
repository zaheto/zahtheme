@php
    $no_thumbnail_url = get_template_directory_uri() . '/resources/images/no-image.svg';
@endphp

<div class="container">
@if($category_list_builder)
    <!-- Mobile Grid (under 768px) -->
    <div class="block md:hidden">
        <div class="category-list-grid grid grid-cols-2 gap-4 my-4">
            @foreach($category_list_builder as $category_id)
                @php
                    $category = get_term($category_id, 'product_cat');
                    if (!is_wp_error($category) && $category) {
                        $category_link = get_term_link($category);
                        $category_link = is_wp_error($category_link) ? '#' : $category_link;
                        $thumbnail_id = get_term_meta($category_id, 'thumbnail_id', true);
                        $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : $no_thumbnail_url;
                @endphp
                <div class="category-item">
                    <a href="{{ $category_link }}">
                        @if($image_url)
                            <img src="{{ $image_url }}" alt="{{ $category->name }}">
                        @endif
                        <h3>{{ $category->name }}</h3>
                    </a>
                </div>
                @php
                    }
                @endphp
            @endforeach
        </div>
    </div>

    <!-- Desktop Slider (768px and above) -->
    <section class="hidden md:block category-list-builder swiper">
        <div class="swiper-wrapper" data-slider="true">
            @foreach($category_list_builder as $category_id)
                @php
                    $category = get_term($category_id, 'product_cat');
                    if (!is_wp_error($category) && $category) {
                        $category_link = get_term_link($category);
                        $category_link = is_wp_error($category_link) ? '#' : $category_link;
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
                @php
                    }
                @endphp
            @endforeach
        </div>

        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </section>
@endif
</div>