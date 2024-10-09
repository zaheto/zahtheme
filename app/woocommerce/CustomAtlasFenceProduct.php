<?php

namespace App\woocommerce;

use WC_Product_Simple;

class CustomAtlasFenceProduct
{
    protected $product;

    public function __construct(WC_Product_Simple $product)
    {
        $this->product = $product;
    }

    public function getAcfData()
    {
        return [
            'widthMin' => get_field('width_min', $this->product->get_id()),
            'widthMax' => get_field('width_max', $this->product->get_id()),
            'heights' => explode("\n", get_field('panel_height', $this->product->get_id())),
            'pricePerMeter' => get_field('price_panels_lin_meter', $this->product->get_id()),
            'priceULeft' => get_field('price_u_profile_left', $this->product->get_id()),
            'priceURight' => get_field('price_u_profile_right', $this->product->get_id()),
            'priceUHorizontal' => get_field('price_u_horizontal_panel', $this->product->get_id()),
            'priceReinforcing' => get_field('price_reinforcing_profile', $this->product->get_id()),
            'priceRivets' => get_field('price_rivets', $this->product->get_id()),
            'priceSelfTapping' => get_field('price_self_tapping_screw', $this->product->get_id()),
            'priceDowels' => get_field('price_dowels', $this->product->get_id()),
            'priceCorners' => get_field('price_corners', $this->product->get_id()),
        ];
    }

    public function getPrice()
    {
        // Your existing getPrice method logic here
        // For example:
        return $this->product->get_price();
    }

    public function getName()
    {
        // Your existing getName method logic here
        // For example:
        return $this->product->get_name();
    }

    // Add any other methods you have in your existing CustomAtlasFenceProduct class
}