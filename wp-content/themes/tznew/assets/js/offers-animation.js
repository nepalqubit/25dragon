/**
 * Offers Section Animation
 * Handles fade animation between multiple offers
 */

(function($) {
    'use strict';

    class OffersAnimation {
        constructor() {
            this.currentIndex = 0;
            this.offers = $('.offer-card');
            this.totalOffers = this.offers.length;
            this.animationSpeed = parseInt($('#offers-section').data('animation-speed')) || 5000;
            this.isAnimating = false;
            
            if (this.totalOffers > 0) {
                this.init();
            }
        }

        init() {
            // Show all offers simultaneously for pair display
            this.offers.show().addClass('active');
            
            // Add hover effects for individual cards
            this.offers.hover(
                function() {
                    $(this).addClass('hover-effect');
                },
                function() {
                    $(this).removeClass('hover-effect');
                }
            );
        }

        startAnimation() {
            this.animationInterval = setInterval(() => {
                if (!this.isAnimating) {
                    this.nextOffer();
                }
            }, this.animationSpeed);
        }

        pauseAnimation() {
            clearInterval(this.animationInterval);
        }

        resumeAnimation() {
            this.startAnimation();
        }

        nextOffer() {
            if (this.isAnimating || this.totalOffers <= 1) return;
            
            this.isAnimating = true;
            const currentOffer = this.offers.eq(this.currentIndex);
            const nextIndex = (this.currentIndex + 1) % this.totalOffers;
            const nextOffer = this.offers.eq(nextIndex);

            // Fade out current offer
            currentOffer.fadeOut(600, () => {
                currentOffer.removeClass('active');
                
                // Fade in next offer
                nextOffer.fadeIn(600, () => {
                    nextOffer.addClass('active');
                    this.currentIndex = nextIndex;
                    this.isAnimating = false;
                });
            });
        }

        goToOffer(index) {
            if (this.isAnimating || index === this.currentIndex || index >= this.totalOffers) return;
            
            this.isAnimating = true;
            const currentOffer = this.offers.eq(this.currentIndex);
            const targetOffer = this.offers.eq(index);

            currentOffer.fadeOut(400, () => {
                currentOffer.removeClass('active');
                
                targetOffer.fadeIn(400, () => {
                    targetOffer.addClass('active');
                    this.currentIndex = index;
                    this.isAnimating = false;
                });
            });
        }
    }

    // Initialize when document is ready
    $(document).ready(function() {
        if ($('#offers-section').length) {
            new OffersAnimation();
        }
    });

})(jQuery);