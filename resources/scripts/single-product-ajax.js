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
                // Get values from the actual form inputs, not hidden fields
                var calculatedPrice = document.getElementById('calculated_price').value;
                var panelWidth = document.querySelector('#atlas-panel-width').value;
                var panelHeight = document.querySelector('#atlas-panel-height').value;
                var numberOfPanels = document.querySelector('#atlas-number-of-panels').value;

                // Convert and validate values
                calculatedPrice = parseFloat(calculatedPrice) || 0;
                panelWidth = parseFloat(panelWidth) || 0;
                panelHeight = parseFloat(panelHeight) || 0;
                numberOfPanels = parseInt(numberOfPanels) || 0;

                // Append to formData with correct names matching PHP expectations
                formData.append('custom_price', calculatedPrice.toFixed(2));
                formData.append('atlas_panel_width', panelWidth.toFixed(2));
                formData.append('atlas_panel_height', panelHeight.toFixed(2));
                formData.append('atlas_number_of_panels', numberOfPanels);
            }

            // Handle Sigma product data
            if (parent_elem.classList.contains('product-tag-sigma')) {
                // Get values from the actual form inputs, not hidden fields
                var calculatedPrice = document.getElementById('calculated_price').value;
                var panelWidth = document.querySelector('#sigma-panel-width').value;
                var panelHeight = document.querySelector('#sigma-panel-height').value;
                var numberOfPanels = document.querySelector('#sigma-number-of-panels').value;

                // Convert and validate values
                calculatedPrice = parseFloat(calculatedPrice) || 0;
                panelWidth = parseFloat(panelWidth) || 0;
                panelHeight = parseFloat(panelHeight) || 0;
                numberOfPanels = parseInt(numberOfPanels) || 0;

                // Append to formData with correct names matching PHP expectations
                formData.append('custom_price', calculatedPrice.toFixed(2));
                formData.append('sigma_panel_width', panelWidth.toFixed(2));
                formData.append('sigma_panel_height', panelHeight.toFixed(2));
                formData.append('sigma_number_of_panels', numberOfPanels);
            }

            // Handle gamma product data
            if (parent_elem.classList.contains('product-tag-gamma')) {
                // Get values from the actual form inputs, not hidden fields
                var calculatedPrice = document.getElementById('calculated_price').value;
                var panelWidth = document.querySelector('#gamma-panel-width').value;
                var panelHeight = document.querySelector('#gamma-panel-height').value;
                var numberOfPanels = document.querySelector('#gamma-number-of-panels').value;

                // Convert and validate values
                calculatedPrice = parseFloat(calculatedPrice) || 0;
                panelWidth = parseFloat(panelWidth) || 0;
                panelHeight = parseFloat(panelHeight) || 0;
                numberOfPanels = parseInt(numberOfPanels) || 0;

                // Append to formData with correct names matching PHP expectations
                formData.append('custom_price', calculatedPrice.toFixed(2));
                formData.append('gamma_panel_width', panelWidth.toFixed(2));
                formData.append('gamma_panel_height', panelHeight.toFixed(2));
                formData.append('gamma_number_of_panels', numberOfPanels);
            }

            // Handle piramida product data
            if (parent_elem.classList.contains('product-tag-piramida')) {
                // Get values from the actual form inputs, not hidden fields
                var calculatedPrice = document.getElementById('calculated_price').value;
                var panelWidth = document.querySelector('#piramida-panel-width').value;
                var panelHeight = document.querySelector('#piramida-panel-height').value;
                var numberOfPanels = document.querySelector('#piramida-number-of-panels').value;

                // Convert and validate values
                calculatedPrice = parseFloat(calculatedPrice) || 0;
                panelWidth = parseFloat(panelWidth) || 0;
                panelHeight = parseFloat(panelHeight) || 0;
                numberOfPanels = parseInt(numberOfPanels) || 0;

                // Append to formData with correct names matching PHP expectations
                formData.append('custom_price', calculatedPrice.toFixed(2));
                formData.append('piramida_panel_width', panelWidth.toFixed(2));
                formData.append('piramida_panel_height', panelHeight.toFixed(2));
                formData.append('piramida_number_of_panels', numberOfPanels);
            }

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