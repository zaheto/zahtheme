// zah ajax add to cart js.

document.addEventListener('DOMContentLoaded', function() {
    var cart_forms = document.querySelectorAll('.summary form.cart');
    cart_forms.forEach(function(cart_form) {
        cart_form.addEventListener('submit', function(event) {
            var parent_elem = cart_form.closest('.product.type-product');
            if (!parent_elem) {
                return;
            }
            if (parent_elem.classList.contains('product-type-external') || parent_elem.classList.contains('product-type-subscription') || parent_elem.classList.contains('product-type-variable-subscription') || parent_elem.classList.contains('product-type-grouped') || parent_elem.classList.contains('product-type-mix-and-match')) {
                return;
            }
            event.preventDefault();

            var atc_elem = cart_form.querySelector('.single_add_to_cart_button');
            var formData = new FormData(cart_form);

            /* formData.append('action', 'zah_pdp_ajax_atc'); */

            if (atc_elem.value) {
                formData.append('add-to-cart', atc_elem.value);
            }
            
            // Handle Atlas product data
            if (parent_elem.classList.contains('product-tag-atlas')) {
                // Get hidden input values instead of form elements
                var calculatedPrice = document.getElementById('calculated_price').value;
                var panelWidth = document.getElementById('atlas-panel-width').value;
                var panelHeight = document.getElementById('atlas-panel-height').value;
                var numberOfPanels = document.getElementById('atlas-number-of-panels').value;

                // Only append if values exist
                if (calculatedPrice) formData.append('calculated_price', calculatedPrice);
                if (panelWidth) formData.append('atlas-panel-width', panelWidth);
                if (panelHeight) formData.append('atlas-panel-height', panelHeight);
                if (numberOfPanels) formData.append('atlas-number-of-panels', numberOfPanels);

                // Debug output
                console.log('Atlas Data:', {
                    price: calculatedPrice,
                    width: panelWidth,
                    height: panelHeight,
                    panels: numberOfPanels
                });
            }

            atc_elem.classList.remove('added');
            atc_elem.classList.remove('not-added');
            atc_elem.classList.add('loading');

            var wce_add_cart = new Event('adding_to_cart');
            document.body.dispatchEvent(wce_add_cart);

            

            fetch(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'zah_pdp_ajax_atc'), {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            }).then(function(resp) {
                return resp.json();
            }).then(function(resp) {
                if (!resp) {
                    return;
                }
                var cur_page = window.location.toString();
                cur_page = cur_page.replace('add-to-cart', 'added-to-cart');
                if (resp.error && resp.product_url) {
                    window.location = resp.product_url;
                    return;
                }
                atc_elem.classList.remove('loading');

                if (0 < resp.notices.indexOf('error')) {
                    document.body.insertAdjacentHTML('beforeend', resp.notices);
                    atc_elem.classList.add('not-added');
                } else {
                    atc_elem.classList.add('added');
                    document.querySelector('body').classList.toggle('drawer-open');
                    if (jQuery) {
                        jQuery(document.body).trigger('added_to_cart', [resp.fragments, resp.cart_hash, jQuery(atc_elem)]);
                    }
                    if ('undefined' === typeof wc_add_to_cart_params) {
                        var wc_fragment = new Event('wc_fragment_refresh');
                        document.body.dispatchEvent(wc_fragment);
                    }
                }
            });
        });
    });
});