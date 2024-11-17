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
            $panel_heights = $gammaData['panel_height'] ?? [];

            // Set default values for the form inputs
            $default_width = $predefined_sizes[0]['width'] ?? '';
            $default_height = $predefined_sizes[0]['height'] ?? '';
        @endphp

<section class="fences-product">
    <div id="gamma-calculator-form" class="gamma-calculator-section">
        <h2 class="mb-2">gamma Въведете вашите индивидуални размери на оградата:</h2>
        <form id="gamma-fence-calculator" class="gamma-calculator-section">
            <div class="form-row">
                <label for="gamma-panel-width" class="font-normal">Ширина на паното (m)</label>
                <input 
                class="border border-black/10 rounded-md px-4 py-2"
                    type="number" 
                    id="gamma-panel-width" 
                    name="gamma-panel-width" 
                    step="0.01" 
                    min="{{ $gammaData['width_min'] }}" 
                    max="{{ $gammaData['width_max'] }}" 
                    value="1.8"
                    required
                >
            </div>
    
            <div class="form-row">
                <label for="gamma-panel-height" class="font-normal">Височина на паното (m):</label>
                <select id="gamma-panel-height" name="gamma-panel-height" required>
                    @foreach ($gammaData['panel_height'] as $height)
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
                <label for="gamma-number-of-panels" class="font-normal"> Брой пана (бр.) </label>
                <input 
                class="border border-black/10 rounded-md px-4 py-2"
                    type="number" 
                    id="gamma-number-of-panels" 
                    name="gamma-number-of-panels" 
                    min="1" 
                    value="1"
                    required
                >
            </div>
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
    
            $('#gamma-panel-width').val(defaultWidth);
            $('#gamma-panel-height').val(defaultHeight);
    
            // Handle click on predefined sizes
            $('.main-product-sizes__item').on('click', function(e) {
                e.preventDefault();
                const width = $(this).data('l');
                const height = $(this).data('h');
    
                // Update form inputs
                $('#gamma-panel-width').val(width);
                $('#gamma-panel-height').val(height);
    
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