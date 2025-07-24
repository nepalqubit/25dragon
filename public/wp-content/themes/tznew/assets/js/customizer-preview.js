/**
 * Customizer Live Preview JavaScript
 * Handles real-time preview of color and style changes
 *
 * @package TZnew
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Helper function to update CSS custom property
    function updateCSSProperty(property, value) {
        document.documentElement.style.setProperty(property, value);
    }

    // Helper function to generate CSS rule
    function generateCSS(selector, property, value) {
        var style = document.getElementById('tznew-customizer-preview');
        if (!style) {
            style = document.createElement('style');
            style.id = 'tznew-customizer-preview';
            document.head.appendChild(style);
        }
        
        var css = style.innerHTML;
        var rule = selector + ' { ' + property + ': ' + value + '; }';
        
        // Remove existing rule for this selector and property if it exists
        var regex = new RegExp(selector.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\s*\\{[^}]*' + property.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\s*:[^;}]*;?[^}]*\\}', 'g');
        css = css.replace(regex, '');
        
        style.innerHTML = css + rule;
    }

    // ==========================================================================
    // PRIMARY COLOR SCHEME
    // ==========================================================================

    // Primary Color
    wp.customize('tznew_primary_color', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-primary-color', newval);
        });
    });

    // Primary Hover Color
    wp.customize('tznew_primary_hover', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-primary-hover', newval);
        });
    });

    // Primary Light Color
    wp.customize('tznew_primary_light', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-primary-light', newval);
        });
    });

    // Primary Dark Color
    wp.customize('tznew_primary_dark', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-primary-dark', newval);
        });
    });

    // ==========================================================================
    // SECONDARY COLOR SCHEME
    // ==========================================================================

    // Secondary Color
    wp.customize('tznew_secondary_color', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-secondary-color', newval);
        });
    });

    // Secondary Hover Color
    wp.customize('tznew_secondary_hover', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-secondary-hover', newval);
        });
    });

    // ==========================================================================
    // ACCENT COLORS
    // ==========================================================================

    // Accent Color
    wp.customize('tznew_accent_color', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-accent-color', newval);
        });
    });

    // Success Color
    wp.customize('tznew_success_color', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-success-color', newval);
        });
    });

    // Warning Color
    wp.customize('tznew_warning_color', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-warning-color', newval);
        });
    });

    // Danger Color
    wp.customize('tznew_danger_color', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-danger-color', newval);
        });
    });

    // ==========================================================================
    // TEXT COLORS
    // ==========================================================================

    // Primary Text Color
    wp.customize('tznew_text_primary', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-text-primary', newval);
        });
    });

    // Secondary Text Color
    wp.customize('tznew_text_secondary', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-text-secondary', newval);
        });
    });

    // Muted Text Color
    wp.customize('tznew_text_muted', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-text-muted', newval);
        });
    });

    // ==========================================================================
    // BACKGROUND COLORS
    // ==========================================================================

    // Primary Background
    wp.customize('tznew_bg_primary', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-bg-primary', newval);
        });
    });

    // Secondary Background
    wp.customize('tznew_bg_secondary', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-bg-secondary', newval);
        });
    });

    // Muted Background
    wp.customize('tznew_bg_muted', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-bg-muted', newval);
        });
    });

    // ==========================================================================
    // TYPOGRAPHY SETTINGS
    // ==========================================================================

    // Primary Font Family
    wp.customize('tznew_font_primary', function(value) {
        value.bind(function(newval) {
            var fontFamily = '"' + newval + '", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
            updateCSSProperty('--trek-font-family-primary', fontFamily);
            
            // Load Google Font if needed
            if (newval !== 'inherit') {
                loadGoogleFont(newval);
            }
        });
    });

    // Heading Font Family
    wp.customize('tznew_font_heading', function(value) {
        value.bind(function(newval) {
            var fontFamily = '"' + newval + '", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
            updateCSSProperty('--trek-font-family-heading', fontFamily);
            
            // Load Google Font if needed
            if (newval !== 'inherit') {
                loadGoogleFont(newval);
            }
        });
    });

    // Base Font Size
    wp.customize('tznew_font_size_base', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-font-size-base', newval + 'px');
        });
    });

    // ==========================================================================
    // BUTTON STYLES
    // ==========================================================================

    // Button Border Radius
    wp.customize('tznew_button_radius', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-radius-lg', newval + 'px');
        });
    });

    // Button Padding
    wp.customize('tznew_button_padding', function(value) {
        value.bind(function(newval) {
            generateCSS('.trek-btn', 'padding', newval + 'px 24px');
        });
    });

    // ==========================================================================
    // CARD STYLES
    // ==========================================================================

    // Card Border Radius
    wp.customize('tznew_card_radius', function(value) {
        value.bind(function(newval) {
            updateCSSProperty('--trek-radius-2xl', newval + 'px');
        });
    });

    // Card Shadow Intensity
    wp.customize('tznew_card_shadow', function(value) {
        value.bind(function(newval) {
            var shadowMap = {
                'none': 'none',
                'small': '0 1px 2px 0 rgb(0 0 0 / 0.05)',
                'medium': '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)',
                'large': '0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1)',
                'xl': '0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)'
            };
            var shadowValue = shadowMap[newval] || shadowMap['medium'];
            updateCSSProperty('--trek-shadow-md', shadowValue);
        });
    });

    // ==========================================================================
    // ANIMATION SETTINGS
    // ==========================================================================

    // Enable Animations
    wp.customize('tznew_enable_animations', function(value) {
        value.bind(function(newval) {
            if (newval) {
                // Remove animation disable rule
                var style = document.getElementById('tznew-disable-animations');
                if (style) {
                    style.remove();
                }
            } else {
                // Add animation disable rule
                var style = document.createElement('style');
                style.id = 'tznew-disable-animations';
                style.innerHTML = '*, *::before, *::after { transition: none !important; animation: none !important; }';
                document.head.appendChild(style);
            }
        });
    });

    // Animation Speed
    wp.customize('tznew_animation_speed', function(value) {
        value.bind(function(newval) {
            var speedMap = {
                'fast': '0.15s',
                'normal': '0.3s',
                'slow': '0.5s'
            };
            var transitionSpeed = speedMap[newval] || '0.3s';
            updateCSSProperty('--trek-transition-normal', transitionSpeed + ' ease-in-out');
        });
    });

    // ==========================================================================
    // HELPER FUNCTIONS
    // ==========================================================================

    /**
     * Load Google Font dynamically with proper CORS handling
     */
    function loadGoogleFont(fontName) {
        if (fontName === 'inherit') return;
        
        var fontId = 'google-font-' + fontName.toLowerCase().replace(/\s+/g, '-');
        
        // Check if font is already loaded
        if (document.getElementById(fontId)) {
            return;
        }
        
        // Add preconnect links if not already present
        if (!document.querySelector('link[href="https://fonts.googleapis.com"]')) {
            var preconnect1 = document.createElement('link');
            preconnect1.rel = 'preconnect';
            preconnect1.href = 'https://fonts.googleapis.com';
            document.head.appendChild(preconnect1);
            
            var preconnect2 = document.createElement('link');
            preconnect2.rel = 'preconnect';
            preconnect2.href = 'https://fonts.gstatic.com';
            preconnect2.crossOrigin = 'anonymous';
            document.head.appendChild(preconnect2);
        }
        
        // Create link element for Google Font with crossorigin attribute
        var link = document.createElement('link');
        link.id = fontId;
        link.rel = 'stylesheet';
        link.crossOrigin = 'anonymous';
        link.href = 'https://fonts.googleapis.com/css2?family=' + fontName.replace(/\s+/g, '+') + ':wght@300;400;500;600;700;800&display=swap';
        
        // Add error handling
        link.onerror = function() {
            console.warn('Failed to load Google Font: ' + fontName);
        };
        
        document.head.appendChild(link);
    }

    /**
     * Debounce function to limit rapid fire events
     */
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    /**
     * Initialize preview functionality
     */
    function initializePreview() {
        // Add class to body to indicate customizer preview mode
        document.body.classList.add('customizer-preview-active');
        
        // Add visual indicators for customizable elements
        if (window.location.search.indexOf('customize_messenger_channel') !== -1) {
            addCustomizerHelpers();
        }
    }

    /**
     * Add visual helpers for customizer
     */
    function addCustomizerHelpers() {
        var style = document.createElement('style');
        style.innerHTML = `
            .customizer-preview-active .trek-meta-card:hover,
            .customizer-preview-active .trek-booking-card:hover,
            .customizer-preview-active .trek-btn:hover {
                outline: 2px dashed #0073aa;
                outline-offset: 2px;
            }
            
            .customizer-preview-active .trek-meta-card::after,
            .customizer-preview-active .trek-booking-card::after {
                content: "Customizable";
                position: absolute;
                top: -8px;
                right: -8px;
                background: #0073aa;
                color: white;
                font-size: 10px;
                padding: 2px 6px;
                border-radius: 3px;
                font-weight: bold;
                z-index: 1000;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .customizer-preview-active .trek-meta-card:hover::after,
            .customizer-preview-active .trek-booking-card:hover::after {
                opacity: 1;
            }
        `;
        document.head.appendChild(style);
    }

    // Initialize when DOM is ready
    $(document).ready(function() {
        initializePreview();
    });

    // Handle customizer ready event
    wp.customize.bind('ready', function() {
        console.log('TZnew Customizer Preview Ready');
    });

})(jQuery);