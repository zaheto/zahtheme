// resources/scripts/calculator.js

jQuery(document).ready(function($) {
    // Get elements
    const priceElement = $('.price');
    const addToCartButton = $('button.single_add_to_cart_button');
    const widthInput = $('#atlas-panel-width');
    const heightInput = $('#atlas-panel-height');
    const panelsInput = $('#atlas-number-of-panels');

    // Initialize toggle functionality
    $('.required-materials--toggle-link').on('click', function(e) {
        e.preventDefault();
        const resultsSection = $('#atlas-calculator-results');
        const toggleIcon = $(this).find('.toggle-icon');
        
        resultsSection.slideToggle(300, function() {
            if (resultsSection.is(':visible')) {
                toggleIcon.text('-');
                $(this).removeClass('hidden');
            } else {
                toggleIcon.text('+');
                $(this).addClass('hidden');
            }
        });
    });

    // Validation function
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

        try {
            const panelWidth = parseFloat(widthInput.val());
            const panelHeight = parseFloat(heightInput.val());
            const numberOfPanels = parseInt(panelsInput.val());

            // Material calculations
            const blindsProfilePcs = Math.max((panelHeight - 0.045) / 0.1 * numberOfPanels, 0);
            const blindsProfileLm = Math.max((panelWidth - 0.01) * blindsProfilePcs, 0);

            const uProfileLeftPcs = numberOfPanels;
            const uProfileLeftLm = panelHeight * numberOfPanels;

            const uProfileRightPcs = numberOfPanels;
            const uProfileRightLm = panelHeight * numberOfPanels;

            const horizontalUProfilePcs = numberOfPanels;
            const horizontalUProfileLm = panelWidth * numberOfPanels;

            // Calculate Reinforcing Profile
            let F20 = 0;
            if (panelWidth > 1.29 && panelWidth < 2.1) {
                F20 = 1;
            } else if (panelWidth > 2.09) {
                F20 = 2;
            }
            const reinforcingProfilePcs = F20;
            const reinforcingProfileLm = panelHeight * F20 * numberOfPanels;

            // Calculate Rivets
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

            const selfTappingScrewPcs = numberOfPanels * 10;
            const dowelsPcs = numberOfPanels * 10 + F20 * numberOfPanels;
            const cornerPcs = F20 * numberOfPanels;

            // Price Calculations
            let totalPrice = parseFloat(zahCalculatorData.pricing.base_price || 0) * numberOfPanels;
            
            // Add component prices
            totalPrice += uProfileLeftLm * parseFloat(zahCalculatorData.pricing.price_u_profile_left || 0);
            totalPrice += uProfileRightLm * parseFloat(zahCalculatorData.pricing.price_u_profile_right || 0);
            totalPrice += horizontalUProfileLm * parseFloat(zahCalculatorData.pricing.price_u_horizontal_panel || 0);
            totalPrice += reinforcingProfileLm * parseFloat(zahCalculatorData.pricing.price_reinforcing_profile || 0);
            totalPrice += rivetsPcs * parseFloat(zahCalculatorData.pricing.price_rivets || 0);

            // Update displayed price
            priceElement.html(`
                <span class="woocommerce-Price-amount amount">
                    <bdi>Крайна цена: ${totalPrice.toFixed(2)}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
                </span>
            `);

            // Show results if section is not hidden
            const resultsSection = $('#atlas-calculator-results');
            if (!resultsSection.hasClass('hidden')) {
                resultsSection.show();
            }

            // Update WooCommerce data
            addToCartButton.attr('data-calculated-price', totalPrice.toFixed(2));
            $('#calculated_price').val(totalPrice.toFixed(2));

            // Update materials list
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

        } catch (error) {
            console.error("Calculation error:", error);
        }
    }

    // Handle predefined sizes
    $('.main-product-sizes__item').on('click', function(e) {
        e.preventDefault();
        
        $('.main-product-sizes__item').removeClass('selected');
        $(this).addClass('selected');
        
        const width = $(this).data('l');
        const height = $(this).data('h');
        
        widthInput.val(width);
        heightInput.val(height);
        
        calculatePrice();
    });

    // Add event listeners for auto-calculation
    widthInput.on('input', calculatePrice);
    heightInput.on('change', calculatePrice);
    panelsInput.on('input', calculatePrice);

    // Update add to cart button state
    $('input, select').on('input change', function() {
        addToCartButton.prop('disabled', !areInputsValid());
    });

    // Set initial values and calculate
    widthInput.val(widthInput.val() || '1.8');
    heightInput.find('option:first').prop('selected', true);
    panelsInput.val(panelsInput.val() || '1');
    
    // Initial calculation
    calculatePrice();
});