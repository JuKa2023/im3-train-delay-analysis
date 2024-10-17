// Chart options and labels
const chartOptions = [
    { type: 'monthly', label: 'Monatlich' },
    { type: 'weekly', label: 'Wöchentlich' },
    { type: 'daily', label: 'Täglich' }
];

// Descriptions for each chart type
const descriptions = {
    monthly: "Dieses Diagramm zeigt die Beziehung zwischen Zugverspätungen und Wetterstörungen im Verlauf des letzten Monats",
    weekly: "Dieses Diagramm zeigt wöchentliche Trends der Zugverspätungen und Wetterstörungen. Höhere Werte sind während der Woche zu beobachten als am Wochenende.",
    daily: "Dieses Diagramm zeigt tägliche Muster der Zugverspätungen und Wetterstörungen, wobei morgens und abends die Spitzenzeiten sind."
};

// Initialize Chart.js context
const ctx = document.getElementById('myChart').getContext('2d');
let chart;

// Function to fetch data for statistics
async function fetchStatisticsData() {
    const response = await fetch('api.php?resolution=daily');
    return await response.json();
}

function calculateStatistics(data) {
    const totalWeeks = Math.ceil(data.length / 7); // Assuming data points are daily

    // Total number of trains and delays
    const totalDelays = data.reduce((sum, item) => sum + parseInt(item.total_delays), 0);
    const totalTrains = data.reduce((sum, item) => sum + parseInt(item.total_trains), 0);

    const avgWeeklyDelays = Math.round(totalDelays / totalWeeks);

    //Total trains per week
    const totalTrainsPerWeek = Math.round(totalTrains / totalWeeks);


    // Weather-related delays (only delays on days with disruption score > 1.0)
    const weatherRelatedDelays = data
        .filter(item => parseFloat(item.disruption_score) > 15.0)
        .reduce((sum, item) => sum + parseInt(item.total_delays), 0);

    const weatherCorrelation = Math.round((weatherRelatedDelays / totalDelays) * 100);

    // Average number of bad weather days per week
    const badWeatherDays = data.filter(item => parseFloat(item.disruption_score) > 1.0).length;
    const avgBadWeatherDays = Math.round(badWeatherDays / totalWeeks);

    return {
        avgWeeklyDelays,
        totalTrainsPerWeek,
        weatherCorrelation,
        avgBadWeatherDays
    };
}

// Function to update statistics in HTML
function updateStatistics(stats) {
    document.getElementById('avg-weekly-delays').textContent = stats.avgWeeklyDelays;
    document.getElementById('weather-correlation').textContent = `${stats.weatherCorrelation}%`;
    document.getElementById('avg-bad-weather-days').textContent = stats.avgBadWeatherDays;
    document.getElementById('total-trains-per-week').textContent = `/${stats.totalTrainsPerWeek}`;
}

// Function to initialize statistics
async function initializeStatistics() {
    const data = await fetchStatisticsData();
    const stats = calculateStatistics(data);
    updateStatistics(stats);
}

// Fetch Combined Train and Weather Data
async function fetchCombinedData(resolution = 'daily') {
    const response = await fetch(`api.php?resolution=${resolution}`);
    const combinedData = await response.json();

    // Preprocess combined data if necessary
    return combinedData.map(item => ({
        date: item.date,
        total_trains: parseInt(item.total_trains),
        total_delays: parseInt(item.total_delays),
        avg_delay: parseFloat(item.avg_delay).toFixed(2),
        avg_temperature: parseFloat(item.avg_temperature).toFixed(2),
        disruption_score: parseFloat(item.disruption_score).toFixed(2)
    }));
}

