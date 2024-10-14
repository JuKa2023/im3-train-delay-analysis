// Chart options and labels
const chartOptions = [
    { type: 'monthly', label: 'Monatlich' },
    { type: 'weekly', label: 'Wöchentlich' },
    { type: 'daily', label: 'Täglich' }
];

// Descriptions for each chart type
const descriptions = {
    monthly: "Dieses Diagramm zeigt die Beziehung zwischen Zugverspätungen und Wetterstörungen im Jahr 2023.",
    weekly: "Dieses Diagramm zeigt wöchentliche Trends der Zugverspätungen und Wetterstörungen. Höhere Werte sind während der Woche zu beobachten als am Wochenende.",
    daily: "Dieses Diagramm zeigt tägliche Muster der Zugverspätungen und Wetterstörungen, wobei morgens und abends die Spitzenzeiten sind."
};

// Initialize Chart.js context
const ctx = document.getElementById('myChart').getContext('2d');
let chart;

// Fetch Combined Train and Weather Data
async function fetchCombinedData() {
    const response = await fetch('api.php');
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

    const combinedData = await fetchCombinedData();

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
        labels = combinedData.map(data => data.date);
        dataVerspaetungen = combinedData.map(data => data.total_delays);
        dataWetterstoerungen = combinedData.map(data => data.disruption_score);
        chartType = 'bar';
    }

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
                    backgroundColor: chartType === 'line' ? 'transparent' : '#5F94D7',
                    fill: false
                },
                {
                    label: 'Wetterstörungen',
                    data: dataWetterstoerungen,
                    borderColor: '#8C238C',
                    backgroundColor: chartType === 'line' ? 'transparent' : '#8C238C',
                    fill: false
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
                    }
                },
                y: {
                    display: true,
                    ticks: {
                        color: '#BFBFBF' // Set the color of the y-axis labels
                    },
                    grid: {
                        color: '#3C3C3C' // Set the color of the y-axis grid lines
                    },
                    border: {
                        color: '#BFBFBF' // Set the color of the y-axis line
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

    // Create menu items excluding the selected type
    chartOptions.forEach(option => {
        if (option.type !== selectedType) {
            const menuItem = document.createElement('a');
            menuItem.href = '#';
            menuItem.setAttribute('data-chart-type', option.type);
            menuItem.className = 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100';
            menuItem.role = 'menuitem';
            menuItem.innerText = option.label;
            menuItem.addEventListener('click', function (e) {
                e.preventDefault();
                createChart(option.type);
                dropdownMenu.classList.add('hidden');
            });
            dropdownMenuItems.appendChild(menuItem);
        }
    });
}

// Function to set the dropdown menu width
function setDropdownWidth() {
    const dropdownButton = document.getElementById('dropdownButton');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const buttonWidth = dropdownButton.offsetWidth;
    dropdownMenu.style.width = buttonWidth + 'px';
}

// Initial Chart Load
createChart('monthly');

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
// Fetch Combined Data for Scatter Chart and create chart
async function createScatterChart() {
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
                label: 'Disruption Score vs. Percentage of Delayed Trains',
                data: processedData,
                backgroundColor: '#D60001',
                borderColor: '#D60001',
                pointRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    type: 'linear',
                    position: 'bottom',
                    title: {
                        display: true,
                        text: 'Disruption Score',
                        color: '#E6E6E6'
                    },
                    ticks: {
                        color: '#E6E6E6',
                        padding: 10
                    },
                    grid: {
                        drawOnChartArea: false,
                        color: '#3C3C3C',
                        borderColor: '#E6E6E6',
                        drawBorder: true,
                        drawTicks: true
                    },
                    border: {
                        color: '#E6E6E6'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Percentage of Delayed Trains (%)',
                        color: '#E6E6E6'
                    },
                    ticks: {
                        color: '#E6E6E6',
                        padding: 10
                    },
                    grid: {
                        drawOnChartArea: false,
                        color: 'transparent',
                        borderColor: '#E6E6E6',
                        drawBorder: true,
                        drawTicks: true
                    },
                    border: {
                        color: '#E6E6E6'
                    }
                }
            },
            plugins: {
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