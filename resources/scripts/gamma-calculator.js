jQuery(document).ready(function ($) {  // Pass $ as parameter
    // Hide the results section by default
    $('#gamma-calculator-results').hide();

    $('.required-materials--toggle-link').on('click', function(e) {
        e.preventDefault();
        const resultsSection = $('#gamma-calculator-results');
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
    const priceElement = $('.inside-product--top-wrap .price');
    const addToCartButton = $('button.single_add_to_cart_button');

    // Input elements
    const widthInput = $('#gamma-panel-width');
    const heightInput = $('#gamma-panel-height');
    const panelsInput = $('#gamma-number-of-panels');

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
            // Debug: Log the gamma_pricing object
            console.log('gamma_pricing:', gamma_pricing);
    
            // Debug: Log the sale price
            console.log('Sale Price:', gamma_pricing.sale_price);
    
            // Debug: Log the base price
            console.log('Base Price:', gamma_pricing.base_price);

            // Format width to exactly 2 decimal places
            const formattedWidth = panelWidth.toFixed(2);
            const formattedHeight = (Math.floor(panelHeight * 1000) / 1000).toFixed(3);
            
            // Material calculations
            let blindsProfilePcs = Math.max((panelHeight - 0.05) / 0.16 * numberOfPanels, 0);
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
    
            // Price Calculations
            let basePrice = parseFloat(gamma_pricing.base_price) * blindsProfileLm;
            let totalPrice = basePrice;
    
            // Add essential components
            totalPrice += uProfileLeftLm * parseFloat(gamma_pricing.price_u_profile_left || 0);
            totalPrice += uProfileRightLm * parseFloat(gamma_pricing.price_u_profile_right || 0);
            totalPrice += horizontalUProfileLm * parseFloat(gamma_pricing.price_u_horizontal_panel || 0);
            totalPrice += reinforcingProfileLm * parseFloat(gamma_pricing.price_reinforcing_profile || 0);
            totalPrice += rivetsPcs * parseFloat(gamma_pricing.price_rivets || 0);
            totalPrice += selfTappingScrewPcs * parseFloat(gamma_pricing.price_self_tapping_screw || 0);
            totalPrice += dowelsPcs * parseFloat(gamma_pricing.price_dowels || 0);
            totalPrice += cornerPcs * parseFloat(gamma_pricing.price_corners || 0);
    
            // Calculate the discounted price if a sale price exists and is valid
            let discountedPrice = totalPrice;
            if (gamma_pricing.sale_price && 
                parseFloat(gamma_pricing.sale_price) > 0 && 
                parseFloat(gamma_pricing.sale_price) < parseFloat(gamma_pricing.base_price)) {
                
                // Start with the base blinds profile calculation using sale price
                discountedPrice = parseFloat(gamma_pricing.sale_price) * blindsProfileLm;
                
                // Add the rest of the components (these aren't discounted)
                discountedPrice += uProfileLeftLm * parseFloat(gamma_pricing.price_u_profile_left || 0);
                discountedPrice += uProfileRightLm * parseFloat(gamma_pricing.price_u_profile_right || 0);
                discountedPrice += horizontalUProfileLm * parseFloat(gamma_pricing.price_u_horizontal_panel || 0);
                discountedPrice += reinforcingProfileLm * parseFloat(gamma_pricing.price_reinforcing_profile || 0);
                discountedPrice += rivetsPcs * parseFloat(gamma_pricing.price_rivets || 0);
                discountedPrice += selfTappingScrewPcs * parseFloat(gamma_pricing.price_self_tapping_screw || 0);
                discountedPrice += dowelsPcs * parseFloat(gamma_pricing.price_dowels || 0);
                discountedPrice += cornerPcs * parseFloat(gamma_pricing.price_corners || 0);
            }

            // Add these debug logs right before the discount calculation
            console.log('Sale price type:', typeof gamma_pricing.sale_price);
            console.log('Base price type:', typeof gamma_pricing.base_price);
            console.log('Sale price as float:', parseFloat(gamma_pricing.sale_price));
            console.log('Base price as float:', parseFloat(gamma_pricing.base_price));
            console.log('Is sale price valid?', 
                parseFloat(gamma_pricing.sale_price) > 0 && 
                parseFloat(gamma_pricing.sale_price) < parseFloat(gamma_pricing.base_price)
            );

            // Debug: Log the total and discounted prices
            console.log('Total Price:', totalPrice);
            console.log('Discounted Price:', discountedPrice);
    
            // Calculate the discount percentage
            let discountPercentage = 0;
            if (totalPrice > 0 && discountedPrice < totalPrice) {  // Changed condition
                discountPercentage = Math.round(100 - (discountedPrice / totalPrice * 100));
                console.log('Discount Percentage:', discountPercentage + '%');
            }
    
            // Update the discount badge
            $('.product-badge.sale').text(`-${discountPercentage}%`);
    
            // Set hidden fields for total_price and discounted_price
            $('#total_price').val(totalPrice.toFixed(2));
            $('#discounted_price').val(discountedPrice.toFixed(2));
    
            // In your gamma-calculator.js, find this section and replace it:
            // Update displayed price
            if (gamma_pricing.sale_price && 
                parseFloat(gamma_pricing.sale_price) > 0 && 
                parseFloat(gamma_pricing.sale_price) < parseFloat(gamma_pricing.base_price)) {
                
                console.log('Entering discount price display condition'); // Add this debug log
                
                priceElement.html(`
                    <span class="woocommerce-Price-amount amount">
                        <del>
                            <bdi>${totalPrice.toFixed(2)}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
                        </del>
                        <ins>
                            <bdi>${discountedPrice.toFixed(2)}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
                        </ins>
                        <span class="custom-text-after-price">(вкл. ДДС)</span>
                    </span>
                `);
            } else {
                console.log('Using regular price display'); // Add this debug log
                
                priceElement.html(`
                    <span class="woocommerce-Price-amount amount">
                        <bdi>${totalPrice.toFixed(2)}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
                        <span class="custom-text-after-price">(вкл. ДДС)</span>
                    </span>
                `);
            }
    
            // Update hidden fields and button attributes
            $('#calculated_price').val(discountedPrice.toFixed(2));
            $('#gamma_panel_width').val(formattedWidth);
            $('#gamma_panel_height').val(formattedHeight);
            $('#gamma_number_of_panels').val(numberOfPanels);
    
            addToCartButton.attr('data-calculated-price', discountedPrice.toFixed(2));
            addToCartButton.attr('data-panel-width', formattedWidth);
            addToCartButton.attr('data-panel-height', formattedHeight);
            addToCartButton.attr('data-panels', numberOfPanels);
    
            // Display materials
            $('#gamma-results').html(`
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
    
            $('#gamma-final-price').html(`<p>Крайна цена: ${discountedPrice.toFixed(2)} лв.</p>`);
    
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
         // Add this part to dynamically update the badge text
         $('.product-badge.sale').each(function () {
            const totalPrice = $(this).data('total-price');
            const discountedPrice = $(this).data('discounted-price');
            const discountPercentage = $(this).data('discount-percentage');

            // Update the badge text dynamically
            if (discountPercentage > 0) {
                $(this).html(`-${discountPercentage}%`);
                console.log(`Discount Badge Updated: -${discountPercentage}%`);
            }
        });
    });

    // Trigger initial calculation
    calculatePrice();
});