jQuery(document).ready(function ($) {
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
            // Debug: Log the siding_pricing object
            // //console.log('siding_pricing:', siding_pricing);
            // //console.log('Sale Price:', siding_pricing.sale_price);
            // //console.log('Base Price:', siding_pricing.base_price);
    
            const sqm = parseFloat(siding_pricing.panel_siding_sqm);
            const useful = parseFloat(siding_pricing.panel_siding_useful);
            const basePrice = parseFloat(siding_pricing.base_price);
    
            // Calculate areas
            const totalArea = width * panelNumber * sqm;
            const usableArea = width * panelNumber * useful;
            
            // Calculate total price (regular price)
            const totalPrice = totalArea * basePrice;
    
            // Calculate discounted price if a sale price exists and is valid
            let discountedPrice = totalPrice;
            if (siding_pricing.sale_price && 
                parseFloat(siding_pricing.sale_price) > 0 && 
                parseFloat(siding_pricing.sale_price) < parseFloat(siding_pricing.base_price)) {
                
                discountedPrice = totalArea * parseFloat(siding_pricing.sale_price);
            }
    
            // Debug: Log the prices
            // //console.log('Total Price:', totalPrice);
            // //console.log('Discounted Price:', discountedPrice);
    
            // Calculate the discount percentage
            let discountPercentage = 0;
            if (totalPrice > 0 && discountedPrice < totalPrice) {
                discountPercentage = Math.round(100 - (discountedPrice / totalPrice * 100));
                //console.log('Discount Percentage:', discountPercentage + '%');
            }
    
            // Update the discount badge
            $('.product-badge.sale').text(`-${discountPercentage}%`);
    
            // Set hidden fields for total_price and discounted_price
            $('#total_price').val(totalPrice.toFixed(2));
            $('#discounted_price').val(discountedPrice.toFixed(2));
    
            // Update displayed price
            if (siding_pricing.sale_price && 
                parseFloat(siding_pricing.sale_price) > 0 && 
                parseFloat(siding_pricing.sale_price) < parseFloat(siding_pricing.base_price)) {
                
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
                priceElement.html(`
                    <span class="woocommerce-Price-amount amount">
                        <bdi>${totalPrice.toFixed(2)}&nbsp;<span class="woocommerce-Price-currencySymbol">лв.</span></bdi>
                        <span class="custom-text-after-price">(вкл. ДДС)</span>
                    </span>
                `);
            }
    
            // Update hidden fields and button attributes
            $('#calculated_price').val(discountedPrice.toFixed(2));
            $('#siding_width').val(width.toFixed(2));
            $('#siding_panel_number').val(panelNumber);
    
            // Update button data attributes
            addToCartButton.attr('data-calculated-price', discountedPrice.toFixed(2));
            addToCartButton.attr('data-width', width.toFixed(2));
            addToCartButton.attr('data-panels', panelNumber);
    
            // Update results
            $('#siding-results .total-area').html(`<p>Обща площ: ${totalArea.toFixed(2)} m²</p>`);
            $('#siding-results .usable-area').html(`<p>Полезна площ: ${usableArea.toFixed(2)} m²</p>`);
            $('#siding-results .final-price').html(`<p>Крайна цена: ${discountedPrice.toFixed(2)} лв.</p>`);
    
        } catch (error) {
            console.error("An error occurred during calculations: ", error);
        }
    }

    // Add event listeners
    widthInput.on('input', calculatePrice);
    panelNumberInput.on('input', calculatePrice);

    // Initial calculation
    calculatePrice();
});