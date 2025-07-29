/**
 * Testimonial Frontend JavaScript
 *
 * @package TZnew
 */

(function($) {
    'use strict';

    /**
     * Testimonial Slider Class
     */
    class TestimonialSlider {
        constructor(element, options = {}) {
            this.slider = $(element);
            this.wrapper = this.slider.find('.testimonials-slider-wrapper');
            this.slides = this.wrapper.find('.testimonial-slide');
            this.prevBtn = this.slider.find('.slider-prev');
            this.nextBtn = this.slider.find('.slider-next');
            this.dots = this.slider.find('.slider-dot');
            
            this.options = {
                autoplay: true,
                autoplayDelay: 5000,
                loop: true,
                ...options
            };
            
            this.currentSlide = 0;
            this.totalSlides = this.slides.length;
            this.autoplayTimer = null;
            
            this.init();
        }
        
        init() {
            if (this.totalSlides <= 1) {
                this.hideControls();
                return;
            }
            
            this.bindEvents();
            this.updateSlider();
            
            if (this.options.autoplay) {
                this.startAutoplay();
            }
        }
        
        bindEvents() {
            this.prevBtn.on('click', () => this.prevSlide());
            this.nextBtn.on('click', () => this.nextSlide());
            
            this.dots.on('click', (e) => {
                const index = $(e.target).data('slide');
                this.goToSlide(index);
            });
            
            // Pause autoplay on hover
            this.slider.on('mouseenter', () => this.pauseAutoplay());
            this.slider.on('mouseleave', () => {
                if (this.options.autoplay) {
                    this.startAutoplay();
                }
            });
            
            // Keyboard navigation
            $(document).on('keydown', (e) => {
                if (this.slider.is(':hover')) {
                    if (e.key === 'ArrowLeft') {
                        this.prevSlide();
                    } else if (e.key === 'ArrowRight') {
                        this.nextSlide();
                    }
                }
            });
        }
        
        prevSlide() {
            this.currentSlide = this.currentSlide > 0 ? this.currentSlide - 1 : 
                               (this.options.loop ? this.totalSlides - 1 : 0);
            this.updateSlider();
        }
        
        nextSlide() {
            this.currentSlide = this.currentSlide < this.totalSlides - 1 ? this.currentSlide + 1 : 
                               (this.options.loop ? 0 : this.totalSlides - 1);
            this.updateSlider();
        }
        
        goToSlide(index) {
            if (index >= 0 && index < this.totalSlides) {
                this.currentSlide = index;
                this.updateSlider();
            }
        }
        
        updateSlider() {
            const translateX = -this.currentSlide * 100;
            this.wrapper.css('transform', `translateX(${translateX}%)`);
            
            // Update dots
            this.dots.removeClass('active');
            this.dots.eq(this.currentSlide).addClass('active');
            
            // Update button states
            if (!this.options.loop) {
                this.prevBtn.prop('disabled', this.currentSlide === 0);
                this.nextBtn.prop('disabled', this.currentSlide === this.totalSlides - 1);
            }
        }
        
        startAutoplay() {
            this.pauseAutoplay();
            this.autoplayTimer = setInterval(() => {
                this.nextSlide();
            }, this.options.autoplayDelay);
        }
        
        pauseAutoplay() {
            if (this.autoplayTimer) {
                clearInterval(this.autoplayTimer);
                this.autoplayTimer = null;
            }
        }
        
        hideControls() {
            this.prevBtn.hide();
            this.nextBtn.hide();
            this.dots.parent().hide();
        }
    }

    /**
     * Testimonial Grid Class
     */
    class TestimonialGrid {
        constructor(element, options = {}) {
            this.grid = $(element);
            this.cards = this.grid.find('.testimonial-card');
            
            this.options = {
                animateOnScroll: true,
                animationDelay: 100,
                ...options
            };
            
            this.init();
        }
        
        init() {
            if (this.options.animateOnScroll) {
                this.setupScrollAnimation();
            }
            
            this.setupCardInteractions();
        }
        
        setupScrollAnimation() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            $(entry.target).addClass('animate-in');
                        }, index * this.options.animationDelay);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            
            this.cards.each(function() {
                observer.observe(this);
            });
        }
        
        setupCardInteractions() {
            this.cards.on('click', function(e) {
                // Prevent default if clicking on interactive elements
                if ($(e.target).is('a, button, input, select, textarea')) {
                    return;
                }
                
                // Add click effect
                $(this).addClass('clicked');
                setTimeout(() => {
                    $(this).removeClass('clicked');
                }, 200);
            });
        }
    }

    /**
     * Star Rating Display
     */
    function initStarRatings() {
        $('.star-rating').each(function() {
            const rating = parseFloat($(this).data('rating')) || 0;
            const stars = $(this).find('i');
            
            stars.each(function(index) {
                const starValue = index + 1;
                const $star = $(this);
                
                if (rating >= starValue) {
                    $star.addClass('filled');
                } else if (rating > starValue - 1) {
                    // Partial star
                    const percentage = (rating - (starValue - 1)) * 100;
                    $star.addClass('partial').css('background', 
                        `linear-gradient(90deg, #f59e0b ${percentage}%, #d1d5db ${percentage}%)`);
                }
            });
        });
    }

    /**
     * Testimonial Filtering
     */
    class TestimonialFilter {
        constructor(container) {
            this.container = $(container);
            this.filters = this.container.find('[data-filter]');
            this.items = this.container.find('.testimonial-card');
            
            this.init();
        }
        
        init() {
            this.filters.on('click', (e) => {
                e.preventDefault();
                const filter = $(e.target).data('filter');
                this.applyFilter(filter);
                this.updateActiveFilter($(e.target));
            });
        }
        
        applyFilter(filter) {
            this.items.each(function() {
                const $item = $(this);
                const shouldShow = filter === 'all' || $item.hasClass(filter);
                
                if (shouldShow) {
                    $item.fadeIn(300);
                } else {
                    $item.fadeOut(300);
                }
            });
        }
        
        updateActiveFilter($activeFilter) {
            this.filters.removeClass('active');
            $activeFilter.addClass('active');
        }
    }

    /**
     * Testimonial Search
     */
    class TestimonialSearch {
        constructor(searchInput, container) {
            this.searchInput = $(searchInput);
            this.container = $(container);
            this.items = this.container.find('.testimonial-card');
            this.noResults = this.container.find('.no-results');
            
            this.init();
        }
        
        init() {
            let searchTimeout;
            
            this.searchInput.on('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.performSearch(e.target.value);
                }, 300);
            });
        }
        
        performSearch(query) {
            const searchTerm = query.toLowerCase().trim();
            let visibleCount = 0;
            
            this.items.each(function() {
                const $item = $(this);
                const content = $item.text().toLowerCase();
                const matches = content.includes(searchTerm);
                
                if (matches || searchTerm === '') {
                    $item.show();
                    visibleCount++;
                } else {
                    $item.hide();
                }
            });
            
            // Show/hide no results message
            if (visibleCount === 0 && searchTerm !== '') {
                this.showNoResults();
            } else {
                this.hideNoResults();
            }
        }
        
        showNoResults() {
            if (this.noResults.length === 0) {
                this.container.append('<div class="no-results no-testimonials">No testimonials found matching your search.</div>');
                this.noResults = this.container.find('.no-results');
            }
            this.noResults.show();
        }
        
        hideNoResults() {
            this.noResults.hide();
        }
    }

    /**
     * Lazy Loading for Images
     */
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Testimonial Modal
     */
    class TestimonialModal {
        constructor() {
            this.modal = null;
            this.init();
        }
        
        init() {
            $(document).on('click', '[data-testimonial-modal]', (e) => {
                e.preventDefault();
                const testimonialId = $(e.target).data('testimonial-modal');
                this.openModal(testimonialId);
            });
        }
        
        openModal(testimonialId) {
            // Create modal if it doesn't exist
            if (!this.modal) {
                this.createModal();
            }
            
            // Load testimonial content
            this.loadTestimonial(testimonialId);
            
            // Show modal
            this.modal.fadeIn(300);
            $('body').addClass('modal-open');
        }
        
        createModal() {
            this.modal = $(`
                <div class="testimonial-modal">
                    <div class="modal-overlay"></div>
                    <div class="modal-content">
                        <button class="modal-close">&times;</button>
                        <div class="modal-body"></div>
                    </div>
                </div>
            `);
            
            $('body').append(this.modal);
            
            // Bind close events
            this.modal.find('.modal-close, .modal-overlay').on('click', () => {
                this.closeModal();
            });
            
            // Close on escape key
            $(document).on('keydown', (e) => {
                if (e.key === 'Escape' && this.modal.is(':visible')) {
                    this.closeModal();
                }
            });
        }
        
        loadTestimonial(testimonialId) {
            const modalBody = this.modal.find('.modal-body');
            modalBody.html('<div class="loading">Loading...</div>');
            
            // In a real implementation, you would load the testimonial via AJAX
            // For now, we'll just show a placeholder
            setTimeout(() => {
                modalBody.html(`
                    <div class="testimonial-card modal-testimonial">
                        <h3>Full Testimonial View</h3>
                        <p>This would contain the full testimonial content for ID: ${testimonialId}</p>
                    </div>
                `);
            }, 500);
        }
        
        closeModal() {
            this.modal.fadeOut(300);
            $('body').removeClass('modal-open');
        }
    }

    /**
     * Initialize everything when document is ready
     */
    $(document).ready(function() {
        // Initialize sliders
        $('.testimonials-slider').each(function() {
            const options = {
                autoplay: $(this).data('autoplay') !== false,
                autoplayDelay: $(this).data('delay') || 5000,
                loop: $(this).data('loop') !== false
            };
            new TestimonialSlider(this, options);
        });
        
        // Initialize grids
        $('.testimonials-grid').each(function() {
            new TestimonialGrid(this);
        });
        
        // Initialize star ratings
        initStarRatings();
        
        // Initialize filtering if filter controls exist
        if ($('[data-filter]').length) {
            new TestimonialFilter('.testimonials-container');
        }
        
        // Initialize search if search input exists
        if ($('.testimonial-search').length) {
            new TestimonialSearch('.testimonial-search', '.testimonials-container');
        }
        
        // Initialize lazy loading
        initLazyLoading();
        
        // Initialize modal
        new TestimonialModal();
        
        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 500);
            }
        });
    });

    /**
     * Handle window resize
     */
    $(window).on('resize', function() {
        // Recalculate slider positions
        $('.testimonials-slider').each(function() {
            const slider = $(this).data('testimonialSlider');
            if (slider) {
                slider.updateSlider();
            }
        });
    });

    /**
     * Add CSS classes for animations
     */
    const style = document.createElement('style');
    style.textContent = `
        .testimonial-card {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
        }
        
        .testimonial-card.animate-in {
            opacity: 1;
            transform: translateY(0);
        }
        
        .testimonial-card.clicked {
            transform: scale(0.98);
        }
        
        .testimonial-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            display: none;
        }
        
        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
        }
        
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 12px;
            max-width: 90%;
            max-height: 90%;
            overflow-y: auto;
            padding: 2rem;
        }
        
        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 2rem;
            cursor: pointer;
            color: #666;
        }
        
        .modal-close:hover {
            color: #000;
        }
        
        body.modal-open {
            overflow: hidden;
        }
        
        .loading {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        
        @media (prefers-reduced-motion: reduce) {
            .testimonial-card,
            .testimonial-modal {
                transition: none;
            }
        }
    `;
    document.head.appendChild(style);

})(jQuery);