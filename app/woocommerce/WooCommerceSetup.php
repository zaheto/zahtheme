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
        add_action('woocommerce_before_single_product', [$this, 'bootCustomProductClasses']);
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

    public function bootCustomProductClasses()
    {
        global $product;
        
        if ($product instanceof \WC_Product_Simple && has_term('atlas', 'product_tag', $product->get_id())) {
            $this->enqueueCustomScripts();
        }
    }

    public function enqueueCustomScripts()
    {
        global $product;
        
        wp_enqueue_script('atlas-fence-calculator', get_template_directory_uri() . '/resources/scripts/atlas-fence-calculator.js', ['jquery'], null, true);
        $customProduct = new CustomAtlasFenceProduct($product);
        wp_localize_script('atlas-fence-calculator', 'atlasData', $customProduct->getAcfData());
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