<section class="fences-product">
    <div id="siding-calculator-form" class="siding-calculator-section">
        <h2 class="mb-2">Въведете вашите индивидуални размери:</h2>
        <form id="siding-fence-calculator" class="siding-calculator-section">
            <div class="form-row">
                <label for="siding-width" class="font-normal">Дължина (m)</label>
                <input 
                    class="border border-black/10 rounded-md px-4 py-2"
                    type="number" 
                    id="siding-width" 
                    name="siding-width" 
                    step="0.01" 
                    min="0.1"
                    value="1"
                    required
                >
            </div>

            <div class="form-row">
                <label for="siding-panel-number" class="font-normal">Брой пана</label>
                <input 
                    class="border border-black/10 rounded-md px-4 py-2"
                    type="number" 
                    id="siding-panel-number" 
                    name="siding-panel-number" 
                    min="1"
                    value="1"
                    required
                >
            </div>

            <div class="form-row">
                <label for="siding-price-sqm" class="font-normal">Цена на кв.м.</label>
                <input 
                    class="border border-black/10 rounded-md px-4 py-2 bg-black/5"
                    type="text" 
                    id="siding-price-sqm" 
                    name="siding-price-sqm" 
                    value="{{ number_format($sidingData['base_price'], 2) }} лв."
                    disabled
                >
            </div>
             <!-- Add hidden fields for total_price and discounted_price -->
             <input type="hidden" name="total_price" id="total_price" value="">
             <input type="hidden" name="discounted_price" id="discounted_price" value="">
            
        </form>
        <div class="opt-square-wrap mt-4">
            <span class="opt-square">Обща площ: <b><span id="total-area">0</span> m²</b></span>
            |
            <span class="opt-square">Полезна площ: <b><span id="usable-area">0</span> m²</b></span>
        </div>
        @if(get_field('fence_information'))
            <div class="fence-information">
                {!! get_field('fence_information') !!}
            </div>
        @endif
    </div>
</section>

<script>
    var siding_pricing = {
        base_price: '{{ $sidingData['base_price'] }}',
        sale_price: '{{ $product->get_sale_price() }}',  // Add this line
        panel_siding_sqm: '{{ $sidingData['panel_siding_sqm'] }}',
        panel_siding_useful: '{{ $sidingData['panel_siding_useful'] }}'
    };
</script>

<script>
jQuery(document).ready(function($) {
    const sidingWidth = $('#siding-width');
    const panelNumber = $('#siding-panel-number');
    const totalArea = $('#total-area');
    const usableArea = $('#usable-area');
    const addToCartButton = $('.single_add_to_cart_button');
    
    const panelSidingSqm = {{ $sidingData['panel_siding_sqm'] }};
    const panelSidingUseful = {{ $sidingData['panel_siding_useful'] }};
    const basePrice = {{ $sidingData['base_price'] }};

    function calculateAreas() {
        const width = parseFloat(sidingWidth.val()) || 0;
        const panels = parseInt(panelNumber.val()) || 0;

        const totalAreaCalc = width * panels * panelSidingSqm;
        const usableAreaCalc = width * panels * panelSidingUseful;
        const price = totalAreaCalc * basePrice;

        totalArea.text(totalAreaCalc.toFixed(2));
        usableArea.text(usableAreaCalc.toFixed(2));

        // Update add to cart button data
        addToCartButton
            .attr('data-calculated-price', price.toFixed(2))
            .attr('data-width', width.toFixed(2))
            .attr('data-panels', panels);
    }

    sidingWidth.on('input', calculateAreas);
    panelNumber.on('input', calculateAreas);
    
    // Initial calculation
    calculateAreas();
});
</script>