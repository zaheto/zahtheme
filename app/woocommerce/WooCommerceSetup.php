<?php

namespace App\WooCommerce;

class WooCommerceSetup
{
    public function __construct()
    {
        add_action('init', [$this, 'registerCustomAtlasFenceProductType']);
        add_filter('product_type_selector', [$this, 'addCustomAtlasFenceProductType']);
        add_filter('woocommerce_product_class', [$this, 'getCustomAtlasFenceProductClass'], 10, 2);
        add_action('admin_footer', [$this, 'customAtlasFenceCustomJs']);
        add_action('woocommerce_before_single_product', [$this, 'atlasFenceCalculatorForm']);
        add_action('wp_ajax_calculate_atlas_fence_price', [$this, 'calculateAtlasFencePrice']);
        add_action('wp_ajax_nopriv_calculate_atlas_fence_price', [$this, 'calculateAtlasFencePrice']);
    }

    public function registerCustomAtlasFenceProductType()
    {
        require_once __DIR__ . '/CustomAtlasFenceProduct.php';
    }

    public function addCustomAtlasFenceProductType($types)
    {
        $types['custom_atlas_fence'] = __('Custom ATLAS Fence', 'sage');
        return $types;
    }

    public function getCustomAtlasFenceProductClass($classname, $product_type)
    {
        if ($product_type === 'custom_atlas_fence') {
            $classname = 'App\WooCommerce\CustomAtlasFenceProduct';
        }
        return $classname;
    }

    public function customAtlasFenceCustomJs()
    {
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

    public function atlasFenceCalculatorForm()
    {
        error_log('atlasFenceCalculatorForm method called');
        global $product;
        error_log('Product type: ' . $product->get_type());
        error_log('Product ID: ' . $product->get_id());
        
        if ($product->get_type() !== 'custom_atlas_fence') {
            error_log('Not a custom_atlas_fence product, exiting');
            return;
        }

        $width_min = get_field('width_min', $product->get_id());
        $width_max = get_field('width_max', $product->get_id());
        error_log('Width min: ' . $width_min . ', Width max: ' . $width_max);

        $height_options = [
            0.745, 0.845, 0.945, 1.045, 1.145, 1.245, 1.345, 1.445, 1.545, 1.645, 1.745, 1.845, 1.945,
            2.045, 2.145, 2.245, 2.445, 2.545, 2.645, 2.745, 2.845, 2.945, 3.045, 3.145
        ];

        error_log('Rendering form HTML');
        ob_start();
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
        $form_html = ob_get_clean();
        error_log('Form HTML: ' . $form_html);
        echo $form_html;
    }

    public function calculateAtlasFencePrice()
    {
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
}