//import domReady from '@roots/sage/client/dom-ready';

// if you're using a bundler, first import:
import Headroom from "headroom.js";

// Import calculator
import './calculator.js';


//import Swiper from 'swiper';
import Swiper from 'swiper/bundle';

// grab an element
var myElement = document.querySelector(".header");
var options = {
  // vertical offset in px before element is first unpinned
  offset : 0,
  // or you can specify offset individually for up/down scroll
  offset: {
      up: 100,
      down: 250
  },
  // scroll tolerance in px before state changes
  tolerance : 0,
  // or you can specify tolerance individually for up/down scroll
  tolerance : {
      up : 5,
      down : 0
  },
};
// construct an instance of Headroom, passing the element
var headroom = new Headroom(myElement, options);
// initialise
headroom.init();


jQuery(function($) {

// Function to display selected options
function displaySelectedOptions() {
  let selectedOptions = "";

  // Get all select fields
  const selects = document.querySelectorAll('.wpcpo-option-field.field-select');

  // Get selected values from select fields
  selects.forEach(function(select) {
      if (select.value) {
          selectedOptions += select.value + "<br>";
      }
  });

  // Get all radio buttons
  const radios = document.querySelectorAll('.wpcpo-option-field.field-radio');

  // Get selected value from radio buttons
  radios.forEach(function(radio) {
      if (radio.checked) {
          selectedOptions += radio.value + "<br>";
      }
  });



  }

  // Event listeners for select fields
  const selects = document.querySelectorAll('.wpcpo-option-field.field-select');
  selects.forEach(function(select) {
    select.addEventListener('change', function() {
        //console.log("Select field changed:", this.value);
        displaySelectedOptions();
    });
  });

  // Event listeners for radio buttons
  const radios = document.querySelectorAll('.wpcpo-option-field.field-radio');
  radios.forEach(function(radio) {
    radio.addEventListener('change', function() {
        //console.log("Radio button changed:", this.value);
        displaySelectedOptions();
    });
  });

  // Initial display of selected options (if needed)
  displaySelectedOptions();

    var productList = new Swiper(".product-list-slider", {
      freeMode: true,
      watchSlidesProgress: true,
      touchRatio: 0.2,
      slideToClickedSlide: true,
      breakpoints: {
        768: {
          slidesPerView: 2,
          centeredSlides: true,
          spaceBetween: 4,
        },
        1280: {
          spaceBetween: 4,
          slidesPerView: 7,
        },
      },
    });

    // Function to fade out WooCommerce messages
    function fadeOutWooCommerceMessages() {
      $('.woocommerce-message').each(function() {
        var $message = $(this);
        setTimeout(function() {
          $message.fadeOut('slow');
        }, 2000);
      });
    }

    // Observe changes to the body element and its descendants
    var observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        // Check if the mutation added nodes and if any of those nodes contain a WooCommerce message
        if (mutation.addedNodes.length > 0 && $(mutation.target).find('.woocommerce-message').length > 0) {
          fadeOutWooCommerceMessages();
        }
      });
    });

    // Start observing changes to the body element and its descendants
    observer.observe(document.body, { childList: true, subtree: true });



  $('.product-categories ul.children').each(function() {
      var parentLi = $(this).parent('li');
      parentLi.prepend('<span class="toggle-button"></span>');
  });

  $('.toggle-button').click(function() {
      $(this).toggleClass('open');
      $(this).siblings('ul.children').slideToggle();
  });

  // Open all parent categories of the current category
  $('.current-cat').parents('ul.children').each(function() {
      $(this).prev('.toggle-button').addClass('open');
      $(this).show();
  });

  // Open the first level of sub-categories of the current category
  $('.current-cat > .toggle-button').addClass('open');
  $('.current-cat > ul.children').show();


  var nextEl = ".swiper-button-next";
  var prevEl = ".swiper-button-prev";
    // Get the number of slides
  var numberOfSlides = document.querySelectorAll('.subcategories-slider .swiper-slide').length;

  // Set the maximum number of slides per view
  var maxSlidesPerView = 7;

  // Determine the slides per view based on the number of slides
  var slidesPerView = numberOfSlides < maxSlidesPerView ? 'auto' : maxSlidesPerView;

  var gallerySubcategories = new Swiper(".subcategories-slider", {
    loop: true,
    slidesPerView: numberOfSlides < 2 ? 'auto' : 2,
    spaceBetween: 8,
    keyboardControl: true,
    keyboard: true,
    navigation: numberOfSlides > maxSlidesPerView ? { nextEl: nextEl, prevEl: prevEl } : undefined,
    breakpoints: {
      768: {
        slidesPerView: numberOfSlides < 7 ? 'auto' : 7, // 7 slides per view or 'auto' if fewer
      },
    },

  });
  if (numberOfSlides > maxSlidesPerView) {
      document.querySelector(nextEl).style.display = "flex";
      document.querySelector(prevEl).style.display = "flex";
  }

  var readMoreLink = document.querySelector('.read-more');
  var shortDescription = document.querySelector('.short-description');
  var fullDescription = document.querySelector('.full-description');

  readMoreLink?.addEventListener('click', function(e) {
      e.preventDefault();
      shortDescription.classList.toggle('hidden');
      fullDescription.classList.toggle('hidden');

      if (readMoreLink.textContent === 'ВИЖ ОЩЕ') {
          readMoreLink.textContent = 'ЗАТВОРИ';
      } else {
          readMoreLink.textContent = 'ВИЖ ОЩЕ';
      }
  });



  var homepageSlider = new Swiper(".slider-home", {
    loop: true,
    slidesPerView: 1,
    keyboardControl: true,
    keyboard: true,
    // If we need pagination
    pagination: {
      el: '.swiper-pagination',
    },

    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });

  var categorySlider = new Swiper(".category-list-builder", {
    autoHeight: true, //enable auto height
    keyboardControl: true,
    keyboard: true,
    slidesPerView: 2,
    spaceBetween: 12,
    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    breakpoints: {
      760: {
        slidesPerView: 2,
        spaceBetween: 12,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 12,
      },
      1024: {
        slidesPerView: 4,
        spaceBetween: 12,
      },
      1280: {
        spaceBetween: 16,
        slidesPerView: 5,
      },
      1440: {
        spaceBetween: 16,
        slidesPerView: 6,
      },
    },
  });


  // var moreProductsSLider = new Swiper(".more-products-slider", {
  //   loop: false,
  //   autoHeight: true, //enable auto height
  //   keyboardControl: true,
  //   keyboard: true,
  //   slidesPerView: 2,
  //   spaceBetween: 12,

  //   breakpoints: {
  //     760: {
  //       slidesPerView: 2,
  //       spaceBetween: 12,
  //       pagination: {
  //         el: '.swiper-pagination',
  //         clickable: true,
  //       }
  //     },
  //     768: {
  //       slidesPerView: 3,
  //       spaceBetween: 12,
  //       pagination: {
  //         el: '.swiper-pagination',
  //         clickable: true,
  //       }
  //     },
  //     1024: {
  //       slidesPerView: 4,
  //       spaceBetween: 12,
  //       pagination: {
  //         el: '.swiper-pagination',
  //         clickable: true,
  //       },
  //       // Navigation arrows
  //       navigation: {
  //         nextEl: '.swiper-button-next',
  //         prevEl: '.swiper-button-prev',
  //       }
  //     },
  //     1280: {
  //       spaceBetween: 16,
  //       slidesPerView: 5,
  //       pagination: {
  //         el: '.swiper-pagination',
  //         clickable: true,
  //       },
  //       // Navigation arrows
  //       navigation: {
  //         nextEl: '.swiper-button-next',
  //         prevEl: '.swiper-button-prev',
  //       }
  //     },
  //     1440: {
  //       spaceBetween: 16,
  //       slidesPerView: 6,
  //       pagination: {
  //         el: '.swiper-pagination',
  //         clickable: true,
  //       },
  //       // Navigation arrows
  //       navigation: {
  //         nextEl: '.swiper-button-next',
  //         prevEl: '.swiper-button-prev',
  //       }
  //     },
  //   },
  // });

  
    $('.more-products-slider').each(function() {
      const $slider = $(this);
      const productsCount = parseInt($slider.data('products-count'));
      
      // Only initialize Swiper if 6 or more products
      if (productsCount >= 6) {
          new Swiper($slider[0], {
              loop: false,
              autoHeight: true,
              keyboardControl: true,
              keyboard: true,
              slidesPerView: 2,
              spaceBetween: 12,

              breakpoints: {
                  760: {
                      slidesPerView: 2,
                      spaceBetween: 12,
                      pagination: {
                          el: '.swiper-pagination',
                          clickable: true,
                      }
                  },
                  768: {
                      slidesPerView: 3,
                      spaceBetween: 12,
                      pagination: {
                          el: '.swiper-pagination',
                          clickable: true,
                      }
                  },
                  1024: {
                      slidesPerView: 4,
                      spaceBetween: 12,
                      pagination: {
                          el: '.swiper-pagination',
                          clickable: true,
                      },
                      navigation: {
                          nextEl: '.small-swiper-button-next',
                          prevEl: '.small-swiper-button-prev',
                      }
                  },
                  1280: {
                      spaceBetween: 16,
                      slidesPerView: 5,
                      pagination: {
                          el: '.swiper-pagination',
                          clickable: true,
                      },
                      navigation: {
                          nextEl: '.small-swiper-button-next',
                          prevEl: '.small-swiper-button-prev',
                      }
                  },
                  1440: {
                      spaceBetween: 16,
                      slidesPerView: 6,
                      pagination: {
                          el: '.swiper-pagination',
                          clickable: true,
                      },
                      navigation: {
                          nextEl: '.small-swiper-button-next',
                          prevEl: '.small-swiper-button-prev',
                      }
                  },
              },
          });
      }
  });

  // Initialize Swiper for smaller screens only
