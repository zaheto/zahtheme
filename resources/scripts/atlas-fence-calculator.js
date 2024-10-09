jQuery(document).ready(function($) {
    // Set min and max for width input
    $('#atlas-panel-width').attr('min', atlasData.widthMin).attr('max', atlasData.widthMax);
    
    // Populate height dropdown
    var heightSelect = $('#atlas-panel-height');
    $.each(atlasData.heights, function(index, value) {
        heightSelect.append($('<option></option>').attr('value', value).text(value));
    });
    
    $('#atlas-calculate').click(function() {
        var panelWidth = parseFloat($('#atlas-panel-width').val());
        var panelHeight = parseFloat($('#atlas-panel-height').val());
        var numberOfPanels = parseInt($('#atlas-number-of-panels').val());

        // Calculate Blinds Profile
        var blindsProfilePcs = Math.max((panelHeight - 0.045) / 0.1 * numberOfPanels, 0);
        var blindsProfileLm = Math.max((panelWidth - 0.01) * blindsProfilePcs, 0);
        var blindsProfileCost = blindsProfileLm * parseFloat(atlasData.pricePerMeter);

        // Calculate U Profiles
        var uProfileLeftPcs = numberOfPanels;
        var uProfileLeftLm = panelHeight * numberOfPanels;
        var uProfileLeftCost = uProfileLeftLm * parseFloat(atlasData.priceULeft);

        var uProfileRightPcs = numberOfPanels;
        var uProfileRightLm = panelHeight * numberOfPanels;
        var uProfileRightCost = uProfileRightLm * parseFloat(atlasData.priceURight);

        // Calculate Horizontal U Profile
        var horizontalUProfilePcs = numberOfPanels;
        var horizontalUProfileLm = panelWidth * numberOfPanels;
        var horizontalUProfileCost = horizontalUProfileLm * parseFloat(atlasData.priceUHorizontal);

        // Calculate Reinforcing Profile
        var F20 = 0;
        if (panelWidth > 1.29 && panelWidth < 2.1) {
            F20 = 1;
        } else if (panelWidth > 2.09) {
            F20 = 2;
        }
        var reinforcingProfilePcs = F20;
        var reinforcingProfileLm = panelHeight * F20 * numberOfPanels;
        var reinforcingProfileCost = reinforcingProfileLm * parseFloat(atlasData.priceReinforcing);

        // Calculate Rivets
        var rivetsPcs = Math.ceil(((blindsProfilePcs / numberOfPanels + 1) * 4 + F20 * 2) * numberOfPanels / 100) * 100;
        var rivetsCost = rivetsPcs * parseFloat(atlasData.priceRivets);

        // Calculate Self-tapping Screws
        var selfTappingScrewPcs = numberOfPanels * 10;
        var selfTappingScrewCost = selfTappingScrewPcs * parseFloat(atlasData.priceSelfTapping);

        // Calculate Dowels
        var dowelsPcs = numberOfPanels * 10 + F20 * numberOfPanels;
        var dowelsCost = dowelsPcs * parseFloat(atlasData.priceDowels);

        // Calculate Corners
        var cornerPcs = F20 * numberOfPanels;
        var cornerCost = cornerPcs * parseFloat(atlasData.priceCorners);

        // Calculate total cost
        var totalCost = blindsProfileCost + uProfileLeftCost + uProfileRightCost + 
                        horizontalUProfileCost + reinforcingProfileCost + rivetsCost + 
                        selfTappingScrewCost + dowelsCost + cornerCost;

        // Display Results
        $('#atlas-results').html(`
            <p>Blinds Profile: ${blindsProfilePcs.toFixed(2)} Pcs, ${blindsProfileLm.toFixed(2)} lm, Cost: ${blindsProfileCost.toFixed(2)}</p>
            <p>U Profile Left: ${uProfileLeftPcs} Pcs, ${uProfileLeftLm.toFixed(3)} lm, Cost: ${uProfileLeftCost.toFixed(2)}</p>
            <p>U Profile Right: ${uProfileRightPcs} Pcs, ${uProfileRightLm.toFixed(3)} lm, Cost: ${uProfileRightCost.toFixed(2)}</p>
            <p>Horizontal U Profile: ${horizontalUProfilePcs} Pcs, ${horizontalUProfileLm.toFixed(2)} lm, Cost: ${horizontalUProfileCost.toFixed(2)}</p>
            <p>Reinforcing Profile: ${reinforcingProfilePcs} Pcs, ${reinforcingProfileLm.toFixed(3)} lm, Cost: ${reinforcingProfileCost.toFixed(2)}</p>
            <p>Rivets: ${rivetsPcs} Pcs, Cost: ${rivetsCost.toFixed(2)}</p>
            <p>Self-tapping Screws: ${selfTappingScrewPcs} Pcs, Cost: ${selfTappingScrewCost.toFixed(2)}</p>
            <p>Dowels: ${dowelsPcs} Pcs, Cost: ${dowelsCost.toFixed(2)}</p>
            <p>Corner: ${cornerPcs} Pcs, Cost: ${cornerCost.toFixed(2)}</p>
            <p>Total Cost: ${totalCost.toFixed(2)}</p>
        `);

        // Update product price
        updateProductPrice(totalCost);
    });

    function updateProductPrice(newPrice) {
        // Send an AJAX request to update the product price
        $.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: 'POST',
            data: {
                action: 'update_atlas_fence_price',
                price: newPrice,
                product_id: $('input[name="product_id"]').val()
            },
            success: function(response) {
                if(response.success) {
                    $('.price').html(response.data.price_html);
                }
            }
        });
    }
});