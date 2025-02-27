<?php
/**
 * Result Count
 *
 * Shows text: Showing x - x of x results.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/result-count.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} 
?>
<section class="filterby-top flex flex-col lg:flex-row items-center justify-between py-2 ">
	<div class="flex items-center gap-2">
		<a href="javascript:;" id="toggleFilters" class="toggle-filters">
			<x-iconsax-out-setting-4 class="w-[24px] h-[24px] block text-black mr-1" />
			<?= _e('Hide Filters','zah') ?>
		</a>
		<p class="woocommerce-result-count hidden lg:flex text-14 text-black/40 mb-0">
			<?php
			// phpcs:disable WordPress.Security
			if ( 1 === intval( $total ) ) {
				_e( 'Showing the single result', 'woocommerce' );
			} elseif ( $total <= $per_page || -1 === $per_page ) {
				$orderedby_placeholder = empty( $orderedby ) ? '%2$s' : '<span class="screen-reader-text">%2$s</span>';
				/* translators: 1: total results 2: sorted by */
				printf( _n( 'Showing all %1$d result', 'Showing all %1$d results', $total, 'woocommerce' ) . $orderedby_placeholder, $total, esc_html( $orderedby ) );
			} else {
				$first                 = ( $per_page * $current ) - $per_page + 1;
				$last                  = min( $total, $per_page * $current );
				$orderedby_placeholder = empty( $orderedby ) ? '%4$s' : '<span class="screen-reader-text">%4$s</span>';
				/* translators: 1: first result 2: last result 3: total results 4: sorted by */
				printf( _nx( 'Showing %1$d&ndash;%2$d of %3$d result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, 'with first and last result', 'woocommerce' ) . $orderedby_placeholder, $first, $last, $total, esc_html( $orderedby ) );
			}
			// phpcs:enable WordPress.Security
			?>
		</p>
	</div>
