jQuery(document).ready(function ($) {
    // EUR to BGN conversion rate (1 EUR = 1.95470 BGN)
    const exchangeRate = 1.95580;

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

    function calculatePrice() {
        const width = parseFloat(widthInput.val());
        const panelNumber = parseInt(panelNumberInput.val());

        if (!width || !panelNumber) return;

        try {
            const sqm = parseFloat(siding_pricing.panel_siding_sqm);
            const useful = parseFloat(siding_pricing.panel_siding_useful);
            const basePriceEUR = parseFloat(siding_pricing.base_price); // Base price is in EUR

            // Calculate areas
            const totalArea = width * panelNumber * sqm;
            const usableArea = width * panelNumber * useful;

            // Calculate total price in EUR
            const totalPriceEUR = totalArea * basePriceEUR;

            // Convert to BGN
            const totalPriceBGN = totalPriceEUR * exchangeRate;

            // Calculate discounted price if a sale price exists and is valid
            let discountedPriceEUR = totalPriceEUR;
            let discountedPriceBGN = totalPriceBGN;

            if (siding_pricing.sale_price &&
                parseFloat(siding_pricing.sale_price) > 0 &&
                parseFloat(siding_pricing.sale_price) < parseFloat(siding_pricing.base_price)) {

                discountedPriceEUR = totalArea * parseFloat(siding_pricing.sale_price);
                discountedPriceBGN = discountedPriceEUR * exchangeRate;
            }

            // Calculate the discount percentage
            let discountPercentage = 0;
            if (totalPriceEUR > 0 && discountedPriceEUR < totalPriceEUR) {
                discountPercentage = Math.round(100 - (discountedPriceEUR / totalPriceEUR * 100));
            }

            // Update the discount badge
            $('.product-badge.sale').text(`-${discountPercentage}%`);

            // Set hidden fields for total_price and discounted_price (store in EUR since store currency is EUR)
            $('#total_price').val(totalPriceEUR.toFixed(2));
            $('#discounted_price').val(discountedPriceEUR.toFixed(2));

            // Update displayed price - EUR as main currency, BGN as secondary
            if (siding_pricing.sale_price &&
                parseFloat(siding_pricing.sale_price) > 0 &&
                parseFloat(siding_pricing.sale_price) < parseFloat(siding_pricing.base_price)) {

                priceElement.html(`
                    <span class="woocommerce-Price-amount amount" data-bgn-converted="true">
                        <span class="regular-price-witheuro">
                            <del class="eur-regular">€${totalPriceEUR.toFixed(2)}</del> / <del>
                                <bdi>${totalPriceBGN.toFixed(2)}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
                            </del>
                        </span>
                        <span class="sale-price-witheuro">
                            <ins class="eur-sale">€${discountedPriceEUR.toFixed(2)}</ins> / <ins>
                                <bdi>${discountedPriceBGN.toFixed(2)}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
                            </ins>
                        </span>
                        <span class="custom-text-after-price">(вкл. ДДС)</span>
                    </span>
                `);
            } else {
                priceElement.html(`
                    <span class="woocommerce-Price-amount amount" data-bgn-converted="true">
                        <bdi>€${totalPriceEUR.toFixed(2)}</bdi> / <bdi>${totalPriceBGN.toFixed(2)}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
                        <span class="custom-text-after-price">(вкл. ДДС)</span>
                    </span>
                `);
            }

            // Immediately remove any BGN conversion spans that might have been added
            setTimeout(() => {
                priceElement.find('.amount-bgn').remove();
            }, 0);

            // Update hidden fields and button attributes (store EUR price for cart since store currency is EUR)
            $('#calculated_price').val(discountedPriceEUR.toFixed(2));
            $('#siding_width').val(width.toFixed(2));
            $('#siding_panel_number').val(panelNumber);

            // Update button data attributes
            addToCartButton.attr('data-calculated-price', discountedPriceEUR.toFixed(2));
            addToCartButton.attr('data-width', width.toFixed(2));
            addToCartButton.attr('data-panels', panelNumber);

            // Update results
            $('#siding-results .total-area').html(`<p>Обща площ: ${totalArea.toFixed(2)} m²</p>`);
            $('#siding-results .usable-area').html(`<p>Полезна площ: ${usableArea.toFixed(2)} m²</p>`);
            $('#siding-results .final-price').html(`<p>Крайна цена: €${discountedPriceEUR.toFixed(2)} / ${discountedPriceBGN.toFixed(2)} лв.</p>`);

        } catch (error) {
            console.error("An error occurred during calculations: ", error);
        }
    }

    // Add event listeners
    widthInput.on('input', calculatePrice);
    panelNumberInput.on('input', calculatePrice);

    // Initial calculation
    calculatePrice();

    // Aggressively remove any BGN conversion that the plugin might add
    const removeBGNConversion = () => {
        $('.price .amount-bgn').remove();
        // Also mark the price element as converted to prevent the plugin from adding conversion
        $('.price .woocommerce-Price-amount.amount').attr('data-bgn-converted', 'true');
    };

    // Watch for changes and remove BGN conversions immediately
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === 1 && ($(node).hasClass('amount-bgn') || $(node).find('.amount-bgn').length)) {
                    $(node).remove();
                    removeBGNConversion();
                }
            });
        });
    });

    if (priceElement.length) {
        observer.observe(priceElement[0], {
            childList: true,
            subtree: true
        });
    }

    // Run cleanup periodically to catch any stragglers
    setInterval(removeBGNConversion, 100);

    // Also remove immediately on load
    setTimeout(removeBGNConversion, 10);
    setTimeout(removeBGNConversion, 100);
    setTimeout(removeBGNConversion, 500);
});