// Function to Create/Update Chart
async function createChart(type) {
    // Destroy existing chart instance if it exists
    if (chart) {
        chart.destroy();
    }

    let labels, dataVerspaetungen, dataWetterstoerungen, chartType;

    // Fetch data based on chart type
    const combinedData = await fetchCombinedData(type === 'daily' ? 'hourly' : 'daily');

    // Prepare the data according to the chart type
    if (type === 'monthly') {
        labels = combinedData.map(data => data.date);
        dataVerspaetungen = combinedData.map(data => data.total_delays);
        dataWetterstoerungen = combinedData.map(data => data.disruption_score);
        chartType = 'line';
    } else if (type === 'weekly') {
        labels = combinedData.map(data => data.date);
        dataVerspaetungen = combinedData.map(data => data.total_delays);
        dataWetterstoerungen = combinedData.map(data => data.disruption_score);
        chartType = 'line';
    } else if (type === 'daily') {
        labels = combinedData.map(data => {
            const date = new Date(data.date);
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        });
        dataVerspaetungen = combinedData.map(data => data.total_delays);
        dataWetterstoerungen = combinedData.map(data => data.disruption_score);
        chartType = 'line'; // Changed from 'bar' to 'line'
    }

    // Create new chart
    // Create new chart
    chart = new Chart(ctx, {
        type: chartType,
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Zugverspätungen',
                    data: dataVerspaetungen,
                    borderColor: '#5F94D7',
                    backgroundColor: 'transparent',
                    fill: false,
                    yAxisID: 'y'
                },
                {
                    label: 'Wetterstörungen',
                    data: dataWetterstoerungen,
                    borderColor: '#8C238C',
                    backgroundColor: 'transparent',
                    fill: false,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            layout: {
                padding: {
                    top: 10,
                    bottom: 10
                }
            },
            scales: {
                x: {
                    display: true,
                    ticks: {
                        color: '#E6E6E6' // Set the color of the x-axis labels
                    },
                    grid: {
                        color: '#3C3C3C' // Set the color of the x-axis grid lines
                    },
                    border: {
                        color: '#BFBFBF' // Set the color of the x-axis line
                    },
                    title: {  // Add axis title
                        display: true,
                        text: 'X-Axis Label',  // Replace with actual x-axis description
                        color: '#E6E6E6'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    ticks: {
                        color: '#BFBFBF' // Set the color of the y-axis labels
                    },
                    grid: {
                        color: '#3C3C3C' // Set the color of the y-axis grid lines
                    },
                    border: {
                        color: '#BFBFBF' // Set the color of the y-axis line
                    },
                    title: {  // Add axis title for left y-axis
                        display: true,
                        text: 'Zugverspätungen (Unit)',  // Replace with actual left y-axis description
                        color: '#E6E6E6'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    ticks: {
                        color: '#BFBFBF' // Set the color of the right y-axis labels
                    },
                    grid: {
                        drawOnChartArea: false, // No grid lines for right y-axis
                    },
                    border: {
                        color: '#BFBFBF' // Set the color of the right y-axis line
                    },
                    title: {  // Add axis title for right y-axis
                        display: true,
                        text: 'Wetterstörungen (Unit)',  // Replace with actual right y-axis description
                        color: '#E6E6E6'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        color: '#E6E6E6' // Set the color of the legend text
                    }
                },
                tooltip: {
                    backgroundColor: '#3C3C3C',
                    titleColor: '#E6E6E6',
                    bodyColor: '#E6E6E6'
                }
            }
        }
    });

    // Update the chart description
    document.getElementById('chartDescription').innerText = descriptions[type];

    // Update the dropdown button text
    const dropdownButton = document.getElementById('dropdownButton');
    const selectedOption = chartOptions.find(option => option.type === type);
    dropdownButton.innerHTML = `${selectedOption.label} <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
    </svg>`;

    // Set the dropdown menu width to match the button
    setDropdownWidth();

    // Update the dropdown menu items
    updateDropdownMenu(type);
}

// Function to update the dropdown menu
function updateDropdownMenu(selectedType) {
    const dropdownMenuItems = document.getElementById('dropdownMenuItems');
    // Clear existing menu items
    dropdownMenuItems.innerHTML = '';

    // Create menu items including the selected type
    chartOptions.forEach(option => {
        const menuItem = document.createElement('a');
        menuItem.href = '#';
        menuItem.setAttribute('data-chart-type', option.type);
        menuItem.className = 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100';
        menuItem.role = 'menuitem';
        menuItem.innerText = option.label;

        // Apply active style if this is the selected type (light gray background)
        if (option.type === selectedType) {
            menuItem.classList.add('active-item'); // Add light gray background to the selected chart type
        }

        // Add click event listener to switch charts when clicking the option
        menuItem.addEventListener('click', function (e) {
            e.preventDefault();
            createChart(option.type);  // Re-create chart with the selected type
            dropdownMenu.classList.add('hidden'); // Close the dropdown after selection
        });

        dropdownMenuItems.appendChild(menuItem);
    });
}

// Function to set the dropdown menu width
function setDropdownWidth() {
    const dropdownButton = document.getElementById('dropdownButton');
    const dropdownMenu = document.getElementById('dropdownMenu');

    // Calculate the width based on the longest word
    let longestWord = '';
    chartOptions.forEach(option => {
        if (option.label.length > longestWord.length) {
            longestWord = option.label;
        }
    });

    // Create an invisible span to measure the width of the longest word
    const testSpan = document.createElement('span');
    testSpan.innerText = longestWord;
    testSpan.style.visibility = 'hidden'; // Make it invisible
    document.body.appendChild(testSpan);
    const longestWordWidth = testSpan.offsetWidth;
    document.body.removeChild(testSpan);

    // Set the width of both the button and dropdown to match the longest word
    dropdownButton.style.width = `${longestWordWidth + 50}px`; // Add some padding
    dropdownMenu.style.width = `${longestWordWidth + 50}px`;
}

// Initial Chart Load and Statistics Initialization
document.addEventListener('DOMContentLoaded', function () {
    initializeStatistics();
    createChart('monthly');
    createScatterChart();
});

// Dropdown Menu Toggle
const dropdownButton = document.getElementById('dropdownButton');
const dropdownMenu = document.getElementById('dropdownMenu');

dropdownButton.addEventListener('click', function (event) {
    event.stopPropagation();
    dropdownMenu.classList.toggle('hidden');
});

// Close the dropdown when clicking outside
window.addEventListener('click', function (event) {
    if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.classList.add('hidden');
    }
});





