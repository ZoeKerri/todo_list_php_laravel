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
    
    /* Ghi đè h1, h3 cho riêng trang này */
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

    .summary-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .summary-card { 
        background-color: var(--card-bg); 
        border-radius: 12px; 
        padding: 15px; 
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .summary-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
    .summary-card-header span { font-size: 0.9rem; color: var(--text-muted); }
    .summary-card-header .icon { font-size: 1.2rem; color: var(--text-primary); padding: 5px; border-radius: 50%; }
    .summary-card-header .icon-completed { background-color: green; }
    .summary-card-header .icon-pending { background-color: orange; }
    .summary-card-header .icon-streak { background-color: purple; }
    .summary-card-header .icon-total { background-color: blue; }
    .summary-card h2 { font-size: 2rem; margin: 0; color: var(--text-primary); }

    .streak-card h2 { font-size: 2.5rem; margin: 0 0 5px 0; color: var(--text-primary); }
    .streak-card p { font-size: 0.9rem; color: var(--text-muted); margin: 0; }

    .progress-group { margin-bottom: 15px; }
    .progress-labels { display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px; }
    .progress-labels .value { color: var(--accent-color); font-weight: bold; }
    .progress-bar-container { width: 100%; background-color: var(--border-color); border-radius: 10px; height: 10px; overflow: hidden; }
    .progress-bar { height: 100%; background-color: var(--accent-color); border-radius: 10px; }

    .week-selector { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        text-align: center; 
        background-color: var(--border-color); 
        border-radius: 10px; 
        padding: 10px 15px; 
        margin: 15px 0; 
        transition: background-color 0.3s ease;
    }
    .week-selector a { color: var(--accent-color); font-size: 1.5rem; text-decoration: none; font-weight: bold; cursor: pointer; }
    .week-selector span { font-size: 1rem; font-weight: bold; color: var(--text-primary); }
    
    .chart-legend { display: flex; justify-content: center; gap: 20px; margin-top: 15px; font-size: 0.9rem; }
    .legend-item { display: flex; align-items: center; gap: 8px; }
    .legend-dot { width: 10px; height: 10px; border-radius: 50%; }
</style>
@endpush


@section('content')

<h1>Statistics</h1>

<h3>Summary</h3>
<div class="summary-grid">
    <div class="summary-card">
        <div class="summary-card-header"> <span>Đã hoàn thành</span> <i class="fas fa-check icon icon-completed"></i> </div> <h2>9</h2>
    </div>
    <div class="summary-card">
        <div class="summary-card-header"> <span>Đang chờ</span> <i class="fas fa-hourglass-half icon icon-pending"></i> </div> <h2>5</h2>
    </div>
    <div class="summary-card">
        <div class="summary-card-header"> <span>Chuỗi dài nhất</span> <i class="fas fa-clock icon icon-streak"></i> </div> <h2>9</h2>
    </div>
    <div class="summary-card">
        <div class="summary-card-header"> <span>Tổng công việc</span> <i class="fas fa-tasks icon icon-total"></i> </div> <h2>14</h2>
    </div>
</div>

<div class="stat-card streak-card" style="margin-top: 20px;">
    <h3><i class="fas fa-fire" style="color: orange;"></i> Current Streak</h3>
    <h2>2 days</h2>
    <p>Keep completing tasks daily to maintain your streak!</p>
</div>

<div class="stat-card">
    <h3>Progress</h3>
    <div class="progress-group">
        <div class="progress-labels"> <span>Completion Rate</span> <span class="value">64%</span> </div>
        <div class="progress-bar-container"> <div class="progress-bar" style="width: 64%;"></div> </div>
        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 5px;">You've completed 9 tasks in total</p>
    </div>
    <div class="progress-group">
        <div class="progress-labels"> <span>Progress this week</span> <span class="value">4/5</span> </div>
        <div class="progress-bar-container"> <div class="progress-bar" style="width: 80%;"></div> </div>
        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 5px;">Weekly task completion summary</p>
    </div>
</div>

<div class="stat-card" style="padding: 10px;">
    <h3>Weekly Task Chart</h3>
    <div class="week-selector">
        <a id="prev-week">&lsaquo;</a>
        <span id="week-display-label">Week of 2025-06-02</span>
        <a id="next-week">&rsaquo;</a>
    </div>
    <div style="width: 100%; height: 350px; position: relative;">
        <canvas id="taskChart"></canvas>
    </div>
    <div class="chart-legend">
        <div class="legend-item"> <span class="legend-dot" style="background-color: #00e676;"></span> Completed </div>
        <div class="legend-item"> <span class="legend-dot" style="background-color: #ffd600;"></span> Pending </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- Toàn bộ JS cho biểu đồ của bạn ---
    const chartColors = {
        completed: { bg: '#00e676' },
        pending: { bg: '#ffd600' }
    };
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { stacked: true, grid: { display: false }, ticks: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-muted').trim() } },
            y: { stacked: true, grid: { color: getComputedStyle(document.documentElement).getPropertyValue('--border-color').trim() }, ticks: { color: getComputedStyle(document.documentElement).getPropertyValue('--text-muted').trim() } }
        }
    };
    const allWeekData = [
        { label: 'Tuần 1 (2025-06-02)', labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], completed: [0, 0, 0, 0, 0, 4, 0], pending: [0, 0, 0, 0, 0, 1, 0] },
        { label: 'Tuần 2 (2025-06-09)', labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], completed: [3, 2, 2, 1, 5, 2, 1], pending: [1, 0, 0, 2, 0, 1, 1] },
        { label: 'Tuần 3 (2025-06-16)', labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], completed: [0, 1, 4, 2, 2, 3, 0], pending: [1, 1, 0, 0, 1, 0, 0] }
    ];
    let currentWeekIndex = 0; 
    
    // Phải bọc trong 'DOMContentLoaded' vì script này
    // được nạp sau khi layout 'app.blade.php' chạy
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('taskChart').getContext('2d');
        const initialData = allWeekData[currentWeekIndex];
        const taskChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: initialData.labels,
                datasets: [
                    { label: 'Completed', data: initialData.completed, backgroundColor: chartColors.completed.bg, borderRadius: 5 },
                    { label: 'Pending', data: initialData.pending, backgroundColor: chartColors.pending.bg, borderRadius: 5 }
                ]
            },
            options: chartOptions
        });

        const prevWeekBtn = document.getElementById('prev-week');
        const nextWeekBtn = document.getElementById('next-week');
        const weekDisplayLabel = document.getElementById('week-display-label');

        function updateChartAndButtons() {
            const data = allWeekData[currentWeekIndex];
            weekDisplayLabel.textContent = data.label;
            taskChart.data.labels = data.labels;
            taskChart.data.datasets[0].data = data.completed;
            taskChart.data.datasets[1].data = data.pending;
            taskChart.update();
            prevWeekBtn.style.pointerEvents = (currentWeekIndex === 0) ? 'none' : 'auto';
            prevWeekBtn.style.opacity = (currentWeekIndex === 0) ? '0.5' : '1';
            nextWeekBtn.style.pointerEvents = (currentWeekIndex === allWeekData.length - 1) ? 'none' : 'auto';
            nextWeekBtn.style.opacity = (currentWeekIndex === allWeekData.length - 1) ? '0.5' : '1';
        }

        prevWeekBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentWeekIndex > 0) {
                currentWeekIndex--;
                updateChartAndButtons();
            }
        });
        nextWeekBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentWeekIndex < allWeekData.length - 1) {
                currentWeekIndex++;
                updateChartAndButtons();
            }
        });
        updateChartAndButtons();
    });
</script>
@endpush