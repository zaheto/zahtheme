jQuery(document).ready(function ($) {
    if (typeof Swiper === 'undefined') {
      console.error('Swiper is required for subcategories slider');
      return;
    }
  
    function initSubcategoriesSlider() {
      const sliderContainer = document.querySelector('.subcategories-slider');
      if (!sliderContainer) return;
  
      const numberOfSlides = document.querySelectorAll('.subcategories-slider .swiper-slide').length;
      
      if (numberOfSlides > 0) {
        try {
          const subcategoriesSlider = new Swiper(".subcategories-slider", {
            loop: false,
            slidesPerView: 2, // Default for mobile
            spaceBetween: 8,
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
              // => 768px and up
              768: {
                slidesPerView: 4,
                spaceBetween: 16,
              },
              // => 1024px and up
              1028: {
                slidesPerView: 6,
                spaceBetween: 16,
              },
              // => 1280px and up
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
  
    initSubcategoriesSlider();
});