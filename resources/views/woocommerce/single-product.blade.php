{{--
The Template for displaying all single products

This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.

HOWEVER, on occasion WooCommerce will need to update template files and you
(the theme developer) will need to copy the new files to your theme to
maintain compatibility. We try to do this as little as possible, but it does
happen. When this occurs the version of the template file will be bumped and
the readme will list any important changes.

@see         https://docs.woocommerce.com/document/template-structure/
@package     WooCommerce\Templates
@version     1.6.4
--}}

@php
if (has_term('atlas', 'product_tag', get_the_ID())) {

  $atlas_pricing = [
      'price_panels_lin_meter' => get_field('price_panels_lin_meter', get_the_ID()),
      'price_u_profile_left' => get_field('price_u_profile_left', get_the_ID()),
      'price_u_profile_right' => get_field('price_u_profile_right', get_the_ID()),
      'price_u_horizontal_panel' => get_field('price_u_horizontal_panel', get_the_ID()),
      'price_reinforcing_profile' => get_field('price_reinforcing_profile', get_the_ID()),
      'price_rivets' => get_field('price_rivets', get_the_ID()),
      'price_self_tapping_screw' => get_field('price_self_tapping_screw', get_the_ID()),
      'price_dowels' => get_field('price_dowels', get_the_ID()),
      'price_corners' => get_field('price_corners', get_the_ID())
  ];

  $product = wc_get_product(get_the_ID());
  $regular_price = $product->get_regular_price();

  $atlas_panel_height = explode("\r\n", get_field('panel_height', get_the_ID()));
  $width_min = get_field('width_min', get_the_ID());
  $width_max = get_field('width_max', get_the_ID());
}
@endphp




@extends('layouts.app')


@section('content')
  @php
    do_action('get_header', 'shop');
    do_action('woocommerce_before_main_content');
  @endphp


  @include('partials.content-page-build')

  @while(have_posts())
    @php
      the_post();
      do_action('woocommerce_before_single_product');
      wc_get_template_part('content', 'single-product');
    @endphp
  @endwhile

  @if (has_term('atlas', 'product_tag', get_the_ID()))
    <div id="atlas-calculator">
        <h2>Fence Panel Calculator - ATLAS Model</h2>
        <form id="atlas-fence-calculator">
            <label for="atlas-panel-width">Panel Width (m):</label>
            <input type="number" id="atlas-panel-width" name="atlas-panel-width" step="0.01" min="{{ $width_min }}" max="{{ $width_max }}" required><br><br>

            <label for="atlas-panel-height">Panel Height (m):</label>
            <select id="atlas-panel-height" name="atlas-panel-height" required>
                @foreach ($atlas_panel_height as $height)
                    <option value="{{ $height }}">{{ $height }}</option>
                @endforeach
            </select><br><br>

            <label for="atlas-number-of-panels">Number of Panels:</label>
            <input type="number" id="atlas-number-of-panels" name="atlas-number-of-panels" min="1" required><br><br>

            <button type="button" id="atlas-calculate">Calculate</button>
        </form>

        <h2>Required Materials for ATLAS Model:</h2>
        <div id="atlas-results"></div>

        <h2>Final Price for ATLAS Model:</h2>
        <div id="atlas-final-price"></div>
    </div>
@endif



  @php
    do_action('woocommerce_after_main_content');
    do_action('get_sidebar', 'shop');
    do_action('get_footer', 'shop');
  @endphp
@endsection


