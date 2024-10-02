console.log('ATLAS Fence Calculator script loaded');

jQuery(document).ready(function($) {
    console.log('DOM ready, searching for ATLAS Fence Calculator form');
    var $form = $('.atlas-fence-calculator');
    console.log('Form found:', $form.length > 0);

    if ($form.length === 0) {
        console.error('ATLAS Fence Calculator form not found');
        return;
    }

    console.log('Attaching click event to calculate button');
    $('#calculate-price').on('click', function(e) {
        e.preventDefault();
        console.log('Calculate button clicked');
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
                    // Update the product price display
                    $('.price').html(response.data.price_html);
                    // Update hidden fields for add to cart
                    $('input[name="fence_width"]').val(width);
                    $('input[name="fence_height"]').val(height);
                    $('input[name="num_panels"]').val(num_panels);
                } else {
                    console.error('Error calculating price:', response.data);
                    alert('Error calculating price. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
                alert('Error calculating price. Please try again.');
            }
        });
    });

    console.log('ATLAS Fence Calculator initialization complete');
});