{{--
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 *
 * @var bool   $readonly If the input should be set to readonly mode.
 * @var string $type     The input type attribute.
 */
--}}

@php if ( !defined( 'ABSPATH' ) ) { exit; } @endphp

@if ( $max_value && $min_value === $max_value )

	<div class="quantity hidden">
		<input type="hidden" id="{{ esc_attr( $input_id ) }}" class="qty" name="{{ esc_attr( $input_name ) }}" value="{{ esc_attr( $min_value ) }}" />
	</div>

@else
	@php
	/* translators: %s: Quantity. */
	$labelledby = ! empty( $args['product_name'] ) ? sprintf( __( '%s quantity', 'woocommerce' ), strip_tags( $args['product_name'] ) ) : '';
	@endphp

	<div class="quantity">
		<label class="screen-reader-text" for="{{ esc_attr( $input_id ) }}">{{ __( 'Quantity', 'woocommerce' ) }}</label>
		<input
			type="number"
			id="{{ esc_attr( $input_id ) }}"
			class="input-text qty text"
			step="{{ esc_attr( $step ) }}"
			min="{{ esc_attr( $min_value ) }}"
			max="{{ esc_attr( 0 < $max_value ? $max_value : '' ) }}"
			name="{{ esc_attr( $input_name ) }}"
			value="{{ esc_attr( $input_value ) }}"
			title="{{ esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ) }}"
			size="4"
			pattern="{{ esc_attr( $pattern ) }}"
			inputmode="{{ esc_attr( $inputmode ) }}"
			aria-labelledby="{{ esc_attr( $labelledby ) }}"
			readonly />
	</div>
@endif
