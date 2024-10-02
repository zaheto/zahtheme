jQuery(document).ready(function($) {
    $('#atlas-fence-form').on('submit', function(e) {
        e.preventDefault();
        var width = $('#fence_width').val();
        var height = $('#fence_height').val();
        var num_panels = $('#num_panels').val();

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
                $('#atlas-fence-price').html('Calculated Price: $' + response.data.price);
                // Update the add to cart button with the new price
                $('input[name="add-to-cart"]').val(response.data.price);
            }
        });
    });
});