jQuery(document).ready(function ($) {  // Pass $ as parameter
    // Hide the results section by default
    $('#atlas-calculator-results').hide();

    $('.required-materials--toggle-link').on('click', function(e) {
                e.preventDefault();
                const resultsDiv = $(this).closest('.mt-8').find('[id$="calculator-results"]');
                const toggleIcon = $(this).find('.toggle-icon');
                resultsDiv.slideToggle(300, function() {
                    toggleIcon.text($(this).is(':visible') ? '-' : '+');
                });
            });

    // Get price elements
    const priceElement = $('.price');
    const addToCartButton = $('button.single_add_to_cart_button');

    // Input elements
    const widthInput = $('#atlas-panel-width');
    const heightInput = $('#atlas-panel-height');
    const panelsInput = $('#atlas-number-of-panels');

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
            let blindsProfilePcs = Math.max((panelHeight - 0.045) / 0.1 * numberOfPanels, 0);
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
            let totalPrice = parseFloat(atlas_pricing.base_price) * blindsProfileLm;
            //console.log('Base Price:', atlas_pricing.base_price, 'Blinds Profile Lm:', blindsProfileLm, 'Subtotal:', parseFloat(atlas_pricing.base_price) * blindsProfileLm);


           // Add essential components
            totalPrice += uProfileLeftLm * parseFloat(atlas_pricing.price_u_profile_left || 0);
            
            //console.log('uProfileLeftLm:', uProfileLeftLm, 'Price U Profile Left:', atlas_pricing.price_u_profile_left, 'Subtotal:', uProfileLeftLm * parseFloat(atlas_pricing.price_u_profile_left || 0));

            totalPrice += uProfileRightLm * parseFloat(atlas_pricing.price_u_profile_right || 0);
            //console.log('uProfileRightLm:', uProfileRightLm, 'Price U Profile Right:', atlas_pricing.price_u_profile_right, 'Subtotal:', uProfileRightLm * parseFloat(atlas_pricing.price_u_profile_right || 0));

            totalPrice += horizontalUProfileLm * parseFloat(atlas_pricing.price_u_horizontal_panel || 0);
            //console.log('horizontalUProfileLm:', horizontalUProfileLm, 'Price U Horizontal Panel:', atlas_pricing.price_u_horizontal_panel, 'Subtotal:', horizontalUProfileLm * parseFloat(atlas_pricing.price_u_horizontal_panel || 0));

            totalPrice += reinforcingProfileLm * parseFloat(atlas_pricing.price_reinforcing_profile || 0);
            //console.log('reinforcingProfileLm:', reinforcingProfileLm, 'Price Reinforcing Profile:', atlas_pricing.price_reinforcing_profile, 'Subtotal:', reinforcingProfileLm * parseFloat(atlas_pricing.price_reinforcing_profile || 0));

            totalPrice += rivetsPcs * parseFloat(atlas_pricing.price_rivets || 0);
            //console.log('rivetsPcs:', rivetsPcs, 'Price Rivets:', atlas_pricing.price_rivets, 'Subtotal:', rivetsPcs * parseFloat(atlas_pricing.price_rivets || 0));

            totalPrice += selfTappingScrewPcs * parseFloat(atlas_pricing.price_self_tapping_screw || 0);
            //console.log('selfTappingScrewPcs:', selfTappingScrewPcs, 'Price Self Tapping Screw:', atlas_pricing.price_self_tapping_screw, 'Subtotal:', selfTappingScrewPcs * parseFloat(atlas_pricing.price_self_tapping_screw || 0));

            totalPrice += dowelsPcs * parseFloat(atlas_pricing.price_dowels || 0);
            //console.log('dowelsPcs:', dowelsPcs, 'Price Dowels:', atlas_pricing.price_dowels, 'Subtotal:', dowelsPcs * parseFloat(atlas_pricing.price_dowels || 0));

            totalPrice += cornerPcs * parseFloat(atlas_pricing.price_corners || 0);
            //console.log('cornerPcs:', cornerPcs, 'Price Corners:', atlas_pricing.price_corners, 'Subtotal:', cornerPcs * parseFloat(atlas_pricing.price_corners || 0));

            //console.log('Total Price:', totalPrice);

            // Update displayed price
            priceElement.html(`<span class="woocommerce-Price-amount amount"> 
                <bdi>Крайна цена: ${totalPrice.toFixed(2)}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
            </span>`);

            // Update hidden fields with specific formatting
            $('#calculated_price').val(totalPrice.toFixed(2));
            $('#atlas_panel_width').val(formattedWidth);  // Will be like "1.80"
            $('#atlas_panel_height').val(formattedHeight); // Will be like "1.245"
            $('#atlas_number_of_panels').val(numberOfPanels);

            // Update button attributes with same formatting
            addToCartButton.attr('data-calculated-price', totalPrice.toFixed(2));
            addToCartButton.attr('data-panel-width', formattedWidth);
            addToCartButton.attr('data-panel-height', formattedHeight);
            addToCartButton.attr('data-panels', numberOfPanels);

            // Debug log
            // Material calculations debug
            console.log('Price Calculator Debug:', {
                inputs: {
                    width: panelWidth,
                    height: panelHeight,
                    panels: numberOfPanels,
                    formattedWidth: formattedWidth,
                    formattedHeight: formattedHeight
                },
                materialCalculations: {
                    blindsProfile: {
                        pieces: blindsProfilePcs,
                        formula: `(${panelHeight} - 0.045) / 0.1 * ${numberOfPanels}`,
                        linearMeters: blindsProfileLm,
                        formula_lm: `(${panelWidth} - 0.01) * ${blindsProfilePcs}`,
                        price: parseFloat(atlas_pricing.base_price) * blindsProfileLm
                    },
                    uProfileLeft: {
                        pieces: uProfileLeftPcs,
                        linearMeters: uProfileLeftLm,
                        formula: `${panelHeight} * ${numberOfPanels}`,
                        price: uProfileLeftLm * parseFloat(atlas_pricing.price_u_profile_left || 0)
                    },
                    uProfileRight: {
                        pieces: uProfileRightPcs,
                        linearMeters: uProfileRightLm,
                        formula: `${panelHeight} * ${numberOfPanels}`,
                        price: uProfileRightLm * parseFloat(atlas_pricing.price_u_profile_right || 0)
                    },
                    horizontalUProfile: {
                        pieces: horizontalUProfilePcs,
                        linearMeters: horizontalUProfileLm,
                        formula: `${panelWidth} * ${numberOfPanels}`,
                        price: horizontalUProfileLm * parseFloat(atlas_pricing.price_u_horizontal_panel || 0)
                    },
                    reinforcingProfile: {
                        pieces: reinforcingProfilePcs,
                        F20_condition: `Width ${panelWidth} -> F20 = ${F20}`,
                        linearMeters: reinforcingProfileLm,
                        formula: `${panelHeight} * ${F20} * ${numberOfPanels}`,
                        price: reinforcingProfileLm * parseFloat(atlas_pricing.price_reinforcing_profile || 0)
                    },
                    rivets: {
                        pieces: rivetsPcs,
                        formula: `F20=${F20} -> ${(blindsProfilePcs / numberOfPanels + 1) * (F20 == 0 ? 4 : F20 == 1 ? 5 : 6) * numberOfPanels} + (${F20} * 2)`,
                        price: rivetsPcs * parseFloat(atlas_pricing.price_rivets || 0)
                    },
                    selfTappingScrews: {
                        pieces: selfTappingScrewPcs,
                        formula: `${numberOfPanels} * 10`,
                        price: selfTappingScrewPcs * parseFloat(atlas_pricing.price_self_tapping_screw || 0)
                    },
                    dowels: {
                        pieces: dowelsPcs,
                        formula: `${numberOfPanels} * 10 + ${F20} * ${numberOfPanels}`,
                        price: dowelsPcs * parseFloat(atlas_pricing.price_dowels || 0)
                    },
                    corners: {
                        pieces: cornerPcs,
                        formula: `${F20} * ${numberOfPanels}`,
                        price: cornerPcs * parseFloat(atlas_pricing.price_corners || 0)
                    }
                },
                pricing: {
                    basePrice: atlas_pricing.base_price,
                    uProfileLeftPrice: atlas_pricing.price_u_profile_left,
                    uProfileRightPrice: atlas_pricing.price_u_profile_right,
                    horizontalUProfilePrice: atlas_pricing.price_u_horizontal_panel,
                    reinforcingProfilePrice: atlas_pricing.price_reinforcing_profile,
                    rivetsPrice: atlas_pricing.price_rivets,
                    selfTappingScrewPrice: atlas_pricing.price_self_tapping_screw,
                    dowelsPrice: atlas_pricing.price_dowels,
                    cornersPrice: atlas_pricing.price_corners
                },
                totalPrice: {
                    final: totalPrice.toFixed(2),
                    breakdown: {
                        blindsProfile: parseFloat(atlas_pricing.base_price) * blindsProfileLm,
                        uProfileLeft: uProfileLeftLm * parseFloat(atlas_pricing.price_u_profile_left || 0),
                        uProfileRight: uProfileRightLm * parseFloat(atlas_pricing.price_u_profile_right || 0),
                        horizontalUProfile: horizontalUProfileLm * parseFloat(atlas_pricing.price_u_horizontal_panel || 0),
                        reinforcingProfile: reinforcingProfileLm * parseFloat(atlas_pricing.price_reinforcing_profile || 0),
                        rivets: rivetsPcs * parseFloat(atlas_pricing.price_rivets || 0),
                        selfTappingScrews: selfTappingScrewPcs * parseFloat(atlas_pricing.price_self_tapping_screw || 0),
                        dowels: dowelsPcs * parseFloat(atlas_pricing.price_dowels || 0),
                        corners: cornerPcs * parseFloat(atlas_pricing.price_corners || 0)
                    }
                },
                hiddenFields: {
                    calculated_price: $('#calculated_price').val(),
                    atlas_panel_width: $('#atlas_panel_width').val(),
                    atlas_panel_height: $('#atlas_panel_height').val(),
                    atlas_number_of_panels: $('#atlas_number_of_panels').val()
                }
            });


            // When updating the results, check if they should be visible
            const resultsSection = $('#atlas-calculator-results');
            if (!resultsSection.hasClass('hidden')) {
                resultsSection.show();
            }

            // Update add to cart button data and hidden input
            addToCartButton.attr('data-calculated-price', totalPrice.toFixed(2));
            $('#calculated_price').val(totalPrice.toFixed(2));

            // Display materials
            $('#atlas-results').html(`
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

            $('#atlas-final-price').html(`<p>Крайна цена: ${totalPrice.toFixed(2)} лв.</p>`);

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