<?php

namespace App\WooCommerce;

class WooCommerceSetup {
    public function __construct() {
        add_filter('product_type_selector', [$this, 'addCustomAtlasFenceProductType']);
        add_action('admin_footer', [$this, 'customAtlasFenceCustomJs']);
        add_action('woocommerce_before_add_to_cart_form', [$this, 'atlasFenceCalculatorForm']);
        add_action('wp_ajax_calculate_atlas_fence_price', [$this, 'calculateAtlasFencePrice']);
        add_action('wp_ajax_nopriv_calculate_atlas_fence_price', [$this, 'calculateAtlasFencePrice']);
    }

    public function addCustomAtlasFenceProductType($types) {
        $types['custom_atlas_fence'] = __('Custom ATLAS Fence');
        return $types;
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
        $height_min = get_field('height_min', $product->get_id());
        $height_max = get_field('height_max', $product->get_id());
    
        // Display ACF fields
        ?>
        <div class="atlas-fence-details">
            <h3><?php _e('ATLAS Fence Details', 'sage'); ?></h3>
            <ul>
                <li><?php _e('Width Range:', 'sage'); ?> <?php echo esc_html($width_min); ?>m - <?php echo esc_html($width_max); ?>m</li>
                <li><?php _e('Height Range:', 'sage'); ?> <?php echo esc_html($height_min); ?>m - <?php echo esc_html($height_max); ?>m</li>
                <li><?php _e('Panel Price (pcs):', 'sage'); ?> $<?php echo esc_html(get_field('price_panels_pcs', $product->get_id())); ?></li>
                <li><?php _e('Panel Price (linear meter):', 'sage'); ?> $<?php echo esc_html(get_field('price_panels_lin_meter', $product->get_id())); ?></li>
                <li><?php _e('U Profile Left Price:', 'sage'); ?> $<?php echo esc_html(get_field('price_u_profile_left', $product->get_id())); ?></li>
                <li><?php _e('U Profile Right Price:', 'sage'); ?> $<?php echo esc_html(get_field('price_u_profile_right', $product->get_id())); ?></li>
                <li><?php _e('Horizontal U Profile Price:', 'sage'); ?> $<?php echo esc_html(get_field('price_u_horizontal_panel', $product->get_id())); ?></li>
                <li><?php _e('Panel Price (sq.m):', 'sage'); ?> $<?php echo esc_html(get_field('price_panel_sqm', $product->get_id())); ?></li>
                <li><?php _e('Reinforcing Profile Price:', 'sage'); ?> $<?php echo esc_html(get_field('reinforcing_profile', $product->get_id())); ?></li>
                <li><?php _e('Rivets Price:', 'sage'); ?> $<?php echo esc_html(get_field('price_rivets', $product->get_id())); ?></li>
            </ul>
        </div>
    
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
    }

    public function calculateAtlasFencePrice() {
        $product_id = $_POST['product_id'];
        $width = floatval($_POST['width']);
        $height = floatval($_POST['height']);
        $num_panels = intval($_POST['num_panels']);

        $product = wc_get_product($product_id);

        if ($product && $product->get_type() === 'custom_atlas_fence') {
            $price = $product->calculate_price($width, $height, $num_panels);
            wp_send_json_success(array('price' => number_format($price, 2)));
        } else {
            wp_send_json_error('Invalid product');
        }
    }
}