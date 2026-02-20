<?php

namespace App;

/**
 * WooCommerce Template Hooks
 *
 * Action/filter hooks used for WooCommerce functions/templates.
 *
 * @package WooCommerce/Templates
 * @version 2.1.0
 */

// remove_filter('body_class', 'wc_body_class');
// remove_filter('post_class', 'wc_product_post_class', 20);

/**
 * WP Header.
 *
 * @see wc_generator_tag()
 */
// remove_filter('get_the_generator_html', 'wc_generator_tag', 10);
// remove_filter('get_the_generator_xhtml', 'wc_generator_tag', 10);

/**
 * Content Wrappers.
 *
 * @see woocommerce_output_content_wrapper()
 * @see woocommerce_output_content_wrapper_end()
 */
// remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
// remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
 * Sale flashes.
 *
 * @see woocommerce_show_product_loop_sale_flash()
 * @see woocommerce_show_product_sale_flash()
 */
// remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
// remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);

/**
 * Breadcrumbs.
 *
 * @see woocommerce_breadcrumb()
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

/**
 * Sidebar.
 *
 * @see woocommerce_get_sidebar()
 */
// remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

/**
 * Archive descriptions.
 *
 * @see woocommerce_taxonomy_archive_description()
 * @see woocommerce_product_archive_description()
 */
remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description', 10);

add_action('woocommerce_after_shop_loop', 'woocommerce_taxonomy_archive_description', 10);

/**
 * Product loop start.
 */
// remove_filter('woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories');

/**
 * Products Loop.
 *
 * @see woocommerce_result_count()
 * @see woocommerce_catalog_ordering()
 */
// remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
// remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
// remove_action('woocommerce_no_products_found', 'wc_no_products_found');

/**
 * Product Loop Items.
 *
 * @see woocommerce_template_loop_product_link_open()
 * @see woocommerce_template_loop_product_link_close()
 * @see woocommerce_template_loop_add_to_cart()
 * @see woocommerce_template_loop_product_thumbnail()
 * @see woocommerce_template_loop_product_title()
 * @see woocommerce_template_loop_category_link_open()
 * @see woocommerce_template_loop_category_title()
 * @see woocommerce_template_loop_category_link_close()
 * @see woocommerce_template_loop_price()
 * @see woocommerce_template_loop_rating()
 */
// remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
// remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
//  remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
// add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 10);
// remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
//remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
//add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

// remove_action('woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10);
// remove_action('woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10);
// remove_action('woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10);

//remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
//add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_price', 30);
// remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

/**
 * Subcategories.
 *
 * @see woocommerce_subcategory_thumbnail()
 */
// remove_action('woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10);

/**
 * Before Single Products Summary Div.
 *
 * @see woocommerce_show_product_images()
 * @see woocommerce_show_product_thumbnails()
 */
// remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
// remove_action('woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20);

/**
 * After Single Products Summary Div.
 *
 * @see woocommerce_output_product_data_tabs()
 * @see woocommerce_upsell_display()
 * @see woocommerce_output_related_products()
 */
 remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
 add_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);


remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 30 );


add_action( 'woocommerce_before_shop_loop_item_title', 'zah_shop_out_of_stock', 8 );

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 6 );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );







/**
 * Product Summary Box.
 *
 * @see woocommerce_template_single_title()
 * @see woocommerce_template_single_rating()
 * @see woocommerce_template_single_price()
 * @see woocommerce_template_single_excerpt()
 * @see woocommerce_template_single_meta()
 * @see woocommerce_template_single_sharing()
 */
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
//remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
// add_action('woocommerce_before_add_to_cart_form', 'woocommerce_template_single_price', 10);
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
// add_action('woocommerce_before_add_to_cart_form', 'woocommerce_template_single_excerpt', 20);
//remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
//remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_stock_status', 10);

/**
 * Reviews
 *
 * @see woocommerce_review_display_gravatar()
 * @see woocommerce_review_display_rating()
 * @see woocommerce_review_display_meta()
 * @see woocommerce_review_display_comment_text()
 */
