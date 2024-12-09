jQuery(document).ready(function ($) {  // Pass $ as parameter
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

    // Main calculation function
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

            // Updated Rivets calculation
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
            let totalPrice = parseFloat(sigma_pricing.base_price) * blindsProfileLm;
            //console.log('Base Price:', sigma_pricing.base_price, 'Blinds Profile Lm:', blindsProfileLm, 'Subtotal:', parseFloat(sigma_pricing.base_price) * blindsProfileLm);


           // Add essential components
            totalPrice += uProfileLeftLm * parseFloat(sigma_pricing.price_u_profile_left || 0);
            //console.log('uProfileLeftLm:', uProfileLeftLm, 'Price U Profile Left:', sigma_pricing.price_u_profile_left, 'Subtotal:', uProfileLeftLm * parseFloat(sigma_pricing.price_u_profile_left || 0));

            totalPrice += uProfileRightLm * parseFloat(sigma_pricing.price_u_profile_right || 0);
            //console.log('uProfileRightLm:', uProfileRightLm, 'Price U Profile Right:', sigma_pricing.price_u_profile_right, 'Subtotal:', uProfileRightLm * parseFloat(sigma_pricing.price_u_profile_right || 0));

            totalPrice += horizontalUProfileLm * parseFloat(sigma_pricing.price_u_horizontal_panel || 0);
            //console.log('horizontalUProfileLm:', horizontalUProfileLm, 'Price U Horizontal Panel:', sigma_pricing.price_u_horizontal_panel, 'Subtotal:', horizontalUProfileLm * parseFloat(sigma_pricing.price_u_horizontal_panel || 0));

            totalPrice += reinforcingProfileLm * parseFloat(sigma_pricing.price_reinforcing_profile || 0);
            //console.log('reinforcingProfileLm:', reinforcingProfileLm, 'Price Reinforcing Profile:', sigma_pricing.price_reinforcing_profile, 'Subtotal:', reinforcingProfileLm * parseFloat(sigma_pricing.price_reinforcing_profile || 0));

            totalPrice += rivetsPcs * parseFloat(sigma_pricing.price_rivets || 0);
            //console.log('rivetsPcs:', rivetsPcs, 'Price Rivets:', sigma_pricing.price_rivets, 'Subtotal:', rivetsPcs * parseFloat(sigma_pricing.price_rivets || 0));

            totalPrice += selfTappingScrewPcs * parseFloat(sigma_pricing.price_self_tapping_screw || 0);
            //console.log('selfTappingScrewPcs:', selfTappingScrewPcs, 'Price Self Tapping Screw:', sigma_pricing.price_self_tapping_screw, 'Subtotal:', selfTappingScrewPcs * parseFloat(sigma_pricing.price_self_tapping_screw || 0));

            totalPrice += dowelsPcs * parseFloat(sigma_pricing.price_dowels || 0);
            //console.log('dowelsPcs:', dowelsPcs, 'Price Dowels:', sigma_pricing.price_dowels, 'Subtotal:', dowelsPcs * parseFloat(sigma_pricing.price_dowels || 0));

            totalPrice += cornerPcs * parseFloat(sigma_pricing.price_corners || 0);
            //console.log('cornerPcs:', cornerPcs, 'Price Corners:', sigma_pricing.price_corners, 'Subtotal:', cornerPcs * parseFloat(sigma_pricing.price_corners || 0));

            //console.log('Total Price:', totalPrice);

            // Update displayed price
            priceElement.html(`<span class="woocommerce-Price-amount amount"> 
                <bdi>Крайна цена: ${totalPrice.toFixed(2)}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
            </span>`);

            // Update hidden fields with specific formatting
            $('#calculated_price').val(totalPrice.toFixed(2));
            $('#sigma_panel_width').val(formattedWidth);  // Will be like "1.80"
            $('#sigma_panel_height').val(formattedHeight); // Will be like "1.245"
            $('#sigma_number_of_panels').val(numberOfPanels);

            // Update button attributes with same formatting
            addToCartButton.attr('data-calculated-price', totalPrice.toFixed(2));
            addToCartButton.attr('data-panel-width', formattedWidth);
            addToCartButton.attr('data-panel-height', formattedHeight);
            addToCartButton.attr('data-panels', numberOfPanels);

            // Debug log
            // console.log('Calculator Values Updated sigma:', {
            //     price: totalPrice.toFixed(2),
            //     width: formattedWidth,
            //     height: formattedHeight,
            //     panels: numberOfPanels,
            //     hiddenFields: {
            //         calculated_price: $('#calculated_price').val(),
            //         sigma_panel_width: $('#sigma_panel_width').val(),
            //         sigma_panel_height: $('#sigma_panel_height').val(),
            //         sigma_number_of_panels: $('#sigma_number_of_panels').val()
            //     }
            // });


            // When updating the results, check if they should be visible
            const resultsSection = $('#sigma-calculator-results');
            if (!resultsSection.hasClass('hidden')) {
                resultsSection.show();
            }

            // Update add to cart button data and hidden input
            addToCartButton.attr('data-calculated-price', totalPrice.toFixed(2));
            $('#calculated_price').val(totalPrice.toFixed(2));

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

            $('#sigma-final-price').html(`<p>Крайна цена: ${totalPrice.toFixed(2)} лв.</p>`);

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