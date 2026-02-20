jQuery(document).ready(function ($) {  // Pass $ as parameter

    // EUR conversion rate (1 EUR = 1.95470 BGN)
    const exchangeRate = 1.95580;

    // Hide the results section by default
    $('#sigma-calculator-results').hide();

    $('.required-materials--toggle-link').on('click', function(e) {
        e.preventDefault();
        const resultsSection = $('#sigma-calculator-results');
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
    const widthInput = $('#sigma-panel-width');
    const heightInput = $('#sigma-panel-height');
    const panelsInput = $('#sigma-number-of-panels');

    // Get the selected predefined size
    const selectedPredefinedSize = $('.main-product-sizes__item.selected');
    
    if (selectedPredefinedSize.length) {
        const width = selectedPredefinedSize.data('l');
        const height = selectedPredefinedSize.data('h');
        const panels = selectedPredefinedSize.data('panels') || 1;
        
        // Set initial values
        widthInput.val(width);
        heightInput.val(height).trigger('change');
        panelsInput.val(panels);
    }

    // Rest of the code remains the same but replace jQuery with $ 
    // Function to check if all inputs have valid values
    function areInputsValid() {
        const width = parseFloat(widthInput.val());
        const height = parseFloat(heightInput.val());
        const panels = parseInt(panelsInput.val());

        return !isNaN(width) && !isNaN(height) && !isNaN(panels) && 
               width >= parseFloat(widthInput.attr('min')) && 
               width <= parseFloat(widthInput.attr('max')) && 
               panels > 0;
    }

    function calculatePrice() {
        if (!areInputsValid()) return;
    
        let panelWidth = parseFloat(widthInput.val());
        let panelHeight = parseFloat(heightInput.val());
        let numberOfPanels = parseInt(panelsInput.val());
    
        try {
            // Format width to exactly 2 decimal places
            const formattedWidth = panelWidth.toFixed(2);
            
            // Format height to exactly 3 decimal places without rounding
            const formattedHeight = (Math.floor(panelHeight * 1000) / 1000).toFixed(3);
            
            // Material calculations
            let blindsProfilePcs = Math.max((panelHeight - 0.06) / 0.08 * numberOfPanels, 0);
            let blindsProfileLm = Math.max((panelWidth - 0.01) * blindsProfilePcs, 0);
    
            let uProfileLeftPcs = numberOfPanels;
            let uProfileLeftLm = panelHeight * numberOfPanels;
    
            let uProfileRightPcs = numberOfPanels;
            let uProfileRightLm = panelHeight * numberOfPanels;
    
            let horizontalUProfilePcs = numberOfPanels;
            let horizontalUProfileLm = panelWidth * numberOfPanels;
    
            let F20 = 0;
            if (panelWidth > 1.29 && panelWidth < 2.1) {
                F20 = 1;
            } else if (panelWidth > 2.09) {
                F20 = 2;
            }
            let reinforcingProfilePcs = F20;
            let reinforcingProfileLm = panelHeight * F20 * numberOfPanels;
    
            // Rivets calculation
            let rivetsPcs = 0;
            if (numberOfPanels > 0) {
                let innerCalculation = 0;
                if (F20 == 0) {
                    innerCalculation = (blindsProfilePcs / numberOfPanels + 1) * 4 * numberOfPanels;
                } else if (F20 == 1) {
                    innerCalculation = (blindsProfilePcs / numberOfPanels + 1) * 5 * numberOfPanels;
                } else if (F20 == 2) {
                    innerCalculation = (blindsProfilePcs / numberOfPanels + 1) * 6 * numberOfPanels;
                }
                rivetsPcs = Math.ceil((innerCalculation + (F20 * 2)) / numberOfPanels / 100) * 100 * numberOfPanels;
            }
    
            let selfTappingScrewPcs = numberOfPanels * 10;
            let dowelsPcs = numberOfPanels * 10 + F20 * numberOfPanels;
            let cornerPcs = F20 * numberOfPanels;
    
            // Price Calculations - prices are now stored in EUR
            let basePriceEUR = parseFloat(sigma_pricing.base_price) * blindsProfileLm;
            let totalPriceEUR = basePriceEUR;

            // Add essential components
            totalPriceEUR += uProfileLeftLm * parseFloat(sigma_pricing.price_u_profile_left || 0);
            totalPriceEUR += uProfileRightLm * parseFloat(sigma_pricing.price_u_profile_right || 0);
            totalPriceEUR += horizontalUProfileLm * parseFloat(sigma_pricing.price_u_horizontal_panel || 0);
            totalPriceEUR += reinforcingProfileLm * parseFloat(sigma_pricing.price_reinforcing_profile || 0);
            totalPriceEUR += rivetsPcs * parseFloat(sigma_pricing.price_rivets || 0);
            totalPriceEUR += selfTappingScrewPcs * parseFloat(sigma_pricing.price_self_tapping_screw || 0);
            totalPriceEUR += dowelsPcs * parseFloat(sigma_pricing.price_dowels || 0);
            totalPriceEUR += cornerPcs * parseFloat(sigma_pricing.price_corners || 0);

            // Calculate the discounted price if a sale price exists and is valid
            let discountedPriceEUR = totalPriceEUR;
            if (sigma_pricing.sale_price && sigma_pricing.sale_price > 0 && sigma_pricing.sale_price < sigma_pricing.base_price) {
                discountedPriceEUR = parseFloat(sigma_pricing.sale_price) * blindsProfileLm;
                discountedPriceEUR += uProfileLeftLm * parseFloat(sigma_pricing.price_u_profile_left || 0);
                discountedPriceEUR += uProfileRightLm * parseFloat(sigma_pricing.price_u_profile_right || 0);
                discountedPriceEUR += horizontalUProfileLm * parseFloat(sigma_pricing.price_u_horizontal_panel || 0);
                discountedPriceEUR += reinforcingProfileLm * parseFloat(sigma_pricing.price_reinforcing_profile || 0);
                discountedPriceEUR += rivetsPcs * parseFloat(sigma_pricing.price_rivets || 0);
                discountedPriceEUR += selfTappingScrewPcs * parseFloat(sigma_pricing.price_self_tapping_screw || 0);
                discountedPriceEUR += dowelsPcs * parseFloat(sigma_pricing.price_dowels || 0);
                discountedPriceEUR += cornerPcs * parseFloat(sigma_pricing.price_corners || 0);
            }

            // Calculate the discount percentage
            let discountPercentage = 0;
            if (totalPriceEUR > 0 && discountedPriceEUR > 0) {
                discountPercentage = Math.round(100 - (discountedPriceEUR / totalPriceEUR * 100));
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
            if (sigma_pricing.sale_price && sigma_pricing.sale_price > 0 && sigma_pricing.sale_price < sigma_pricing.base_price) {
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
    
            // Update hidden fields and button attributes
            $('#calculated_price').val(discountedPriceEUR.toFixed(2));
            $('#sigma_panel_width').val(formattedWidth);
            $('#sigma_panel_height').val(formattedHeight);
            $('#sigma_number_of_panels').val(numberOfPanels);

            addToCartButton.attr('data-calculated-price', discountedPriceEUR.toFixed(2));
            addToCartButton.attr('data-panel-width', formattedWidth);
            addToCartButton.attr('data-panel-height', formattedHeight);
            addToCartButton.attr('data-panels', numberOfPanels);
    
            // Display materials
            $('#sigma-results').html(`
                <ul>
                    <li>Профил Жалюзи: <span>${blindsProfilePcs.toFixed(2)} бр. / ${blindsProfileLm.toFixed(2)} лм</span></li>
                    <li>Профил U отляво: <span>${uProfileLeftPcs} бр. / ${uProfileLeftLm.toFixed(3)} лм</span></li>
                    <li>Профил U отдясно: <span>${uProfileRightPcs} бр. / ${uProfileRightLm.toFixed(3)} лм</span></li>
                    <li>Хоризонтален профил U: <span>${horizontalUProfilePcs} бр. / ${horizontalUProfileLm.toFixed(2)} лм</span></li>
                    <li>Укрепващ профил: <span>${reinforcingProfilePcs} бр. / ${reinforcingProfileLm.toFixed(3)} лм</span></li>
                    <li>Заклепки: <span>${rivetsPcs} бр.</span></li>
                    <li>Самонарезни винтове: <span>${selfTappingScrewPcs} бр.</span></li>
                    <li>Тапи: <span>${dowelsPcs} бр.</span></li>
                    <li>Ъгъл: <span>${cornerPcs} бр.</span></li>
                </ul>
            `);
    
            $('#sigma-final-price').html(`<p>Крайна цена: €${discountedPriceEUR.toFixed(2)} / ${discountedPriceBGN} лв.</p>`);
    
        } catch (error) {
            console.error("An error occurred during calculations: ", error);
        }
    }
    

    // Add event listeners to all inputs
    widthInput.on('input', calculatePrice);
    heightInput.on('change', calculatePrice);
    panelsInput.on('input', calculatePrice);

    // Enable/disable add to cart button based on valid inputs
    $('input, select').on('input change', function() {
        if (areInputsValid()) {
            addToCartButton.prop('disabled', false);
        } else {
            addToCartButton.prop('disabled', true);
        }
    });

    // Updated predefined sizes click handler
    $('.main-product-sizes__item').on('click', function(e) {
        e.preventDefault();
        
        // Remove selected class from all items
        $('.main-product-sizes__item').removeClass('selected');
        // Add selected class to clicked item
        $(this).addClass('selected');
        
        // Get values from the clicked item
        const width = $(this).data('l');
        const height = $(this).data('h');
        const panels = $(this).data('panels') || 1;
        
        // Update form inputs
        widthInput.val(width);
        heightInput.val(height).trigger('change');
        panelsInput.val(panels);
        
        // Trigger calculation
        calculatePrice();
    });

    // Trigger initial calculation
    calculatePrice();
});