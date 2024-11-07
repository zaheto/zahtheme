<div id="atlas-calculator-form" class="atlas-calculator-section">
    <h2 class="mb-2">Въведете вашите индивидуални размери на оградата:</h2>
    <form id="atlas-fence-calculator" class="atlas-calculator-section">
        <div class="form-row">
            <label for="atlas-panel-width" class="font-normal">Ширина на паното (m)</label>
            <input 
            class="border border-black/10 rounded-md px-4 py-2"
                type="number" 
                id="atlas-panel-width" 
                name="atlas-panel-width" 
                step="0.01" 
                min="{{ $atlasData['width_min'] }}" 
                max="{{ $atlasData['width_max'] }}" 
                value="1.8"
                required
            >
        </div>

        <div class="form-row">
            <label for="atlas-panel-height" class="font-normal">Височина на паното (m):</label>
            <select id="atlas-panel-height" name="atlas-panel-height" required>
                @foreach ($atlasData['panel_height'] as $height)
                    <option value="{{ $height }}">{{ $height }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-row">
            <label for="atlas-number-of-panels" class="font-normal"> Брой пана (бр.) </label>
            <input 
            class="border border-black/10 rounded-md px-4 py-2"
                type="number" 
                id="atlas-number-of-panels" 
                name="atlas-number-of-panels" 
                min="1" 
                value="1"
                required
            >
        </div>
    </form>

    <div class="predefined-sizes">
        <p class="text-sm mt-4 mb-2">Или изберете някой от често поръчваните размери:</p>
        <div class="flex flex-wrap ">
            <a href="#" class=" main-product-sizes__item selected" data-l="1.8" data-h="1.245">1.8(ш) x 1.245(в)</a>
            <a href="#" class=" main-product-sizes__item" data-l="1.8" data-h="1.045">1.8(ш) x 1.045(в)</a>
            <a href="#" class=" main-product-sizes__item" data-l="2.0" data-h="1.245">2.0(ш) x 1.245(в)</a>
            <a href="#" class=" main-product-sizes__item" data-l="2.0" data-h="1.545">2.0(ш) x 1.545(в)</a>
            <a href="#" class=" main-product-sizes__item" data-l="2.0" data-h="2.045">2.0(ш) x 2.045(в)</a>
            <a href="#" class=" main-product-sizes__item" data-l="1.0" data-h="2.045">1.0(ш) x 2.045(в)</a>
            <a href="#" class=" main-product-sizes__item" data-l="1.5" data-h="1.845">1.5(ш) x 1.845(в)</a>
            <a href="#" class=" main-product-sizes__item" data-l="1.8" data-h="1.845">1.8(ш) x 1.845(в)</a>
        </div>
    </div>
    
    @if(get_field('fence_information'))
        <div class="fence-information mt-8">
            {!! get_field('fence_information') !!}
        </div>
    @endif

</div>