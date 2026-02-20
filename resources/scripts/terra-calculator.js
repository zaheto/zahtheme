// In terra-calculator.js
jQuery(document).ready(function ($) {

    // EUR conversion rate (1 EUR = 1.95470 BGN)
      const exchangeRate = 1.95580;

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
            // Debug: Log the terra_pricing object
            console.log('terra_pricing:', terra_pricing);
            console.log('Sale Price:', terra_pricing.sale_price);
            console.log('Base Price:', terra_pricing.base_price);
    
            const panelWidth = parseFloat(widthInput.val());
            const panelHeight = parseFloat(heightInput.val());
            const cassetteDistance = parseFloat(cassetteDistanceInput.val());
            const baseDistance = parseFloat(baseDistanceInput.val());
            const numberOfPanels = parseInt(panelsInput.val());
    
            // Format values
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
    
            // Calculate base total price - prices are now stored in EUR
            let basePriceEUR = parseFloat(terra_pricing.base_price) * profileCassettesLm;
            let totalPriceEUR = basePriceEUR;

            // Add essential components
            totalPriceEUR += uProfileLm * parseFloat(terra_pricing.price_u_profile_left || 0);
            totalPriceEUR += rivetsPcs * parseFloat(terra_pricing.price_rivets || 0);
            totalPriceEUR += selfTappingScrewPcs * parseFloat(terra_pricing.price_self_tapping_screw || 0);
            totalPriceEUR += dowelsPcs * parseFloat(terra_pricing.price_dowels || 0);

            // Calculate the discounted price if a sale price exists and is valid
            let discountedPriceEUR = totalPriceEUR;
            if (terra_pricing.sale_price &&
                parseFloat(terra_pricing.sale_price) > 0 &&
                parseFloat(terra_pricing.sale_price) < parseFloat(terra_pricing.base_price)) {

                discountedPriceEUR = parseFloat(terra_pricing.sale_price) * profileCassettesLm;
                discountedPriceEUR += uProfileLm * parseFloat(terra_pricing.price_u_profile_left || 0);
                discountedPriceEUR += rivetsPcs * parseFloat(terra_pricing.price_rivets || 0);
                discountedPriceEUR += selfTappingScrewPcs * parseFloat(terra_pricing.price_self_tapping_screw || 0);
                discountedPriceEUR += dowelsPcs * parseFloat(terra_pricing.price_dowels || 0);
            }

            // Debug: Log the prices
            console.log('Total Price EUR:', totalPriceEUR);
            console.log('Discounted Price EUR:', discountedPriceEUR);

            // Calculate the discount percentage
            let discountPercentage = 0;
            if (totalPriceEUR > 0 && discountedPriceEUR < totalPriceEUR) {
                discountPercentage = Math.round(100 - (discountedPriceEUR / totalPriceEUR * 100));
                console.log('Discount Percentage:', discountPercentage + '%');
            }

            // Update the discount badge
            $('.product-badge.sale').text(`-${discountPercentage}%`);

            // Calculate BGN prices from EUR with proper rounding
            const totalPriceBGN = (Math.round(totalPriceEUR * exchangeRate * 100) / 100).toFixed(2);
            const discountedPriceBGN = (Math.round(discountedPriceEUR * exchangeRate * 100) / 100).toFixed(2);

            // Set hidden fields for total_price and discounted_price (in EUR)
            $('#total_price').val(totalPriceEUR.toFixed(2));
            $('#discounted_price').val(discountedPriceEUR.toFixed(2));

            // Update displayed price - EUR as primary, BGN as secondary
            if (terra_pricing.sale_price &&
                parseFloat(terra_pricing.sale_price) > 0 &&
                parseFloat(terra_pricing.sale_price) < parseFloat(terra_pricing.base_price)) {

                priceElement.html(`
                    <span class="woocommerce-Price-amount amount" data-bgn-converted="true">
                        <span class="regular-price-witheuro">
                            <del class="eur-regular eur-inline">€${totalPriceEUR.toFixed(2)}</del> /
                            <del>
                                <bdi>${totalPriceBGN}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
                            </del>
                        </span>
                        <span class="sale-price-witheuro">
                            <ins class="eur-sale eur-inline">€${discountedPriceEUR.toFixed(2)}</ins> /
                            <ins>
                                <bdi>${discountedPriceBGN}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
                            </ins>
                        </span>
                        <span class="custom-text-after-price">(вкл. ДДС)</span>
                    </span>
                `);
            } else {
                priceElement.html(`
                    <span class="woocommerce-Price-amount amount" data-bgn-converted="true">
                        <bdi>€${totalPriceEUR.toFixed(2)}&nbsp;<span class="woocommerce-Price-currencySymbol"></span></bdi> /
                        <bdi>${totalPriceBGN}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
                        <span class="custom-text-after-price">(вкл. ДДС)</span>
                    </span>
                `);
            }
    
            // Update form data for cart
            $('#calculated_price').val(discountedPriceEUR.toFixed(2));
            $('#terra_panel_width').val(formattedWidth);
            $('#terra_panel_height').val(formattedHeight);
            $('#terra_number_of_panels').val(numberOfPanels);
            $('#terra_panel_distance_cassettes').val(cassetteDistance.toFixed(2));
            $('#terra_panel_base_distance').val(baseDistance.toFixed(2));

            // Update button attributes
            addToCartButton.attr('data-calculated-price', discountedPriceEUR.toFixed(2));
            addToCartButton.attr('data-panel-width', formattedWidth);
            addToCartButton.attr('data-panel-height', formattedHeight);
            addToCartButton.attr('data-panels', numberOfPanels);
    
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
    
            $('#terra-final-price').html(`<p>Крайна цена: €${discountedPriceEUR.toFixed(2)} / ${discountedPriceBGN} лв.</p>`);
    
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