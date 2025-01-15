@php
            // Retrieve the models from the options page
            $models = get_field('models', 'option');
            $predefined_sizes = [];

            if ($models && is_array($models)) {
                foreach ($models as $model) {
                    $tag = $model['tag'];
                    if ($tag && has_term($tag, 'product_tag', get_the_ID())) {
                        $predefined_sizes = $model['predefined_sizes'];
                        break;
                    }
                }
            }

            // Retrieve the panel heights from the product ACF module
            $panel_heights = $piramidaData['panel_height'] ?? [];

            // Set default values for the form inputs
            $default_width = $predefined_sizes[0]['width'] ?? '';
            $default_height = $predefined_sizes[0]['height'] ?? '';
        @endphp

<section class="fences-product">
    <div id="piramida-calculator-form" class="piramida-calculator-section">
        <h2 class="mb-2">Въведете вашите индивидуални размери на оградата:</h2>
        <form id="piramida-fence-calculator" class="piramida-calculator-section">
            <div class="form-row">
                <label for="piramida-panel-width" class="font-normal">Ширина на паното (m)</label>
                <input 
                class="border border-black/10 rounded-md px-4 py-2"
                    type="number" 
                    id="piramida-panel-width" 
                    name="piramida-panel-width" 
                    step="0.01" 
                    min="{{ $piramidaData['width_min'] }}" 
                    max="{{ $piramidaData['width_max'] }}" 
                    value="1.8"
                    required
                >
            </div>
    
            <div class="form-row">
                <label for="piramida-panel-height" class="font-normal">Височина на паното (m):</label>
                <select id="piramida-panel-height" name="piramida-panel-height" required>
                    @foreach ($piramidaData['panel_height'] as $height)
                        @php
                            $height = trim($height);
                            // Get the first predefined size height for comparison
                            $default_height = $predefined_sizes[0]['height'] ?? '';
                        @endphp
                        <option value="{{ $height }}" {{ $height == $default_height ? 'selected' : '' }}>
                            {{ $height }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <div class="form-row">
                <label for="piramida-number-of-panels" class="font-normal"> Брой пана (бр.) </label>
                <input 
                class="border border-black/10 rounded-md px-4 py-2"
                    type="number" 
                    id="piramida-number-of-panels" 
                    name="piramida-number-of-panels" 
                    min="1" 
                    value="1"
                    required
                >
            </div>
            <!-- Add hidden fields for total_price and discounted_price -->
            <input type="hidden" name="total_price" id="total_price" value="">
            <input type="hidden" name="discounted_price" id="discounted_price" value="">
        </form>
    
        

        @php
        // Retrieve the models from the options page
        $models = get_field('models', 'option');
        $predefined_sizes = [];
    
        if ($models && is_array($models)) {
            foreach ($models as $model) {
                $tag = $model['tag'];
                if ($tag && has_term($tag, 'product_tag', get_the_ID())) {
                    $predefined_sizes = $model['predefined_sizes'];
                    break;
                }
            }
        }
    @endphp
    
    <div class="predefined-sizes">
        <h2>Или изберете някой от често поръчваните размери:</h2>
        <ul>
            @if ($predefined_sizes && is_array($predefined_sizes))
                @foreach ($predefined_sizes as $index => $size)
                    @php
                        // Ensure height exists in panel_heights
                        $is_height_valid = in_array($size['height'], $panel_heights);
                        if (!$is_height_valid) continue; // Skip invalid heights
                    @endphp
                    <li>
                        <a href="#" 
                           class="main-product-sizes__item {{ $index === 0 ? 'selected' : '' }}"
                           data-l="{{ $size['width'] }}"
                           data-h="{{ $size['height'] }}"
                           data-panels="{{ $size['number_of_panels'] }}">
                            {{ $size['width'] }}(ш) x {{ $size['height'] }}(в)
                            @if($size['number_of_panels'] > 1)
                                - {{ $size['number_of_panels'] }} пана
                            @endif
                        </a>
                    </li>
                @endforeach
            @else
                <li>{{ __('No predefined sizes available', 'zah') }}</li>
            @endif
        </ul>
    </div>
    
    <script>
        jQuery(document).ready(function($) {
            // Set default values for the form inputs
            const firstPredefinedSize = $('.main-product-sizes__item').first();
            const defaultWidth = firstPredefinedSize.data('l');
            const defaultHeight = firstPredefinedSize.data('h');
    
            $('#piramida-panel-width').val(defaultWidth);
            $('#piramida-panel-height').val(defaultHeight);
    
            // Handle click on predefined sizes
            $('.main-product-sizes__item').on('click', function(e) {
                e.preventDefault();
                const width = $(this).data('l');
                const height = $(this).data('h');
    
                // Update form inputs
                $('#piramida-panel-width').val(width);
                $('#piramida-panel-height').val(height);
    
                // Update selected class
                $('.main-product-sizes__item').removeClass('selected');
                $(this).addClass('selected');
            });
        });
    </script>
        
    
    
    </div>
    @if(get_field('fence_information'))
    <div class="fence-information">
        {!! get_field('fence_information') !!}
    </div>
    @endif
</section>