// function initSwiper() {
//   if (window.innerWidth < 1024) {
//     return new Swiper(".cart-products-slider", {
//       loop: true,
//       autoHeight: true,
//       keyboardControl: true,
//       keyboard: true,
//       direction: 'horizontal',
//       slidesPerView: 1,
//       spaceBetween: 12,
//       pagination: {
//         el: '.swiper-pagination',
//         clickable: true,
//       },
//       breakpoints: {
//         760: {
//           slidesPerView: 1,
//           spaceBetween: 12,
//           direction: 'horizontal'
//         },
//         768: {
//           slidesPerView: 1,
//           spaceBetween: 12,
//           direction: 'horizontal'
//         }
//       },
//     });
//   }
//   return null;
// }

// let swiper = initSwiper();

// window.addEventListener('resize', function() {
//   if (swiper) {
//     swiper.destroy();
//     swiper = null;
//   }
//   swiper = initSwiper();
// });



  var galleryThumbs = new Swiper(".product-thumbnails", {
    freeMode: true,
    slidesPerView: 5,
    spaceBetween: 4,
    watchSlidesProgress: true,
    touchRatio: 0.2,
    slideToClickedSlide: true,
    breakpoints: {
      760: {
        slidesPerView: 4,
        centeredSlides: true,
        spaceBetween: 4,
      },
      768: {
        slidesPerView: 5,
        centeredSlides: true,
        spaceBetween: 4,
      },
      1280: {
        spaceBetween: 10,
        slidesPerView: 7,
      },
    },
  });



  var landignGallery = new Swiper(".landing-gallery", {
    grabCursor: true,
    a11y: false,
    freeMode: true,
    speed: 11000,
    loop: true,
    slidesPerView: "auto",
    autoplay: {
      delay: 0.5,
      disableOnInteraction: false,
    },
    breakpoints: {
      760: {
        slidesPerView: 2
      },
      768: {
        slidesPerView: 3
      },
      1024: {
        slidesPerView: 4
      },
      1280: {
        slidesPerView: 5
      },
      1440: {
        slidesPerView: 5
      },
    },
  });

  var howToSlider = new Swiper(".how-to-list", {

    slidesPerView: 2,
    spaceBetween: 12,
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true
    },
    breakpoints: {
      760: {
        slidesPerView: 2
      },
      768: {
        slidesPerView: 2
      },
      1024: {
        slidesPerView: 3
      },
      1280: {
        slidesPerView: 3
      },
      1440: {
        slidesPerView: 3
      },
    },
  });

  var featuresLanding = new Swiper(".features-landing", {
    loop: true,
    slidesPerView: 2,
    spaceBetween: 12,
    autoplay: {
      delay: 2500,
      disableOnInteraction: false,
    },

    breakpoints: {
      760: {
        slidesPerView: 1,
        spaceBetween: 12
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 12
      },
      1024: {
        slidesPerView: 4,
        spaceBetween: 12
      },
      1280: {
        spaceBetween: 16,
        slidesPerView: 4
      },
      1440: {
        spaceBetween: 32,
        slidesPerView: 4
      },
    },
  });


  $('#desktop-menu').on('click', function(e) {
    e.preventDefault()
    $('.main-nav').toggleClass('active')
  });

  if($(window).width() <= 1024) {
    $('ul.nav li').on("click", function () {
      $('ul.nav li').not($(this)).removeClass('active')
      $(this).toggleClass('active')
    })
  }


  $('.mobile-nav .dropdown-products').detach().insertAfter('.header .products a')

  $(document).on('click', '#close-cart-popup', function () {
    $('.overlay, .cart-popup').removeClass('active')
    $('.overlay, .mobile-nav').removeClass('active')
    $(document.body).removeClass('disable-scroll');
  });

  $('#search-menu').on('click', function(e) {
    e.preventDefault()
    $('.search-box').toggleClass('active')
  });

  $('.overlay').on('click', function() {
    $(document.body).removeClass('disable-scroll');
    $('#close-cart-popup').trigger('click')
    $('.cart-content .popup, .popup-video').removeClass('active')
    if($(window).width() <= 768) {
        $('.hamburger-button').removeClass('active')
        $('.main-nav').slideUp()
    }
  });

  $('#openTableSizeModal').on('click', function(e) {
    e.preventDefault()
    $('#tableSizeModal').addClass('open')
    $(document.body).addClass('disable-scroll');
  });

  $('#closeTableSizeModal').on('click', function(e) {
    e.preventDefault()
    $('#tableSizeModal').removeClass('open')
    $(document.body).removeClass('disable-scroll');
  });

  $('#mini-cart').on("click", function (e) {
    e.preventDefault()
    $('.overlay, .cart-popup').addClass('open')
    $(document.body).addClass('disable-scroll');
  });

  $('#mobile-menu').on("click", function (e) {
    e.preventDefault()
    $('.overlay, .mobile-nav').addClass('active')
    $(document.body).addClass('disable-scroll');
  });

  $('#toggleFilters').on('click', function(e) {
    e.preventDefault()
    $('#aside').toggleClass('open')
  });

  if ( $( 'body' ).first().hasClass( 'woocommerce-cart' ) ) {
    $('#mini-cart').on("click", function (e) {
      e.preventDefault()
        $('.overlay').addClass('hidden')
        $(document.body).addClass('auto');
    })
  }

  $('.cart-info').click(function (e) {
    e.preventDefault()
    $('.overlay, .cart-popup').addClass('active')
    $(document.body).addClass('disable-scroll');
  })

  // $('.quantity-inner .qty').on('change', function () {
  //     let qtyVal = $(this).val()
  //     $('.btn-submit-single-product').attr('data-qty', qtyVal)
  //     //console.log('qtyVal', qtyVal)
  // })


  setTimeout(function() {
    jQuery('.woocommerce-message').fadeOut('fast')
  }, 2000);



  $('#play-video').click(function(e) {
    e.preventDefault(); // Prevent default action of the link

    // Prepare the iframe using the videoSrc variable
    var iframe = $('<iframe/>', {
      'src': videoSrc,
      'width': '100%',
      'height': '500',
      'frameborder': '0',
      'allow': 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture',
      'allowfullscreen': true
    });

    // Append the iframe to the container
    $('#videoContainer').html(iframe);

    // Show the modal
    $('#videoModal').removeClass('hidden').addClass('flex');
  });

  // Close the modal
  $('.close-modal').click(function() {
    $('#videoModal').removeClass('flex').addClass('hidden');
    $('#videoContainer').html(''); // Remove the iframe
  });



  $('#quick-order').click(function(e) {
    e.preventDefault(); // Prevent default action of the link

    // Show the modal
    $('#quickModal').removeClass('hidden').addClass('block');
  });

  // Close the modal
  $('.close-modal').click(function() {
    $('#quickModal').removeClass('block').addClass('hidden');
  });


});


