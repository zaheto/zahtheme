jQuery(document).ready(function () {
    //console.log('Atlas Pricing Data:', atlas_pricing);

    // Add toggle functionality
    jQuery('.required-materials--toggle-link').on('click', function(e) {
        e.preventDefault();
        const resultsSection = jQuery('#atlas-calculator-results');
        const toggleIcon = jQuery(this).find('.toggle-icon');
        
        resultsSection.slideToggle(300, function() {
            // After animation completes, update the icon
            if (resultsSection.is(':visible')) {
                toggleIcon.text('-');
                jQuery(this).removeClass('hidden');
            } else {
                toggleIcon.text('+');
                jQuery(this).addClass('hidden');
            }
        });
    });

    // Get price elements
    const priceElement = jQuery('.price');
    const addToCartButton = jQuery('button.single_add_to_cart_button');

    // Input elements
    const widthInput = jQuery('#atlas-panel-width');
    const heightInput = jQuery('#atlas-panel-height');
    const panelsInput = jQuery('#atlas-number-of-panels');

    // Set default values
    widthInput.val(1.8);
    heightInput.find('option:first').prop('selected', true);
    panelsInput.val(1);

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

        //console.log('Base price:', atlas_pricing.base_price);
        //console.log('Number of panels:', numberOfPanels);

        try {
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

            //console.log('Starting price calculations:');
            
            // Price Calculations
            let totalPrice = parseFloat(atlas_pricing.base_price) * numberOfPanels;
            //console.log('After base price:', totalPrice);

            // Add essential components
            totalPrice += uProfileLeftLm * parseFloat(atlas_pricing.price_u_profile_left || 0);
            totalPrice += uProfileRightLm * parseFloat(atlas_pricing.price_u_profile_right || 0);
            totalPrice += horizontalUProfileLm * parseFloat(atlas_pricing.price_u_horizontal_panel || 0);
            totalPrice += reinforcingProfileLm * parseFloat(atlas_pricing.price_reinforcing_profile || 0);
            totalPrice += rivetsPcs * parseFloat(atlas_pricing.price_rivets || 0);
            // Update displayed price
            priceElement.html(`<span class="woocommerce-Price-amount amount"> 
                <bdi>Крайна цена: ${totalPrice.toFixed(2)}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
            </span>`);

              // When updating the results, check if they should be visible
                const resultsSection = jQuery('#atlas-calculator-results');
                if (!resultsSection.hasClass('hidden')) {
                    resultsSection.show();
                }

            // Update add to cart button data and hidden input
            addToCartButton.attr('data-calculated-price', totalPrice.toFixed(2));
            jQuery('#calculated_price').val(totalPrice.toFixed(2));

            // Display materials
            jQuery('#atlas-results').html(`
                <ul>
                    <li>Профил Жалюзи: <span>${blindsProfilePcs.toFixed(2)} бр. / ${blindsProfileLm.toFixed(2)} лм</span></li>
                    <li>Профил U отляво:  <span>${uProfileLeftPcs} бр. / ${uProfileLeftLm.toFixed(3)} лм</span></li>
                    <li>Профил U отдясно:  <span>${uProfileRightPcs} бр. / ${uProfileRightLm.toFixed(3)} лм</span></li>
                    <li>Хоризонтален профил U:  <span>${horizontalUProfilePcs} бр. / ${horizontalUProfileLm.toFixed(2)} лм</span></li>
                    <li>Укрепващ профил:  <span>${reinforcingProfilePcs} бр. / ${reinforcingProfileLm.toFixed(3)} лм</span></li>
                    <li>Заклепки:  <span>${rivetsPcs} бр.</span></li>
                    <li>Самонарезни винтове:  <span>${selfTappingScrewPcs} бр.</span></li>
                    <li>Тапи:  <span>${dowelsPcs} бр.</span></li>
                    <li>Ъгъл:  <span>${cornerPcs} бр.</span></li>
                </ul>
            `);

            jQuery('#atlas-final-price').html(`<p>Крайна цена: ${totalPrice.toFixed(2)} лв.</p>`);

        } catch (error) {
            console.error("An error occurred during calculations: ", error);
        }
    }

    // Add event listeners to all inputs
    widthInput.on('input', calculatePrice);
    heightInput.on('change', calculatePrice);
    panelsInput.on('input', calculatePrice);

    // Enable/disable add to cart button based on valid inputs
    jQuery('input, select').on('input change', function() {
        if (areInputsValid()) {
            addToCartButton.prop('disabled', false);
        } else {
            addToCartButton.prop('disabled', true);
        }
    });

    // Updated predefined sizes click handler
    jQuery('.main-product-sizes__item').on('click', function(e) {
        e.preventDefault();
        
        // Remove selected class from all items
        jQuery('.main-product-sizes__item').removeClass('selected');
        // Add selected class to clicked item
        jQuery(this).addClass('selected');
        
        // Get the width and height from data attributes
        const width = jQuery(this).data('l');
        const height = jQuery(this).data('h');
        
        // Update form inputs
        widthInput.val(width);
        
        // Find and select the matching height option
        heightInput.val(height);
        
        // Trigger calculation
        calculatePrice();
    });

    // Trigger initial calculation with default values
    calculatePrice();
});