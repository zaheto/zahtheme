jQuery(document).ready(function ($) {
  if (typeof Swiper === 'undefined') {
    console.error('Swiper is required for subcategories slider');
    return;
  }

  let subcategoriesSlider;

  function initSubcategoriesSlider() {
    const sliderContainer = document.querySelector('.subcategories-slider');
    if (!sliderContainer || window.innerWidth < 768) return;

    const numberOfSlides = document.querySelectorAll('.subcategories-slider .swiper-slide').length;
    
    if (numberOfSlides > 0) {
      try {
        subcategoriesSlider = new Swiper(".subcategories-slider", {
          loop: false,
          slidesPerView: 4,
          spaceBetween: 16,
          centeredSlides: false,
          initialSlide: 0,
          roundLengths: true,
          watchOverflow: true,
          keyboard: {
            enabled: true,
            onlyInViewport: true,
          },
          navigation: {
            nextEl: '.subcategories-next',
            prevEl: '.subcategories-prev'
          },
          breakpoints: {
            768: {
              slidesPerView: 4,
              spaceBetween: 16,
            },
            1028: {
              slidesPerView: 6,
              spaceBetween: 16,
            },
            1280: {
              slidesPerView: 7,
              spaceBetween: 16,
            }
          },
          on: {
            init: function() {
              // Hide prev button on initial load
              document.querySelector('.subcategories-prev').style.opacity = '0';
              document.querySelector('.subcategories-prev').style.pointerEvents = 'none';
            },
            slideChange: function() {
              const prevButton = document.querySelector('.subcategories-prev');
              const nextButton = document.querySelector('.subcategories-next');
              
              // Handle prev button
              if (this.isBeginning) {
                prevButton.style.opacity = '0';
                prevButton.style.pointerEvents = 'none';
              } else {
                prevButton.style.opacity = '1';
                prevButton.style.pointerEvents = 'auto';
              }
              
              // Handle next button
              if (this.isEnd) {
                nextButton.style.opacity = '0';
                nextButton.style.pointerEvents = 'none';
              } else {
                nextButton.style.opacity = '1';
                nextButton.style.pointerEvents = 'auto';
              }
            }
          }
        });

        const navigationButtons = document.querySelectorAll('.subcategories-prev, .subcategories-next');
        navigationButtons.forEach(button => {
          if (numberOfSlides > 7) {
            button.style.display = 'flex';
          } else {
            button.style.display = 'none';
          }
        });

        return subcategoriesSlider;
      } catch (error) {
        console.error('Error initializing Swiper:', error);
      }
    }
  }

  // Initialize on load
  initSubcategoriesSlider();

  // Handle resize events
  let subcategoriesResizeTimer;
  window.addEventListener('resize', function() {
      clearTimeout(subcategoriesResizeTimer);
      subcategoriesResizeTimer = setTimeout(function() {
          if (subcategoriesSlider && typeof subcategoriesSlider.destroy === 'function') {
              subcategoriesSlider.destroy();
          }
          initSubcategoriesSlider();
      }, 250);
  });
});