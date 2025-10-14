@extends('layouts.admin')

@section('content')
<div class="container-fluid py-5 professional-dashboard">

    <!-- Stat Cards -->
    <div class="row g-4 mb-10">
        @php
            $cards = [
                ['label' => 'Total Requests', 'value' => $totalRequests, 'color' => 'indigo'],
                ['label' => 'Accepted Requests', 'value' => $totalAcceptedRequests, 'color' => 'emerald'],
                ['label' => 'Passengers', 'value' => $totalPassengers, 'color' => 'amber'],
                ['label' => 'Drivers', 'value' => $totalDrivers, 'color' => 'pink'],
                ['label' => 'In Progress', 'value' => $totalInProgress, 'color' => 'teal'],
                ['label' => 'Completed', 'value' => $totalCompleted, 'color' => 'purple'],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-lg-4 col-md-6">
                <div class="card stat-card h-100 shadow-sm border-0 border-start border-5 border-{{ $card['color'] }}">
                    <div class="card-body text-center">
                        <h2 class="fw-bolder mb-1 text-{{ $card['color'] }}">{{ $card['value'] }}</h2>
                        <p class="mb-0 text-muted small">{{ $card['label'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Charts -->
    <div class="row g-4">
        <div class="col-md-4 mb-4">
            <div class="card chart-card shadow-sm border-0">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="fw-bold text-dark mb-2">Requests Overview</h6>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <canvas id="barChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card chart-card shadow-sm border-0">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="fw-bold text-dark mb-2">Growth Trend</h6>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <canvas id="lineChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card chart-card shadow-sm border-0">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h6 class="fw-bold text-dark mb-2">Distribution</h6>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <canvas id="doughnutChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = ['Total Requests', 'Accepted', 'Passengers', 'Drivers'];
    const dataValues = [
        {{ $totalRequests }},
        {{ $totalAcceptedRequests }},
        {{ $totalPassengers }},
        {{ $totalDrivers }}
    ];

    // Impactful colors
    const backgroundColors = [
        '#6366F1', // Indigo
        '#22C55E', // Emerald
        '#F59E0B', // Amber
        '#EC4899'  // Pink
    ];

    // Gradient helper
    const gradientFill = (ctx, color1, color2) => {
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        return gradient;
    };

    // Spider (Radar) Chart
new Chart(document.getElementById('barChart'), {
    type: 'radar',
    data: {
        labels,
        datasets: [{
            label: 'Request Data',
            data: dataValues,
            backgroundColor: 'rgba(99, 102, 241, 0.2)', // Soft Indigo
            borderColor: '#6366F1',
            pointBackgroundColor: '#6366F1',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: '#6366F1',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            r: {
                angleLines: { color: '#E5E7EB' },
                grid: { color: '#E5E7EB' },
                pointLabels: { color: '#6B7280', font: { size: 12 } },
                ticks: {
                    color: '#6B7280',
                    backdropColor: 'transparent',
                    beginAtZero: true
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});


    // Line Chart
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Trend',
                data: dataValues,
                borderColor: '#6366F1',
                backgroundColor: 'rgba(99,102,241,0.2)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#6366F1',
                pointBorderColor: '#fff',
                pointRadius: 5
            }]
        },
        options: { 
            plugins: { legend: { display: false } },
            maintainAspectRatio: false
        }
    });

    // Doughnut Chart
    new Chart(document.getElementById('doughnutChart'), {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: dataValues,
                backgroundColor: backgroundColors,
                hoverOffset: 12,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            cutout: '70%',
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    position: 'bottom',
                    labels: { color: '#374151', font: { size: 12, weight: 'bold' } }
                }
            }
        }
    });
</script>

<style>
/* Professional Dashboard */
.professional-dashboard {
    font-family: 'Inter', sans-serif;
}

/* Stat Cards */
.stat-card {
    border-radius: 1rem;
    background: #fff;
    transition: all 0.2s ease-in-out;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}
.stat-card h2 {
    font-size: 2rem;
}

/* Chart Cards */
.chart-card {
    border-radius: 1rem;
    background: #fff;
    padding: 15px;
}
.chart-card h6 {
    font-size: 0.95rem;
    color: #374151;
}

/* Custom border colors for stat cards */
.border-indigo { border-color: #6366F1 !important; }
.text-indigo { color: #6366F1 !important; }

.border-emerald { border-color: #22C55E !important; }
.text-emerald { color: #22C55E !important; }

.border-amber { border-color: #F59E0B !important; }
.text-amber { color: #F59E0B !important; }

.border-pink { border-color: #EC4899 !important; }
.text-pink { color: #EC4899 !important; }

.border-teal { border-color: #14B8A6 !important; }
.text-teal { color: #14B8A6 !important; }

.border-purple { border-color: #8B5CF6 !important; }
.text-purple { color: #8B5CF6 !important; }
</style>
@endsection
