@extends('layouts.admin')

@section('content')
<style>
    body {
        background: url('/images/hoii.png') no-repeat center center fixed;
        background-size: cover;
        position: relative;
        color: #fff;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.75);
        z-index: -1;
    }

    .glass-card {
        background: rgba(255, 163, 163, 0.08);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 20px;
        color: #fff;
        box-shadow: 0 8px 24px rgba(201, 69, 69, 0.641);
    }

    h1 {
        color: #facc15;
        font-weight: 800;
    }

    .card-title {
        font-weight: 600;
        color: #f3f4f6;
    }

    .chart-container canvas {
        background: transparent;
    }
</style>

<div class="container py-4">
    <h1 class="text-center mb-5 display-6">Admin Dashboard</h1>

    {{-- Stat Cards --}}
    <div class="row g-4">
        @php
            $cards = [
                ['label' => 'Total Requests', 'value' => $totalRequests, 'color' => '#1e3a8a', 'icon' => 'bi-journal-text'],
                ['label' => 'Accepted Requests', 'value' => $totalAcceptedRequests, 'color' => '#10b981', 'icon' => 'bi-check2-circle'],
                ['label' => 'Total Passengers', 'value' => $totalPassengers, 'color' => '#06b6d4', 'icon' => 'bi-people-fill'],
                ['label' => 'Total Drivers', 'value' => $totalDrivers, 'color' => '#ef4444', 'icon' => 'bi-truck-front-fill'],
                ['label' => 'In-Progress Rides', 'value' => $totalInProgress, 'color' => '#f59e0b', 'icon' => 'bi-hourglass-split'],
                ['label' => 'Completed Rides', 'value' => $totalCompleted, 'color' => '#8b5cf6', 'icon' => 'bi-check-lg'],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="glass-card text-center" style="background: linear-gradient(135deg, {{ $card['color'] }}, #60a5fa);">
                    <h5 class="card-title"><i class="bi {{ $card['icon'] }} me-2"></i>{{ $card['label'] }}</h5>
                    <h2>{{ $card['value'] }}</h2>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Charts --}}
    <div class="row mt-5 g-4">
        <div class="col-md-4">
            <div class="glass-card chart-container">
                <h6 class="text-center fw-bold mb-3"> Bar Chart</h6>
                <canvas id="barChart" height="250"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card chart-container">
                <h6 class="text-center fw-bold mb-3"> Line Chart</h6>
                <canvas id="lineChart" height="250"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card chart-container">
                <h6 class="text-center fw-bold mb-3"> Pie Chart</h6>
                <canvas id="pieChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = ['Total Requests', 'Accepted', 'Passengers', 'Drivers'];
    const data = [
        {{ $totalRequests }},
        {{ $totalAcceptedRequests }},
        {{ $totalPassengers }},
        {{ $totalDrivers }}
    ];
    const backgroundColors = ['#1e3a8a', '#10b981', '#06b6d4', '#ef4444'];

    function setupChart(id, type, options) {
        const ctx = document.getElementById(id).getContext('2d');
        new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    label: 'Count',
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: '#facc15',
                    borderWidth: 1,
                    fill: false,
                    tension: 0.4
                }]
            },
            options: options
        });
    }

    const commonOptions = {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: { display: true }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { precision: 0, color: '#f3f4f6' }
            },
            x: {
                ticks: { color: '#f3f4f6' }
            }
        }
    };

    setupChart('barChart', 'bar', {
        ...commonOptions,
        plugins: { ...commonOptions.plugins, title: { text: 'Requests & Users' } }
    });

    setupChart('lineChart', 'line', {
        ...commonOptions,
        plugins: { ...commonOptions.plugins, title: { text: 'Trend Overview' } }
    });

    setupChart('pieChart', 'pie', {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { color: '#fff' } },
            title: { display: true, text: 'Data Distribution' }
        }
    });
</script>
@endsection
