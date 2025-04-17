define([
    'jquery',
    'domReady!'
], function ($) {
    'use strict';

    return function (config) {
        var sliderId = config.sliderId;
        var $slider = $('#' + sliderId);
        var $slides = $slider.find('.lookbook-slide');
        var $prevBtn = $('.lookbook-slider-prev[data-slider-id="' + sliderId + '"]');
        var $nextBtn = $('.lookbook-slider-next[data-slider-id="' + sliderId + '"]');
        var slideCount = $slides.length;
        var slideWidth = $slides.outerWidth(true);
        var visibleSlides = Math.floor($slider.width() / slideWidth);
        var currentPosition = 0;
        var maxPosition = slideCount - visibleSlides;

        // Initialize slider
        function initSlider() {
            // Set slider width
            $slider.css({
                'position': 'relative',
                'overflow': 'hidden',
                'display': 'flex',
                'transition': 'transform 0.5s ease'
            });

            // Set slide width
            $slides.css({
                'flex': '0 0 auto',
                'margin-right': '15px'
            });

            // Hide prev button at start
            if (currentPosition === 0) {
                $prevBtn.addClass('disabled');
            }

            // Hide next button if all slides are visible
            if (slideCount <= visibleSlides) {
                $nextBtn.addClass('disabled');
            }

            // Handle window resize
            $(window).on('resize', function() {
                slideWidth = $slides.outerWidth(true);
                visibleSlides = Math.floor($slider.width() / slideWidth);
                maxPosition = slideCount - visibleSlides;

                // Reset position if needed
                if (currentPosition > maxPosition) {
                    currentPosition = maxPosition > 0 ? maxPosition : 0;
                    updateSliderPosition();
                }

                // Update button states
                updateButtonStates();
            });

            // Attach click handlers
            $prevBtn.on('click', prevSlide);
            $nextBtn.on('click', nextSlide);
        }

        // Go to previous slide
        function prevSlide() {
            if (currentPosition > 0) {
                currentPosition--;
                updateSliderPosition();
                updateButtonStates();
            }
        }

        // Go to next slide
        function nextSlide() {
            if (currentPosition < maxPosition) {
                currentPosition++;
                updateSliderPosition();
                updateButtonStates();
            }
        }

        // Update slider position
        function updateSliderPosition() {
            var translateX = -currentPosition * slideWidth;
            $slider.css('transform', 'translateX(' + translateX + 'px)');
        }

        // Update button states
        function updateButtonStates() {
            // Enable/disable prev button
            if (currentPosition === 0) {
                $prevBtn.addClass('disabled');
            } else {
                $prevBtn.removeClass('disabled');
            }

            // Enable/disable next button
            if (currentPosition >= maxPosition || slideCount <= visibleSlides) {
                $nextBtn.addClass('disabled');
            } else {
                $nextBtn.removeClass('disabled');
            }
        }



        // Initialize the slider
        initSlider();
    };
});
