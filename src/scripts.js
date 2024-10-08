
const monthlyData = [
    { datum: '2023-01', verspaetungen: 65, wetterstoerungen: 12 },
    { datum: '2023-02', verspaetungen: 59, wetterstoerungen: 15 },
    { datum: '2023-03', verspaetungen: 80, wetterstoerungen: 8 },
    { datum: '2023-04', verspaetungen: 81, wetterstoerungen: 10 },
    { datum: '2023-05', verspaetungen: 56, wetterstoerungen: 7 },
    { datum: '2023-06', verspaetungen: 55, wetterstoerungen: 5 }
];

const aprilDailyData = [
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

// Monthly Line Chart
const monthlyChartCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyChartCtx, {
    type: 'line',
    data: {
        labels: monthlyData.map(data => data.datum),
        datasets: [
            {
                label: 'Zugverspätungen',
                data: monthlyData.map(data => data.verspaetungen),
                borderColor: '#5F94D7',
                fill: false
            },
            {
                label: 'Wetterstörungen',
                data: monthlyData.map(data => data.wetterstoerungen),
                borderColor: '#8C238C',
                fill: false
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: { display: true },
            y: { display: true }
        },
        plugins: {
            tooltip: {
                backgroundColor: '#3C3C3C',
                titleColor: '#E6E6E6',
                bodyColor: '#E6E6E6'
            }
        }
    }
});

// April Bar Chart
const aprilChartCtx = document.getElementById('aprilChart').getContext('2d');
new Chart(aprilChartCtx, {
    type: 'bar',
    data: {
        labels: aprilDailyData.map(data => data.tag),
        datasets: [
            {
                label: 'Zugverspätungen',
                data: aprilDailyData.map(data => data.verspaetungen),
                backgroundColor: '#5F94D7'
            },
            {
                label: 'Wetterstörungen',
                data: aprilDailyData.map(data => data.wetterstoerungen),
                backgroundColor: '#8C238C'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: { display: true },
            y: { display: true }
        },
        plugins: {
            tooltip: {
                backgroundColor: '#3C3C3C',
                titleColor: '#E6E6E6',
                bodyColor: '#E6E6E6'
            }
        }
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
                    color: '#E6E6E6',
                    borderColor: '#E6E6E6',
                    drawBorder: true,
                    drawTicks: true
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Anzahl Verpätete Züge',
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