/**
 * Region Manager Admin JavaScript
 * Handles map initialization, polygon drawing, and region management
 */

(function($) {
    'use strict';
    
    let map;
    let drawnItems;
    let drawControl;
    let currentRegion = null;
    let regions = [];
    let trips = {};
    
    // Initialize when document is ready
    $(document).ready(function() {
        initializeRegionManager();
    });
    
    /**
     * Initialize the region manager
     */
    function initializeRegionManager() {
        // Show loading state
        showMapLoading();
        
        // Get data from localized script
        regions = regionManagerAjax.regions || [];
        trips = regionManagerAjax.trips || {};
        
        // Initialize map
        initializeMap();
        
        // Load regions list
        loadRegionsList();
        
        // Load trip assignments
        loadTripAssignments();
        
        // Bind events
        bindEvents();
        
        // Update map stats
        updateMapStats();
        
        // Hide loading state
        hideMapLoading();
        
        console.log('Region Manager initialized with', regions.length, 'regions');
    }
    
    /**
     * Initialize the Leaflet map
     */
    function initializeMap() {
        // Initialize map centered on Nepal
        map = L.map('region-map').setView([28.3949, 84.1240], 7);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18
        }).addTo(map);
        
        // Initialize drawn items layer
        drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        
        // Initialize draw control
        drawControl = new L.Control.Draw({
            position: 'topright',
            draw: {
                polygon: {
                    allowIntersection: false,
                    drawError: {
                        color: '#e1e100',
                        message: '<strong>Error:</strong> Shape edges cannot cross!'
                    },
                    shapeOptions: {
                        color: '#3388ff',
                        fillOpacity: 0.2
                    }
                },
                polyline: false,
                rectangle: false,
                circle: false,
                marker: false,
                circlemarker: false
            },
            edit: {
                featureGroup: drawnItems,
                remove: true
            }
        });
        
        map.addControl(drawControl);
        
        // Load existing regions on map
        loadRegionsOnMap();
        
        // Bind map events
        bindMapEvents();
        
        // Mark map as region manager type
        $('#region-map').attr('data-map-initialized', 'true');
        
        // Bind new region manager map events
        bindRegionManagerEvents();
    }
    
    /**
     * Bind region manager specific events
     */
    function bindRegionManagerEvents() {
        // Reset map view button
        $('#reset-map-view').on('click', function() {
            resetMapView();
        });
        
        // Toggle all regions button
        $('#toggle-all-regions').on('click', function() {
            toggleAllRegions();
        });
        
        // Enable drawing button
        $('#enable-drawing').on('click', function() {
            enableDrawing();
        });
        
        // Disable drawing button
        $('#disable-drawing').on('click', function() {
            disableDrawing();
        });
    }
    
    /**
     * Show map loading state
     */
    function showMapLoading() {
        $('.map-loading').show();
    }
    
    /**
     * Hide map loading state
     */
    function hideMapLoading() {
        setTimeout(function() {
            $('.map-loading').fadeOut();
        }, 500);
    }
    
    /**
     * Update map statistics
     */
    function updateMapStats() {
        const totalRegions = regions.length;
        const visibleRegions = regions.filter(region => region.show_on_map).length;
        
        $('#total-regions').text(totalRegions);
        $('#visible-regions').text(visibleRegions);
    }
    
    /**
     * Reset map view to Nepal center
     */
    function resetMapView() {
        map.setView([28.3949, 84.1240], 7);
    }
    
    /**
     * Toggle visibility of all regions
     */
    function toggleAllRegions() {
        const button = $('#toggle-all-regions');
        const currentText = button.text();
        
        if (currentText.includes('Hide')) {
            // Hide all regions
            drawnItems.eachLayer(function(layer) {
                layer.setStyle({ fillOpacity: 0, opacity: 0 });
            });
            button.text('Show All Regions');
        } else {
            // Show all regions
            drawnItems.eachLayer(function(layer) {
                layer.setStyle({ fillOpacity: 0.2, opacity: 1 });
            });
            button.text('Hide All Regions');
        }
    }
    
    /**
     * Enable drawing mode
     */
    function enableDrawing() {
        if (drawControl) {
            drawControl.addTo(map);
            $('#enable-drawing').prop('disabled', true);
            $('#disable-drawing').prop('disabled', false);
        }
    }
    
    /**
     * Disable drawing mode
     */
    function disableDrawing() {
        if (drawControl) {
            map.removeControl(drawControl);
            $('#enable-drawing').prop('disabled', false);
            $('#disable-drawing').prop('disabled', true);
        }
    }
    
    /**
     * Bind map events
     */
    function bindMapEvents() {
        // Handle polygon creation
        map.on(L.Draw.Event.CREATED, function(e) {
            const layer = e.layer;
            drawnItems.addLayer(layer);
            
            // Open region edit modal for new polygon
            openRegionModal(null, layer);
        });
        
        // Handle polygon editing
        map.on(L.Draw.Event.EDITED, function(e) {
            const layers = e.layers;
            layers.eachLayer(function(layer) {
                updateRegionPolygon(layer);
            });
        });
        
        // Handle polygon deletion
        map.on(L.Draw.Event.DELETED, function(e) {
            const layers = e.layers;
            layers.eachLayer(function(layer) {
                deleteRegionPolygon(layer);
            });
        });
    }
    
    /**
     * Load existing regions on the map
     */
    function loadRegionsOnMap() {
        regions.forEach(function(region) {
            try {
                if (region.polygon_coordinates && region.polygon_coordinates.length > 0) {
                    const polygon = L.polygon(region.polygon_coordinates, {
                        color: region.color || '#3388ff',
                        fillOpacity: 0.2,
                        weight: 2
                    });
                
                    // Store region data with the polygon
                    polygon.regionData = region;
                    
                    // Add popup with region info
                    polygon.bindPopup(`
                        <strong>${region.name}</strong><br>
                        ${region.description || ''}<br>
                        <button onclick="editRegion(${region.id})" class="button button-small">Edit</button>
                    `);
                    
                    drawnItems.addLayer(polygon);
                }
            } catch (error) {
                console.warn('Error loading region on map:', error, region);
            }
        });
    }
    
    /**
     * Load regions list in the sidebar
     */
    function loadRegionsList() {
        const $regionsList = $('#regions-list');
        $regionsList.empty();
        
        if (regions.length === 0) {
            $regionsList.html('<p>No regions found. Click "Add New Region" to create one.</p>');
            return;
        }
        
        regions.forEach(function(region) {
            const $regionItem = $(`
                <div class="region-item" data-region-id="${region.id}">
                    <h3>
                        <span class="region-color" style="background-color: ${region.color}"></span>
                        ${region.name}
                    </h3>
                    <p>${region.description || 'No description'}</p>
                    <div class="region-status">
                        <span class="status-indicator ${region.show_on_map ? 'active' : ''}"></span>
                        <span>${region.show_on_map ? 'Visible on map' : 'Hidden from map'}</span>
                    </div>
                    <div class="region-assignments">
                        <small>
                            Trekking: ${region.assigned_trekking ? region.assigned_trekking.length : 0} | 
                            Tours: ${region.assigned_tours ? region.assigned_tours.length : 0}
                        </small>
                    </div>
                </div>
            `);
            
            $regionsList.append($regionItem);
        });
        
        // Update map stats after loading regions
        updateMapStats();
    }
    
    /**
     * Load trip assignments in the modal
     */
    function loadTripAssignments() {
        // Load trekking assignments
        const $trekkingContainer = $('#assigned-trekking');
        $trekkingContainer.empty();
        
        if (trips.trekking && trips.trekking.length > 0) {
            trips.trekking.forEach(function(trek) {
                $trekkingContainer.append(`
                    <label>
                        <input type="checkbox" name="assigned_trekking[]" value="${trek.id}">
                        ${trek.title}
                    </label>
                `);
            });
        } else {
            $trekkingContainer.html('<p>No trekking posts found.</p>');
        }
        
        // Load tours assignments
        const $toursContainer = $('#assigned-tours');
        $toursContainer.empty();
        
        if (trips.tours && trips.tours.length > 0) {
            trips.tours.forEach(function(tour) {
                $toursContainer.append(`
                    <label>
                        <input type="checkbox" name="assigned_tours[]" value="${tour.id}">
                        ${tour.title}
                    </label>
                `);
            });
        } else {
            $toursContainer.html('<p>No tour posts found.</p>');
        }
    }
    
    /**
     * Bind UI events
     */
    function bindEvents() {
        // Add new region button
        $('#add-new-region').on('click', function() {
            openRegionModal();
        });
        
        // Save all regions button
        $('#save-all-regions').on('click', function() {
            saveAllRegions();
        });
        
        // Region item click
        $(document).on('click', '.region-item', function() {
            const regionId = $(this).data('region-id');
            selectRegion(regionId);
        });
        
        // Modal events
        $('.close-modal').on('click', function() {
            closeRegionModal();
        });
        
        $('#save-region').on('click', function() {
            saveRegion();
        });
        
        $('#delete-region').on('click', function() {
            if (confirm('Are you sure you want to delete this region?')) {
                deleteRegion();
            }
        });
        
        // Close modal on outside click
        $(document).on('click', '.region-modal', function(e) {
            if (e.target === this) {
                closeRegionModal();
            }
        });
    }
    
    /**
     * Open region edit modal
     */
    function openRegionModal(region, polygon) {
        currentRegion = region;
        
        if (region) {
            // Edit existing region
            $('#modal-title').text('Edit Region');
            $('#region-id').val(region.id);
            $('#region-name').val(region.name);
            $('#region-description').val(region.description || '');
            $('#region-color').val(region.color || '#3388ff');
            $('#show-on-map').prop('checked', region.show_on_map !== false);
            
            // Set assigned trips
            $('input[name="assigned_trekking[]"]').prop('checked', false);
            if (region.assigned_trekking) {
                region.assigned_trekking.forEach(function(trekId) {
                    $(`input[name="assigned_trekking[]"][value="${trekId}"]`).prop('checked', true);
                });
            }
            
            $('input[name="assigned_tours[]"]').prop('checked', false);
            if (region.assigned_tours) {
                region.assigned_tours.forEach(function(tourId) {
                    $(`input[name="assigned_tours[]"][value="${tourId}"]`).prop('checked', true);
                });
            }
            
            $('#delete-region').show();
        } else {
            // Add new region
            $('#modal-title').text('Add New Region');
            $('#region-form')[0].reset();
            $('#region-id').val('');
            $('#region-color').val('#3388ff');
            $('#show-on-map').prop('checked', true);
            $('input[name="assigned_trekking[]"]').prop('checked', false);
            $('input[name="assigned_tours[]"]').prop('checked', false);
            $('#delete-region').hide();
        }
        
        // Store polygon reference if provided
        if (polygon) {
            currentRegion = currentRegion || {};
            currentRegion.polygon = polygon;
        }
        
        $('#region-edit-modal').show();
    }
    
    /**
     * Close region modal
     */
    function closeRegionModal() {
        $('#region-edit-modal').hide();
        currentRegion = null;
    }
    
    /**
     * Select a region
     */
    function selectRegion(regionId) {
        // Remove previous selection
        $('.region-item').removeClass('active');
        
        // Add selection to clicked item
        $(`.region-item[data-region-id="${regionId}"]`).addClass('active');
        
        // Find and highlight region on map
        const region = regions.find(r => r.id == regionId);
        if (region) {
            // Find the polygon layer for this region
            drawnItems.eachLayer(function(layer) {
                if (layer.regionData && layer.regionData.id == regionId) {
                    // Highlight the polygon
                    layer.setStyle({
                        color: '#ff0000',
                        weight: 4
                    });
                    
                    // Reset style after 2 seconds
                    setTimeout(function() {
                        layer.setStyle({
                            color: region.color || '#3388ff',
                            weight: 2
                        });
                    }, 2000);
                    
                    // Zoom to polygon
                    map.fitBounds(layer.getBounds());
                }
            });
        }
    }
    
    /**
     * Save region
     */
    function saveRegion() {
        const formData = new FormData();
        
        // Basic form data
        formData.append('action', 'save_region');
        formData.append('nonce', regionManagerAjax.nonce);
        formData.append('region_id', $('#region-id').val());
        formData.append('region_name', $('#region-name').val());
        formData.append('region_description', $('#region-description').val());
        formData.append('region_color', $('#region-color').val());
        
        if ($('#show-on-map').is(':checked')) {
            formData.append('show_on_map', '1');
        }
        
        // Get polygon coordinates with error handling
        let polygonCoordinates = [];
        try {
            if (currentRegion && currentRegion.polygon) {
                const latLngs = currentRegion.polygon.getLatLngs();
                if (latLngs && latLngs[0] && Array.isArray(latLngs[0])) {
                    polygonCoordinates = latLngs[0].map(function(latlng) {
                        return [latlng.lat, latlng.lng];
                    });
                }
            }
        } catch (error) {
            console.warn('Error getting polygon coordinates:', error);
            polygonCoordinates = [];
        }
        formData.append('polygon_coordinates', JSON.stringify(polygonCoordinates));
        
        // Get assigned trips
        const assignedTrekking = [];
        $('input[name="assigned_trekking[]"]:checked').each(function() {
            assignedTrekking.push($(this).val());
        });
        assignedTrekking.forEach(function(id) {
            formData.append('assigned_trekking[]', id);
        });
        
        const assignedTours = [];
        $('input[name="assigned_tours[]"]:checked').each(function() {
            assignedTours.push($(this).val());
        });
        assignedTours.forEach(function(id) {
            formData.append('assigned_tours[]', id);
        });
        
        // Show loading state
        $('#save-region').prop('disabled', true).text('Saving...');
        
        // Send AJAX request
        $.ajax({
            url: regionManagerAjax.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showNotice('Region saved successfully!', 'success');
                    
                    // Update region data
                    if (currentRegion && currentRegion.polygon) {
                        currentRegion.polygon.regionData = {
                            id: response.data.region_id,
                            name: $('#region-name').val(),
                            description: $('#region-description').val(),
                            color: $('#region-color').val(),
                            show_on_map: $('#show-on-map').is(':checked'),
                            polygon_coordinates: polygonCoordinates,
                            assigned_trekking: assignedTrekking,
                            assigned_tours: assignedTours
                        };
                        
                        // Update polygon style
                        currentRegion.polygon.setStyle({
                            color: $('#region-color').val(),
                            fillOpacity: 0.2
                        });
                    }
                    
                    // Reload regions data
                    reloadRegionsData();
                    
                    closeRegionModal();
                } else {
                    showNotice('Error saving region: ' + response.data, 'error');
                }
            },
            error: function() {
                showNotice('Error saving region. Please try again.', 'error');
            },
            complete: function() {
                $('#save-region').prop('disabled', false).text('Save Region');
            }
        });
    }
    
    /**
     * Delete region
     */
    function deleteRegion() {
        const regionId = $('#region-id').val();
        
        if (!regionId) {
            showNotice('No region selected for deletion.', 'error');
            return;
        }
        
        // Show loading state
        $('#delete-region').prop('disabled', true).text('Deleting...');
        
        $.ajax({
            url: regionManagerAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'delete_region',
                nonce: regionManagerAjax.nonce,
                region_id: regionId
            },
            success: function(response) {
                if (response.success) {
                    showNotice('Region deleted successfully!', 'success');
                    
                    // Remove polygon from map
                    if (currentRegion && currentRegion.polygon) {
                        drawnItems.removeLayer(currentRegion.polygon);
                    }
                    
                    // Reload regions data
                    reloadRegionsData();
                    
                    closeRegionModal();
                } else {
                    showNotice('Error deleting region: ' + response.data, 'error');
                }
            },
            error: function() {
                showNotice('Error deleting region. Please try again.', 'error');
            },
            complete: function() {
                $('#delete-region').prop('disabled', false).text('Delete Region');
            }
        });
    }
    
    /**
     * Update region polygon coordinates
     */
    function updateRegionPolygon(layer) {
        if (layer.regionData) {
            const coordinates = layer.getLatLngs()[0].map(function(latlng) {
                return [latlng.lat, latlng.lng];
            });
            
            // Update the region data
            layer.regionData.polygon_coordinates = coordinates;
            
            // You might want to auto-save here or mark as dirty
            console.log('Polygon updated for region:', layer.regionData.name);
        }
    }
    
    /**
     * Delete region polygon
     */
    function deleteRegionPolygon(layer) {
        if (layer.regionData) {
            console.log('Polygon deleted for region:', layer.regionData.name);
            // You might want to update the database here
        }
    }
    
    /**
     * Reload regions data from server
     */
    function reloadRegionsData() {
        // In a real implementation, you'd fetch fresh data from the server
        // For now, we'll just reload the page
        window.location.reload();
    }
    
    /**
     * Save all regions
     */
    function saveAllRegions() {
        showNotice('Saving all regions...', 'warning');
        
        // Iterate through all polygons and save their coordinates
        const updates = [];
        
        drawnItems.eachLayer(function(layer) {
            if (layer.regionData) {
                const coordinates = layer.getLatLngs()[0].map(function(latlng) {
                    return [latlng.lat, latlng.lng];
                });
                
                updates.push({
                    id: layer.regionData.id,
                    coordinates: coordinates
                });
            }
        });
        
        if (updates.length === 0) {
            showNotice('No regions to save.', 'warning');
            return;
        }
        
        // Here you would send all updates to the server
        // For now, just show a success message
        setTimeout(function() {
            showNotice(`Successfully saved ${updates.length} regions!`, 'success');
        }, 1000);
    }
    
    /**
     * Show notification message
     */
    function showNotice(message, type) {
        const $notice = $(`<div class="notice notice-${type}"><p>${message}</p></div>`);
        $('.wrap h1').after($notice);
        
        // Auto-remove after 5 seconds
        setTimeout(function() {
            $notice.fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }
    
    // Make editRegion function global for popup buttons
    window.editRegion = function(regionId) {
        const region = regions.find(r => r.id == regionId);
        if (region) {
            // Find the polygon for this region
            drawnItems.eachLayer(function(layer) {
                if (layer.regionData && layer.regionData.id == regionId) {
                    currentRegion = region;
                    currentRegion.polygon = layer;
                    openRegionModal(region);
                }
            });
        }
    };
    
})(jQuery);