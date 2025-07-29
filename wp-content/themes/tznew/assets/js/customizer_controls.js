/**
 * TZnew Theme Customizer Controls JS
 * Handles custom controls for the WordPress Customizer
 *
 * @package TZnew
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Initialize when DOM is ready
    $(document).ready(function() {
        initializeCustomControls();
    });

    // Initialize custom controls
    function initializeCustomControls() {
        // Initialize section order drag-and-drop
        initSectionOrderControl();
        
        // Initialize icon picker
        initIconPicker();
        
        // Initialize taxonomy grid toggle
        initTaxonomyGridToggle();
        
        console.log('TZnew Customizer Controls Initialized');
    }
    
    /**
     * Initialize section order drag-and-drop control
     */
    function initSectionOrderControl() {
        var sectionOrderControl = $('#customize-control-tznew_section_order');
        
        if (sectionOrderControl.length) {
            var orderInput = sectionOrderControl.find('input');
            var orderValue = JSON.parse(orderInput.val());
            var orderList = $('<ul class="section-order-list"></ul>');
            
            // Create UI for drag-and-drop
            sectionOrderControl.append(orderList);
            
            // Add style for the drag-and-drop UI
            $('<style>\n' +
              '.section-order-list { margin: 10px 0; padding: 0; }\n' +
              '.section-order-list li { background: #fff; border: 1px solid #ddd; padding: 10px; margin-bottom: 5px; cursor: move; list-style: none; }\n' +
              '.section-order-list li:hover { background: #f9f9f9; }\n' +
              '.section-order-list li.ui-sortable-helper { box-shadow: 0 2px 5px rgba(0,0,0,0.2); }\n' +
              '</style>').appendTo('head');
            
            // Add items to the list
            $.each(orderValue, function(index, sectionId) {
                var sectionName = getSectionName(sectionId);
                orderList.append('<li data-section-id="' + sectionId + '">' + sectionName + '</li>');
            });
            
            // Make the list sortable (requires jQuery UI)
            if ($.fn.sortable) {
                orderList.sortable({
                    update: function() {
                        var newOrder = [];
                        orderList.find('li').each(function() {
                            newOrder.push($(this).data('section-id'));
                        });
                        orderInput.val(JSON.stringify(newOrder)).trigger('change');
                    }
                });
            } else {
                console.warn('jQuery UI Sortable is required for section ordering');
                orderList.append('<li class="error">jQuery UI Sortable is required for drag-and-drop functionality</li>');
            }
        }
    }
    
    /**
     * Get human-readable section name from section ID
     */
    function getSectionName(sectionId) {
        var sectionNames = {
            'hero': 'Hero Section',
            'featured_treks': 'Featured Treks',
            'regions': 'Trekking Regions',
            'trek_blocks': 'Interesting Trek Blocks',
            'why_choose': 'Why Choose Nepal',
            'statistics': 'Statistics',
            'popular_tours': 'Popular Tours',
            'popular_trips': 'Popular Trips',
            'destinations': 'Destinations',
            'blog': 'Blog',
            'testimonials': 'Testimonials',
            'cta': 'Call to Action',
            'footer': 'Footer'
        };
        
        return sectionNames[sectionId] || sectionId.replace('_', ' ');
    }
    
    /**
     * Initialize icon picker for hero section
     */
    function initIconPicker() {
        var iconControl = $('#customize-control-tznew_hero_icon');
        
        if (iconControl.length) {
            var iconInput = iconControl.find('input');
            var currentIcon = iconInput.val();
            var previewArea = $('<div class="icon-preview"></div>');
            var iconSelector = $('<div class="icon-selector"></div>');
            
            // Add style for the icon picker
            $('<style>\n' +
              '.icon-preview { margin: 10px 0; font-size: 24px; }\n' +
              '.icon-selector { margin: 10px 0; display: grid; grid-template-columns: repeat(5, 1fr); gap: 5px; }\n' +
              '.icon-option { padding: 8px; text-align: center; cursor: pointer; border: 1px solid #ddd; }\n' +
              '.icon-option:hover { background: #f9f9f9; }\n' +
              '.icon-option.selected { background: #0073aa; color: #fff; }\n' +
              '</style>').appendTo('head');
            
            // Add preview area
            iconControl.append(previewArea);
            updateIconPreview(currentIcon, previewArea);
            
            // Add icon selector
            iconControl.append(iconSelector);
            
            // Popular FontAwesome icons for trekking/travel
            var popularIcons = [
                'fa-mountain', 'fa-hiking', 'fa-campground', 'fa-map-marker-alt', 'fa-compass',
                'fa-route', 'fa-map', 'fa-binoculars', 'fa-tree', 'fa-sun',
                'fa-snowflake', 'fa-cloud', 'fa-water', 'fa-fire', 'fa-camera'
            ];
            
            // Add icon options
            $.each(popularIcons, function(index, icon) {
                var isSelected = (icon === currentIcon) ? ' selected' : '';
                var iconOption = $('<div class="icon-option' + isSelected + '" data-icon="' + icon + '"><i class="fas ' + icon + '"></i></div>');
                iconSelector.append(iconOption);
                
                // Handle icon selection
                iconOption.on('click', function() {
                    var selectedIcon = $(this).data('icon');
                    iconInput.val(selectedIcon).trigger('change');
                    updateIconPreview(selectedIcon, previewArea);
                    iconSelector.find('.icon-option').removeClass('selected');
                    $(this).addClass('selected');
                });
            });
        }
    }
    
    /**
     * Update icon preview
     */
    function updateIconPreview(icon, previewArea) {
        if (icon.startsWith('<svg')) {
            // Handle SVG code
            previewArea.html(icon);
        } else {
            // Handle FontAwesome class
            previewArea.html('<i class="fas ' + icon + '"></i>');
        }
    }
    
    /**
     * Initialize taxonomy grid toggle
     */
    function initTaxonomyGridToggle() {
        var gridToggleControl = $('#customize-control-tznew_destinations_grid');
        
        if (gridToggleControl.length) {
            var toggleInput = gridToggleControl.find('input');
            var previewArea = $('<div class="grid-toggle-preview"></div>');
            
            // Add style for the grid toggle preview
            $('<style>\n' +
              '.grid-toggle-preview { margin: 10px 0; }\n' +
              '.grid-preview-grid, .grid-preview-list { padding: 10px; border: 1px solid #ddd; margin-bottom: 5px; }\n' +
              '.grid-preview-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 5px; }\n' +
              '.grid-preview-list { display: flex; flex-direction: column; gap: 5px; }\n' +
              '.grid-preview-item { background: #f1f1f1; padding: 5px; text-align: center; font-size: 10px; }\n' +
              '.grid-preview-active { border: 2px solid #0073aa; }\n' +
              '</style>').appendTo('head');
            
            // Create grid preview
            var gridPreview = $('<div class="grid-preview-grid' + (toggleInput.prop('checked') ? ' grid-preview-active' : '') + '">Grid View</div>');
            for (var i = 0; i < 4; i++) {
                gridPreview.append('<div class="grid-preview-item">Item ' + (i + 1) + '</div>');
            }
            
            // Create list preview
            var listPreview = $('<div class="grid-preview-list' + (!toggleInput.prop('checked') ? ' grid-preview-active' : '') + '">List View</div>');
            for (var j = 0; j < 4; j++) {
                listPreview.append('<div class="grid-preview-item">Item ' + (j + 1) + '</div>');
            }
            
            // Add previews to control
            previewArea.append(gridPreview).append(listPreview);
            gridToggleControl.append(previewArea);
            
            // Update preview when toggle changes
            toggleInput.on('change', function() {
                if ($(this).prop('checked')) {
                    gridPreview.addClass('grid-preview-active');
                    listPreview.removeClass('grid-preview-active');
                } else {
                    listPreview.addClass('grid-preview-active');
                    gridPreview.removeClass('grid-preview-active');
                }
            });
        }
    }

})(jQuery);