var canRunClickFunc = true;

function makeTouchstartWithClick(event) {
  if (!canRunClickFunc) {
    return false;
  }
  setTimeout(function() {
    canRunClickFunc = true;
  }, 700);
  var elem = event.target;
  var elemp = elem.closest('.close-drawer');
  if (elem.classList.contains('close-drawer') || elemp) {
    document.querySelector('body').classList.remove('filter-open');
    document.querySelector('body').classList.remove('mobile-toggled');
    document.querySelector('body').classList.remove('drawer-open');
    return;
  }
  var elemp = elem.closest('.menu-toggle');
  if (elem.classList.contains('menu-toggle') || elemp) {
    event.stopPropagation();
    event.preventDefault();
    document.querySelector('body').classList.add('mobile-toggled');
    return;
  }
  if (elem.classList.contains('mobile-overlay')) {
    document.querySelector('body').classList.remove('filter-open');
    document.querySelector('body').classList.remove('mobile-toggled');
    return;
  }
}

document.addEventListener('DOMContentLoaded', function() {
  window.addEventListener('load', function(event) {
    var vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', vh + 'px');
  });
  window.addEventListener('resize', function(event) {
    var vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', vh + 'px');
  });

  document.addEventListener('click', function(event) {
    var is_inner = event.target.closest('.cart-popup');
    if (!event.target.classList.contains('cart-popup') && !is_inner) {
      document.querySelector('body').classList.remove('drawer-open');
    }
    var is_inner2 = event.target.closest('.cart-click');
    if (event.target.classList.contains('cart-click') || is_inner2) {
      var is_header = event.target.closest('.site-header-cart');
      if (is_header) {
        event.preventDefault();
        document.querySelector('body').classList.toggle('drawer-open');
        document.getElementById('zahCartDrawer').focus();
      }
    }
    if (event.target.classList.contains('close-drawer')) {
      document.querySelector('body').classList.remove('drawer-open');
    }
    makeTouchstartWithClick(event);
  });

  // initSingleProductAjax();
});

