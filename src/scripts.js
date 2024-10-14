// Data Definitions
const monthlyData = [
    { datum: '2023-01', verspaetungen: 65, wetterstoerungen: 12 },
    { datum: '2023-02', verspaetungen: 59, wetterstoerungen: 15 },
    { datum: '2023-03', verspaetungen: 80, wetterstoerungen: 8 },
    { datum: '2023-04', verspaetungen: 81, wetterstoerungen: 10 },
    { datum: '2023-05', verspaetungen: 56, wetterstoerungen: 7 },
    { datum: '2023-06', verspaetungen: 55, wetterstoerungen: 5 }
];

const weeklyData = [
    { woche: 'Woche 1', verspaetungen: 20, wetterstoerungen: 3 },
    { woche: 'Woche 2', verspaetungen: 25, wetterstoerungen: 5 },
    { woche: 'Woche 3', verspaetungen: 22, wetterstoerungen: 4 },
    { woche: 'Woche 4', verspaetungen: 18, wetterstoerungen: 2 },
    { woche: 'Woche 5', verspaetungen: 24, wetterstoerungen: 6 },
    { woche: 'Woche 6', verspaetungen: 19, wetterstoerungen: 3 }
];

const dailyData = [
    { tag: '1', verspaetungen: 85, wetterstoerungen: 12 },
    { tag: '2', verspaetungen: 60, wetterstoerungen: 5 },
    { tag: '3', verspaetungen: 75, wetterstoerungen: 8 },
    { tag: '4', verspaetungen: 90, wetterstoerungen: 15 },
    { tag: '5', verspaetungen: 70, wetterstoerungen: 7 },
    { tag: '6', verspaetungen: 65, wetterstoerungen: 6 },
    { tag: '7', verspaetungen: 55, wetterstoerungen: 4 },
    { tag: '8', verspaetungen: 95, wetterstoerungen: 18 },
    { tag: '9', verspaetungen: 80, wetterstoerungen: 10 },
    { tag: '10', verspaetungen: 85, wetterstoerungen: 11 }
];

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

// Initialize Chart.js
const ctx = document.getElementById('myChart').getContext('2d');
let chart;

// Function to Create/Update Chart
function createChart(type) {
    // Destroy existing chart instance if it exists
    if (chart) {
        chart.destroy();
    }

    let labels, dataVerspaetungen, dataWetterstoerungen, chartType;

    if (type === 'monthly') {
        labels = monthlyData.map(data => data.datum);
        dataVerspaetungen = monthlyData.map(data => data.verspaetungen);
        dataWetterstoerungen = monthlyData.map(data => data.wetterstoerungen);
        chartType = 'line';
    } else if (type === 'weekly') {
        labels = weeklyData.map(data => data.woche);
        dataVerspaetungen = weeklyData.map(data => data.verspaetungen);
        dataWetterstoerungen = weeklyData.map(data => data.wetterstoerungen);
        chartType = 'line';
    } else if (type === 'daily') {
        labels = dailyData.map(data => data.tag);
        dataVerspaetungen = dailyData.map(data => data.verspaetungen);
        dataWetterstoerungen = dailyData.map(data => data.wetterstoerungen);
        chartType = 'bar';
    }

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
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 10,
                    bottom: 10
                }},
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

// Weather vs Delay Scatter Chart
const scatterChartCtx = document.getElementById('scatterChart').getContext('2d');
new Chart(scatterChartCtx, {
    type: 'scatter',
    data: {
        datasets: [{
            label: 'Wetter vs. Verspätungen',
            data: monthlyData.map(data => ({
                x: data.wetterstoerungen,
                y: data.verspaetungen
            })),
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
                    text: 'Anzahl Wetterstörungen',
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
                    text: 'Anzahl Verspätete Züge',
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

// Button state active
function toggleActiveState(button) {
    // Toggle the active state by adding/removing the border color class
    button.classList.toggle('border-blue-500');
}