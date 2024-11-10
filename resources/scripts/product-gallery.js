jQuery(document).ready(function($) {
    let mainSlider = null;
    let thumbnailSlider = null;
    let modalSlider = null;

    // Calculate settings based on screen size
    const calculateSettings = () => {
        const isMobile = window.innerWidth <= 768;
        return {
            spaceBetween: isMobile ? 10 : 0,
            slidesPerView: isMobile ? 1.2 : 1, // Show 20% of next slide on mobile
            showThumbnails: true // Always show thumbnails
        };
    };

    let settings = calculateSettings();

    function initSliders() {
        // Initialize thumbnail slider (desktop only)
        if (settings.showThumbnails) {
            thumbnailSlider = new Swiper('.product-thumbnails', {
                spaceBetween: 10,
                slidesPerView: 4,
                watchSlidesProgress: true,
                loop: true,
                loopedSlides: 4,
                preloadImages: false,
                lazy: true,
                centerInsufficientSlides: true,
                navigation: {
                    nextEl: '.thumb-next',
                    prevEl: '.thumb-prev',
                },
                breakpoints: {
                    768: {
                        slidesPerView: 4,
                        spaceBetween: 10
                    }
                }
            });
        }

        // Initialize main slider
        mainSlider = new Swiper('.product-main-images', {
            autoHeight: true,
            loop: true,
            loopedSlides: 4,
            spaceBetween: settings.spaceBetween,
            slidesPerView: settings.slidesPerView,
            threshold: 5, // Increase resistance to accidental swipes
            
            lazy: {
                loadPrevNext: true,
                loadPrevNextAmount: 2,
                preloaderClass: 'swiper-lazy-preloader'
            },
            
            navigation: {
                nextEl: '.main-next',
                prevEl: '.main-prev',
            },
            
            pagination: {
                el: '.swiper-pagination',
                type: 'bullets',
                clickable: true,
                dynamicBullets: true,
                dynamicMainBullets: 3
            },
            
            thumbs: settings.showThumbnails ? {
                swiper: thumbnailSlider,
                autoScrollOffset: 1
            } : null,
            
            zoom: {
                maxRatio: 3,
                minRatio: 1,
            },
            
            keyboard: { enabled: true },
            watchOverflow: true,
            observer: true,
            observeParents: true,
            
            on: {
                init: function() {
                    // Setup pagination
                    if (!$('.product-main-images .swiper-pagination').length) {
                        $('.product-main-images').append('<div class="swiper-pagination"></div>');
                    }
                    
                    // Load edge preview on mobile
                    if (!settings.showThumbnails) {
                        loadNextSlideImage();
                        // Hide thumbnails on mobile
                        $('.product-thumbnails').hide();
                    }

                    // Update slide heights
                    updateSlideHeights();
                },
                
                slideChange: function() {
                    // Load next slide for edge preview
                    if (!settings.showThumbnails) {
                        loadNextSlideImage();
                    }
                    handleLazyLoad();
                },
                
                resize: function() {
                    updateSlideHeights();
                }
            }
        });

        // Thumbnail click handler
        $('.product-thumbnails').on('click', '.swiper-slide', function() {
            const index = $(this).index();
            mainSlider.slideTo(index);
        });

        return { mainSlider, thumbnailSlider };
    }

    function updateSlideHeights() {
        const slides = $('.product-main-images .swiper-slide');
        let maxHeight = 0;
        
        slides.each(function() {
            const height = $(this).find('img').height();
            maxHeight = Math.max(maxHeight, height);
        });

        if (maxHeight > 0) {
            slides.find('img').css('height', maxHeight);
        }
    }

    function loadNextSlideImage() {
        const nextSlide = $('.product-main-images .swiper-slide-next img[data-src]');
        if (nextSlide.length) {
            nextSlide.attr('src', nextSlide.data('src'))
                     .removeAttr('data-src');
            
            if (nextSlide.data('srcset')) {
                nextSlide.attr('srcset', nextSlide.data('srcset'))
                        .removeAttr('data-srcset');
            }
        }
    }

    function initModal() {
        const modal = $('#gallery-modal');

        $('.product-main-images .swiper-slide:not(.swiper-slide-duplicate)').on('click', function(e) {
            e.preventDefault();
            const currentIndex = $(this).index();
            
            if (!modalSlider) {
                const modalSlides = $('.product-main-images .swiper-slide:not(.swiper-slide-duplicate)').map(function() {
                    const img = $(this).find('img').clone();
                    return `
                        <div class="swiper-slide">
                            <div class="swiper-zoom-container">
                                ${img.prop('outerHTML')}
                            </div>
                        </div>
                    `;
                }).get().join('');

                $('.modal-swiper .swiper-wrapper').html(modalSlides);

                modalSlider = new Swiper('.modal-swiper', {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    loop: true,
                    loopedSlides: 4,
                    zoom: {
                        maxRatio: 3,
                        minRatio: 1,
                    },
                    navigation: {
                        nextEl: '.modal-next',
                        prevEl: '.modal-prev',
                    },
                    keyboard: { enabled: true },
                    on: {
                        zoomChange: function(scale, imageEl, slideEl) {
                            $(slideEl).toggleClass('is-zoomed', scale > 1);
                        }
                    }
                });

                modalSlider.on('slideChange', function() {
                    if (mainSlider) {
                        mainSlider.slideTo(modalSlider.realIndex);
                    }
                });
            }

            modal.addClass('active').removeClass('hidden');
            $('body').addClass('modal-open');
            modalSlider.slideTo(currentIndex);
            modalSlider.update();
        });

        // Close modal handlers
        $('.modal-close, .modal-overlay').on('click', function() {
            modal.removeClass('active').addClass('hidden');
            $('body').removeClass('modal-open');
            if (modalSlider) modalSlider.zoom.out();
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && modal.hasClass('active')) {
                modal.removeClass('active').addClass('hidden');
                $('body').removeClass('modal-open');
            }
        });

        // Double tap to zoom
        let lastTap = 0;
        $('.modal-swiper').on('touchend', '.swiper-slide', function(e) {
            const currentTime = new Date().getTime();
            const tapLength = currentTime - lastTap;
            
            if (tapLength < 500 && tapLength > 0) {
                if ($(this).hasClass('is-zoomed')) {
                    modalSlider.zoom.out();
                } else {
                    modalSlider.zoom.in();
                }
            }
            lastTap = currentTime;
        });
    }

    // Resize handling
    let resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            const newSettings = calculateSettings();
            
            if (newSettings.showThumbnails !== settings.showThumbnails) {
                settings = newSettings;
                if (mainSlider) mainSlider.destroy();
                if (thumbnailSlider) thumbnailSlider.destroy();
                initSliders();
            } else {
                settings = newSettings;
                if (mainSlider) {
                    mainSlider.params.spaceBetween = settings.spaceBetween;
                    mainSlider.params.slidesPerView = settings.slidesPerView;
                    mainSlider.update();
                }
            }
            
            if (modalSlider) modalSlider.update();
            $('.product-thumbnails').toggle(settings.showThumbnails);
        }, 250);
    });

    // WooCommerce variation handling
    if ($('form.variations_form').length) {
        $('form.variations_form')
            .on('show_variation', function(event, variation) {
                if (variation.image && variation.image.full_src) {
                    const slideIndex = $('.product-main-images img[src="' + variation.image.full_src + '"]')
                        .closest('.swiper-slide')
                        .index();
                    
                    if (slideIndex >= 0) {
                        mainSlider.slideTo(slideIndex);
                    }
                }
            })
            .on('reset_image', function() {
                mainSlider.slideTo(0);
            });
    }

    function handleLazyLoad() {
        $('.product-main-images .swiper-slide-visible img[data-src], .product-thumbnails .swiper-slide-visible img[data-src]').each(function() {
            const $img = $(this);
            const src = $img.data('src');
            if (src) {
                $img.attr('src', src).removeAttr('data-src');
                if ($img.data('srcset')) {
                    $img.attr('srcset', $img.data('srcset')).removeAttr('data-srcset');
                }
            }
        });
    }

    // Initialize everything
    initSliders();
    initModal();
    handleLazyLoad();
});