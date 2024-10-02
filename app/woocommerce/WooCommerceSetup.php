<?php

namespace App\WooCommerce;

class WooCommerceSetup {
    public function __construct() {
        error_log("WooCommerceSetup constructor called");
        add_action('init', [$this, 'registerCustomAtlasFenceProductType']);
        add_filter('product_type_selector', [$this, 'addCustomAtlasFenceProductType']);
        add_filter('woocommerce_product_class', [$this, 'getCustomAtlasFenceProductClass'], 10, 2);
        add_action('admin_footer', [$this, 'customAtlasFenceCustomJs']);
        add_action('woocommerce_before_add_to_cart_form', [$this, 'atlasFenceCalculatorForm']);
        add_action('wp_ajax_calculate_atlas_fence_price', [$this, 'calculateAtlasFencePrice']);
        add_action('wp_ajax_nopriv_calculate_atlas_fence_price', [$this, 'calculateAtlasFencePrice']);
    }

    public function registerCustomAtlasFenceProductType() {
        error_log("Registering Custom ATLAS Fence product type");
        require_once __DIR__ . '/CustomAtlasFenceProduct.php';
    }

    public function addCustomAtlasFenceProductType($types) {
        error_log("Adding Custom ATLAS Fence to product type selector");
        $types['custom_atlas_fence'] = __('Custom ATLAS Fence', 'sage');
        return $types;
    }

    public function getCustomAtlasFenceProductClass($classname, $product_type) {
        error_log("Getting product class for type: $product_type");
        if ($product_type === 'custom_atlas_fence') {
            $classname = 'App\WooCommerce\CustomAtlasFenceProduct';
        }
        return $classname;
    }

    public function customAtlasFenceCustomJs() {
        if ('product' != get_post_type()) {
            return;
        }
        error_log("Injecting Custom ATLAS Fence JS");
        ?>
        <script type='text/javascript'>
            console.log('Initializing Custom ATLAS Fence JS');
            jQuery(document).ready(function($){
                $('.options_group.pricing').addClass('show_if_custom_atlas_fence').show();
                // Ensure the product type stays selected
                $('select#product-type').val('custom_atlas_fence').trigger('change');
            });
        </script>
        <?php
    }

    public function atlasFenceCalculatorForm() {
        global $product;
        error_log("atlasFenceCalculatorForm called for product ID: " . $product->get_id());
        error_log("Product type: " . $product->get_type());
        error_log("Product class: " . get_class($product));
        
        if ($product->get_type() !== 'custom_atlas_fence') {
            error_log("Not a custom_atlas_fence product, exiting");
            error_log("Available product types: " . implode(', ', array_keys(wc_get_product_types())));
            return;
        }

        $width_min = get_field('width_min', $product->get_id());
        $width_max = get_field('width_max', $product->get_id());
        $height_min = get_field('height_min', $product->get_id());
        $height_max = get_field('height_max', $product->get_id());

        error_log("Rendering ATLAS Fence Calculator Form");
        echo "<!-- ATLAS Fence Calculator Form Start -->";
        ?>
        <div class="atlas-fence-calculator">
            <h3><?php _e('Calculate Your ATLAS Fence', 'sage'); ?></h3>
            <form id="atlas-fence-form">
                <p>
                    <label for="fence_width"><?php _e('Fence Width (m):', 'sage'); ?></label>
                    <input type="number" id="fence_width" name="fence_width" min="<?php echo esc_attr($width_min); ?>" max="<?php echo esc_attr($width_max); ?>" step="0.01" required>
                </p>
                <p>
                    <label for="fence_height"><?php _e('Fence Height (m):', 'sage'); ?></label>
                    <input type="number" id="fence_height" name="fence_height" min="<?php echo esc_attr($height_min); ?>" max="<?php echo esc_attr($height_max); ?>" step="0.01" required>
                </p>
                <p>
                    <label for="num_panels"><?php _e('Number of Panels:', 'sage'); ?></label>
                    <input type="number" id="num_panels" name="num_panels" min="1" step="1" required>
                </p>
                <button type="submit"><?php _e('Calculate Price', 'sage'); ?></button>
            </form>
            <div id="atlas-fence-price"></div>
        </div>
        <?php
        echo "<!-- ATLAS Fence Calculator Form End -->";
        error_log("ATLAS Fence Calculator Form rendered");
    }

    public function calculateAtlasFencePrice() {
        error_log("AJAX request received for ATLAS fence price calculation");
        error_log("POST data: " . json_encode($_POST));

        $product_id = $_POST['product_id'];
        $width = floatval($_POST['width']);
        $height = floatval($_POST['height']);
        $num_panels = intval($_POST['num_panels']);

        $product = wc_get_product($product_id);

        if ($product && $product->get_type() === 'custom_atlas_fence') {
            $price = $product->calculate_price($width, $height, $num_panels);
            error_log("Calculated price: $price");
            wp_send_json_success(array('price' => number_format($price, 2)));
        } else {
            error_log("Invalid product or product type for ATLAS fence calculation. Product ID: $product_id, Type: " . ($product ? $product->get_type() : 'null'));
            wp_send_json_error('Invalid product');
        }
    }
}