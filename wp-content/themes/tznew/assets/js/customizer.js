/**
 * Theme Customizer Live Preview
 *
 * @package TZnew
 */

(function($) {
    'use strict';

    // Site title and description
    wp.customize('blogname', function(value) {
        value.bind(function(to) {
            $('.site-title a').text(to);
        });
    });

    wp.customize('blogdescription', function(value) {
        value.bind(function(to) {
            $('.site-description').text(to);
        });
    });

    // Header text color
    wp.customize('header_textcolor', function(value) {
        value.bind(function(to) {
            if ('blank' === to) {
                $('.site-title, .site-description').css({
                    'clip': 'rect(1px, 1px, 1px, 1px)',
                    'position': 'absolute'
                });
            } else {
                $('.site-title, .site-description').css({
                    'clip': 'auto',
                    'position': 'relative'
                });
                $('.site-title a, .site-description').css({
                    'color': to
                });
            }
        });
    });

    // Contact Information
    wp.customize('tznew_phone', function(value) {
        value.bind(function(to) {
            $('.contact-phone').text(to);
        });
    });

    wp.customize('tznew_email', function(value) {
        value.bind(function(to) {
            $('.contact-email').text(to);
        });
    });

    wp.customize('tznew_address', function(value) {
        value.bind(function(to) {
            $('.contact-address').text(to);
        });
    });

    // Social Media Links
    var socialNetworks = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'tripadvisor'];
    
    socialNetworks.forEach(function(network) {
        wp.customize('tznew_social_' + network, function(value) {
            value.bind(function(to) {
                var $link = $('.social-' + network);
                if (to) {
                    $link.attr('href', to).show();
                } else {
                    $link.hide();
                }
            });
        });
    });

    // Footer Copyright
    wp.customize('tznew_footer_copyright', function(value) {
        value.bind(function(to) {
            $('.footer-copyright').html(to);
        });
    });

    // Colors
    wp.customize('tznew_primary_color', function(value) {
        value.bind(function(to) {
            updateColorCSS('primary', to);
        });
    });

    wp.customize('tznew_secondary_color', function(value) {
        value.bind(function(to) {
            updateColorCSS('secondary', to);
        });
    });

    wp.customize('tznew_accent_color', function(value) {
        value.bind(function(to) {
            updateColorCSS('accent', to);
        });
    });

    // Function to update color CSS
    function updateColorCSS(colorType, colorValue) {
        var $style = $('#tznew-customizer-' + colorType + '-color');
        
        if ($style.length === 0) {
            $style = $('<style id="tznew-customizer-' + colorType + '-color"></style>');
            $('head').append($style);
        }

        var css = '';
        
        if (colorType === 'primary') {
            css = `
                :root { --primary-color: ${colorValue}; }
                .btn-primary, .button-primary { background-color: ${colorValue}; border-color: ${colorValue}; }
                .btn-primary:hover, .button-primary:hover { background-color: ${colorValue}dd; border-color: ${colorValue}dd; }
                .text-primary { color: ${colorValue} !important; }
                .bg-primary { background-color: ${colorValue} !important; }
                a { color: ${colorValue}; }
                a:hover { color: ${colorValue}dd; }
                .site-header .main-navigation a:hover { color: ${colorValue}; }
            `;
        } else if (colorType === 'secondary') {
            css = `
                :root { --secondary-color: ${colorValue}; }
                .btn-secondary, .button-secondary { background-color: ${colorValue}; border-color: ${colorValue}; }
                .btn-secondary:hover, .button-secondary:hover { background-color: ${colorValue}dd; border-color: ${colorValue}dd; }
                .text-secondary { color: ${colorValue} !important; }
                .bg-secondary { background-color: ${colorValue} !important; }
                .post-meta a { color: ${colorValue}; }
            `;
        } else if (colorType === 'accent') {
            css = `
                :root { --accent-color: ${colorValue}; }
                .text-accent { color: ${colorValue} !important; }
                .bg-accent { background-color: ${colorValue} !important; }
                .booking-cta .btn { background-color: ${colorValue}; border-color: ${colorValue}; }
                .booking-cta .btn:hover { background-color: ${colorValue}dd; border-color: ${colorValue}dd; }
            `;
        }
        
        $style.html(css);
    }

})(jQuery);