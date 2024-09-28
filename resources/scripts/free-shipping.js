jQuery(document).ready(function($) {
  function updateFreeShippingBar() {
      let cartTotal = parseFloat($('.cart-subtotal .woocommerce-Price-amount.amount').text().replace(/[^\d.]/g, ''));
      let minAmount = parseFloat($('.free-delivery-bar--cart span').data('min'));
      let awayFromFreeDelivery = minAmount - cartTotal;
      let progressPercentage = (cartTotal / minAmount) * 100;

      if (progressPercentage > 100) {
          progressPercentage = 100;
      }

      $('.free-delivery-bar--cart .bar-body span').css('width', progressPercentage + '%');

      if (cartTotal < minAmount) {
          $('.free-delivery-bar--cart p span').text(wc_price(awayFromFreeDelivery));
          $('.free-delivery-bar--cart p').html(`👋 AYou are <span>${wc_price(awayFromFreeDelivery)}</span> away from free delivery`);
      } else {
          $('.free-delivery-bar--cart p').html(`<span class="icon icon-tick-circle"></span> A Congrats! You've reached free shipping.`);
      }
  }

  $(document.body).on('updated_cart_totals', function() {
      updateFreeShippingBar();
  });

  updateFreeShippingBar();
});