// remove_action('woocommerce_review_before', 'woocommerce_review_display_gravatar', 10);
// remove_action('woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 10);
// remove_action('woocommerce_review_meta', 'woocommerce_review_display_meta', 10);
// remove_action('woocommerce_review_comment_text', 'woocommerce_review_display_comment_text', 10);

/**
 * Product Add to cart.
 *
 * @see woocommerce_template_single_add_to_cart()
 * @see woocommerce_simple_add_to_cart()
 * @see woocommerce_grouped_add_to_cart()
 * @see woocommerce_variable_add_to_cart()
 * @see woocommerce_external_add_to_cart()
 * @see woocommerce_single_variation()
 * @see woocommerce_single_variation_add_to_cart_button()
 */
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
// remove_action('woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30);
// remove_action('woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30);
// remove_action('woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30);
// remove_action('woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30);
// remove_action('woocommerce_single_variation', 'woocommerce_single_variation', 10);
// remove_action('woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20);

/**
 * Pagination after shop loops.
 *
 * @see woocommerce_pagination()
 */
// remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);

/**
 * Product page tabs.
 */
// remove_filter('woocommerce_product_tabs', 'woocommerce_default_product_tabs');
// remove_filter('woocommerce_product_tabs', 'woocommerce_sort_product_tabs', 99);

/**
 * Additional Information tab.
 *
 * @see wc_display_product_attributes()
 */
remove_action('woocommerce_product_additional_information', 'wc_display_product_attributes', 10);

/**
 * Checkout.
 *
 * @see woocommerce_checkout_login_form()
 * @see woocommerce_checkout_coupon_form()
 * \
 * @see woocommerce_order_review()
 * @see woocommerce_checkout_payment()
 * @see wc_checkout_privacy_policy_text()
 * @see wc_terms_and_conditions_page_content()
 * @see wc_get_pay_buttons()
 */
// remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);
// remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
// remove_action('woocommerce_checkout_order_review', 'woocommerce_order_review', 10);
// remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
remove_action('woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20);
remove_action('woocommerce_checkout_terms_and_conditions', 'wc_terms_and_conditions_page_content', 30);
remove_action('woocommerce_checkout_order_review', 'woocommerce_review_order_before_shipping', 10);



// remove_action('woocommerce_checkout_before_customer_details', 'wc_get_pay_buttons', 30);

/**
 * Cart widget
 */
// remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10);
// remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20);
// remove_action('woocommerce_widget_shopping_cart_total', 'woocommerce_widget_shopping_cart_subtotal', 10);

/**
 * Cart.
 *
 * @see woocommerce_cross_sell_display()
 * @see woocommerce_cart_totals()
 * @see wc_get_pay_buttons()
 * @see woocommerce_button_proceed_to_checkout()
 * @see wc_empty_cart_message()
 */
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
// add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display');
// remove_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10);
// add_action('woocommerce_cart_coupon', 'woocommerce_cart_totals', 10);
// remove_action('woocommerce_proceed_to_checkout', 'wc_get_pay_buttons', 10);
// remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);
// remove_action('woocommerce_cart_is_empty', 'wc_empty_cart_message', 10);

/**
 * Footer.
 *
 * @see  wc_print_js()
 * @see woocommerce_demo_store()
 */
// remove_action('wp_footer', 'wc_print_js', 25);
// remove_action('wp_footer', 'woocommerce_demo_store');

/**
 * Order details.
 *
 * @see woocommerce_order_details_table()
 * @see woocommerce_order_again_button()
 */
// remove_action('woocommerce_view_order', 'woocommerce_order_details_table', 10);
// remove_action('woocommerce_thankyou', 'woocommerce_order_details_table', 10);
// remove_action('woocommerce_order_details_after_order_table', 'woocommerce_order_again_button');

/**
 * Order downloads.
 *
 * @see woocommerce_order_downloads_table()
 */
// remove_action('woocommerce_available_downloads', 'woocommerce_order_downloads_table', 10);

/**
 * Auth.
 *
 * @see woocommerce_output_auth_header()
 * @see woocommerce_output_auth_footer()
 */
// remove_action('woocommerce_auth_page_header', 'woocommerce_output_auth_header', 10);
// remove_action('woocommerce_auth_page_footer', 'woocommerce_output_auth_footer', 10);

add_action( 'zah_before_site', 'zah_header_cart_drawer', 5 );

/**
 * Comments.
 *
 * Disable Jetpack comments.
 */
// remove_filter('jetpack_comment_form_enabled_for_product', '__return_false');

/**
 * Enable comments/reviews for products
 */
add_filter('comments_open', function($open, $post_id) {
    $post = get_post($post_id);
    if ($post && $post->post_type === 'product') {
        return true;
    }
    return $open;
}, 10, 2);

/**
 * My Account.
 */
// remove_action('woocommerce_account_navigation', 'woocommerce_account_navigation');
// remove_action('woocommerce_account_content', 'woocommerce_account_content');
// remove_action('woocommerce_account_orders_endpoint', 'woocommerce_account_orders');
// remove_action('woocommerce_account_view-order_endpoint', 'woocommerce_account_view_order');
// remove_action('woocommerce_account_downloads_endpoint', 'woocommerce_account_downloads');
// remove_action('woocommerce_account_edit-address_endpoint', 'woocommerce_account_edit_address');
// remove_action('woocommerce_account_payment-methods_endpoint', 'woocommerce_account_payment_methods');
// remove_action('woocommerce_account_add-payment-method_endpoint', 'woocommerce_account_add_payment_method');
// remove_action('woocommerce_account_edit-account_endpoint', 'woocommerce_account_edit_account');
// remove_action('woocommerce_register_form', 'wc_registration_privacy_policy_text', 20);

/**
 * Notices.
 */
// remove_action('woocommerce_cart_is_empty', 'woocommerce_output_all_notices', 5);
// remove_action('woocommerce_shortcode_before_product_cat_loop', 'woocommerce_output_all_notices', 10);
// remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
// remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
// remove_action('woocommerce_before_cart', 'woocommerce_output_all_notices', 10);
// remove_action('woocommerce_before_checkout_form_cart_notices', 'woocommerce_output_all_notices', 10);
// remove_action('woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10);
// remove_action('woocommerce_account_content', 'woocommerce_output_all_notices', 5);
// remove_action('woocommerce_before_customer_login_form', 'woocommerce_output_all_notices', 10);
// remove_action('woocommerce_before_lost_password_form', 'woocommerce_output_all_notices', 10);
// remove_action('before_woocommerce_pay', 'woocommerce_output_all_notices', 10);
// remove_action('woocommerce_before_reset_password_form', 'woocommerce_output_all_notices', 10);

add_filter( 'woocommerce_add_to_cart_fragments', 'zah_cart_link_fragment' );

/**
 * Remove reviews tab from product tabs
 */
add_filter('woocommerce_product_tabs', function($tabs) {
    unset($tabs['reviews']);
    return $tabs;
}, 98);

/**
 * Display product reviews on single product page
 */
add_action('woocommerce_after_single_product_summary', function() {
    if (is_product() && comments_open()) {
        echo \Roots\view('woocommerce.single-product.reviews')->render();
    }
}, 15);

/**
 * Remove WooCommerce default rating HTML generation
 */
add_filter('woocommerce_product_review_comment_form_args', function($comment_form) {
    // We'll handle rating in our custom template, so remove WooCommerce's default
    return $comment_form;
}, 20);

/**
 * Remove WooCommerce rating field HTML injection
 */
remove_action('comment_form_rating', 'woocommerce_product_review_comment_form_rating_field', 10);

/**
 * Disable WooCommerce from adding rating stars via JS
 */
add_action('wp_footer', function() {
    if (is_product()) {
        ?>
        <style>
            #reviews .comment-form-rating p.stars {
                display: none !important;
            }
            #reviews .comment-form-rating select[name="rating"] {
                display: none !important;
            }
        </style>
        <?php
    }
}, 100);

