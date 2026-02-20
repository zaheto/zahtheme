@php
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

    $panel_heights  = $romb_grandeData['panel_height'] ?? [];
    $default_height = $predefined_sizes[0]['height'] ?? '';
@endphp

<section class="fences-product">
    <div id="romb-grande-calculator-form" class="romb-grande-calculator-section">
        <h2 class="mb-2">Въведете вашите индивидуални размери на оградата:</h2>
        <form id="romb-grande-fence-calculator" class="romb-grande-calculator-section">
            <div class="form-row">
                <label for="romb-grande-panel-width" class="font-normal">Ширина на паното (m)</label>
                <input
                    class="border border-black/10 rounded-md px-4 py-2"
                    type="number"
                    id="romb-grande-panel-width"
                    name="romb-grande-panel-width"
                    step="0.01"
                    min="{{ $romb_grandeData['width_min'] }}"
                    max="{{ $romb_grandeData['width_max'] }}"
                    value="1.8"
                    required
                >
            </div>

            <div class="form-row">
                <label for="romb-grande-panel-height" class="font-normal">Височина на паното (m):</label>
                <select id="romb-grande-panel-height" name="romb-grande-panel-height" required>
                    @foreach ($panel_heights as $height)
                        @php $height = trim($height); @endphp
                        <option value="{{ $height }}" {{ $height == $default_height ? 'selected' : '' }}>
                            {{ $height }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <label for="romb-grande-number-of-panels" class="font-normal">Брой пана (бр.)</label>
                <input
                    class="border border-black/10 rounded-md px-4 py-2"
                    type="number"
                    id="romb-grande-number-of-panels"
                    name="romb-grande-number-of-panels"
                    min="1"
                    value="1"
                    required
                >
            </div>

            <input type="hidden" name="total_price"      id="total_price"      value="">
            <input type="hidden" name="discounted_price" id="discounted_price" value="">
        </form>

        @php
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
                            $is_height_valid = in_array($size['height'], $panel_heights);
                            if (!$is_height_valid) continue;
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
                const firstSize = $('.main-product-sizes__item').first();
                $('#romb-grande-panel-width').val(firstSize.data('l'));
                $('#romb-grande-panel-height').val(firstSize.data('h'));

                $('.main-product-sizes__item').on('click', function(e) {
                    e.preventDefault();
                    $('#romb-grande-panel-width').val($(this).data('l'));
                    $('#romb-grande-panel-height').val($(this).data('h'));
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
