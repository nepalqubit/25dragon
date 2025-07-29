<?php
/*
Template Name: Chart Debug
*/

get_header();
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Chart.js Debug Page</h1>
    
    <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
        <h2 class="text-xl font-bold mb-4">Chart.js Loading Status</h2>
        <div id="chartStatus" class="mb-4">
            <p class="text-yellow-600">Checking Chart.js status...</p>
        </div>
        
        <div class="elevation-chart-container relative" style="height: 400px; width: 100%;">
            <canvas id="debugChart"></canvas>
            <div id="debugChartLoading" class="absolute inset-0 flex items-center justify-center bg-blue-50 rounded-lg">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"></div>
                    <p class="text-sm text-blue-600">Loading debug chart...</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-gray-100 p-6 rounded-lg">
        <h2 class="text-xl font-bold mb-4">Console Output</h2>
        <div id="consoleOutput" class="bg-black text-green-400 p-4 rounded font-mono text-sm h-64 overflow-y-auto">
            <!-- Console messages will appear here -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusDiv = document.getElementById('chartStatus');
    const consoleOutput = document.getElementById('consoleOutput');
    
    function log(message, type = 'info') {
        const timestamp = new Date().toLocaleTimeString();
        const color = type === 'error' ? 'text-red-400' : type === 'success' ? 'text-green-400' : 'text-blue-400';
        consoleOutput.innerHTML += `<div class="${color}">[${timestamp}] ${message}</div>`;
        consoleOutput.scrollTop = consoleOutput.scrollHeight;
        console.log(`[Chart Debug] ${message}`);
    }
    
    log('Debug script started');
    
    // Check Chart.js loading
    function checkChartJs() {
        if (typeof Chart !== 'undefined') {
            log('Chart.js is loaded successfully!', 'success');
            log(`Chart.js version: ${Chart.version || 'Unknown'}`, 'info');
            statusDiv.innerHTML = '<p class="text-green-600 font-bold">✓ Chart.js is loaded and ready</p>';
            initDebugChart();
        } else {
            log('Chart.js is not loaded yet', 'error');
            statusDiv.innerHTML = '<p class="text-red-600 font-bold">✗ Chart.js is not loaded</p>';
        }
    }
    
    function initDebugChart() {
        const ctx = document.getElementById('debugChart');
        const loadingDiv = document.getElementById('debugChartLoading');
        
        if (!ctx) {
            log('Canvas element not found', 'error');
            return;
        }
        
        log('Initializing debug chart...');
        
        try {
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'],
                    datasets: [{
                        label: 'Test Elevation',
                        data: [1000, 1500, 2000, 1800, 2200],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Debug Chart - Chart.js Working!'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            title: {
                                display: true,
                                text: 'Altitude (m)'
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
            
            log('Debug chart created successfully!', 'success');
            loadingDiv.style.display = 'none';
            
        } catch (error) {
            log(`Error creating chart: ${error.message}`, 'error');
            loadingDiv.innerHTML = '<div class="text-center"><p class="text-sm text-red-600">Chart creation failed</p></div>';
        }
    }
    
    // Initial check
    checkChartJs();
    
    // Check periodically if Chart.js is not loaded
    let checkCount = 0;
    const maxChecks = 50;
    const checkInterval = setInterval(function() {
        checkCount++;
        if (typeof Chart !== 'undefined') {
            clearInterval(checkInterval);
            checkChartJs();
        } else if (checkCount >= maxChecks) {
            clearInterval(checkInterval);
            log('Chart.js loading timeout after 5 seconds', 'error');
            statusDiv.innerHTML = '<p class="text-red-600 font-bold">✗ Chart.js loading timeout</p>';
        } else {
            log(`Waiting for Chart.js... (${checkCount}/${maxChecks})`);
        }
    }, 100);
    
    // Log window.chartJsLoaded status
    setTimeout(() => {
        log(`window.chartJsLoaded: ${window.chartJsLoaded || 'undefined'}`);
    }, 1000);
});
</script>

<?php
get_footer();
?>