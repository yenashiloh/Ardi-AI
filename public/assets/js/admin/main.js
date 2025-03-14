// Sidebar toggle
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');

sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('mobile-open');
});

// Dark mode toggle
const darkModeToggle = document.getElementById('darkModeToggle');

darkModeToggle.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    updateCharts();
});

// Sales Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
let revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: Array.from({ length: 30 }, (_, i) => i + 1),
        datasets: [
            {
                label: 'Sales',
                data: [
                    80, 90, 70, 85, 95, 100, 75, 95, 85, 90, 110, 120, 130, 140, 
                    135, 125, 120, 130, 140, 150, 160, 170, 180, 190, 185, 175, 
                    170, 160, 150, 140
                ],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 2
            },
            {
                label: 'Marketing',
                data: [
                    60, 70, 65, 80, 85, 90, 95, 100, 110, 105, 95, 90, 100, 110, 
                    120, 130, 140, 130, 120, 110, 100, 120, 130, 140, 150, 140, 
                    130, 120, 110, 100
                ],
                borderColor: '#a855f7',
                backgroundColor: 'rgba(168, 85, 247, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 2
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Gender Gauge Chart
const genderCtx = document.getElementById('genderChart').getContext('2d');
let genderChart = new Chart(genderCtx, {
    type: 'doughnut',
    data: {
        labels: ['Male', 'Female', 'Other'],
        datasets: [
            {
                data: [52, 38, 10],
                backgroundColor: ['#3b82f6', '#a855f7', '#22c55e'],
                borderWidth: 0,
                cutout: '70%'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Update charts when dark mode changes
function updateCharts() {
    const isDarkMode = document.body.classList.contains('dark-mode');

    // Update Revenue Chart
    revenueChart.options.scales.y.grid.color = isDarkMode 
        ? 'rgba(255, 255, 255, 0.1)' 
        : 'rgba(0, 0, 0, 0.05)';
    revenueChart.update();
}

// Add some animations for cards
const cards = document.querySelectorAll('.stat-card, .chart-card');

function animateCards() {
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 * index);
    });
}

// Initialize animations
window.addEventListener('load', () => {
    animateCards();
});
