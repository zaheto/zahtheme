<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class PageBuildComposer extends Composer
{
    protected static $views = [
        'partials.content-page',
        'woocommerce.single-product',
        'partials.content-page-build',
        'blocks.big-slider',
        'blocks.product-list',
        'blocks.category-list',
        'blocks.half-section',
        'blocks.seo-section',
        'blocks.landing-hero',
        'blocks.landing-intro',
        'blocks.landing-half-section',
        'blocks.landing-spacer',
        'blocks.landing-gallery',
        'blocks.landing-how-to',
        'blocks.landing-product-size',
        'blocks.landing-in-the-box',

    ];

    public function with()
    {
        $id = get_the_ID();
        $page_build = get_field('page_build', $id);
        $big_slider = get_field('big_slider', $id);
        $product_list_builder = get_field('product_list_builder', $id);
        $section_heading_product_list = get_field('section_heading_product_list', $id);
        $heading_color = get_field('heading_color', $id);
        $is_slider = get_field('is_slider', $id);
        $category_list_builder = get_field('category_list_builder', $id);
        $half_section = get_field('half_section', $id);
        $seo_section = get_field('seo_section', $id);
        $landing_hero_section = get_field('landing_hero_section', $id);
        $landing_introduction_section = get_field('landing_introduction_section', $id);
        $landing_half_section = get_field('landing_half_section', $id);
        $landing_spacer = get_field('landing_spacer', $id);
        $landing_gallery_section = get_field('landing_gallery', $id);
        $landing_how_to_section = get_field('landing_how_to_section', $id);
        $landing_product_size = get_field('landing_product_size', $id);
        $landing_in_the_box_section = get_field('landing_in_the_box_section', $id);







        return compact('page_build', 'big_slider', 'section_heading_product_list', 'is_slider', 'heading_color', 'product_list_builder', 'category_list_builder', 'half_section', 'seo_section', 'landing_hero_section', 'landing_introduction_section', 'landing_half_section', 'landing_spacer', 'landing_gallery_section', 'landing_how_to_section', 'landing_product_size', 'landing_in_the_box_section');
    }
}
