@extends('layouts.app')

@section('title', 'Team Summary')

@push('styles')
<style>
    .content-header {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
        padding: 15px 0;
    }
    .content-header .title {
        font-size: 1.2rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
    }
    .content-header a {
        color: var(--text-primary);
        font-size: 1.2rem;
        text-decoration: none;
    }
    
    .summary-card-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }
    .summary-box {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .summary-box .meta h2 {
        font-size: 2.2rem;
        margin: 0 0 5px 0;
    }
    .summary-box .meta span {
        font-size: 0.9rem;
        color: var(--text-muted);
    }
    .summary-box .icon-display {
        font-size: 2.5rem;
    }
    .text-danger { color: #e74c3c; }
    .text-warning { color: #f39c12; }
    .text-success { color: #2ecc71; }
    
    .search-bar {
        position: relative;
        margin: 25px 0;
    }
    .search-bar i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }
    .search-bar input {
        width: 100%;
        padding: 12px 15px 12px 45px;
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 1rem;
    }
    
    .member-table {
        width: 100%;
        border-collapse: collapse;
        background-color: var(--card-bg);
        border-radius: 12px;
        overflow: hidden;
    }
    .member-table thead {
        background-color: var(--bg-tertiary);
    }
    .member-table th,
    .member-table td {
        padding: 12px 15px;
        text-align: left;
        font-size: 0.9rem;
    }
    .member-table th {
        color: var(--text-primary);
        font-weight: 600;
    }
    .member-table td {
        color: var(--text-primary);
    }
    .member-table tbody tr {
        border-bottom: 1px solid var(--border-color);
    }
    .member-table tbody tr:last-child {
        border-bottom: none;
    }
    .member-table tbody tr:hover {
        background-color: var(--bg-tertiary);
    }
    .member-table td:not(:first-child) {
        text-align: center;
    }
    
    .loading {
        text-align: center;
        padding: 40px;
        color: var(--text-muted);
    }
</style>
@endpush

@section('content')

<div class="content-header">
    <a href="#" onclick="history.back(); return false;"><i class="fas fa-arrow-left"></i></a>
    <h2 class="title">Team Summary</h2>
</div>

<div id="loadingState" class="loading">
    <i class="fas fa-spinner fa-spin"></i>
    <p>Loading team summary...</p>
</div>

<div id="contentArea" style="display: none;">
    <h3 style="font-size: 1.1rem; font-weight: 600; color: var(--text-primary); margin-bottom: 15px;">
        Overall Team Summary
    </h3>
    <div class="summary-card-grid" id="teamSummary">
        <!-- Will be populated by JS -->
    </div>
    
    <h3 style="font-size: 1.1rem; font-weight: 600; color: var(--text-primary); margin: 25px 0 15px 0;">
        Summary by Member
    </h3>
    
    <div class="search-bar">
        <i class="fas fa-search"></i>
        <input type="text" id="memberSearchInput" placeholder="Search Member">
    </div>
    
    <table class="member-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Completed</th>
                <th>Pending</th>
                <th>Late</th>
            </tr>
        </thead>
        <tbody id="memberTableBody">
            <!-- Will be populated by JS -->
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script>
    const teamId = {{ $id ?? 'null' }};
    
    function getApiToken() {
        const sessionToken = '{{ session("jwt_token") }}';
        if (sessionToken && sessionToken !== '' && sessionToken !== 'null') {
            return sessionToken;
        }
        const localToken = localStorage.getItem('access_token');
        if (localToken) {
            return localToken;
        }
        return null;
    }
    
    const apiToken = getApiToken();
    let teamData = null;
    let tasksData = [];
    let allMembers = [];
    
    document.addEventListener('DOMContentLoaded', function() {
        if (!teamId || !apiToken) {
            alert('Invalid team ID or not authenticated');
            window.location.href = '/group';
            return;
        }
        
        loadData();
        setupSearch();
    });
    
    async function loadData() {
        try {
            // Load team
            const teamResponse = await fetch(`/api/v1/team/detail/${teamId}`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            const teamResult = await teamResponse.json();
            if (teamResponse.ok && teamResult.status === 200) {
                teamData = teamResult.data;
                allMembers = teamData.teamMembers || [];
            }
            
            // Load tasks
            const tasksResponse = await fetch(`/api/v1/team-task/by-team/${teamId}`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            const tasksResult = await tasksResponse.json();
            if (tasksResponse.ok && tasksResult.status === 200) {
                tasksData = tasksResult.data || [];
            }
            
            displaySummary();
            displayMemberTable();
            
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('contentArea').style.display = 'block';
        } catch (error) {
            console.error('Error loading data:', error);
            document.getElementById('loadingState').innerHTML = `
                <i class="fas fa-exclamation-triangle"></i>
                <p>Error loading data: ${error.message}</p>
            `;
        }
    }
    
    function displaySummary() {
        const now = new Date();
        const completed = tasksData.filter(t => t.isCompleted).length;
        const pending = tasksData.filter(t => !t.isCompleted && new Date(t.deadline) > now).length;
        const late = tasksData.filter(t => !t.isCompleted && new Date(t.deadline) <= now).length;
        
        document.getElementById('teamSummary').innerHTML = `
            <div class="summary-box">
                <div class="meta">
                    <h2 class="text-danger">${late}</h2>
                    <span>Pending & Late</span>
                </div>
                <i class="fas fa-exclamation-triangle text-danger icon-display"></i>
            </div>
            <div class="summary-box">
                <div class="meta">
                    <h2 class="text-warning">${pending}</h2>
                    <span>Pending</span>
                </div>
                <i class="fas fa-clock text-warning icon-display"></i>
            </div>
            <div class="summary-box">
                <div class="meta">
                    <h2 class="text-success">${completed}</h2>
                    <span>Complete</span>
                </div>
                <i class="fas fa-check-circle text-success icon-display"></i>
            </div>
        `;
    }
    
    function displayMemberTable(filterText = '') {
        const now = new Date();
        const tbody = document.getElementById('memberTableBody');
        
        let filteredMembers = allMembers;
        if (filterText) {
            const lowerFilter = filterText.toLowerCase();
            filteredMembers = allMembers.filter(m => {
                const name = (m.user?.name || m.user?.email || '').toLowerCase();
                return name.includes(lowerFilter);
            });
        }
        
        tbody.innerHTML = filteredMembers.map(member => {
            const memberTasks = tasksData.filter(t => t.memberId === member.id);
            const completed = memberTasks.filter(t => t.isCompleted).length;
            const pending = memberTasks.filter(t => !t.isCompleted && new Date(t.deadline) > now).length;
            const late = memberTasks.filter(t => !t.isCompleted && new Date(t.deadline) <= now).length;
            const name = member.user?.name || member.user?.email || 'Unknown';
            
            return `
                <tr>
                    <td>${escapeHtml(name)}</td>
                    <td>${completed}</td>
                    <td>${pending}</td>
                    <td>${late}</td>
                </tr>
            `;
        }).join('');
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function setupSearch() {
        document.getElementById('memberSearchInput').addEventListener('input', function(e) {
            displayMemberTable(e.target.value.trim());
        });
    }
</script>
@endpush

