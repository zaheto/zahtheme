console.log('ATLAS Fence Calculator script loaded');

jQuery(document).ready(function($) {
    console.log('DOM ready, searching for ATLAS Fence Calculator form');
    var $form = $('#atlas-fence-form');
    console.log('Form found:', $form.length > 0);

    if ($form.length === 0) {
        console.error('ATLAS Fence Calculator form not found');
        return;
    }

    console.log('Attaching submit event to form');
    $form.on('submit', function(e) {
        e.preventDefault();
        console.log('Form submitted');
        var width = $('#fence_width').val();
        var height = $('#fence_height').val();
        var num_panels = $('#num_panels').val();

        console.log('Sending AJAX request with data:', {
            width: width,
            height: height,
            num_panels: num_panels,
            product_id: $('input[name="add-to-cart"]').val()
        });

        $.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: 'POST',
            data: {
                action: 'calculate_atlas_fence_price',
                width: width,
                height: height,
                num_panels: num_panels,
                product_id: $('input[name="add-to-cart"]').val()
            },
            success: function(response) {
                console.log('AJAX response received:', response);
                if (response.success) {
                    $('#atlas-fence-price').html('Calculated Price: $' + response.data.price);
                    // Update the add to cart button with the new price
                    $('input[name="add-to-cart"]').val(response.data.price);
                } else {
                    console.error('Error calculating price:', response.data);
                    $('#atlas-fence-price').html('Error calculating price. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
                $('#atlas-fence-price').html('Error calculating price. Please try again.');
            }
        });
    });

    console.log('ATLAS Fence Calculator initialization complete');
});