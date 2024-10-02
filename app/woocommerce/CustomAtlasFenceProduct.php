<?php

namespace App\WooCommerce;

class CustomAtlasFenceProduct extends \WC_Product {
    protected $product_type;

    public function __construct($product = 0) {
        $this->product_type = 'custom_atlas_fence';
        parent::__construct($product);
    }

    public function get_type() {
        return 'custom_atlas_fence';
    }

    public function calculate_price($width, $height, $num_panels) {
        error_log("Calculating price for ATLAS fence: width=$width, height=$height, num_panels=$num_panels");
        
        // Fetch ACF fields
        $price_per_linear_meter = $this->get_regular_price();
        $total_linear_meters = $width * $num_panels;
        $total_price = $total_linear_meters * $price_per_linear_meter;
        
        $price_panels_pcs = get_field('price_panels_pcs', $this->get_id());
        $price_panels_lin_meter = get_field('price_panels_lin_meter', $this->get_id());
        $price_u_profile_left = get_field('price_u_profile_left', $this->get_id());
        $price_u_profile_right = get_field('price_u_profile_right', $this->get_id());
        $price_u_horizontal_panel = get_field('price_u_horizontal_panel', $this->get_id());
        $price_panel_sqm = get_field('price_panel_sqm', $this->get_id());
        $reinforcing_profile = get_field('reinforcing_profile', $this->get_id());
        $price_rivets = get_field('price_rivets', $this->get_id());

        error_log("ACF fields: " . json_encode([
            'price_panels_pcs' => $price_panels_pcs,
            'price_panels_lin_meter' => $price_panels_lin_meter,
            'price_u_profile_left' => $price_u_profile_left,
            'price_u_profile_right' => $price_u_profile_right,
            'price_u_horizontal_panel' => $price_u_horizontal_panel,
            'price_panel_sqm' => $price_panel_sqm,
            'reinforcing_profile' => $reinforcing_profile,
            'price_rivets' => $price_rivets,
        ]));

        // Calculate materials needed
        $blinds_profile = ceil($width * $num_panels * 10);
        $u_profile_left = ceil($height * $num_panels);
        $u_profile_right = ceil($height * $num_panels);
        $horizontal_u_profile = ceil($width * $num_panels);
        $panel_area = $width * $height * $num_panels;
        $reinforcing_profile_length = ceil($height * $num_panels * 2);
        $rivets = ceil($num_panels * 100);

        // Calculate total price
        $total_price = 
            ($blinds_profile * $price_panels_lin_meter) +
            ($u_profile_left * $price_u_profile_left) +
            ($u_profile_right * $price_u_profile_right) +
            ($horizontal_u_profile * $price_u_horizontal_panel) +
            ($panel_area * $price_panel_sqm) +
            ($reinforcing_profile_length * $reinforcing_profile) +
            ($rivets * $price_rivets);

        error_log("Calculated total price: $total_price");
        return $total_price;
    }
}