jQuery(document).ready(function ($) {

    const exchangeRate = 1.95580;

    // Lookup table: Sheet1 Romb 4 ($K$3:$L$15) — exact match by height
    const ROMB_MAX_SLATS = {
        0.78: 3,  1.02: 4, 1.26: 5,  1.5: 6,   1.74: 7,
        1.98: 8,  2.22: 9, 2.46: 10, 2.7: 11,  2.94: 12, 3.18: 13
    };

    $('#romb-max-calculator-results').hide();

    $('.required-materials--toggle-link').off('click').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const resultsSection = $('#romb-max-calculator-results');
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

    $('#romb-max-calculator-results').on('click', function (e) { e.stopPropagation(); });
    $(document).off('click.rombMaxCalculator');

    const priceElement    = $('.inside-product--top-wrap .price');
    const addToCartButton = $('button.single_add_to_cart_button');
    const widthInput      = $('#romb-max-panel-width');
    const heightInput     = $('#romb-max-panel-height');
    const panelsInput     = $('#romb-max-number-of-panels');

    const selectedPredefinedSize = $('.main-product-sizes__item.selected');
    if (selectedPredefinedSize.length) {
        widthInput.val(selectedPredefinedSize.data('l'));
        heightInput.val(selectedPredefinedSize.data('h')).trigger('change');
        panelsInput.val(selectedPredefinedSize.data('panels') || 1);
    }

    function areInputsValid() {
        const width  = parseFloat(widthInput.val());
        const height = parseFloat(heightInput.val());
        const panels = parseInt(panelsInput.val());
        return !isNaN(width) && !isNaN(height) && !isNaN(panels) &&
               width >= parseFloat(widthInput.attr('min')) &&
               width <= parseFloat(widthInput.attr('max')) &&
               panels > 0;
    }

    function calculatePrice() {
        if (!areInputsValid()) return;

        const panelWidth     = parseFloat(widthInput.val());
        const panelHeight    = parseFloat(heightInput.val());
        const numberOfPanels = parseInt(panelsInput.val());

        try {
            const formattedWidth  = panelWidth.toFixed(2);
            const formattedHeight = (Math.floor(panelHeight * 1000) / 1000).toFixed(3);

            // Blinds Profile: VLOOKUP(height, Romb4 table) * panels
            const slatsPerPanel    = ROMB_MAX_SLATS[panelHeight] || 0;
            const blindsProfilePcs = slatsPerPanel * numberOfPanels;
            const blindsProfileLm  = Math.max((panelWidth - 0.01) * blindsProfilePcs, 0);

            const uProfileLeftPcs  = numberOfPanels;
            const uProfileLeftLm   = panelHeight * numberOfPanels;
            const uProfileRightPcs = numberOfPanels;
            const uProfileRightLm  = panelHeight * numberOfPanels;

            const uHorizPcs = numberOfPanels;
            const uHorizLm  = panelWidth * numberOfPanels;

            // Reinforcing Profile: IF(width > 1.69, panels, 0)
            const reinforcingPcs = (panelWidth > 1.69) ? numberOfPanels : 0;
            const reinforcingLm  = reinforcingPcs * panelHeight;

            // Rivets: IF(width < 1.7, 14*blindsPcs + 4*panels, 16*blindsPcs + 4)
            const rivetsPcs = panelWidth < 1.7
                ? 14 * blindsProfilePcs + 4 * numberOfPanels
                : 16 * blindsProfilePcs + 4;

            const selfTappingScrewPcs = numberOfPanels * 10;

            // Corner: = numberOfPanels always  (C24 = C17 = Horizontal U Profile pcs)
            const cornerPcs = numberOfPanels;

            // Dowels: 10*panels + corner = 11*panels
            const dowelsPcs = 10 * numberOfPanels + cornerPcs;

            // --- Price calculations ---
            let totalPriceEUR = parseFloat(romb_max_pricing.base_price) * blindsProfileLm;
            totalPriceEUR += uProfileLeftLm      * parseFloat(romb_max_pricing.price_u_profile_left      || 0);
            totalPriceEUR += uProfileRightLm     * parseFloat(romb_max_pricing.price_u_profile_right     || 0);
            totalPriceEUR += uHorizLm            * parseFloat(romb_max_pricing.price_u_horizontal_panel  || 0);
            totalPriceEUR += reinforcingLm       * parseFloat(romb_max_pricing.price_reinforcing_profile  || 0);
            totalPriceEUR += rivetsPcs           * parseFloat(romb_max_pricing.price_rivets              || 0);
            totalPriceEUR += selfTappingScrewPcs * parseFloat(romb_max_pricing.price_self_tapping_screw  || 0);
            totalPriceEUR += dowelsPcs           * parseFloat(romb_max_pricing.price_dowels              || 0);
            totalPriceEUR += cornerPcs           * parseFloat(romb_max_pricing.price_corners             || 0);

            let discountedPriceEUR = totalPriceEUR;
            if (romb_max_pricing.sale_price && romb_max_pricing.sale_price > 0 && romb_max_pricing.sale_price < romb_max_pricing.base_price) {
                discountedPriceEUR  = parseFloat(romb_max_pricing.sale_price) * blindsProfileLm;
                discountedPriceEUR += uProfileLeftLm      * parseFloat(romb_max_pricing.price_u_profile_left      || 0);
                discountedPriceEUR += uProfileRightLm     * parseFloat(romb_max_pricing.price_u_profile_right     || 0);
                discountedPriceEUR += uHorizLm            * parseFloat(romb_max_pricing.price_u_horizontal_panel  || 0);
                discountedPriceEUR += reinforcingLm       * parseFloat(romb_max_pricing.price_reinforcing_profile  || 0);
                discountedPriceEUR += rivetsPcs           * parseFloat(romb_max_pricing.price_rivets              || 0);
                discountedPriceEUR += selfTappingScrewPcs * parseFloat(romb_max_pricing.price_self_tapping_screw  || 0);
                discountedPriceEUR += dowelsPcs           * parseFloat(romb_max_pricing.price_dowels              || 0);
                discountedPriceEUR += cornerPcs           * parseFloat(romb_max_pricing.price_corners             || 0);
            }

            let discountPercentage = 0;
            if (totalPriceEUR > 0 && discountedPriceEUR > 0) {
                discountPercentage = Math.round(100 - (discountedPriceEUR / totalPriceEUR * 100));
            }
            $('.product-badge.sale').text(`-${discountPercentage}%`);

            const totalPriceBGN      = (Math.round(totalPriceEUR      * exchangeRate * 100) / 100).toFixed(2);
            const discountedPriceBGN = (Math.round(discountedPriceEUR * exchangeRate * 100) / 100).toFixed(2);

            $('#total_price').val(totalPriceEUR.toFixed(2));
            $('#discounted_price').val(discountedPriceEUR.toFixed(2));

            if (romb_max_pricing.sale_price && romb_max_pricing.sale_price > 0 && romb_max_pricing.sale_price < romb_max_pricing.base_price) {
                priceElement.html(`
                    <span class="woocommerce-Price-amount amount" data-bgn-converted="true">
                        <span class="regular-price-witheuro">
                            <del class="eur-regular eur-inline">€${totalPriceEUR.toFixed(2)}</del> /
                            <del><bdi>${totalPriceBGN}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi></del>
                        </span>
                        <span class="sale-price-witheuro">
                            <ins class="eur-sale eur-inline">€${discountedPriceEUR.toFixed(2)}</ins> /
                            <ins><bdi>${discountedPriceBGN}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi></ins>
                        </span>
                        <span class="custom-text-after-price">(вкл. ДДС)</span>
                    </span>
                `);
            } else {
                priceElement.html(`
                    <span class="woocommerce-Price-amount amount" data-bgn-converted="true">
                        <bdi>€${totalPriceEUR.toFixed(2)}&nbsp;<span class="woocommerce-Price-currencySymbol"></span></bdi> /
                        <bdi>${discountedPriceBGN}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
                        <span class="custom-text-after-price">(вкл. ДДС)</span>
                    </span>
                `);
            }

            $('#calculated_price').val(discountedPriceEUR.toFixed(2));
            $('#romb_max_panel_width').val(formattedWidth);
            $('#romb_max_panel_height').val(formattedHeight);
            $('#romb_max_number_of_panels').val(numberOfPanels);

            addToCartButton.attr('data-calculated-price', discountedPriceEUR.toFixed(2));
            addToCartButton.attr('data-panel-width', formattedWidth);
            addToCartButton.attr('data-panel-height', formattedHeight);
            addToCartButton.attr('data-panels', numberOfPanels);

            $('#romb-max-results').html(`
                <ul>
                    <li>Профил Жалюзи: <span>${blindsProfilePcs} бр. / ${blindsProfileLm.toFixed(2)} лм</span></li>
                    <li>Профил U отляво: <span>${uProfileLeftPcs} бр. / ${uProfileLeftLm.toFixed(3)} лм</span></li>
                    <li>Профил U отдясно: <span>${uProfileRightPcs} бр. / ${uProfileRightLm.toFixed(3)} лм</span></li>
                    <li>Хоризонтален профил U: <span>${uHorizPcs} бр. / ${uHorizLm.toFixed(2)} лм</span></li>
                    <li>Укрепващ профил: <span>${reinforcingPcs} бр. / ${reinforcingLm.toFixed(3)} лм</span></li>
                    <li>Заклепки: <span>${rivetsPcs} бр.</span></li>
                    <li>Самонарезни винтове: <span>${selfTappingScrewPcs} бр.</span></li>
                    <li>Ъгъл: <span>${cornerPcs} бр.</span></li>
                    <li>Тапи: <span>${dowelsPcs} бр.</span></li>
                </ul>
            `);

        } catch (error) {
            console.error('ROMB MAX calculator error:', error);
        }
    }

    widthInput.on('input', calculatePrice);
    heightInput.on('change', calculatePrice);
    panelsInput.on('input', calculatePrice);

    $('input, select').on('input change', function () {
        addToCartButton.prop('disabled', !areInputsValid());
    });

    $('.main-product-sizes__item').on('click', function (e) {
        e.preventDefault();
        $('.main-product-sizes__item').removeClass('selected');
        $(this).addClass('selected');
        widthInput.val($(this).data('l'));
        heightInput.val($(this).data('h')).trigger('change');
        panelsInput.val($(this).data('panels') || 1);
        calculatePrice();
    });

    calculatePrice();
});