// jQuery part for WooCommerce cart events
jQuery(document).ready(function($) {
  $('body').on('added_to_cart', function(event, fragments, cart_hash) {
    if (!$('body').hasClass('elementor-editor-active')) {
      $('body').addClass('drawer-open');
      $('#zahCartDrawer').focus();
    }
  });
});

// XMLHttpRequest interceptor for AJAX loading indicator
var interceptor = (function(open) {
  XMLHttpRequest.prototype.open = function(method, url, async, user, pass) {
    this.addEventListener('readystatechange', function() {
      switch (this.readyState) {
        case 1:
          setTimeout(function() {
            document.querySelector('#ajax-loading').style.display = 'block';
          }, 200);
          break;
        case 4:
          setTimeout(function() {
            document.querySelector('#ajax-loading').style.display = 'none';
          }, 200);
          break;
      }
    }, false);
    if (async !== false) {
      async = true;
    }
    open.call(this, method, url, async, user, pass);
  };
})(XMLHttpRequest.prototype.open);

document.addEventListener('DOMContentLoaded', function() {
  document.querySelector('#ajax-loading').style.display = 'none';
});


function cartDrawerTrapTabKey( event ) {
	var element = document.querySelector( 'body.drawer-open #zahCartDrawer' );
	if ( element ) {
		if ( event.key.toLowerCase() == 'escape' ) {
			document.querySelector( 'body' ).classList.remove( 'drawer-open' );
			return;
		} else if ( event.key.toLowerCase() == 'tab' ) {
			var inputs = ['a[href]','area[href]','input:not([disabled]):not([type="hidden"]):not([aria-hidden])','select:not([disabled]):not([aria-hidden])','textarea:not([disabled]):not([aria-hidden])','button:not([disabled]):not([aria-hidden])','iframe','object','embed','[contenteditable]','[tabindex]:not([tabindex^="-"])'];
			var nodes = element.querySelectorAll(inputs);
			var focusables = Array( ...nodes );
			if ( focusables.length === 0 ) {
				return;
			}
			focusables = focusables.filter( ( node ) => {
				return node.offsetParent !== null;
			} );
			if ( ! element.contains( document.activeElement ) ) {
				focusables[0].focus();
			} else {
				var focusedIndex = focusables.indexOf( document.activeElement );
				if ( event.shiftKey && focusedIndex === 0 ) {
					focusables[ focusables.length - 1 ].focus();
					event.preventDefault();
				}
				if ( ! event.shiftKey && focusables.length > 0 && focusedIndex === focusables.length - 1 ) {
					focusables[0].focus();
					event.preventDefault();
				}
			}
		}
	}


}



/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
if (import.meta.webpackHot) import.meta.webpackHot.accept(console.error);