@if (has_term('atlas', 'product_tag', get_the_ID()))
    <script>
        const regularPrice = {{ $regular_price }};

        jQuery(document).ready(function () {
            console.log("Document is ready");

            // Ensure the ATLAS calculator form exists before proceeding
            if (jQuery('#atlas-fence-calculator').length === 0) {
                console.error("ATLAS Fence Calculator form not found. Please make sure the form HTML is rendered properly.");
                return;
            } else {
                console.log("ATLAS Fence Calculator form found.");
            }

            // Add click event for the calculate button
            jQuery('#atlas-calculate').click(function () {
                console.log("Calculate button clicked.");

                let panelWidth = parseFloat(jQuery('#atlas-panel-width').val());
                let panelHeight = parseFloat(jQuery('#atlas-panel-height').val());
                let numberOfPanels = parseInt(jQuery('#atlas-number-of-panels').val());

                // Log the inputs
                if (isNaN(panelWidth) || isNaN(panelHeight) || isNaN(numberOfPanels)) {
                    console.error("One or more inputs are missing or invalid.");
                    return;
                } else {
                    console.log(`Inputs - Panel Width: ${panelWidth}, Panel Height: ${panelHeight}, Number of Panels: ${numberOfPanels}`);
                }

                // Check min and max width values from input attributes
                let minWidth = parseFloat(jQuery('#atlas-panel-width').attr('min'));
                let maxWidth = parseFloat(jQuery('#atlas-panel-width').attr('max'));

                if (panelWidth < minWidth || panelWidth > maxWidth) {
                    console.error(`Panel width must be between ${minWidth} and ${maxWidth} meters.`);
                    alert(`Panel width must be between ${minWidth} and ${maxWidth} meters.`);
                    return;
                }

                // Material Calculations
                try {
                    console.log("Starting material calculations...");

                    let blindsProfilePcs = Math.max((panelHeight - 0.045) / 0.1 * numberOfPanels, 0);
                    let blindsProfileLm = Math.max((panelWidth - 0.01) * blindsProfilePcs, 0);

                    let uProfileLeftPcs = numberOfPanels;
                    let uProfileLeftLm = panelHeight * numberOfPanels;

                    let uProfileRightPcs = numberOfPanels;
                    let uProfileRightLm = panelHeight * numberOfPanels;

                    let horizontalUProfilePcs = numberOfPanels;
                    let horizontalUProfileLm = panelWidth * numberOfPanels;

                    let F20 = panelWidth > 1.29 && panelWidth < 2.1 ? 1 : panelWidth > 2.09 ? 2 : 0;
                    let reinforcingProfilePcs = F20;
                    let reinforcingProfileLm = panelHeight * F20 * numberOfPanels;

                    let rivetsPcs = Math.ceil(((blindsProfilePcs / numberOfPanels + 1) * (4 + F20) * numberOfPanels + (F20 * 2)) / numberOfPanels / 100) * 100 * numberOfPanels;

                    let selfTappingScrewPcs = numberOfPanels * 10;
                    let dowelsPcs = numberOfPanels * 10 + F20 * numberOfPanels;
                    let cornerPcs = F20 * numberOfPanels;

                    console.log("Material calculations completed.");

                    // Calculate Total Price using Regular Price
                    console.log("Starting price calculations...");

                    let totalPrice = regularPrice * numberOfPanels;

                    console.log(`Total price calculated: ${totalPrice}`);

                    // Display Results
                    jQuery('#atlas-results').html(`
                        <p>Blinds Profile: ${blindsProfilePcs.toFixed(2)} Pcs, ${blindsProfileLm.toFixed(2)} lm</p>
                        <p>U Profile Left: ${uProfileLeftPcs} Pcs, ${uProfileLeftLm.toFixed(3)} lm</p>
                        <p>U Profile Right: ${uProfileRightPcs} Pcs, ${uProfileRightLm.toFixed(3)} lm</p>
                        <p>Horizontal U Profile: ${horizontalUProfilePcs} Pcs, ${horizontalUProfileLm.toFixed(2)} lm</p>
                        <p>Reinforcing Profile: ${reinforcingProfilePcs} Pcs, ${reinforcingProfileLm.toFixed(3)} lm</p>
                        <p>Rivets: ${rivetsPcs} Pcs</p>
                        <p>Self-tapping Screws: ${selfTappingScrewPcs} Pcs</p>
                        <p>Dowels: ${dowelsPcs} Pcs</p>
                        <p>Corner: ${cornerPcs} Pcs</p>
                    `);

                    jQuery('#atlas-final-price').html(`<p>Total Price: ${totalPrice.toFixed(2)} лв.</p>`);
                    console.log("Results displayed successfully.");

                } catch (error) {
                    console.error("An error occurred during calculations: ", error);
                }
            });
        });
    </script>
@endif

