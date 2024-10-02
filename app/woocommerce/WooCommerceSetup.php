<?php

namespace App\WooCommerce;

class WooCommerceSetup {
    public function __construct() {
        add_action('init', [$this, 'registerCustomAtlasFenceProductType']);
        add_filter('product_type_selector', [$this, 'addCustomAtlasFenceProductType']);
        add_filter('woocommerce_product_class', [$this, 'getCustomAtlasFenceProductClass'], 10, 2);
        add_action('admin_footer', [$this, 'customAtlasFenceCustomJs']);
        add_action('woocommerce_before_add_to_cart_button', [$this, 'atlasFenceCalculatorForm']);
        add_action('wp_ajax_calculate_atlas_fence_price', [$this, 'calculateAtlasFencePrice']);
        add_action('wp_ajax_nopriv_calculate_atlas_fence_price', [$this, 'calculateAtlasFencePrice']);
        add_filter('woocommerce_add_to_cart_validation', [$this, 'validateCustomFields'], 10, 3);
        add_filter('woocommerce_add_cart_item_data', [$this, 'addCustomFieldsToCart'], 10, 3);
        add_action('woocommerce_before_calculate_totals', [$this, 'calculateCustomPrice']);
        add_filter('woocommerce_get_item_data', [$this, 'displayCustomFieldsInCart'], 10, 2);
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'addCustomFieldsToOrderItems'], 10, 4);
    }

    public function registerCustomAtlasFenceProductType() {
        require_once __DIR__ . '/CustomAtlasFenceProduct.php';
    }

    public function addCustomAtlasFenceProductType($types) {
        $types['custom_atlas_fence'] = __('Custom ATLAS Fence', 'sage');
        return $types;
    }

    public function getCustomAtlasFenceProductClass($classname, $product_type) {
        if ($product_type === 'custom_atlas_fence') {
            $classname = 'App\WooCommerce\CustomAtlasFenceProduct';
        }
        return $classname;
    }

    public function customAtlasFenceCustomJs() {
        if ('product' != get_post_type()) {
            return;
        }
        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function($){
                $('.options_group.pricing').addClass('show_if_custom_atlas_fence').show();
            });
        </script>
        <?php
    }

    public function atlasFenceCalculatorForm() {
        global $product;
        if ($product->get_type() !== 'custom_atlas_fence') {
            return;
        }

        $width_min = get_field('width_min', $product->get_id());
        $width_max = get_field('width_max', $product->get_id());

        $height_options = [
            0.745, 0.845, 0.945, 1.045, 1.145, 1.245, 1.345, 1.445, 1.545, 1.645, 1.745, 1.845, 1.945, 
            2.045, 2.145, 2.245, 2.445, 2.545, 2.645, 2.745, 2.845, 2.945, 3.045, 3.145
        ];

        ?>
        <div class="atlas-fence-calculator">
            <h4><?php _e('Calculate Your ATLAS Fence', 'sage'); ?></h4>
            <p>
                <label for="fence_width"><?php _e('Fence Width (m):', 'sage'); ?></label>
                <input type="number" id="fence_width" name="fence_width" min="<?php echo esc_attr($width_min); ?>" max="<?php echo esc_attr($width_max); ?>" step="0.01" required>
            </p>
            <p>
                <label for="fence_height"><?php _e('Panel Height (m):', 'sage'); ?></label>
                <select id="fence_height" name="fence_height" required>
                    <?php foreach ($height_options as $height): ?>
                        <option value="<?php echo esc_attr($height); ?>"><?php echo esc_html($height); ?> m</option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <label for="num_panels"><?php _e('Number of Panels:', 'sage'); ?></label>
                <input type="number" id="num_panels" name="num_panels" min="1" step="1" required>
            </p>
            <button type="button" id="calculate-price"><?php _e('Calculate Price', 'sage'); ?></button>
        </div>
        <?php
    }

    public function calculateAtlasFencePrice() {
        $product_id = $_POST['product_id'];
        $width = floatval($_POST['width']);
        $height = floatval($_POST['height']);
        $num_panels = intval($_POST['num_panels']);

        $product = wc_get_product($product_id);

        if ($product && $product->get_type() === 'custom_atlas_fence') {
            $price = $product->calculate_price($width, $height, $num_panels);
            $product->set_price($price);
            $product->save();

            wp_send_json_success(array(
                'price' => wc_price($price),
                'price_html' => $product->get_price_html()
            ));
        } else {
            wp_send_json_error('Invalid product');
        }
    }

    public function validateCustomFields($passed, $product_id, $quantity) {
        if (isset($_POST['fence_width']) && isset($_POST['fence_height']) && isset($_POST['num_panels'])) {
            $width = floatval($_POST['fence_width']);
            $height = floatval($_POST['fence_height']);
            $num_panels = intval($_POST['num_panels']);

            $width_min = get_field('width_min', $product_id);
            $width_max = get_field('width_max', $product_id);

            if ($width < $width_min || $width > $width_max || $num_panels < 1) {
                wc_add_notice(__('Invalid fence dimensions or number of panels.', 'sage'), 'error');
                return false;
            }
        } else {
            wc_add_notice(__('Please enter fence dimensions and number of panels.', 'sage'), 'error');
            return false;
        }
        return $passed;
    }

    public function addCustomFieldsToCart($cart_item_data, $product_id, $variation_id) {
        if (isset($_POST['fence_width'])) {
            $cart_item_data['fence_width'] = sanitize_text_field($_POST['fence_width']);
        }
        if (isset($_POST['fence_height'])) {
            $cart_item_data['fence_height'] = sanitize_text_field($_POST['fence_height']);
        }
        if (isset($_POST['num_panels'])) {
            $cart_item_data['num_panels'] = sanitize_text_field($_POST['num_panels']);
        }
        return $cart_item_data;
    }

    public function calculateCustomPrice($cart) {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        foreach ($cart->get_cart() as $cart_item) {
            $product = $cart_item['data'];
            if ($product->get_type() === 'custom_atlas_fence' && isset($cart_item['fence_width'], $cart_item['fence_height'], $cart_item['num_panels'])) {
                $width = floatval($cart_item['fence_width']);
                $height = floatval($cart_item['fence_height']);
                $num_panels = intval($cart_item['num_panels']);
                $new_price = $product->calculate_price($width, $height, $num_panels);
                $product->set_price($new_price);
            }
        }
    }

    public function displayCustomFieldsInCart($item_data, $cart_item) {
        if (isset($cart_item['fence_width'])) {
            $item_data[] = array(
                'key' => __('Width', 'sage'),
                'value' => wc_clean($cart_item['fence_width']) . 'm'
            );
        }
        if (isset($cart_item['fence_height'])) {
            $item_data[] = array(
                'key' => __('Height', 'sage'),
                'value' => wc_clean($cart_item['fence_height']) . 'm'
            );
        }
        if (isset($cart_item['num_panels'])) {
            $item_data[] = array(
                'key' => __('Panels', 'sage'),
                'value' => wc_clean($cart_item['num_panels'])
            );
        }
        return $item_data;
    }

    public function addCustomFieldsToOrderItems($item, $cart_item_key, $values, $order) {
        if (isset($values['fence_width'])) {
            $item->add_meta_data(__('Width', 'sage'), $values['fence_width'] . 'm');
        }
        if (isset($values['fence_height'])) {
            $item->add_meta_data(__('Height', 'sage'), $values['fence_height'] . 'm');
        }
        if (isset($values['num_panels'])) {
            $item->add_meta_data(__('Panels', 'sage'), $values['num_panels']);
        }
    }
}