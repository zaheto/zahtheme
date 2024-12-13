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
            $panel_heights = $terraData['panel_height'] ?? [];

            // Set default values for the form inputs
            $default_width = $predefined_sizes[0]['width'] ?? '';
            $default_height = $predefined_sizes[0]['height'] ?? '';
        @endphp

<section class="fences-product">
    <div id="terra-calculator-form" class="terra-calculator-section">
        <h2 class="mb-2">Въведете вашите индивидуални размери на оградата:</h2>
        <form id="terra-fence-calculator" class="terra-calculator-section">
            <div class="form-row">
                <label for="terra-panel-width" class="font-normal">Ширина на паното (m)</label>
                <input 
                class="border border-black/10 rounded-md px-4 py-2"
                    type="number" 
                    id="terra-panel-width" 
                    name="terra-panel-width" 
                    step="0.01" 
                    min="{{ $terraData['width_min'] }}" 
                    max="{{ $terraData['width_max'] }}" 
                    value="1.8"
                    required
                >
            </div>
    
            <div class="form-row">
                <label for="terra-panel-height" class="font-normal">Височина на паното (m):</label>
                <input 
                class="border border-black/10 rounded-md px-4 py-2"
                    type="number" 
                    id="terra-panel-height" 
                    name="terra-panel-height" 
                    step="0.01" 
                    value="1.8"
                    required
                />
            </div>

            <div class="form-row">
                <label for="terra-panel-optimal-height" class="font-normal">Препоръчителна височина(m):</label>
                <input type="number" id="terra-panel-optimal-height" name="terra-panel-optimal-height" class="bg-black/5 border border-black/10 rounded-md px-4 py-2" step="0.01" disabled />
            </div>

            <div class="form-row">
                <label for="terra-panel-distance-cassettes" class="font-normal">Разстояние м/у ламели(cm):</label>
                <input type="number" id="terra-panel-distance-cassettes" name="terra-panel-distance-cassettes" class="border border-black/10 rounded-md px-4 py-2" step="0.01" value="2" required />
            </div>

            <div class="form-row">
                <label for="terra-panel-base-distance" class="font-normal">Разстояние от основата(cm):</label>
                <input type="number" id="terra-panel-base-distance" name="terra-panel-base-distance" class="border border-black/10 rounded-md px-4 py-2" step="0.01" value="2" required />
            </div>
    
            <div class="form-row">
                <label for="terra-number-of-panels" class="font-normal"> Брой пана (бр.) </label>
                <input 
                class="border border-black/10 rounded-md px-4 py-2"
                    type="number" 
                    id="terra-number-of-panels" 
                    name="terra-number-of-panels" 
                    min="1" 
                    value="1"
                    required />
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
    
            $('#terra-panel-width').val(defaultWidth);
            $('#terra-panel-height').val(defaultHeight);
    
            // Handle click on predefined sizes
            $('.main-product-sizes__item').on('click', function(e) {
                e.preventDefault();
                const width = $(this).data('l');
                const height = $(this).data('h');
    
                // Update form inputs
                $('#terra-panel-width').val(width);
                $('#terra-panel-height').val(height);
    
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