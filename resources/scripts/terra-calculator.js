// In terra-calculator.js
jQuery(document).ready(function ($) {
    // Hide the results section by default
    // Hide the results section by default
    $('#terra-calculator-results').hide();

    $('.required-materials--toggle-link').on('click', function (e) {
        e.preventDefault();
        const resultsSection = $('#terra-calculator-results');
        const toggleIcon = $(this).find('.toggle-icon');

        if (resultsSection.is(':visible')) {
            resultsSection.slideUp(300, function () {
                resultsSection.addClass('hidden').css('display', '');
                toggleIcon.text('+');
            });
        } else {
            resultsSection.removeClass('hidden').slideDown(300, function () {
                toggleIcon.text('-');
            });
        }
    });

    // Get price elements
    const priceElement = $('.price');
    const addToCartButton = $('button.single_add_to_cart_button');

    // Input elements for Terra
    const widthInput = $('#terra-panel-width');
    const heightInput = $('#terra-panel-height');
    const cassetteDistanceInput = $('#terra-panel-distance-cassettes');
    const baseDistanceInput = $('#terra-panel-base-distance');
    const panelsInput = $('#terra-number-of-panels');
    const optimalHeightField = $('#terra-panel-optimal-height');

    // Function to validate inputs
    function areInputsValid() {
        const width = parseFloat(widthInput.val());
        const height = parseFloat(heightInput.val());
        const cassetteDistance = parseFloat(cassetteDistanceInput.val());
        const baseDistance = parseFloat(baseDistanceInput.val());
        const panels = parseInt(panelsInput.val());

        return !isNaN(width) && !isNaN(height) && !isNaN(cassetteDistance) && 
               !isNaN(baseDistance) && !isNaN(panels) && panels > 0;
    }

    // Main calculation function
    function calculateTerraPrice() {
        if (!areInputsValid()) return;

        try {
            const panelWidth = parseFloat(widthInput.val());
            const panelHeight = parseFloat(heightInput.val());
            const cassetteDistance = parseFloat(cassetteDistanceInput.val());
            const baseDistance = parseFloat(baseDistanceInput.val());
            const numberOfPanels = parseInt(panelsInput.val());

            // Format values like Atlas
            const formattedWidth = panelWidth.toFixed(2);
            const formattedHeight = (Math.floor(panelHeight * 1000) / 1000).toFixed(3);

            // Calculate optimal height
            const G15 = Math.floor((panelHeight - baseDistance/100) / (0.108 + cassetteDistance/100));
            const G16 = Math.ceil((panelHeight - baseDistance/100) / (0.108 + cassetteDistance/100));
            const H15 = G15 * 0.108 + (G15 - 1) * (cassetteDistance/100) + (baseDistance/100);
            const H16 = G16 * 0.108 + (G16 - 1) * (cassetteDistance/100) + (baseDistance/100);
            const G17 = Math.abs(panelHeight - H15);
            const H17 = Math.abs(panelHeight - H16);
            const optimalHeight = G17 <= H17 ? H15 : H16;
            optimalHeightField.val(optimalHeight.toFixed(3));

            // Calculate materials
            const numCassettes = (G17 <= H17 ? G15 : G16);
            const profileCassettesPcs = numCassettes * numberOfPanels;
            const profileCassettesLm = Math.max((panelWidth - 0.01) * profileCassettesPcs, 0);
            const uProfilePcs = numberOfPanels * 2;
            const uProfileLm = optimalHeight * numberOfPanels * 2;
            const rivetsPcs = Math.round(profileCassettesPcs * 8) >= 101 ? numberOfPanels * 200 : numberOfPanels * 100;
            const selfTappingScrewPcs = numberOfPanels * 10;
            const dowelsPcs = numberOfPanels * 10;

            // Calculate total price
            let totalPrice = 0;
            totalPrice += profileCassettesLm * parseFloat(terra_pricing.base_price || 0);
            totalPrice += uProfileLm * parseFloat(terra_pricing.price_u_profile_left || 0);
            totalPrice += rivetsPcs * parseFloat(terra_pricing.price_rivets || 0);
            totalPrice += selfTappingScrewPcs * parseFloat(terra_pricing.price_self_tapping_screw || 0);
            totalPrice += dowelsPcs * parseFloat(terra_pricing.price_dowels || 0);

            // Update UI elements
            priceElement.html(`
                <span class="woocommerce-Price-amount amount">
                    <bdi>Крайна цена: ${totalPrice.toFixed(2)}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
                    <span class="custom-text-after-price">(вкл. ДДС)</span>
                </span>
            `);

            // Update form data for cart
            addToCartButton.attr('data-calculated-price', totalPrice.toFixed(2));
            $('#calculated_price').val(totalPrice.toFixed(2));
            $('#terra_panel_width').val(formattedWidth);
            $('#terra_panel_height').val(formattedHeight);
            $('#terra_number_of_panels').val(numberOfPanels);

            $('#terra_panel_distance_cassettes').val(cassetteDistance.toFixed(2));
            $('#terra_panel_base_distance').val(baseDistance.toFixed(2));

            // Update results display
            $('#terra-results').html(`
                <ul>
                    <li>Profile Cassettes: <span>${profileCassettesPcs} бр., ${profileCassettesLm.toFixed(2)} лм</span></li>
                    <li>U Profile: <span>${uProfilePcs} бр., ${uProfileLm.toFixed(3)} лм</span></li>
                    <li>Rivets: <span>${rivetsPcs} бр.</span></li>
                    <li>Self-tapping Screws: <span>${selfTappingScrewPcs} бр.</span></li>
                    <li>Dowels: <span>${dowelsPcs} бр.</span></li>
                </ul>
            `);

            $('#terra-final-price').html(`<p>Крайна цена: ${totalPrice.toFixed(2)} лв.</p>`);
        } catch (error) {
            console.error('Error in Terra Calculator:', error);
        }
    }

    // Event listeners
    widthInput.on('input', calculateTerraPrice);
    heightInput.on('input', calculateTerraPrice);
    cassetteDistanceInput.on('input', calculateTerraPrice);
    baseDistanceInput.on('input', calculateTerraPrice);
    panelsInput.on('input', calculateTerraPrice);

    // Predefined sizes click handler
    $('.main-product-sizes__item').on('click', function(e) {
        e.preventDefault();
        
        $('.main-product-sizes__item').removeClass('selected');
        $(this).addClass('selected');
        
        const width = $(this).data('l');
        const height = $(this).data('h');
        const panels = $(this).data('panels') || 1;
        
        widthInput.val(width);
        heightInput.val(height);
        panelsInput.val(panels);
        
        calculateTerraPrice();
    });

    // Initial calculation
    calculateTerraPrice();
});