jQuery(document).ready(function ($) { // Pass $ as parameter
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

    // Input elements for Terra
    const widthInput = $('#terra-panel-width');
    const heightInput = $('#terra-panel-height');
    const cassetteDistanceInput = $('#terra-panel-distance-cassettes');
    const baseDistanceInput = $('#terra-panel-base-distance');
    const panelsInput = $('#terra-number-of-panels');
    const optimalHeightField = $('#terra-panel-optimal-height');

    const priceElement = $('.price');
    const addToCartButton = $('button.single_add_to_cart_button');

    // Function to validate inputs
    function areInputsValid() {
        const width = parseFloat(widthInput.val());
        const height = parseFloat(heightInput.val());
        const cassetteDistance = parseFloat(cassetteDistanceInput.val());
        const baseDistance = parseFloat(baseDistanceInput.val());
        const panels = parseInt(panelsInput.val());

        return !isNaN(width) && !isNaN(height) && !isNaN(cassetteDistance) && !isNaN(baseDistance) && !isNaN(panels) &&
               panels > 0;
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

            // Calculate optimal height
            const optimalHeight = (panelHeight + cassetteDistance + baseDistance) / numberOfPanels;
            optimalHeightField.val(optimalHeight.toFixed(2)); // Set optimal height

            // Example material calculations
            let uProfileLeftLm = panelHeight * numberOfPanels;
            let uProfileRightLm = panelHeight * numberOfPanels;
            let horizontalUProfileLm = panelWidth * numberOfPanels;
            let rivetsQty = numberOfPanels * 50; // Example value
            let dowelsQty = numberOfPanels * 10; // Example value

            // Price calculations
            let totalPrice = parseFloat(terra_pricing.base_price) * (panelWidth + panelHeight);
            totalPrice += uProfileLeftLm * parseFloat(terra_pricing.price_u_profile_left || 0);
            totalPrice += uProfileRightLm * parseFloat(terra_pricing.price_u_profile_right || 0);
            totalPrice += horizontalUProfileLm * parseFloat(terra_pricing.price_u_horizontal_panel || 0);
            totalPrice += rivetsQty * parseFloat(terra_pricing.price_rivets || 0);
            totalPrice += dowelsQty * parseFloat(terra_pricing.price_dowels || 0);

            // Update displayed price
            priceElement.html(`<span class="woocommerce-Price-amount amount">
                <bdi>Крайна цена: ${totalPrice.toFixed(2)}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
            </span>`);

            // Update add-to-cart button data
            addToCartButton.attr('data-calculated-price', totalPrice.toFixed(2));
            addToCartButton.attr('data-panel-width', panelWidth.toFixed(2));
            addToCartButton.attr('data-panel-height', panelHeight.toFixed(2));
            addToCartButton.attr('data-panels', numberOfPanels);

            // Update results section
            $('#terra-results').html(`
                <ul>
                    <li>U Profile Left: <span>${uProfileLeftLm.toFixed(2)} лм</span></li>
                    <li>U Profile Right: <span>${uProfileRightLm.toFixed(2)} лм</span></li>
                    <li>Horizontal U Profile: <span>${horizontalUProfileLm.toFixed(2)} лм</span></li>
                    <li>Rivets: <span>${rivetsQty} бр.</span></li>
                    <li>Dowels: <span>${dowelsQty} бр.</span></li>
                </ul>
            `);

            $('#terra-final-price').html(`<p>Крайна цена: ${totalPrice.toFixed(2)} лв.</p>`);

        } catch (error) {
            console.error('Error in Terra Calculator:', error);
        }
    }

    // Attach events to inputs
    widthInput.on('input', calculateTerraPrice);
    heightInput.on('change', calculateTerraPrice);
    cassetteDistanceInput.on('input', calculateTerraPrice);
    baseDistanceInput.on('input', calculateTerraPrice);
    panelsInput.on('input', calculateTerraPrice);

    // Initial calculation
    calculateTerraPrice();
});
