/**
 * TZnew Theme Scripts
 * 
 * Main JavaScript file for the TZnew theme with enhanced animations and interactions
 */

(function($) {
    'use strict';

    // Global variables
    let isScrolling = false;
    let lastScrollTop = 0;
    
    // Document ready
    $(document).ready(function() {
        
        // Initialize all functions
        initMobileMenu();
        initSmoothScrolling();
        initBackToTop();
        initScrollReveal();
        initParallax();
        initializeSearch();
        initFilterSystem();
        initAnimations();
        initHeaderScroll();
        initImageLazyLoading();
        initializeBookingForm();
        initializeInquiryForm();
        initializeContactForm();
        initMultiStepFormStyles();
        
    });
    
    /**
     * Initialize mobile menu functionality
     */
    function initMobileMenu() {
        const $mobileMenuToggle = $('#mobile-menu-toggle');
        const $mobileMenu = $('#mobile-menu');
        const $body = $('body');
        
        if ($mobileMenuToggle.length && $mobileMenu.length) {
            // Toggle mobile menu
            $mobileMenuToggle.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const isOpen = $mobileMenu.hasClass('active');
                
                if (isOpen) {
                     closeMobileMenu();
                 } else {
                     openMobileMenu();
                 }
            });
            
            // Close menu when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#mobile-menu, #mobile-menu-toggle').length) {
                    closeMobileMenu();
                }
            });
            
            // Close menu on escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMobileMenu();
                }
            });
            
            // Close menu when clicking on menu links
            $mobileMenu.find('a').on('click', function() {
                closeMobileMenu();
            });
            
            // Handle window resize
            $(window).on('resize', debounce(function() {
                if ($(window).width() >= 1024) { // lg breakpoint
                    closeMobileMenu();
                }
            }, 250));
        }
        
        function openMobileMenu() {
            $mobileMenu.addClass('active');
            $mobileMenuToggle.addClass('active');
            $body.addClass('mobile-menu-open');
            
            // Update aria attributes
            $mobileMenuToggle.attr('aria-expanded', 'true');
            $mobileMenu.attr('aria-hidden', 'false');
            
            // Focus management
            $mobileMenu.find('a:first').focus();
        }
        
        function closeMobileMenu() {
            $mobileMenu.removeClass('active');
            $mobileMenuToggle.removeClass('active');
            $body.removeClass('mobile-menu-open');
            
            // Update aria attributes
            $mobileMenuToggle.attr('aria-expanded', 'false');
            $mobileMenu.attr('aria-hidden', 'true');
        }
    }
    
    /**
     * Initialize smooth scrolling
     */
    function initSmoothScrolling() {
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 800, 'easeInOutCubic');
            }
        });
    }
    
    /**
     * Initialize back to top functionality
     */
    function initBackToTop() {
        $('body').append('<button class="back-to-top" aria-label="Back to top"><i class="fas fa-arrow-up"></i></button>');
        
        const $backToTop = $('.back-to-top');
        
        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 500) {
                $backToTop.addClass('visible');
            } else {
                $backToTop.removeClass('visible');
            }
        });
        
        $backToTop.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, 800);
        });
    }
    
    /**
     * Initialize scroll reveal animations
     */
    function initScrollReveal() {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        $('.scroll-reveal').each(function() {
            observer.observe(this);
        });
    }
    
    /**
     * Initialize parallax effects
     */
    function initParallax() {
        if (window.innerWidth > 768) {
            $('.parallax').each(function() {
                const $element = $(this);
                const speed = $element.data('speed') || 0.5;
                
                $(window).on('scroll', function() {
                    const scrolled = $(window).scrollTop();
                    const elementTop = $element.offset().top;
                    const elementHeight = $element.outerHeight();
                    const windowHeight = $(window).height();
                    
                    if (scrolled + windowHeight > elementTop && scrolled < elementTop + elementHeight) {
                        const yPos = -(scrolled - elementTop) * speed;
                        $element.css('transform', 'translateY(' + yPos + 'px)');
                    }
                });
            });
        }
    }
    
    /**
     * Initialize search functionality (consolidated)
     */
    function initSearchEnhancements() {
        const $searchForm = $('.search-form');
        const $searchInput = $('.search-field, .search-input');
        
        if (!$searchInput.length) return;
        
        // Add search suggestions and focus effects
        $searchInput.on('focus', function() {
            $(this).closest('.search-form').addClass('focused');
        });
        
        $searchInput.on('blur', function() {
            setTimeout(() => {
                $(this).closest('.search-form').removeClass('focused');
            }, 200);
        });
        
        // Initialize live search if not already initialized by initializeSearch
        if (!$searchForm.find('.live-search-results').length) {
            const $searchResults = $('<div class="search-results"></div>');
            $searchForm.append($searchResults);
            
            let searchTimeout;
            $searchInput.on('input', function() {
                const query = $(this).val().trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length >= 3) {
                    searchTimeout = setTimeout(function() {
                        performLiveSearch(query, $searchResults);
                    }, 300);
                } else {
                    $searchResults.hide().empty();
                }
            });
        }
    }
    
    /**
     * Initialize filter system
     */
    function initFilterSystem() {
        $('.filter-form').on('submit', function(e) {
            e.preventDefault();
            const $form = $(this);
            const $container = $('.posts-container');
            
            $.ajax({
                url: tznew_ajax.ajax_url,
                type: 'POST',
                data: $form.serialize() + '&action=tznew_filter_posts&nonce=' + tznew_ajax.nonce,
                beforeSend: function() {
                    $container.addClass('loading');
                },
                success: function(response) {
                    if (response.success) {
                        $container.html(response.data).removeClass('loading');
                        initScrollReveal();
                    }
                },
                error: function() {
                    $container.removeClass('loading');
                }
            });
        });
    }
    
    /**
     * Initialize animations
     */
    function initAnimations() {
        // Stagger animations for cards
        $('.card').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
            $(this).addClass('animate-fade-in-up');
        });
        
        // Hero animations
        $('.hero-title').addClass('animate-fade-in-up');
        $('.hero-subtitle').addClass('animate-fade-in-up');
        $('.hero-cta').addClass('animate-fade-in-up');
    }
    
    /**
     * Initialize header scroll effects
     */
    function initHeaderScroll() {
        const $header = $('.site-header');
        
        $(window).on('scroll', throttle(function() {
            if ($(this).scrollTop() > 100) {
                $header.addClass('scrolled');
            } else {
                $header.removeClass('scrolled');
            }
        }, 100));
    }
    
    /**
     * Initialize image lazy loading
     */
    function initImageLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            imageObserver.unobserve(img);
                        }
                    }
                });
            });
            
            $('img[data-src]').each(function() {
                imageObserver.observe(this);
            });
        }
    }
    
    /**
      * Trigger scroll reveal for elements in view
      */
     function triggerScrollReveal() {
         $('.scroll-reveal').each(function() {
             const elementTop = $(this).offset().top;
             const elementBottom = elementTop + $(this).outerHeight();
             const viewportTop = $(window).scrollTop();
             const viewportBottom = viewportTop + $(window).height();
             
             if (elementBottom > viewportTop && elementTop < viewportBottom) {
                 $(this).addClass('revealed');
             }
         });
     }
     
     // Window load
     $(window).on('load', function() {
        console.log('Window loaded, hiding preloader');
        // Hide preloader with animation
        $('#preloader').addClass('fade-out');
        setTimeout(function() {
            $('#preloader').remove();
            console.log('Preloader removed');
        }, 600); // Increased timeout to match CSS transition
        
        // Trigger scroll reveal for elements in view
        triggerScrollReveal();
    });
    
    // Fallback: Hide preloader after 2 seconds regardless
    setTimeout(function() {
        if ($('#preloader').length) {
            console.log('Fallback: Removing preloader after timeout');
            $('#preloader').addClass('fade-out');
            setTimeout(function() {
                $('#preloader').remove();
            }, 600);
        }
    }, 2000); // Reduced timeout for faster loading

    /**
     * Main theme initialization
     */
    function initializeTheme() {
        initializeNavigation();
        initializeSearch();
        initializeCards();
        initializeForms();
        initializeModals();
        initializeCounters();
        initializeParallax();
        initializeImageLightbox();
        initializeFilterSystem();
        initializeSmoothScroll();
        initializeBackToTop();
    }

    /**
     * Navigation functionality
     */
    function initializeNavigation() {
        const $header = $('.site-header');
        const $menuToggle = $('.menu-toggle');
        const $mobileMenu = $('.primary-menu-container');

        // Header scroll effect
        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 100) {
                $header.addClass('scrolled');
            } else {
                $header.removeClass('scrolled');
            }
        });

        // Mobile menu toggle
        $menuToggle.on('click', function(e) {
            e.preventDefault();
            $(this).toggleClass('active');
            $mobileMenu.toggleClass('active');
            $('body').toggleClass('menu-open');
        });

        // Close mobile menu on link click
        $('.primary-menu a').on('click', function() {
            $menuToggle.removeClass('active');
            $mobileMenu.removeClass('active');
            $('body').removeClass('menu-open');
        });

        // Close mobile menu on outside click
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.site-header').length) {
                $menuToggle.removeClass('active');
                $mobileMenu.removeClass('active');
                $('body').removeClass('menu-open');
            }
        });
    }

    /**
     * Enhanced search functionality
     */
    function initializeSearch() {
        const $searchForm = $('.search-form');
        const $searchInput = $('.search-field, .search-input');
        
        if (!$searchInput.length) return;
        
        // Check if search elements already exist
        let $searchResults = $searchForm.find('.live-search-results');
        let $clearButton = $searchForm.find('.search-clear');
        
        // Create elements if they don't exist
        if (!$searchResults.length) {
            $searchResults = $('<div class="live-search-results"></div>');
            $searchForm.append($searchResults);
        }
        
        if (!$clearButton.length) {
            $clearButton = $('<button type="button" class="search-clear"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>');
            $searchForm.append($clearButton);
        }

        // Initialize search filters
        initializeSearchFilters();
        
        // Initialize popular searches
        initializePopularSearches($searchInput, $searchResults);

        // Live search functionality
        let searchTimeout;
        $searchInput.on('input', function() {
            const query = $(this).val().trim();
            
            // Show/hide clear button with proper CSS class
            if (query.length > 0) {
                $clearButton.addClass('show');
            } else {
                $clearButton.removeClass('show');
            }
            
            clearTimeout(searchTimeout);
            
            if (query.length >= 2) {
                searchTimeout = setTimeout(function() {
                    performLiveSearch(query, $searchResults);
                }, 300);
            } else {
                $searchResults.hide().empty();
            }
        });

        // Clear search functionality
        $clearButton.on('click', function() {
            $searchInput.val('').focus();
            $(this).removeClass('show');
            $searchResults.hide().empty();
        });

        // Hide results on outside click
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-form').length) {
                $searchResults.hide();
            }
        });

        // Keyboard navigation for live search results
        $searchInput.on('keydown', function(e) {
            if (!$searchResults.is(':visible')) return;
            
            const $items = $searchResults.find('.live-search-item');
            let $current = $items.filter('.highlighted');
            let index = $items.index($current);
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (index < $items.length - 1) {
                    $current.removeClass('highlighted');
                    $items.eq(index + 1).addClass('highlighted');
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (index > 0) {
                    $current.removeClass('highlighted');
                    $items.eq(index - 1).addClass('highlighted');
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                const $highlighted = $searchResults.find('.live-search-item.highlighted');
                if ($highlighted.length) {
                    $highlighted.click();
                }
            }
        });
        
        // Focus and blur effects
        $searchInput.on('focus', function() {
            $(this).closest('.search-form').addClass('focused');
        });
        
        $searchInput.on('blur', function() {
            setTimeout(() => {
                $(this).closest('.search-form').removeClass('focused');
            }, 200);
        });
    }

    /**
     * Initialize search filters
     */
    function initializeSearchFilters() {
        const $filterButtons = $('.filter-btn');
        const $searchResults = $('.search-result-card');
        
        $filterButtons.on('click', function() {
            const filterType = $(this).data('filter');
            
            // Update active button
            $filterButtons.removeClass('active');
            $(this).addClass('active');
            
            // Filter results
            $searchResults.each(function() {
                const $result = $(this);
                const postType = $result.data('post-type');
                
                if (filterType === 'all' || postType === filterType) {
                    $result.show().css('animation', 'fadeInUp 0.5s ease forwards');
                } else {
                    $result.hide();
                }
            });
            
            // Update results count
            updateResultsCount();
        });
    }

    /**
      * Update results count
      */
     function updateResultsCount() {
         const $visibleResults = $('.search-result-card:visible');
         const $countElement = $('.results-count');
         
         if ($countElement.length) {
             const count = $visibleResults.length;
             $countElement.text(count);
             
             const $resultText = $('.result-text');
             if ($resultText.length) {
                 $resultText.text(count === 1 ? 'result' : 'results');
             }
         }
     }

     /**
      * Initialize popular searches
      */
     function initializePopularSearches($searchInput, $searchResults) {
         const popularSearches = [
             'Everest Base Camp Trek',
             'Annapurna Circuit',
             'Manaslu Trek',
             'Langtang Valley',
             'Upper Mustang',
             'Gokyo Lakes',
             'Island Peak',
             'Mera Peak'
         ];

         // Show popular searches when input is focused and empty
         $searchInput.on('focus', function() {
             const query = $(this).val().trim();
             if (query.length === 0) {
                 showPopularSearches($searchResults, popularSearches);
             }
         });

         // Handle popular search clicks
         $(document).on('click', '.popular-search-item', function() {
             const searchTerm = $(this).data('search');
             $searchInput.val(searchTerm).trigger('input');
             $searchResults.hide();
         });
     }

     /**
      * Show popular searches
      */
     function showPopularSearches($resultsContainer, searches) {
         let html = `
             <div class="popular-searches-header p-4 border-b border-gray-100">
                 <div class="flex items-center gap-2 text-gray-600">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                     </svg>
                     <span class="font-medium">Popular Searches</span>
                 </div>
             </div>
             <div class="popular-searches-list p-2">
         `;
         
         searches.forEach(function(search, index) {
             html += `
                 <div class="popular-search-item flex items-center gap-3 p-3 rounded-lg cursor-pointer transition-all hover:bg-gray-50 group" data-search="${search}">
                     <div class="w-8 h-8 bg-blue-50 rounded-full flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                         <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                         </svg>
                     </div>
                     <div class="flex-1">
                         <div class="font-medium text-gray-800 group-hover:text-blue-600 transition-colors">${search}</div>
                     </div>
                     <div class="text-gray-400 group-hover:text-blue-500 transition-colors">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                         </svg>
                     </div>
                 </div>
             `;
         });
         
         html += '</div>';
         
         $resultsContainer.html(html).show();
     }

    /**
     * Perform live search
     */
    function performLiveSearch(query, $resultsContainer) {
        // Show loading state
        $resultsContainer.html(`
            <div class="live-search-item loading">
                <div class="flex items-center gap-2">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                    <span>Searching...</span>
                </div>
            </div>
        `).show();

        $.ajax({
            url: tznew_ajax.ajax_url || ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'tznew_live_search',
                query: query,
                nonce: tznew_ajax.nonce || ajax_object.nonce
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let html = '';
                    response.data.forEach(function(post, index) {
                        const postTypeColors = {
                            'trekking': 'bg-green-100 text-green-700',
                            'tours': 'bg-purple-100 text-purple-700',
                            'blog': 'bg-orange-100 text-orange-700',
                            'post': 'bg-red-100 text-red-700'
                        };
                        
                        const colorClass = postTypeColors[post.post_type] || 'bg-gray-100 text-gray-700';
                        const thumbnail = post.thumbnail || '';
                        
                        html += `
                            <div class="live-search-item group" onclick="window.location.href='${post.permalink}'">
                                <div class="flex items-start gap-3">
                                    ${thumbnail ? 
                                        `<img src="${thumbnail}" alt="${post.title}" class="w-12 h-12 object-cover rounded-lg flex-shrink-0">` : 
                                        `<div class="w-12 h-12 bg-gray-200 rounded-lg flex-shrink-0 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>`
                                    }
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <div class="title font-semibold text-gray-800 group-hover:text-blue-600 transition-colors truncate">${post.title}</div>
                                            <span class="${colorClass} text-xs px-2 py-1 rounded-full font-medium flex-shrink-0">${post.post_type}</span>
                                        </div>
                                        <div class="excerpt text-sm text-gray-600 line-clamp-2">${post.excerpt}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    
                    html += `
                        <div class="live-search-footer p-3 border-t border-gray-100 text-center">
                            <a href="${window.location.origin}/?s=${encodeURIComponent(query)}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                View all results for "${query}"
                            </a>
                        </div>
                    `;
                    
                    $resultsContainer.html(html).show();
                } else {
                    $resultsContainer.html(`
                        <div class="live-search-item text-center py-8">
                            <div class="text-gray-400 mb-2">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.08-2.33"></path>
                                </svg>
                            </div>
                            <div class="text-gray-600 font-medium">No results found</div>
                            <div class="text-gray-500 text-sm mt-1">Try different keywords or check spelling</div>
                        </div>
                    `).show();
                }
            },
            error: function() {
                $resultsContainer.html(`
                    <div class="live-search-item text-center py-8">
                        <div class="text-red-400 mb-2">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="text-gray-600 font-medium">Search error occurred</div>
                        <div class="text-gray-500 text-sm mt-1">Please try again later</div>
                    </div>
                `).show();
            }
        });
    }

    /**
     * Card hover effects and interactions
     */
    function initializeCards() {
        $('.card').each(function() {
            const $card = $(this);
            const $image = $card.find('.card-image img');
            
            $card.on('mouseenter', function() {
                $(this).addClass('hovered');
                $image.addClass('zoomed');
            });
            
            $card.on('mouseleave', function() {
                $(this).removeClass('hovered');
                $image.removeClass('zoomed');
            });
        });
    }

    /**
     * Form enhancements
     */
    function initializeForms() {
        // Floating labels
        $('.form-input').on('focus blur', function() {
            const $this = $(this);
            const $label = $this.siblings('.form-label');
            
            if ($this.val() !== '' || $this.is(':focus')) {
                $label.addClass('floating');
            } else {
                $label.removeClass('floating');
            }
        });

        // Form validation
        $('form').on('submit', function(e) {
            const $form = $(this);
            let isValid = true;

            $form.find('.form-input[required]').each(function() {
                const $input = $(this);
                const value = $input.val().trim();
                
                if (value === '') {
                    $input.addClass('error');
                    isValid = false;
                } else {
                    $input.removeClass('error');
                }
            });

            if (!isValid) {
                e.preventDefault();
                showNotification('Please fill in all required fields', 'error');
            }
        });
    }

    /**
     * Modal functionality
     */
    function initializeModals() {
        // Open modal
        $('[data-modal]').on('click', function(e) {
            e.preventDefault();
            const modalId = $(this).data('modal');
            const $modal = $('#' + modalId);
            
            if ($modal.length) {
                $modal.addClass('active');
                $('body').addClass('modal-open');
            }
        });

        // Close modal
        $('.modal-close, .modal-overlay').on('click', function(e) {
            e.preventDefault();
            $(this).closest('.modal').removeClass('active');
            $('body').removeClass('modal-open');
        });

        // Close modal on escape key
        $(document).on('keydown', function(e) {
            if (e.keyCode === 27) {
                $('.modal.active').removeClass('active');
                $('body').removeClass('modal-open');
            }
        });
    }

    /**
     * Animated counters
     */
    function initializeCounters() {
        $('.counter').each(function() {
            const $counter = $(this);
            const target = parseInt($counter.data('target'));
            const duration = parseInt($counter.data('duration')) || 2000;
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        animateCounter($counter, target, duration);
                        observer.unobserve(entry.target);
                    }
                });
            });
            
            observer.observe($counter[0]);
        });
    }

    /**
     * Animate counter
     */
    function animateCounter($element, target, duration) {
        let start = 0;
        const increment = target / (duration / 16);
        
        const timer = setInterval(function() {
            start += increment;
            $element.text(Math.floor(start));
            
            if (start >= target) {
                $element.text(target);
                clearInterval(timer);
            }
        }, 16);
    }

    /**
     * Parallax effects
     */
    function initializeParallax() {
        if (window.innerWidth > 768) {
            $('.parallax').each(function() {
                const $element = $(this);
                const speed = $element.data('speed') || 0.5;
                
                $(window).on('scroll', function() {
                    const scrolled = $(window).scrollTop();
                    const elementTop = $element.offset().top;
                    const elementHeight = $element.outerHeight();
                    const windowHeight = $(window).height();
                    
                    if (scrolled + windowHeight > elementTop && scrolled < elementTop + elementHeight) {
                        const yPos = -(scrolled - elementTop) * speed;
                        $element.css('transform', 'translateY(' + yPos + 'px)');
                    }
                });
            });
        }
    }

    /**
     * Image lightbox
     */
    function initializeImageLightbox() {
        $('body').append('<div class="lightbox"><div class="lightbox-content"><img src="" alt=""><button class="lightbox-close">&times;</button></div></div>');
        
        const $lightbox = $('.lightbox');
        const $lightboxImg = $('.lightbox img');
        
        $('.gallery img, .lightbox-trigger').on('click', function(e) {
            e.preventDefault();
            const src = $(this).attr('src') || $(this).attr('href');
            const alt = $(this).attr('alt') || '';
            
            $lightboxImg.attr('src', src).attr('alt', alt);
            $lightbox.addClass('active');
            $('body').addClass('lightbox-open');
        });
        
        $('.lightbox-close, .lightbox').on('click', function(e) {
            if (e.target === this) {
                $lightbox.removeClass('active');
                $('body').removeClass('lightbox-open');
            }
        });
    }

    /**
     * Filter system for tours and treks
     */
    function initializeFilterSystem() {
        $('.filter-form').on('submit', function(e) {
            e.preventDefault();
            const $form = $(this);
            const $container = $('.posts-container');
            
            $.ajax({
                url: tznew_ajax.ajax_url,
                type: 'POST',
                data: $form.serialize() + '&action=tznew_filter_posts&nonce=' + tznew_ajax.nonce,
                beforeSend: function() {
                    $container.addClass('loading');
                },
                success: function(response) {
                    if (response.success) {
                        $container.html(response.data).removeClass('loading');
                        initializeScrollReveal();
                    }
                },
                error: function() {
                    showNotification('Filter error occurred', 'error');
                    $container.removeClass('loading');
                }
            });
        });
        
        // Reset filters
        $('.filter-reset').on('click', function(e) {
            e.preventDefault();
            $('.filter-form')[0].reset();
            $('.filter-form').trigger('submit');
        });
    }

    /**
     * Smooth scrolling
     */
    function initializeSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 800, 'easeInOutCubic');
            }
        });
    }

    /**
     * Back to top button
     */
    function initializeBackToTop() {
        $('body').append('<button class="back-to-top" aria-label="Back to top"><i class="fas fa-arrow-up"></i></button>');
        
        const $backToTop = $('.back-to-top');
        
        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 500) {
                $backToTop.addClass('visible');
            } else {
                $backToTop.removeClass('visible');
            }
        });
        
        $backToTop.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, 800);
        });
    }



    /**
     * Initialize animations
     */
    function initializeAnimations() {
        // Stagger animations for cards
        $('.card').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
            $(this).addClass('animate-fade-in-up');
        });
        
        // Hero animations
        $('.hero-title').addClass('animate-fade-in-up');
        $('.hero-subtitle').addClass('animate-fade-in-up');
        $('.hero-cta').addClass('animate-fade-in-up');
    }

    /**
     * Scroll reveal animations
     */
    function initializeScrollReveal() {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        $('.scroll-reveal').each(function() {
            observer.observe(this);
        });
    }

    /**
     * Show notification
     */
    function showNotification(message, type = 'info') {
        const $notification = $(`
            <div class="notification notification-${type}">
                <span class="notification-message">${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `);
        
        $('body').append($notification);
        
        setTimeout(function() {
            $notification.addClass('show');
        }, 100);
        
        setTimeout(function() {
            $notification.removeClass('show');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, 5000);
        
        $notification.find('.notification-close').on('click', function() {
            $notification.removeClass('show');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        });
    }

    /**
     * Utility functions
     */
    
    // Debounce function
    function debounce(func, wait, immediate) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }
    
    // Throttle function
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
    
    // Custom easing for jQuery animations
    $.easing.easeInOutCubic = function(x, t, b, c, d) {
        if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
        return c / 2 * ((t -= 2) * t * t + 2) + b;
    };

    /**
      * Initialize booking form functionality
      */
     function initializeBookingForm() {
         const $bookingForm = $('#booking-form');
         if ($bookingForm.length === 0) return;
         
         // Initialize multi-step functionality
         initMultiStepForm($bookingForm);
         
         // Initialize custom option selections
    initCustomOptionSelections($bookingForm);
    
    // Initialize booking summary updates
    initBookingSummaryUpdates($bookingForm);
         
         // Real-time validation
         $bookingForm.find('input, select, textarea').on('blur', function() {
             validateField($(this));
         });
         
         // Form submission
         $bookingForm.on('submit', function(e) {
             e.preventDefault();
             const $form = $(this);
             
             // Show loading state
             showFormLoading($form, true);
             
             // Validate form
             if (!validateBookingForm($form)) {
                 showFormLoading($form, false);
                 return;
             }
             
             // Prepare form data
             const formData = new FormData(this);
             
             $.ajax({
                 url: tznew_ajax.ajax_url,
                 type: 'POST',
                 data: formData,
                 processData: false,
                 contentType: false,
                 success: function(response) {
                     if (response.success) {
                         $form.hide();
                         $('#booking-success').removeClass('hidden').addClass('show');
                     } else {
                         $('#booking-error').removeClass('hidden').addClass('show');
                         $('#booking-error .error-text').text(response.data.message || 'Booking submission failed');
                     }
                 },
                 error: function() {
                     $('#booking-error').removeClass('hidden').addClass('show');
                     $('#booking-error .error-text').text('An error occurred. Please try again.');
                 },
                 complete: function() {
                     showFormLoading($form, false);
                 }
             });
         });
     }
    
    /**
      * Initialize inquiry form functionality
      */
     function initializeInquiryForm() {
         const $inquiryForm = $('#inquiry-form');
         if ($inquiryForm.length === 0) return;
         
         // Initialize multi-step functionality
         initMultiStepForm($inquiryForm);
         
         // Real-time validation
         $inquiryForm.find('input, select, textarea').on('blur', function() {
             validateField($(this));
         });
         
         // Message character counter
         const $messageField = $inquiryForm.find('#inquiry_message');
         if ($messageField.length) {
             $messageField.on('input', function() {
                 const length = $(this).val().length;
                 const $counter = $(this).closest('.form-group').find('.character-count .current-count');
                 $counter.text(length);
                 
                 if (length < 10) {
                     $(this).addClass('border-red-300').removeClass('border-green-300');
                 } else {
                     $(this).addClass('border-green-300').removeClass('border-red-300');
                 }
             });
         }
         
         // Form submission
         $inquiryForm.on('submit', function(e) {
             e.preventDefault();
             const $form = $(this);
             
             // Show loading state
             showFormLoading($form, true);
             
             // Validate form
             if (!validateInquiryForm($form)) {
                 showFormLoading($form, false);
                 return;
             }
             
             // Prepare form data
             const formData = new FormData(this);
             
             $.ajax({
                 url: tznew_ajax.ajax_url,
                 type: 'POST',
                 data: formData,
                 processData: false,
                 contentType: false,
                 success: function(response) {
                     if (response.success) {
                         $form.hide();
                         $('#inquiry-success').removeClass('hidden').addClass('show');
                     } else {
                         $('#inquiry-error').removeClass('hidden').addClass('show');
                         $('#inquiry-error .error-text').text(response.data.message || 'Inquiry submission failed');
                     }
                 },
                 error: function() {
                     $('#inquiry-error').removeClass('hidden').addClass('show');
                     $('#inquiry-error .error-text').text('An error occurred. Please try again.');
                 },
                 complete: function() {
                     showFormLoading($form, false);
                 }
             });
         });
     }
    
    /**
      * Initialize contact form functionality
      */
     function initializeContactForm() {
         const $contactForm = $('#contact-form');
         if ($contactForm.length === 0) return;
         
         // Real-time validation
         $contactForm.find('input, select, textarea').on('blur', function() {
             validateField($(this));
         });
         
         // Message character counter
         const $messageField = $contactForm.find('#message');
         if ($messageField.length) {
             $messageField.on('input', function() {
                 const length = $(this).val().length;
                 const $counter = $(this).closest('.form-group').find('.character-count .current-count');
                 $counter.text(length);
                 
                 if (length < 10) {
                     $(this).addClass('border-red-300').removeClass('border-green-300');
                 } else {
                     $(this).addClass('border-green-300').removeClass('border-red-300');
                 }
             });
         }
         
         // Form submission
         $contactForm.on('submit', function(e) {
             e.preventDefault();
             const $form = $(this);
             const $submitBtn = $form.find('button[type="submit"]');
             const $buttonText = $submitBtn.find('.button-text');
             const $loadingText = $submitBtn.find('.loading-text');
             
             // Show loading state
             $submitBtn.prop('disabled', true);
             $buttonText.addClass('hidden');
             $loadingText.removeClass('hidden');
             
             // Clear previous messages
             $form.find('.form-messages').empty();
             
             // Validate form
             if (!validateContactForm($form)) {
                 $submitBtn.prop('disabled', false);
                 $buttonText.removeClass('hidden');
                 $loadingText.addClass('hidden');
                 return;
             }
             
             // Prepare form data
             const formData = new FormData(this);
             formData.append('action', 'tznew_submit_contact');
             
             $.ajax({
                 url: tznew_ajax.ajax_url,
                 type: 'POST',
                 data: formData,
                 processData: false,
                 contentType: false,
                 success: function(response) {
                     if (response.success) {
                         showFormMessage($form, response.data.message || 'Thank you! Your message has been sent successfully.', 'success');
                         $form[0].reset();
                         // Reset character counter
                         $form.find('.current-count').text('0');
                         // Clear field states
                         $form.find('.form-group').removeClass('error success');
                         $form.find('.field-error').addClass('hidden');
                     } else {
                         showFormMessage($form, response.data.message || 'Failed to send message. Please try again.', 'error');
                     }
                 },
                 error: function() {
                     showFormMessage($form, 'An error occurred. Please try again later.', 'error');
                 },
                 complete: function() {
                     $submitBtn.prop('disabled', false);
                     $buttonText.removeClass('hidden');
                     $loadingText.addClass('hidden');
                 }
             });
         });
     }
    
    /**
      * Initialize multi-step form functionality
      */
     /**
      * Initialize custom option selections (radio buttons and checkboxes)
      */
     function initCustomOptionSelections($form) {
         // Handle accommodation preference radio buttons
         $form.on('click', '.accommodation-option', function() {
             const $option = $(this);
             const $radio = $option.find('input[type="radio"]');
             const $card = $option.find('.option-card');
             
             // Remove selection from all accommodation options
             $form.find('.accommodation-option .option-card').removeClass('border-green-500 bg-green-50');
             $form.find('.accommodation-option .option-card i').removeClass('text-green-500').addClass('text-gray-400');
             
             // Select this option
             $radio.prop('checked', true);
             $card.addClass('border-green-500 bg-green-50');
             $card.find('i').removeClass('text-gray-400').addClass('text-green-500');
         });
         
         // Handle fitness level radio buttons
         $form.on('click', '.fitness-option', function() {
             const $option = $(this);
             const $radio = $option.find('input[type="radio"]');
             const $card = $option.find('.option-card');
             
             // Remove selection from all fitness options
             $form.find('.fitness-option .option-card').removeClass('border-purple-500 bg-purple-50');
             $form.find('.fitness-option .option-card i').removeClass('text-purple-500').addClass('text-gray-400');
             
             // Select this option
             $radio.prop('checked', true);
             $card.addClass('border-purple-500 bg-purple-50');
             $card.find('i').removeClass('text-gray-400').addClass('text-purple-500');
         });
         
         // Handle dietary requirements checkboxes
         $form.on('click', '.dietary-option', function() {
             const $option = $(this);
             const $checkbox = $option.find('input[type="checkbox"]');
             const $card = $option.find('.option-card');
             
             // Toggle checkbox state
             $checkbox.prop('checked', !$checkbox.prop('checked'));
             
             // Update visual state
             if ($checkbox.prop('checked')) {
                 $card.addClass('border-purple-500 bg-purple-50');
                 $card.find('i').removeClass('text-gray-400').addClass('text-purple-500');
             } else {
                 $card.removeClass('border-purple-500 bg-purple-50');
                 $card.find('i').removeClass('text-purple-500').addClass('text-gray-400');
             }
         });
     }
     
     /**
      * Initialize booking summary updates
      */
     function initBookingSummaryUpdates($form) {
         // Update summary when form fields change
         $form.find('input, select, textarea').on('change input', function() {
             updateBookingSummary($form);
         });
         
         // Initial summary update
         updateBookingSummary($form);
     }
     
     /**
      * Update booking summary with current form values
      */
     function updateBookingSummary($form) {
         const postTitle = $form.find('input[name="post_title"]').val() || 'Selected Trip';
         const groupSize = $form.find('select[name="group_size"]').val();
         const preferredDate = $form.find('input[name="preferred_date"]').val();
         const accommodation = $form.find('input[name="accommodation_preference"]:checked').val();
         
         // Update summary fields
         $form.find('.summary-trip').text(postTitle);
         $form.find('.summary-travelers').text(groupSize ? groupSize + (groupSize == 1 ? ' person' : ' people') : '-');
         $form.find('.summary-date').text(preferredDate ? new Date(preferredDate).toLocaleDateString() : '-');
         $form.find('.summary-accommodation').text(accommodation ? accommodation.charAt(0).toUpperCase() + accommodation.slice(1) : '-');
     }
     
     function initMultiStepForm($form) {
         const $steps = $form.find('.form-step');
         const $progressBar = $form.find('.progress-fill');
         let currentStep = 1;
         const totalSteps = $steps.length;
         
         // Initialize first step
         showStep(currentStep);
         updateProgress();
         
         // Next step buttons
         $form.on('click', '.next-step', function(e) {
             e.preventDefault();
             
             // Validate current step
             if (validateCurrentStep(currentStep)) {
                 if (currentStep < totalSteps) {
                     currentStep++;
                     showStep(currentStep);
                     updateProgress();
                 }
             }
         });
         
         // Previous step buttons
         $form.on('click', '.prev-step', function(e) {
             e.preventDefault();
             
             if (currentStep > 1) {
                 currentStep--;
                 showStep(currentStep);
                 updateProgress();
             }
         });
         
         function showStep(step) {
             $steps.removeClass('active');
             $steps.filter(`[data-step="${step}"]`).addClass('active');
             
             // Update step indicators
             $form.find('.step-header span').removeClass('bg-green-500 text-white').addClass('bg-gray-300 text-gray-600');
             for (let i = 1; i <= step; i++) {
                 $steps.filter(`[data-step="${i}"]`).find('.step-header span').removeClass('bg-gray-300 text-gray-600').addClass('bg-green-500 text-white');
             }
         }
         
         function updateProgress() {
             const progressPercent = (currentStep / totalSteps) * 100;
             $progressBar.css('width', progressPercent + '%');
         }
         
         function validateCurrentStep(step) {
             const $currentStep = $steps.filter(`[data-step="${step}"]`);
             let isValid = true;
             
             // Validate required fields in current step
             $currentStep.find('[required]').each(function() {
                 if (!validateField($(this))) {
                     isValid = false;
                 }
             });
             
             // Additional step-specific validation
             if (step === 1) {
                 // Personal information validation
                 const $email = $currentStep.find('input[type="email"]');
                 if ($email.length && $email.val() && !isValidEmail($email.val())) {
                     showFieldError($email, 'Please enter a valid email address');
                     isValid = false;
                 }
                 
                 const $phone = $currentStep.find('input[type="tel"]');
                 if ($phone.length && $phone.val() && !isValidPhone($phone.val())) {
                     showFieldError($phone, 'Please enter a valid phone number');
                     isValid = false;
                 }
             }
             
             if (step === 2 && $form.attr('id') === 'booking-form') {
                 // Booking form trip details validation
                 const $dateField = $currentStep.find('#preferred_date');
                 if ($dateField.length && $dateField.val()) {
                     const selectedDate = new Date($dateField.val());
                     const minDate = new Date();
                     minDate.setDate(minDate.getDate() + 7);
                     
                     if (selectedDate < minDate) {
                         showFieldError($dateField, 'Please select a date at least one week from today');
                         isValid = false;
                     }
                 }
             }
             
             if (step === 3 || (step === 2 && $form.attr('id') === 'inquiry-form')) {
                 // Message validation
                 const $messageField = $currentStep.find('textarea');
                 if ($messageField.length && $messageField.val().trim().length < 10) {
                     showFieldError($messageField, 'Please provide a more detailed message (minimum 10 characters)');
                     isValid = false;
                 }
             }
             
             return isValid;
         }
      }
      
     /**
      * Initialize multi-step form styles
      */
     function initMultiStepFormStyles() {
         // Add CSS styles for multi-step forms
         const styles = `
             <style>
                 .form-step {
                     display: none;
                 }
                 .form-step.active {
                     display: block;
                 }
                 .progress-bar {
                     background-color: #e5e7eb;
                     border-radius: 9999px;
                     height: 8px;
                     overflow: hidden;
                 }
                 .progress-fill {
                     background-color: #10b981;
                     height: 100%;
                     transition: width 0.3s ease;
                     border-radius: 9999px;
                 }
                 .step-header {
                     display: flex;
                     align-items: center;
                     margin-bottom: 1rem;
                 }
                 .step-header span {
                     display: inline-flex;
                     align-items: center;
                     justify-content: center;
                     width: 2rem;
                     height: 2rem;
                     border-radius: 50%;
                     font-weight: 600;
                     font-size: 0.875rem;
                     margin-right: 0.75rem;
                     transition: all 0.3s ease;
                 }
                 .form-navigation {
                     display: flex;
                     justify-content: space-between;
                     margin-top: 2rem;
                     padding-top: 1.5rem;
                     border-top: 1px solid #e5e7eb;
                 }
                 .btn-nav {
                     padding: 0.75rem 1.5rem;
                     border-radius: 0.5rem;
                     font-weight: 600;
                     transition: all 0.3s ease;
                     cursor: pointer;
                     border: none;
                 }
                 .btn-nav.prev-step {
                     background-color: #f3f4f6;
                     color: #374151;
                 }
                 .btn-nav.prev-step:hover {
                     background-color: #e5e7eb;
                 }
                 .btn-nav.next-step {
                     background-color: #10b981;
                     color: white;
                 }
                 .btn-nav.next-step:hover {
                     background-color: #059669;
                 }
                 .field-error {
                     color: #dc2626;
                     font-size: 0.875rem;
                     margin-top: 0.25rem;
                 }
                 .field-success {
                     color: #059669;
                     font-size: 0.875rem;
                     margin-top: 0.25rem;
                 }
                 .form-field.error input,
                 .form-field.error select,
                 .form-field.error textarea {
                     border-color: #dc2626;
                 }
                 .form-field.success input,
                 .form-field.success select,
                 .form-field.success textarea {
                     border-color: #059669;
                 }
                 
                 /* Enhanced Form Field Styling for Better Readability */
                 .form-input,
                 input[type="text"],
                 input[type="email"],
                 input[type="tel"],
                 input[type="date"],
                 input[type="number"],
                 select,
                 textarea {
                     background-color: #ffffff !important;
                     color: #1f2937 !important;
                     border: 2px solid #d1d5db !important;
                     border-radius: 0.75rem !important;
                     padding: 1rem !important;
                     font-size: 1rem !important;
                     line-height: 1.5 !important;
                     transition: all 0.3s ease !important;
                     box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
                 }
                 
                 .form-input:focus,
                 input[type="text"]:focus,
                 input[type="email"]:focus,
                 input[type="tel"]:focus,
                 input[type="date"]:focus,
                 input[type="number"]:focus,
                 select:focus,
                 textarea:focus {
                     outline: none !important;
                     border-color: #3b82f6 !important;
                     box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
                     background-color: #ffffff !important;
                     color: #1f2937 !important;
                 }
                 
                 .form-input::placeholder,
                 input::placeholder,
                 textarea::placeholder {
                     color: #9ca3af !important;
                     opacity: 1 !important;
                 }
                 
                 /* Dark mode compatibility */
                 @media (prefers-color-scheme: dark) {
                     .form-input,
                     input[type="text"],
                     input[type="email"],
                     input[type="tel"],
                     input[type="date"],
                     input[type="number"],
                     select,
                     textarea {
                         background-color: #ffffff !important;
                         color: #1f2937 !important;
                         border-color: #d1d5db !important;
                     }
                 }
                 
                 /* Mobile Responsiveness */
                 @media (max-width: 768px) {
                     .form-input,
                     input[type="text"],
                     input[type="email"],
                     input[type="tel"],
                     input[type="date"],
                     input[type="number"],
                     select,
                     textarea {
                         font-size: 16px !important;
                         padding: 0.875rem !important;
                     }
                     
                     .form-navigation {
                         flex-direction: column;
                         gap: 1rem;
                     }
                     
                     .btn-nav {
                         width: 100%;
                         text-align: center;
                         padding: 1rem !important;
                     }
                     
                     .grid {
                         grid-template-columns: 1fr !important;
                         gap: 1rem !important;
                     }
                 }
                 
                 /* Checkbox and Radio Button Styling */
                 input[type="checkbox"],
                 input[type="radio"] {
                     width: 1.25rem !important;
                     height: 1.25rem !important;
                     accent-color: #3b82f6 !important;
                 }
                 
                 /* Select dropdown arrow */
                 select {
                     background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
                     background-position: right 0.75rem center !important;
                     background-repeat: no-repeat !important;
                     background-size: 1.5em 1.5em !important;
                     padding-right: 2.5rem !important;
                     appearance: none !important;
                 }
                 
                 /* Error and Success States */
                 .form-group.error .form-input,
                 .form-group.error input,
                 .form-group.error select,
                 .form-group.error textarea {
                     border-color: #ef4444 !important;
                     background-color: #fef2f2 !important;
                     color: #1f2937 !important;
                 }
                 
                 .form-group.success .form-input,
                 .form-group.success input,
                 .form-group.success select,
                 .form-group.success textarea {
                     border-color: #10b981 !important;
                     background-color: #f0fdf4 !important;
                     color: #1f2937 !important;
                 }
                 
                 /* Label Styling */
                 .form-label {
                     color: #374151 !important;
                     font-weight: 600 !important;
                     margin-bottom: 0.5rem !important;
                     display: block !important;
                 }
                 
                 /* Help Text */
                 .help-text {
                     color: #6b7280 !important;
                     font-size: 0.875rem !important;
                     margin-top: 0.25rem !important;
                 }
             </style>
         `;
         
         if (!$('head').find('#multi-step-form-styles').length) {
             $('head').append(styles.replace('<style>', '<style id="multi-step-form-styles">'));
         }
     }
      
     /**
      * Validate booking form
      */
     function validateBookingForm($form) {
         let isValid = true;
         
         // Clear previous messages
         $form.find('.form-messages').empty();
         
         // Required fields validation
         $form.find('[required]').each(function() {
             if (!validateField($(this))) {
                 isValid = false;
             }
         });
         
         // Terms agreement validation
         const $termsCheckbox = $form.find('#terms_agreement');
         if ($termsCheckbox.length && !$termsCheckbox.is(':checked')) {
             showFieldError($termsCheckbox, 'You must agree to the terms and conditions');
             isValid = false;
         }
         
         // Date validation (must be at least 1 week from now)
         const $dateField = $form.find('#preferred_date');
         if ($dateField.length && $dateField.val()) {
             const selectedDate = new Date($dateField.val());
             const minDate = new Date();
             minDate.setDate(minDate.getDate() + 7);
             
             if (selectedDate < minDate) {
                 showFieldError($dateField, 'Please select a date at least one week from today');
                 isValid = false;
             }
         }
         
         return isValid;
     }
    
    /**
      * Validate inquiry form
      */
     function validateInquiryForm($form) {
         let isValid = true;
         
         // Clear previous messages
         $form.find('.form-messages').empty();
         
         // Required fields validation
         $form.find('[required]').each(function() {
             if (!validateField($(this))) {
                 isValid = false;
             }
         });
         
         // Message length validation
         const $messageField = $form.find('#inquiry_message');
         if ($messageField.length && $messageField.val().trim().length < 10) {
             showFieldError($messageField, 'Please provide a more detailed message (minimum 10 characters)');
             isValid = false;
         }
         
         return isValid;
     }
    
    /**
      * Validate contact form
      */
     function validateContactForm($form) {
         let isValid = true;
         
         // Clear previous messages
         $form.find('.form-messages').empty();
         
         // Required fields validation
         $form.find('[required]').each(function() {
             if (!validateField($(this))) {
                 isValid = false;
             }
         });
         
         // Message length validation
         const $messageField = $form.find('#message');
         if ($messageField.length && $messageField.val().trim().length < 10) {
             showFieldError($messageField, 'Please provide a more detailed message (minimum 10 characters)');
             isValid = false;
         }
         
         // Subject length validation
         const $subjectField = $form.find('#subject');
         if ($subjectField.length && $subjectField.val().trim().length < 3) {
             showFieldError($subjectField, 'Subject must be at least 3 characters long');
             isValid = false;
         }
         
         return isValid;
     }
    
    /**
      * Validate individual field
      */
     function validateField($field) {
         const value = $field.val().trim();
         const fieldType = $field.attr('type');
         const fieldName = $field.attr('name');
         const isRequired = $field.prop('required');
         
         // Clear previous error state
         clearFieldError($field);
         
         // Required field validation
         if (isRequired && !value) {
             showFieldError($field, 'This field is required');
             return false;
         }
         
         // Skip validation if field is empty and not required
         if (!value && !isRequired) {
             return true;
         }
         
         // Email validation
         if (fieldType === 'email' && !isValidEmail(value)) {
             showFieldError($field, 'Please enter a valid email address');
             return false;
         }
         
         // Phone validation
         if (fieldType === 'tel' && !isValidPhone(value)) {
             showFieldError($field, 'Please enter a valid phone number');
             return false;
         }
         
         // Date validation
         if (fieldType === 'date' && value) {
             const selectedDate = new Date(value);
             const today = new Date();
             today.setHours(0, 0, 0, 0);
             
             if (selectedDate < today) {
                 showFieldError($field, 'Please select a future date');
                 return false;
             }
         }
         
         // Show success state
         showFieldSuccess($field);
         return true;
     }
     
     /**
      * Show field error
      */
     function showFieldError($field, message) {
         const $formGroup = $field.closest('.form-group');
         let $errorMessage = $formGroup.find('.error-message');
         
         // Fallback to field-error class if error-message doesn't exist
         if ($errorMessage.length === 0) {
             $errorMessage = $formGroup.find('.field-error');
         }
         
         $formGroup.addClass('error').removeClass('success');
         $field.addClass('border-red-300').removeClass('border-green-300');
         $errorMessage.text(message).removeClass('hidden');
     }
     
     /**
      * Show field success
      */
     function showFieldSuccess($field) {
         const $formGroup = $field.closest('.form-group');
         let $errorMessage = $formGroup.find('.error-message');
         
         // Fallback to field-error class if error-message doesn't exist
         if ($errorMessage.length === 0) {
             $errorMessage = $formGroup.find('.field-error');
         }
         
         $formGroup.addClass('success').removeClass('error');
         $field.addClass('border-green-300').removeClass('border-red-300');
         $errorMessage.addClass('hidden');
     }
     
     /**
      * Clear field error
      */
     function clearFieldError($field) {
         const $formGroup = $field.closest('.form-group');
         let $errorMessage = $formGroup.find('.error-message');
         
         // Fallback to field-error class if error-message doesn't exist
         if ($errorMessage.length === 0) {
             $errorMessage = $formGroup.find('.field-error');
         }
         
         $formGroup.removeClass('error success');
         $field.removeClass('border-red-300 border-green-300');
         $errorMessage.addClass('hidden');
     }
     
     /**
      * Show form loading state
      */
     function showFormLoading($form, isLoading) {
         const $submitBtn = $form.find('button[type="submit"]');
         const $loadingDiv = $form.find('.form-loading');
         
         if (isLoading) {
             $submitBtn.prop('disabled', true);
             $loadingDiv.removeClass('hidden');
         } else {
             $submitBtn.prop('disabled', false);
             $loadingDiv.addClass('hidden');
         }
     }
     
     /**
      * Show form message
      */
     function showFormMessage($form, message, type) {
         const $messagesDiv = $form.find('.form-messages');
         const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
         const iconClass = type === 'success' ? 'fas fa-circle-check' : 'fas fa-exclamation-circle';
         
         const messageHtml = `
             <div class="alert ${alertClass} border px-4 py-3 rounded-lg flex items-center space-x-2">
                 <i class="${iconClass}"></i>
                 <span>${message}</span>
             </div>
         `;
         
         $messagesDiv.html(messageHtml);
         
         // Auto-hide success messages after 5 seconds
         if (type === 'success') {
             setTimeout(() => {
                 $messagesDiv.fadeOut();
             }, 5000);
         }
     }
     
     /**
      * Email validation helper
      */
     function isValidEmail(email) {
         const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
         return emailRegex.test(email);
     }
     
     /**
      * Phone validation helper
      */
     function isValidPhone(phone) {
         const phoneRegex = /^[\+]?[1-9][\d\s\-\(\)]{7,}$/;
         return phoneRegex.test(phone.replace(/\s/g, ''));
     }

    // Expose functions globally if needed
    window.tznewTheme = {
        showNotification: showNotification,
        debounce: debounce,
        throttle: throttle
    };

})(jQuery);

/**
 * Vanilla JavaScript for critical functionality
 */

// Critical path CSS loading
function loadCSS(href) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = href;
    document.head.appendChild(link);
}

// Lazy loading for images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    document.querySelectorAll('img[data-src]').forEach(function(img) {
        imageObserver.observe(img);
    });
}

// Service Worker registration for PWA features
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                console.log('SW registered: ', registration);
            })
            .catch(function(registrationError) {
                console.log('SW registration failed: ', registrationError);
            });
    });
}