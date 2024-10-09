<?php

namespace App;

class AjaxHandlers
{
    public function __construct()
    {
        add_action('wp_ajax_update_atlas_fence_price', [$this, 'updateAtlasFencePrice']);
        add_action('wp_ajax_nopriv_update_atlas_fence_price', [$this, 'updateAtlasFencePrice']);
    }

    public function updateAtlasFencePrice()
    {
        $price = floatval($_POST['price']);
        $product_id = intval($_POST['product_id']);

        $product = wc_get_product($product_id);
        $product->set_price($price);
        $product->set_regular_price($price);
        $product->save();

        wp_send_json_success([
            'price_html' => $product->get_price_html()
        ]);
    }
}