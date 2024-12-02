jQuery(document).ready(function ($) {
    // Hide results section by default
    $('#siding-calculator-results').hide();

    // Toggle results section
    $('.required-materials--toggle-link').on('click', function(e) {
        e.preventDefault();
        const resultsSection = $('#siding-calculator-results');
        const toggleIcon = $(this).find('.toggle-icon');
        
        if (resultsSection.is(':visible')) {
            resultsSection.slideUp(300, function() {
                resultsSection.addClass('hidden').css('display', '');
                toggleIcon.text('+');
            });
        } else {
            resultsSection.removeClass('hidden').slideDown(300, function() {
                toggleIcon.text('-');
            });
        }
    });

    // Get price elements
    const priceElement = $('.price');
    const addToCartButton = $('button.single_add_to_cart_button');

    // Input elements
    const widthInput = $('#siding-width');
    const panelNumberInput = $('#siding-panel-number');

    // Main calculation function
    function calculatePrice() {
        const width = parseFloat(widthInput.val());
        const panelNumber = parseInt(panelNumberInput.val());
        
        if (!width || !panelNumber) return;

        const sqm = parseFloat(siding_pricing.panel_siding_sqm);
        const useful = parseFloat(siding_pricing.panel_siding_useful);
        const basePrice = parseFloat(siding_pricing.base_price);

        // Calculate areas
        const totalArea = width * panelNumber * sqm;
        const usableArea = width * panelNumber * useful;
        
        // Calculate final price
        const totalPrice = width * panelNumber * sqm * basePrice;

        // Update price display
        priceElement.html(`<span class="woocommerce-Price-amount amount">
            <bdi>Крайна цена: ${totalPrice.toFixed(2)}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
        </span>`);

        // Update results
        $('#siding-results .total-area').html(`<p>Обща площ: ${totalArea.toFixed(2)} m²</p>`);
        $('#siding-results .usable-area').html(`<p>Полезна площ: ${usableArea.toFixed(2)} m²</p>`);
        $('#siding-results .final-price').html(`<p>Крайна цена: ${totalPrice.toFixed(2)} лв.</p>`);

        // Update hidden fields
        $('#calculated_price').val(totalPrice.toFixed(2));
        $('#siding_width').val(width.toFixed(2));
        $('#siding_panel_number').val(panelNumber);

        // Update button data attributes
        addToCartButton.attr('data-calculated-price', totalPrice.toFixed(2));
    }

    // Add event listeners
    widthInput.on('input', calculatePrice);
    panelNumberInput.on('input', calculatePrice);

    // Initial calculation
    calculatePrice();
});