// Fetch Combined Data for Scatter Chart and create chart
async function createScatterChart() {

    if (chart) {
        chart.destroy();
    }

    const combinedData = await fetchCombinedData();

    // Preprocess the data to calculate percentage of delayed trains
    const processedData = combinedData.map(data => ({
        x: data.disruption_score, // Keep disruption score as the x-axis
        y: (data.total_delays / data.total_trains) * 100  // Calculate percentage of delayed trains
    }));

    // Create the scatter chart after data is fetched and processed
    const scatterChartCtx = document.getElementById('scatterChart').getContext('2d');
    new Chart(scatterChartCtx, {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Störungsindex vs. Prozentual Verspätete Züge',
                data: processedData,
                backgroundColor: '#D60001',
                borderColor: '#D60001',
                pointRadius: 5,
                fill: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            layout: {
                padding: {
                    top: 10,
                    bottom: 10
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Störfaktor',
                        color: '#E6E6E6'
                    },
                    ticks: {
                        color: '#E6E6E6',
                    },
                    grid: {
                        color: '#3C3C3C',  // Add this to include grid lines
                        borderColor: '#BFBFBF',
                        drawBorder: true,
                        drawTicks: true
                    },
                    border: {
                        color: '#BFBFBF'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    title: {
                        display: true,
                        text: 'Prozentual Verspätete Züge (%)',
                        color: '#E6E6E6'
                    },
                    ticks: {
                        color: '#E6E6E6',

                    },
                    grid: {
                        color: '#3C3C3C',  // Add this to include grid lines
                        borderColor: '#BFBFBF',
                        drawBorder: true,
                        drawTicks: true
                    },
                    border: {
                        color: '#BFBFBF'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        color: '#E6E6E6'
                    }
                },
                tooltip: {
                    backgroundColor: '#3C3C3C',
                    titleColor: '#E6E6E6',
                    bodyColor: '#E6E6E6'
                }
            },
            layout: {
                padding: {
                    left: 10,
                    right: 10,
                    top: 20,
                    bottom: 10
                }
            },
            elements: {
                point: {
                    pointStyle: 'circle',
                }
            }
        }
    });
}
// Call the function to create the scatter chart
createScatterChart();

// Button state active
function toggleActiveState(button) {
    // Toggle the active state by adding/removing the border color class
    button.classList.toggle('border-blue-500');
}
