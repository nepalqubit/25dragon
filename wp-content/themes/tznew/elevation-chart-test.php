<?php
/**
 * Elevation Chart Test Page
 * 
 * This file helps test the elevation chart functionality for tours post type.
 * Access via: yoursite.com/wp-content/themes/tznew/elevation-chart-test.php
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-center">Elevation Chart Test</h1>
    
    <div class="max-w-4xl mx-auto">
        <!-- Test Chart.js Loading -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
            <h2 class="text-xl font-bold mb-4">Chart.js Loading Test</h2>
            <div id="chartJsStatus" class="p-4 rounded bg-gray-100">
                <p>Checking Chart.js availability...</p>
            </div>
        </div>
        
        <!-- Test Elevation Chart -->
        <div class="elevation-chart mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl shadow-sm border border-blue-100">
            <h2 class="text-2xl font-bold mb-4 text-blue-800 border-b border-blue-200 pb-3 flex items-center">
                <i class="fas fa-chart-line mr-2 text-blue-600" aria-hidden="true"></i>
                Test Elevation Profile
            </h2>
            <div class="elevation-chart-container relative" style="height: 400px; width: 100%;">
                <canvas id="testElevationChart"></canvas>
                <div id="testChartLoading" class="absolute inset-0 flex items-center justify-center bg-blue-50 rounded-lg">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"></div>
                        <p class="text-sm text-blue-600">Loading test elevation chart...</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Test Results -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold mb-4">Test Results</h2>
            <div id="testResults" class="space-y-2">
                <p class="text-gray-600">Running tests...</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusDiv = document.getElementById('chartJsStatus');
    const resultsDiv = document.getElementById('testResults');
    const results = [];
    
    // Test 1: Chart.js availability
    if (typeof Chart !== 'undefined') {
        statusDiv.innerHTML = '<p class="text-green-600"><i class="fas fa-check-circle mr-2"></i>Chart.js is loaded successfully!</p>';
        results.push('<span class="text-green-600">✓ Chart.js library loaded</span>');
    } else {
        statusDiv.innerHTML = '<p class="text-red-600"><i class="fas fa-times-circle mr-2"></i>Chart.js is not loaded!</p>';
        results.push('<span class="text-red-600">✗ Chart.js library not loaded</span>');
    }
    
    // Test 2: Canvas element
    const canvas = document.getElementById('testElevationChart');
    if (canvas) {
        results.push('<span class="text-green-600">✓ Canvas element found</span>');
    } else {
        results.push('<span class="text-red-600">✗ Canvas element not found</span>');
    }
    
    // Test 3: CSS classes
    const chartContainer = document.querySelector('.elevation-chart-container');
    if (chartContainer) {
        results.push('<span class="text-green-600">✓ Chart container CSS applied</span>');
    } else {
        results.push('<span class="text-red-600">✗ Chart container CSS missing</span>');
    }
    
    // Test 4: Create test chart
    if (typeof Chart !== 'undefined' && canvas) {
        try {
            const testData = {
                labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'],
                datasets: [{
                    label: 'Test Elevation (m)',
                    data: [1400, 2800, 3500, 4200, 5000],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgb(34, 197, 94)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            };
            
            new Chart(canvas, {
                type: 'line',
                data: testData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            title: {
                                display: true,
                                text: 'Elevation (meters)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Days'
                            }
                        }
                    }
                }
            });
            
            // Hide loading indicator
            const loadingDiv = document.getElementById('testChartLoading');
            if (loadingDiv) {
                loadingDiv.style.display = 'none';
            }
            
            results.push('<span class="text-green-600">✓ Test chart created successfully</span>');
        } catch (error) {
            results.push('<span class="text-red-600">✗ Chart creation failed: ' + error.message + '</span>');
        }
    }
    
    // Display results
    resultsDiv.innerHTML = results.join('<br>');
});
</script>

<?php
get_footer();
?>