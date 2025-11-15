@extends('layouts.app')

@section('title', 'Statistics')

@push('styles')
    <style>
        .stat-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .main-content h1 {
            text-align: left;
            font-size: 2.2rem;
            margin-bottom: 20px;
            color: var(--text-primary);
        }

        .main-content h3 {
            color: var(--text-primary);
            font-size: 1.1rem;
            margin-top: 0;
            margin-bottom: 15px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .summary-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 15px;
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .summary-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .summary-card-header span {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .summary-card-header .icon {
            font-size: 1.2rem;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .summary-card-header .icon-completed {
            background-color: #10B981;
        }

        .summary-card-header .icon-pending {
            background-color: #F59E0B;
        }

        .summary-card-header .icon-streak {
            background-color: #8B5CF6;
        }

        .summary-card-header .icon-total {
            background-color: #3B82F6;
        }

        .summary-card h2 {
            font-size: 2rem;
            margin: 0;
            color: var(--text-primary);
        }

        .streak-card h2 {
            font-size: 2.5rem;
            margin: 0 0 5px 0;
            color: var(--text-primary);
        }

        .streak-card p {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin: 0;
        }

        .progress-group {
            margin-bottom: 15px;
        }

        .progress-labels {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .progress-labels .value {
            color: var(--accent-color);
            font-weight: bold;
        }

        .progress-bar-container {
            width: 100%;
            background-color: var(--border-color);
            border-radius: 10px;
            height: 10px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background-color: var(--accent-color);
            border-radius: 10px;
        }

        .date-range-banner {
            background-color: var(--card-bg);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 15px 0;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--accent-color);
            border-right: 4px solid var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .date-range-banner i {
            color: var(--accent-color);
            font-size: 1.2rem;
        }

        .date-range {
            font-weight: 500;
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        .chart-legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .date-filter-form {
            display: flex;
            gap: 10px;
            align-items: center;
            background-color: var(--card-bg);
            padding: 12px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .date-filter-form label {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .date-filter-form input[type="date"] {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background-color: var(--input-bg);
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .btn-filter {
            background-color: var(--accent-color);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-filter:hover {
            background-color: var(--accent-hover);
        }
        
        .task-type-switcher {
            display: flex;
            background-color: var(--card-bg);
            border-radius: 10px;
            padding: 5px;
            margin-bottom: 20px;
            width: fit-content;
        }
        .task-type-switcher a {
            padding: 8px 16px;
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .task-type-switcher a.active {
            background-color: var(--accent-color);
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
    </style>
@endpush


@section('content')

    <div class="header-section">
        <h1>Statistics</h1>

        <form action="{{ route('statistics') }}" method="GET" class="date-filter-form">
            
            <input type="hidden" name="type" value="{{ $currentType }}">

            <label for="start_date">From:</label>
            <input type="date" id="start_date" name="start_date" value="{{ $startDate }}">

            <label for="end_date">To:</label>
            <input type="date" id="end_date" name="end_date" value="{{ $endDate }}">

            <button type="submit" class="btn-filter">Filter</button>
        </form>
    </div>

    <div class="task-type-switcher">
        <a href="{{ route('statistics', ['type' => 'personal', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
           class="{{ $currentType == 'personal' ? 'active' : '' }}">
           <i class="fas fa-user"></i> Personal
        </a>
        <a href="{{ route('statistics', ['type' => 'team', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
           class="{{ $currentType == 'team' ? 'active' : '' }}">
           <i class="fas fa-users"></i> Team
        </a>
    </div>

    <h3>Overview - {{ ucfirst($currentType) }} Tasks</h3>
    
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-card-header">
                <span>Completed</span>
                <div class="icon icon-completed">
                    <i class="fas fa-check"></i>
                </div>
            </div>
            <h2>{{ $completedTasks }}</h2>
        </div>
        <div class="summary-card">
            <div class="summary-card-header">
                <span>Pending</span>
                <div class="icon icon-pending">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
            <h2>{{ $pendingTasks }}</h2>
        </div>
        <div class="summary-card">
            <div class="summary-card-header">
                <span>Current Streak</span>
                <div class="icon icon-streak">
                    <i class="fas fa-fire"></i>
                </div>
            </div>
            <h2>{{ $currentStreak }}</h2>
        </div>
        <div class="summary-card">
            <div class="summary-card-header">
                <span>Total Tasks</span>
                <div class="icon icon-total">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
            <h2>{{ $totalTasks }}</h2>
        </div>
    </div>

    <div class="stat-card streak-card" style="margin-top: 20px;">
        <h3><i class="fas fa-fire" style="color: #F59E0B;"></i> Current Streak</h3>
        <h2>{{ $currentStreak }} days</h2>
        <p>Keep completing tasks daily to maintain your streak!</p>
    </div>

    <div class="stat-card">
        <h3>Progress</h3>
        <div class="progress-group">
            <div class="progress-labels">
                <span>Completion Rate</span>
                <span class="value">{{ $completionRate }}%</span>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar" style="width: {{ $completionRate }}%;"></div>
            </div>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 5px;">You've completed {{ $completedTasks }}
                tasks in this period</p>
        </div>
    </div>

    <div class="stat-card" style="padding: 10px;">
        <h3>Weekly Task Chart</h3>
        <div class="date-range-banner">
            <i class="fas fa-calendar-alt"></i>
            <span class="date-range">{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</span>
        </div>
        <div style="width: 100%; height: 350px; position: relative;">
            <canvas id="taskChart"></canvas>
        </div>

        <div class="chart-legend">
            <div class="legend-item">
                <span id="legend-dot-completed" class="legend-dot"></span>
                <span>Completed</span>
            </div>
            <div class="legend-item">
                <span id="legend-dot-pending" class="legend-dot"></span>
                <span>Pending</span>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const dailyData = @json($dailyData);
        const priorityData = @json($priorityData);

        const chartColors = {
            primary: { bg: '#3B82F6', hover: '#2563EB' },
            success: { bg: '#10B981', hover: '#059669' },
            warning: { bg: '#F59E0B', hover: '#D97706' },
            danger: { bg: '#EF4444', hover: '#DC2626' },
            info: { bg: '#8B5CF6', hover: '#7C3AED' }
        };
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { stacked: true, grid: { display: false }, ticks: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-primary').trim() } },
                y: { stacked: true, grid: { color: getComputedStyle(document.documentElement).getPropertyValue('--border-color').trim() }, ticks: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-muted').trim() } }
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('taskChart').getContext('2d');

            const hasData = dailyData && dailyData.length > 0;

            const chartData = {
                labels: hasData ? dailyData.map(item => item.label) : ['No data available'],
                datasets: [
                    {
                        label: 'Completed',
                        data: hasData ? dailyData.map(item => item.completed) : [0],
                        backgroundColor: chartColors.success.bg,
                        borderColor: chartColors.success.hover,
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.8,
                        categoryPercentage: 0.9
                    },
                    {
                        label: 'Pending',
                        data: hasData ? dailyData.map(item => item.pending) : [0],
                        backgroundColor: chartColors.warning.bg,
                        borderColor: chartColors.warning.hover,
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.8,
                        categoryPercentage: 0.9
                    }
                ]
            };

            const taskChart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: {
                    ...chartOptions,
                    animation: {
                        onComplete: function (animation) {
                            if (!hasData) {
                                const chart = this;
                                const ctx = chart.ctx;
                                const width = chart.width;
                                const height = chart.height;

                                ctx.save();
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                ctx.font = '16px Arial';
                                ctx.fillStyle = 'var(--text-muted)';
                                ctx.fillText('No tasks found in this date range', width / 2, height / 2);
                                ctx.restore();
                            }
                        }
                    },
                    plugins: {
                        ...chartOptions.plugins,
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    if (!hasData) return 'No data';
                                    const label = context.dataset.label || '';
                                    const value = context.raw;
                                    return `${label}: ${value} task${value !== 1 ? 's' : ''}`;
                                },
                                title: function (context) {
                                    return hasData ? context[0].label : 'No data';
                                }
                            }
                        }
                    },
                    scales: {
                        ...chartOptions.scales,
                        y: {
                            ...chartOptions.scales.y,
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                color: '#FFFFFF',
                                callback: function (value) {
                                    if (!hasData) return '';
                                    if (value % 1 === 0) return value;
                                }
                            },
                            grid: {
                                display: hasData
                            }
                        },
                        x: {
                            ...chartOptions.scales.x,
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: hasData ? '#FFFFFF' : 'transparent'
                            }
                        }
                    }
                }
            });

            document.getElementById('legend-dot-completed').style.backgroundColor = chartColors.success.bg;
            document.getElementById('legend-dot-pending').style.backgroundColor = chartColors.warning.bg;

        });
    </script>
@endpush