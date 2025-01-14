jQuery(document).ready(function ($) {
  let subcategoriesSlider;

  function initSubcategoriesSlider() {
      const sliderContainer = document.querySelector('.subcategories-slider');
      if (!sliderContainer) return;

      // Check if element is actually visible
      const isVisible = window.getComputedStyle(sliderContainer).display !== 'none';
      if (!isVisible || window.innerWidth < 768) return;

      const numberOfSlides = document.querySelectorAll('.subcategories-slider .swiper-slide').length;
      
      if (numberOfSlides > 0) {
          try {
              // First, ensure proper Swiper classes
              sliderContainer.classList.add('swiper');
              
              // Add swiper-initialized class to prevent multiple initializations
              if (sliderContainer.classList.contains('swiper-initialized')) {
                  return;
              }

              subcategoriesSlider = new Swiper(".subcategories-slider", {
                  loop: false,
                  slidesPerView: 4,
                  spaceBetween: 16,
                  centeredSlides: false,
                  initialSlide: 0,
                  roundLengths: true,
                  watchOverflow: true,
                  enabled: true,
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
                          slidesPerView: Math.min(4, numberOfSlides),
                          spaceBetween: 16,
                          enabled: true
                      },
                      1028: {
                          slidesPerView: Math.min(4, numberOfSlides),
                          spaceBetween: 16,
                          enabled: true
                      },
                      1280: {
                          slidesPerView: Math.min(5, numberOfSlides),
                          spaceBetween: 16,
                          enabled: true
                      }
                  },
                  on: {
                      init: function() {
                          const prevButton = document.querySelector('.subcategories-prev');
                          const nextButton = document.querySelector('.subcategories-next');
                          
                          if (numberOfSlides > this.params.slidesPerView) {
                              prevButton.style.display = 'flex';
                              nextButton.style.display = 'flex';
                              prevButton.style.opacity = this.isBeginning ? '0' : '1';
                              prevButton.style.pointerEvents = this.isBeginning ? 'none' : 'auto';
                          } else {
                              prevButton.style.display = 'none';
                              nextButton.style.display = 'none';
                          }
                      },
                      slideChange: function() {
                          const prevButton = document.querySelector('.subcategories-prev');
                          const nextButton = document.querySelector('.subcategories-next');
                          
                          prevButton.style.opacity = this.isBeginning ? '0' : '1';
                          prevButton.style.pointerEvents = this.isBeginning ? 'none' : 'auto';
                          
                          nextButton.style.opacity = this.isEnd ? '0' : '1';
                          nextButton.style.pointerEvents = this.isEnd ? 'none' : 'auto';
                      }
                  }
              });

              return subcategoriesSlider;
          } catch (error) {}
      }
  }

  // Initialize with a small delay to ensure DOM is ready
  setTimeout(initSubcategoriesSlider, 100);

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