/**
 * Handle review image uploads
 */
add_action('comment_post', function($comment_id) {
    // Only process for product reviews
    $comment = \get_comment($comment_id);
    if (!$comment || \get_post_type($comment->comment_post_ID) !== 'product') {
        return;
    }

    // Check if files were uploaded
    if (empty($_FILES['review_images']) || empty($_FILES['review_images']['name'][0])) {
        return;
    }

    // Require WordPress file handling functions
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $uploaded_images = [];
    $files = $_FILES['review_images'];
    $max_files = 5;
    $max_file_size = 5 * 1024 * 1024; // 5MB
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

    // Process each uploaded file
    $file_count = count($files['name']);
    if ($file_count > $max_files) {
        $file_count = $max_files;
    }

    for ($i = 0; $i < $file_count; $i++) {
        if (empty($files['name'][$i])) {
            continue;
        }

        // Validate file size
        if ($files['size'][$i] > $max_file_size) {
            continue;
        }

        // Validate file type
        if (!in_array($files['type'][$i], $allowed_types)) {
            continue;
        }

        // Prepare file for upload
        $file = [
            'name'     => $files['name'][$i],
            'type'     => $files['type'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'error'    => $files['error'][$i],
            'size'     => $files['size'][$i]
        ];

        // Upload file
        $upload_overrides = [
            'test_form' => false,
            'mimes'     => [
                'jpg|jpeg|jpe' => 'image/jpeg',
                'png'          => 'image/png',
                'webp'         => 'image/webp',
            ]
        ];

        $uploaded = \wp_handle_upload($file, $upload_overrides);

        if (isset($uploaded['file']) && !isset($uploaded['error'])) {
            // Create attachment
            $attachment = [
                'post_mime_type' => $uploaded['type'],
                'post_title'     => \sanitize_file_name(\pathinfo($uploaded['file'], PATHINFO_FILENAME)),
                'post_content'   => '',
                'post_status'    => 'inherit'
            ];

            $attachment_id = \wp_insert_attachment($attachment, $uploaded['file'], $comment->comment_post_ID);

            if (!\is_wp_error($attachment_id)) {
                // Generate attachment metadata
                $attachment_data = \wp_generate_attachment_metadata($attachment_id, $uploaded['file']);
                \wp_update_attachment_metadata($attachment_id, $attachment_data);

                $uploaded_images[] = $attachment_id;
            }
        }
    }

    // Save image IDs to comment meta
    if (!empty($uploaded_images)) {
        \update_comment_meta($comment_id, 'review_images', $uploaded_images);
    }
}, 10, 1);

/**
 * Prevent review images from being deleted when comment is moderated
 */
add_action('wp_set_comment_status', function($comment_id, $status) {
    // Keep the images regardless of comment status
}, 10, 2);

/**
 * Add meta box to comment edit screen for review images
 */
add_action('add_meta_boxes_comment', function() {
    \add_meta_box(
        'review_images_metabox',
        'Review Images',
        function($comment) {
            $review_images = \get_comment_meta($comment->comment_ID, 'review_images', true);
            $review_images = $review_images ? (is_array($review_images) ? $review_images : []) : [];

            \wp_nonce_field('review_images_save', 'review_images_nonce');

            echo '<div class="review-images-admin">';

            if (!empty($review_images)) {
                echo '<div class="existing-images" style="margin-bottom: 15px;">';
                echo '<h4 style="margin-top: 0;">Existing Images</h4>';
                echo '<div style="display: flex; flex-wrap: wrap; gap: 10px;">';

                foreach ($review_images as $image_id) {
                    $image_url = \wp_get_attachment_image_url($image_id, 'thumbnail');
                    $full_url = \wp_get_attachment_image_url($image_id, 'full');

                    if ($image_url) {
                        echo '<div class="review-image-item" style="position: relative; border: 1px solid #ddd; padding: 5px; background: #f9f9f9;">';
                        echo '<a href="' . \esc_url($full_url) . '" target="_blank">';
                        echo '<img src="' . \esc_url($image_url) . '" style="display: block; max-width: 100px; height: auto;" />';
                        echo '</a>';
                        echo '<label style="display: block; margin-top: 5px; text-align: center;">';
                        echo '<input type="checkbox" name="delete_review_images[]" value="' . \esc_attr($image_id) . '" /> Delete';
                        echo '</label>';
                        echo '</div>';
                    }
                }

                echo '</div>';
                echo '</div>';
            } else {
                echo '<p>No images uploaded for this review.</p>';
            }

            echo '<div class="upload-new-images">';
            echo '<h4>Upload New Images</h4>';
            echo '<input type="file" name="new_review_images[]" accept="image/jpeg,image/jpg,image/png,image/webp" multiple />';
            echo '<p class="description">You can upload up to 5 images. Max file size: 5MB. Allowed formats: JPG, PNG, WebP</p>';
            echo '</div>';

            echo '</div>';
        },
        'comment',
        'normal'
    );
});

/**
 * Save review images when comment is updated in admin
 */
add_action('edit_comment', function($comment_id) {
    // Verify nonce
    if (!isset($_POST['review_images_nonce']) || !\wp_verify_nonce($_POST['review_images_nonce'], 'review_images_save')) {
        return;
    }

    // Get existing images
    $review_images = \get_comment_meta($comment_id, 'review_images', true);
    $review_images = $review_images ? (is_array($review_images) ? $review_images : []) : [];

    // Handle image deletions
    if (!empty($_POST['delete_review_images']) && is_array($_POST['delete_review_images'])) {
        foreach ($_POST['delete_review_images'] as $image_id) {
            $image_id = intval($image_id);

            // Remove from array
            $review_images = array_diff($review_images, [$image_id]);

            // Delete the attachment
            \wp_delete_attachment($image_id, true);
        }
    }

    // Handle new image uploads
    if (!empty($_FILES['new_review_images']) && !empty($_FILES['new_review_images']['name'][0])) {
        // Require WordPress file handling functions
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $files = $_FILES['new_review_images'];
        $max_files = 5;
        $max_file_size = 5 * 1024 * 1024; // 5MB
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

        // Calculate how many more images we can add
        $current_count = count($review_images);
        $remaining_slots = $max_files - $current_count;

        if ($remaining_slots > 0) {
            $file_count = min(count($files['name']), $remaining_slots);

            for ($i = 0; $i < $file_count; $i++) {
                if (empty($files['name'][$i])) {
                    continue;
                }

                // Validate file size
                if ($files['size'][$i] > $max_file_size) {
                    continue;
                }

                // Validate file type
                if (!in_array($files['type'][$i], $allowed_types)) {
                    continue;
                }

                // Prepare file for upload
                $file = [
                    'name'     => $files['name'][$i],
                    'type'     => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error'    => $files['error'][$i],
                    'size'     => $files['size'][$i]
                ];

                // Upload file
                $upload_overrides = [
                    'test_form' => false,
                    'mimes'     => [
                        'jpg|jpeg|jpe' => 'image/jpeg',
                        'png'          => 'image/png',
                        'webp'         => 'image/webp',
                    ]
                ];

                $uploaded = \wp_handle_upload($file, $upload_overrides);

                if (isset($uploaded['file']) && !isset($uploaded['error'])) {
                    $comment = \get_comment($comment_id);

                    // Create attachment
                    $attachment = [
                        'post_mime_type' => $uploaded['type'],
                        'post_title'     => \sanitize_file_name(\pathinfo($uploaded['file'], PATHINFO_FILENAME)),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    ];

                    $attachment_id = \wp_insert_attachment($attachment, $uploaded['file'], $comment->comment_post_ID);

                    if (!\is_wp_error($attachment_id)) {
                        // Generate attachment metadata
                        $attachment_data = \wp_generate_attachment_metadata($attachment_id, $uploaded['file']);
                        \wp_update_attachment_metadata($attachment_id, $attachment_data);

                        $review_images[] = $attachment_id;
                    }
                }
            }
        }
    }

    // Update comment meta with new image array
    \update_comment_meta($comment_id, 'review_images', array_values($review_images));
});
