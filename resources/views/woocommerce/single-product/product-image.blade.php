<?php
defined('ABSPATH') || exit;

global $product;

$attachment_ids = $product->get_gallery_image_ids();
?>

<section class="woocommerce-product-gallery">
  <div  class="swiper product-main-images ">

    <div class="swiper-wrapper">
        <div class="swiper-slide">
          <div class="swiper-zoom-container">
            <?php echo woocommerce_get_product_thumbnail('full'); ?>
          </div>
        </div>

        <!-- Additional Images -->
        <?php foreach ($attachment_ids as $attachment_id) : ?>
            <div class="swiper-slide">
              <div class="swiper-zoom-container">
                  <?php echo wp_get_attachment_image($attachment_id, 'shop_single'); ?>
              </div>
            </div>
        <?php endforeach; ?>
      </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </div>

  <div class="swiper product-thumbnails ">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
          <?php echo woocommerce_get_product_thumbnail('shop_thumbnail'); ?>
        </div>
        <!-- Additional Thumbnails -->
        <?php foreach ($attachment_ids as $attachment_id) : ?>
            <div class="swiper-slide">
                <?php echo wp_get_attachment_image($attachment_id, 'shop_thumbnail'); ?>
            </div>
        <?php endforeach; ?>
    </div>
  </div>


</section>


