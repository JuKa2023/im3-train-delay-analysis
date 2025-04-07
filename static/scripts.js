const chartOptions = [
    { type: 'monthly', label: 'Monatlich' },
    { type: 'weekly', label: 'Wöchentlich' },
    { type: 'daily', label: 'Täglich' }
];

const descriptions = {
    monthly: "Dieses Diagramm zeigt die Beziehung zwischen Zugverspätungen und Wetterstörungen im Verlauf des letzten Monats. Die blaue Linie zeigt die Anzahl verspäteter Züge, während die violette Linie den Wetterstörungsindex darstellt.",
    weekly: "Dieses Diagramm zeigt wöchentliche Trends der Zugverspätungen und Wetterstörungen. Höhere Werte sind während der Woche zu beobachten als am Wochenende.",
    daily: "Dieses Diagramm zeigt tägliche Muster der Zugverspätungen und Wetterstörungen, wobei morgens und abends die Spitzenzeiten sind."
};

// Initialize Chart.js context
const ctx = document.getElementById('myChart').getContext('2d');
let chart;

// api.php: Fetch Statistics Data
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

function updateStatistics(stats) {
    document.getElementById('avg-weekly-delays').textContent = stats.avgWeeklyDelays;
    document.getElementById('weather-correlation').textContent = `${stats.weatherCorrelation}%`;
    document.getElementById('avg-bad-weather-days').textContent = stats.avgBadWeatherDays;
    document.getElementById('total-trains-per-week').textContent = `/${stats.totalTrainsPerWeek}`;
}

async function initializeStatistics() {
    const data = await fetchStatisticsData();
    const stats = calculateStatistics(data);
    updateStatistics(stats);
}

// api.php: Fetch Weather & Train Data for given resolution (daily/hourly)
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
        chartType = 'line';
    }

    chart = new Chart(ctx, {
        type: chartType,
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Anzahl verspäteter Züge',
                    data: dataVerspaetungen,
                    borderColor: '#5F94D7',
                    backgroundColor: 'transparent',
                    fill: false,
                    yAxisID: 'y'
                },
                {
                    label: 'Wetterstörungsindex',
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
                        color: '#E6E6E6'
                    },
                    grid: {
                        color: '#3C3C3C'
                    },
                    border: {
                        color: '#BFBFBF'
                    },
                    title: {
                        display: true,
                        text: type === 'daily' ? 'Tageszeit' : 'Datum',
                        color: '#E6E6E6'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    ticks: {
                        color: '#BFBFBF'
                    },
                    grid: {
                        color: '#3C3C3C'
                    },
                    border: {
                        color: '#BFBFBF'
                    },
                    title: {
                        display: true,
                        text: 'Anzahl verspäteter Züge (Anzahl)',
                        color: '#E6E6E6'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    ticks: {
                        color: '#BFBFBF'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                    border: {
                        color: '#BFBFBF'
                    },
                    title: {
                        display: true,
                        text: 'Wetterstörungsindex (0-100)',
                        color: '#E6E6E6'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        color: '#E6E6E6',
                        usePointStyle: true,
                        pointStyle: 'line'
                    }
                },
                tooltip: {
                    backgroundColor: '#3C3C3C',
                    titleColor: '#E6E6E6',
                    bodyColor: '#E6E6E6',
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.datasetIndex === 0) {
                                label += context.parsed.y + ' Züge';
                            } else {
                                label += context.parsed.y.toFixed(2);
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });

    document.getElementById('chartDescription').innerText = descriptions[type];

    // Update the dropdown button text
    const dropdownButton = document.getElementById('dropdownButton');
    const selectedOption = chartOptions.find(option => option.type === type);
    dropdownButton.innerHTML = `${selectedOption.label} 
    <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
    </svg>`;

    setDropdownWidth();
    updateDropdownMenu(type);
}

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


    const testSpan = document.createElement('span');
    testSpan.innerText = longestWord;
    testSpan.style.visibility = 'hidden'; // Make it invisible
    document.body.appendChild(testSpan);
    const longestWordWidth = testSpan.offsetWidth;
    document.body.removeChild(testSpan);

    dropdownButton.style.width = `${longestWordWidth + 50}px`;
    dropdownMenu.style.width = `${longestWordWidth + 50}px`;
}

// Initial Chart Load and Statistics Initialization
document.addEventListener('DOMContentLoaded', function () {
    initializeStatistics();
    createChart('monthly');
    createScatterChart();
});


const dropdownButton = document.getElementById('dropdownButton');
const dropdownMenu = document.getElementById('dropdownMenu');

dropdownButton.addEventListener('click', function (event) {
    event.stopPropagation();
    dropdownMenu.classList.toggle('hidden');
});

window.addEventListener('click', function (event) {
    if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.classList.add('hidden');
    }
});

// Scatter Chart
let scatterChart;
async function createScatterChart() {
    if (scatterChart) {
        scatterChart.destroy();
    }

    const combinedData = await fetchCombinedData();

    const processedData = combinedData.map(data => ({
        x: data.disruption_score,
        y: (data.total_delays / data.total_trains) * 100
    }));

    const scatterChartCtx = document.getElementById('scatterChart').getContext('2d');
    scatterChart = new Chart(scatterChartCtx, {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Verspätungen vs. Wetterstörungen',
                data: processedData,
                backgroundColor: '#D60001',
                borderColor: '#D60001',
                pointRadius: 5,
                fill: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
                        text: 'Wetterstörungsindex',
                        color: '#E6E6E6'
                    },
                    ticks: {
                        color: '#E6E6E6',
                    },
                    grid: {
                        color: '#3C3C3C',
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
                        text: 'Anteil verspäteter Züge (%)',
                        color: '#E6E6E6'
                    },
                    ticks: {
                        color: '#E6E6E6',
                    },
                    grid: {
                        color: '#3C3C3C',
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
                    bodyColor: '#E6E6E6',
                    callbacks: {
                        label: function (context) {
                            return [
                                `Wetterstörungsindex: ${context.parsed.x.toFixed(2)}`,
                                `Verspätungen: ${context.parsed.y.toFixed(2)}%`
                            ];
                        }
                    }
                }
            }
        }
    });
}

function debounce(func, wait) {
    let timeout;
    return function (...args) {
        const context = this;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

// Create a debounced version of createScatterChart with a 300ms delay to avoid calling it too often and therefore flickering
const debouncedCreateScatterChart = debounce(createScatterChart, 300);
window.addEventListener('resize', debouncedCreateScatterChart);

createScatterChart();