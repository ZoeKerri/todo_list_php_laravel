@extends('layouts.app')

@section('title', 'Team Detail')

@push('styles')
<style>
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding: 15px 0;
    }
    .content-header .title {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
    }
    .content-header .header-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .content-header .header-actions a {
        color: var(--text-primary);
        font-size: 1.2rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 8px;
        transition: background-color 0.2s ease;
    }
    .content-header .header-actions a:hover {
        background-color: var(--bg-tertiary);
    }
    .content-header .primary-action {
        background-color: var(--accent-color);
        color: var(--text-primary) !important;
        font-size: 0.9rem !important;
        padding: 8px 14px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: opacity 0.2s ease;
    }
    .content-header .primary-action i {
        font-size: 1rem;
    }
    .content-header .primary-action:hover {
        opacity: 0.9;
        background-color: var(--accent-color);
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
        transition: background-color 0.3s ease;
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
    
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 25px 0 15px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .section-title a {
        color: var(--accent-color);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: normal;
    }
    
    .task-list {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background-color 0.3s ease;
    }
    .task-list:hover {
        background-color: var(--bg-tertiary);
    }
    .task-list-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        flex: 1;
    }
    .task-checkbox {
        position: relative;
        display: block;
        width: 25px;
        height: 25px;
        cursor: pointer;
        flex-shrink: 0;
    }
    .task-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    .task-checkbox .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: transparent;
        border: 2px solid var(--text-muted);
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    .task-checkbox:hover input ~ .checkmark {
        background-color: var(--hover-bg);
        border-color: var(--text-secondary);
    }
    .task-checkbox input:checked ~ .checkmark {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }
    .task-checkbox .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        left: 8px;
        top: 4px;
        width: 6px;
        height: 12px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    .task-checkbox input:checked ~ .checkmark:after {
        display: block;
    }
    .task-list-meta h4 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0 0 4px 0;
        color: var(--text-primary);
    }
    .task-list-meta p {
        margin: 0;
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    .task-list-meta .task-assignee {
        font-size: 0.9rem;
        color: var(--text-primary);
        margin-bottom: 4px;
    }
    .task-list-meta .task-date {
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    .task-priority {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: 8px;
    }
    .priority-high { background-color: #fee; color: #c33; }
    .priority-medium { background-color: #ffeaa7; color: #d63031; }
    .priority-low { background-color: #dfe6e9; color: #636e72; }
    
    .fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background-color: var(--accent-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--text-primary);
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        z-index: 100;
    }
    .fab:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.5);
    }
    
    .empty-state {
        min-height: 160px;
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.5;
    }
    .task-component-placeholder {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 20px;
        border: 1px dashed var(--border-color);
    }
    .team-task-card {
        background-color: var(--card-bg);
        border-radius: 14px;
        padding: 14px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: background-color 0.3s ease;
        border-left: 6px solid;
    }
    .team-task-card:hover {
        background-color: var(--hover-bg);
    }
    .team-task-card.completed {
        border-left-color: #22c55e;
    }
    .team-task-card.priority-high {
        border-left-color: #ef4444;
    }
    .team-task-card.priority-medium {
        border-left-color: #f97316;
    }
    .team-task-card.priority-low {
        border-left-color: #3b82f6;
    }
    .task-card-left {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        min-width: 0;
    }
    .task-checkbox {
        font-size: 24px;
        color: var(--text-primary);
        flex-shrink: 0;
        cursor: pointer;
    }
    .task-checkbox.completed {
        color: #22c55e;
    }
    .task-content {
        flex: 1;
        min-width: 0;
    }
    .task-title {
        font-size: 18px;
        font-weight: bold;
        color: var(--text-primary);
        margin: 0 0 6px 0;
        word-wrap: break-word;
    }
    .task-title.completed {
        text-decoration: line-through;
        opacity: 0.7;
    }
    .task-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .task-assignee {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-muted);
    }
    .task-assignee i {
        font-size: 20px;
    }
    .task-date {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 14px;
        font-weight: bold;
        color: var(--text-muted);
    }
    .task-date i {
        font-size: 20px;
    }
    .task-arrow {
        font-size: 16px;
        color: var(--text-muted);
        flex-shrink: 0;
        margin-left: 10px;
        cursor: pointer;
        transition: color 0.3s ease;
    }
    .task-arrow:hover {
        color: var(--text-primary);
    }
    
    .loading {
        text-align: center;
        padding: 40px;
        color: var(--text-muted);
    }
    
    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 8px 0;
        min-width: 180px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        display: none;
    }
    .dropdown-menu.show {
        display: block;
    }
    .dropdown-menu a {
        display: block;
        padding: 10px 20px;
        color: var(--text-primary);
        text-decoration: none;
        transition: background-color 0.2s ease;
    }
    .dropdown-menu a:hover {
        background-color: var(--bg-tertiary);
    }
    .settings-btn {
        position: relative;
    }
    
    .modal {
        display: none;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
    }
    .modal.show {
        display: flex;
    }
    .modal-content {
        background-color: var(--card-bg);
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid var(--border-color);
    }
    .modal-header h3 {
        margin: 0;
        font-size: 1.3rem;
        color: var(--text-primary);
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--text-muted);
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background-color 0.2s ease;
    }
    .modal-close:hover {
        background-color: var(--bg-tertiary);
    }
    .modal-body {
        padding: 20px;
    }
    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 20px;
        border-top: 1px solid var(--border-color);
    }
    .btn-primary, .btn-secondary {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        transition: opacity 0.2s ease;
    }
    .btn-primary {
        background-color: var(--accent-color);
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .btn-primary:hover {
        opacity: 0.9;
    }
    .btn-secondary {
        background-color: var(--bg-tertiary);
        color: var(--text-primary);
    }
    .btn-secondary:hover {
        opacity: 0.9;
    }
    .form-select {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: var(--bg-color);
        color: var(--text-primary);
        font-size: 1rem;
    }
</style>
@endpush

@section('content')

<div class="content-header">
    <a href="{{ url('/group') }}"><i class="fas fa-arrow-left"></i></a>
    <h2 class="title" id="teamName">Loading...</h2>
    <div class="header-actions">
        <a href="#" id="addMemberBtn" class="primary-action" style="display: none;">
            <i class="fas fa-user-plus"></i>
            <span>Add new member</span>
        </a>
        <a href="#" id="membersLink">
            <i class="fas fa-users"></i>
            <span id="membersCount">0</span>
        </a>
        <div class="settings-btn" style="position: relative;">
            <a href="#" id="settingsBtn"><i class="fas fa-cog"></i></a>
            <div class="dropdown-menu" id="settingsMenu">
                <a href="#" id="shareBtn"><i class="fas fa-share-alt"></i> Share</a>
                @if(true)
                <a href="#" id="renameBtn"><i class="fas fa-edit"></i> Rename Team</a>
                <a href="#" id="disbandBtn"><i class="fas fa-trash"></i> Disband</a>
                @endif
                <a href="#" id="leaveBtn"><i class="fas fa-sign-out-alt"></i> Leave Team</a>
            </div>
        </div>
    </div>
</div>

<div id="loadingState" class="loading">
    <i class="fas fa-spinner fa-spin"></i>
    <p>Loading team data...</p>
</div>

<div id="contentArea" style="display: none;">
    <div class="section-title">
        <span>Team Summary</span>
        <a href="#" id="seeMoreLink">See more</a>
    </div>
    <div class="summary-card-grid" id="teamSummary">
    </div>
    
    <div class="section-title" id="yourSummaryTitle" style="display: none;">
        <span>Your Summary</span>
    </div>
    <div class="summary-card-grid" id="yourSummary" style="display: none;">
    </div>
    
    <h3 class="section-title" id="yourTasksTitle" style="display: none;">Your tasks</h3>
<div id="yourTasksList" data-component="team-task-self">
</div>
    
    <h3 class="section-title" id="otherTasksTitle" style="display: none;">Other members' tasks</h3>
<div id="otherTasksList" data-component="team-task-others">
</div>
</div>

<a href="#" class="fab" id="fabBtn" style="display: none;">+</a>

@include('todo.group.modals.rename_team')
@include('todo.group.modals.add_member')
@include('todo.group.modals.choose_new_leader')
@include('todo.group.modals.confirm_dialog')
@include('modals.create_team_task')
@include('modals.team_task_detail')

@endsection

@push('scripts')
<script>
    const teamId = {{ $id ?? 'null' }};
    window.teamId = teamId;
    const userId = {{ Auth::id() ?? 'null' }};
    
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
    
    window.getApiToken = getApiToken;
    
    const apiToken = getApiToken();
    let teamData = null;
    let tasksData = [];
    let isLeader = false;
    let currentUserMember = null;
    let allMembers = [];
    let teamTasksPayload = {
        mine: [],
        others: [],
        meta: {}
    };
    
    
    document.addEventListener('DOMContentLoaded', function() {
        if (!teamId || !apiToken) {
            alert('Invalid team ID or not authenticated');
            window.location.href = '/group';
            return;
        }
        
        window.addEventListener('settingsChanged', function(e) {
            if (e.detail && 'show_completed_tasks' in e.detail) {
                if (typeof loadTasks === 'function') {
                    loadTasks();
                } else {
                    displayTasks();
                }
            }
        });
        
        loadTeamData();
        setupEventListeners();
    });
    
    async function loadTeamData() {
        try {
            const teamResponse = await fetch(`/api/v1/team/detail/${teamId}`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            const teamResult = await teamResponse.json();
            if (teamResponse.ok && teamResult.status === 200) {
                teamData = teamResult.data;
                allMembers = Array.isArray(teamData.teamMembers) ? [...teamData.teamMembers] : [];
                isLeader = teamData.teamMembers.some(m => m.userId === userId && m.role === 'LEADER');
                currentUserMember = teamData.teamMembers.find(m => m.userId === userId);
                
                document.getElementById('teamName').textContent = teamData.name;
                document.getElementById('membersCount').textContent = teamData.teamMembers.length;
                document.getElementById('membersLink').href = `/group/${teamId}/members`;
                
                const renameLink = document.getElementById('renameBtn');
                const disbandLink = document.getElementById('disbandBtn');
                const addMemberButton = document.getElementById('addMemberBtn');
                const fabButton = document.getElementById('fabBtn');

                if (isLeader) {
                    if (renameLink) renameLink.style.display = 'block';
                    if (disbandLink) disbandLink.style.display = 'block';
                    if (addMemberButton) addMemberButton.style.display = 'flex';
                    if (fabButton) fabButton.style.display = 'flex';
                } else {
                    if (renameLink) renameLink.style.display = 'none';
                    if (disbandLink) disbandLink.style.display = 'none';
                    if (addMemberButton) addMemberButton.style.display = 'none';
                    if (fabButton) fabButton.style.display = 'none';
                }
                
                await loadTasks();
                
                calculateSummaries();
                
                document.getElementById('loadingState').style.display = 'none';
                document.getElementById('contentArea').style.display = 'block';
            } else {
                throw new Error(teamResult.message || 'Failed to load team');
            }
        } catch (error) {
            console.error('Error loading team:', error);
            document.getElementById('loadingState').innerHTML = `
                <i class="fas fa-exclamation-triangle"></i>
                <p>Error loading team: ${error.message}</p>
            `;
        }
    }
    
    async function loadTasks() {
        try {
            const response = await fetch(`/api/v1/team-task/by-team/${teamId}`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            console.log('Tasks API Response:', result);
            
            if (response.ok && result.status === 200) {
                tasksData = result.data || [];
                console.log('Tasks loaded:', tasksData);
                displayTasks();
                calculateSummaries();
            } else {
                console.error('Failed to load tasks:', result.message || 'Unknown error');
                alert(result.message || 'Failed to load tasks');
            }
        } catch (error) {
            console.error('Error loading tasks:', error);
            alert('Error loading tasks: ' + error.message);
        }
    }
    
    function calculateSummaries() {
        const now = new Date();
        const dataToUse = tasksData;
        
        const teamCompleted = dataToUse.filter(t => t.isCompleted).length;
        const teamPending = dataToUse.filter(t => !t.isCompleted && new Date(t.deadline) > now).length;
        const teamLate = dataToUse.filter(t => !t.isCompleted && new Date(t.deadline) <= now).length;
        
        document.getElementById('teamSummary').innerHTML = `
    <div class="summary-box">
        <div class="meta">
                    <h2 class="text-danger">${teamLate}</h2>
            <span>Pending & Late</span>
        </div>
        <i class="fas fa-exclamation-triangle text-danger icon-display"></i>
    </div>
    <div class="summary-box">
        <div class="meta">
                    <h2 class="text-warning">${teamPending}</h2>
            <span>Pending</span>
        </div>
        <i class="fas fa-clock text-warning icon-display"></i>
    </div>
    <div class="summary-box">
        <div class="meta">
                    <h2 class="text-success">${teamCompleted}</h2>
                    <span>Complete</span>
        </div>
        <i class="fas fa-check-circle text-success icon-display"></i>
    </div>
        `;
        
        if (currentUserMember) {
            const myTasks = dataToUse.filter(t => t.memberId === currentUserMember.id);
            const myCompleted = myTasks.filter(t => t.isCompleted).length;
            const myPending = myTasks.filter(t => !t.isCompleted && new Date(t.deadline) > now).length;
            const myLate = myTasks.filter(t => !t.isCompleted && new Date(t.deadline) <= now).length;
            
            document.getElementById('yourSummaryTitle').style.display = 'flex';
            document.getElementById('yourSummary').style.display = 'grid';
            document.getElementById('yourSummary').innerHTML = `
    <div class="summary-box">
        <div class="meta">
                        <h2 class="text-danger">${myLate}</h2>
            <span>Pending & Late</span>
        </div>
        <i class="fas fa-exclamation-triangle text-danger icon-display"></i>
    </div>
    <div class="summary-box">
        <div class="meta">
                        <h2 class="text-warning">${myPending}</h2>
            <span>Pending</span>
        </div>
        <i class="fas fa-clock text-warning icon-display"></i>
    </div>
    <div class="summary-box">
        <div class="meta">
                        <h2 class="text-success">${myCompleted}</h2>
                        <span>Complete</span>
        </div>
        <i class="fas fa-check-circle text-success icon-display"></i>
    </div>
            `;
        }
    }
    
    function getAssignedMemberById(memberId) {
        if (!allMembers || allMembers.length === 0) return null;
        const member = allMembers.find(m => m.id === memberId);
        return member ? (member.user || null) : null;
    }
    
    function renderTaskCard(task, canEdit, isLeader, assignedMember) {
        const priorityClass = `priority-${(task.priority || 'LOW').toLowerCase()}`;
        const completedClass = task.isCompleted ? 'completed' : '';
        const checkboxIcon = task.isCompleted ? 'fas fa-check-circle' : 'far fa-circle';
        const titleClass = task.isCompleted ? 'completed' : '';
        
        const assignedMemberHtml = assignedMember ? `
            <span class="task-assignee">
                <i class="fas fa-user"></i>
                ${escapeHtml(assignedMember.name || assignedMember.fullName || assignedMember.email || 'Unknown')}
            </span>
        ` : '';
        
        const deadlineDate = new Date(task.deadline);
        const deadlineStr = `${deadlineDate.getDate()}/${deadlineDate.getMonth() + 1}/${deadlineDate.getFullYear()}`;
        
        return `
            <div class="team-task-card ${completedClass} ${priorityClass}" 
                 onclick="${canEdit ? `showToggleTaskDialog(${task.id}, ${!task.isCompleted})` : `viewTaskDetail(${task.id})`}">
                <div class="task-card-left">
                    <i class="${checkboxIcon} task-checkbox ${completedClass}"></i>
                    <div class="task-content">
                        <h4 class="task-title ${titleClass}">${escapeHtml(task.title || 'Untitled')}</h4>
                        <div class="task-meta">
                            ${assignedMemberHtml}
                            <span class="task-date">
                                <i class="fas fa-clock"></i>
                                ${deadlineStr}
                            </span>
        </div>
    </div>
</div>
                <i class="fas fa-chevron-right task-arrow" onclick="event.stopPropagation(); viewTaskDetail(${task.id})"></i>
            </div>
        `;
    }
    
    function getShowCompletedTasks() {
        return localStorage.getItem('show_completed_tasks') === 'true';
    }

    function displayTasks() {
        const yourTasksTitle = document.getElementById('yourTasksTitle');
        const yourTasksList = document.getElementById('yourTasksList');
        const otherTasksTitle = document.getElementById('otherTasksTitle');
        const otherTasksList = document.getElementById('otherTasksList');

        const priorityOrder = { 'HIGH': 0, 'MEDIUM': 1, 'LOW': 2 };
        const sortTasks = (a, b) => {
            if (a.isCompleted !== b.isCompleted) return a.isCompleted ? 1 : -1;
            if (a.priority !== b.priority) {
                const aPriority = priorityOrder[a.priority] ?? 2;
                const bPriority = priorityOrder[b.priority] ?? 2;
                return aPriority - bPriority;
            }
            return new Date(a.deadline) - new Date(b.deadline);
        };

        const showCompleted = getShowCompletedTasks();
        let filteredTasks = tasksData;
        if (!showCompleted) {
            filteredTasks = tasksData.filter(t => {
                const isCompleted = t.isCompleted || t.completed;
                return !isCompleted;
            });
        }

        let myTasks = [];
        let otherTasks = [];

        if (currentUserMember) {
            myTasks = filteredTasks.filter(t => t.memberId === currentUserMember.id).sort(sortTasks);
            otherTasks = filteredTasks.filter(t => t.memberId !== currentUserMember.id).sort(sortTasks);
        } else {
            otherTasks = filteredTasks.slice().sort(sortTasks);
        }

        if (yourTasksTitle) {
            const shouldShowYourTasks = currentUserMember && myTasks.length > 0;
            yourTasksTitle.style.display = shouldShowYourTasks ? 'block' : 'none';
        }
        if (yourTasksList) {
            if (currentUserMember && myTasks.length > 0) {
                yourTasksList.innerHTML = myTasks.map(task => {
                    const assignedMember = getAssignedMemberById(task.memberId);
                    const canEdit = isLeader || task.memberId === currentUserMember.id;
                    return renderTaskCard(task, canEdit, isLeader, assignedMember);
                }).join('');
            } else {
                yourTasksList.innerHTML = '';
            }
        }

        if (otherTasksTitle) {
            otherTasksTitle.style.display = otherTasks.length > 0 ? 'block' : 'none';
        }
        if (otherTasksList) {
            if (otherTasks.length > 0) {
                otherTasksList.innerHTML = otherTasks.map(task => {
                    const assignedMember = getAssignedMemberById(task.memberId);
                    const canEdit = isLeader || (currentUserMember && task.memberId === currentUserMember.id);
                    return renderTaskCard(task, canEdit, isLeader, assignedMember);
                }).join('');
            } else {
                if (tasksData.length === 0) {
                    otherTasksList.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-tasks"></i>
                            <p>Không có task nào</p>
                        </div>
                    `;
                } else {
                    otherTasksList.innerHTML = '';
                }
            }
        }

        updateTeamTasksPayload(myTasks, otherTasks);
    }

    function updateTeamTasksPayload(mine, others) {
        teamTasksPayload = {
            mine,
            others,
            meta: {
                teamId,
                currentMemberId: currentUserMember ? currentUserMember.id : null,
                isLeader,
                members: allMembers
            }
        };

        window.teamTaskContext = {
            data: teamTasksPayload,
            actions: {
                refresh: loadTasks,
                toggle: toggleTask,
                edit: editTask
            }
        };

        document.dispatchEvent(new CustomEvent('team-tasks:ready', {
            detail: window.teamTaskContext
        }));
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function setupEventListeners() {
        const settingsTrigger = document.getElementById('settingsBtn');
        const settingsMenu = document.getElementById('settingsMenu');
        if (settingsTrigger && settingsMenu) {
            settingsTrigger.addEventListener('click', function(e) {
                e.preventDefault();
                settingsMenu.classList.toggle('show');
            });
        }
        
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.settings-btn') && settingsMenu) {
                settingsMenu.classList.remove('show');
            }
        });
        
        const shareLink = document.getElementById('shareBtn');
        if (shareLink) {
            shareLink.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = `/group/${teamId}/share`;
            });
        }

        const renameLink = document.getElementById('renameBtn');
        if (renameLink) {
            renameLink.addEventListener('click', function(e) {
                e.preventDefault();
                showRenameModal();
            });
        }

        const disbandLink = document.getElementById('disbandBtn');
        if (disbandLink) {
            disbandLink.addEventListener('click', function(e) {
                e.preventDefault();
                const confirmAction = () => disbandTeam();
                if (typeof window.showConfirmDialog === 'function') {
                    window.showConfirmDialog(
                        'Giải tán nhóm',
                        'Bạn có chắc chắn muốn giải tán nhóm này? Hành động không thể phục hồi.',
                        confirmAction
                    );
                } else if (confirm('Bạn có chắc chắn muốn giải tán nhóm này? Hành động không thể phục hồi.')) {
                    confirmAction();
                }
            });
        }

        const leaveLink = document.getElementById('leaveBtn');
        if (leaveLink) {
            leaveLink.addEventListener('click', function(e) {
                e.preventDefault();
                leaveTeam();
            });
        }

        const addMemberButton = document.getElementById('addMemberBtn');
        if (addMemberButton) {
            addMemberButton.addEventListener('click', function(e) {
                e.preventDefault();
                showAddMemberModal();
            });
        }

        const fabButton = document.getElementById('fabBtn');
        if (fabButton) {
            fabButton.addEventListener('click', function(e) {
                e.preventDefault();
                if (typeof openCreateTeamTaskModal === 'function') {
                    openCreateTeamTaskModal(teamId);
                } else {
                    alert('Modal function not loaded. Please refresh the page.');
                }
            });
        }

        const seeMoreLink = document.getElementById('seeMoreLink');
        if (seeMoreLink) {
            seeMoreLink.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = `/group/${teamId}/summary`;
            });
        }
    }
    
    function showAddMemberModal() {
        const modal = document.getElementById('addMemberModal');
        if (!modal) return;

        if (typeof membersToAdd !== 'undefined') {
            membersToAdd = [];
        }

        if (typeof updateMembersToAddList === 'function') {
            updateMembersToAddList();
        }

        const searchInput = document.getElementById('searchMemberEmail');
        const resultsDiv = document.getElementById('searchResults');
        if (searchInput) {
            searchInput.value = '';
            setTimeout(() => searchInput.focus(), 0);
        }
        if (resultsDiv) {
            resultsDiv.style.display = 'none';
            resultsDiv.innerHTML = '';
        }

        modal.classList.add('show');
    }

    async function toggleTask(taskId, isCompleted) {
        try {
            const response = await fetch(`/api/v1/team-task/`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id: taskId,
                    is_completed: isCompleted
                })
            });
            
            const result = await response.json();
            if (response.ok && result.status === 200) {
                const task = tasksData.find(t => t.id === taskId);
                if (task) {
                    task.isCompleted = isCompleted;
                }
                calculateSummaries();
                displayTasks();
            } else {
                alert(result.message || 'Failed to update task');
            }
        } catch (error) {
            console.error('Error updating task:', error);
            alert('Error updating task');
        }
    }
    
    function editTask(taskId) {
        window.location.href = `/group/${teamId}/task/${taskId}/edit`;
    }
    
    function viewTaskDetail(taskId) {
        if (typeof openTeamTaskDetailModal === 'function') {
            openTeamTaskDetailModal(taskId, teamId);
        } else {
            setTimeout(() => {
                if (typeof openTeamTaskDetailModal === 'function') {
                    openTeamTaskDetailModal(taskId, teamId);
                } else {
                    console.error('openTeamTaskDetailModal function not found. Please refresh the page.');
                    alert('Modal not loaded. Please refresh the page.');
                }
            }, 100);
        }
    }
    
    function showToggleTaskDialog(taskId, newStatus) {
        const task = tasksData.find(t => t.id === taskId);
        if (!task) return;
        
        const action = newStatus ? 'completed' : 'not completed';
        const message = `Are you sure you want to mark task "${task.title}" as ${action}?`;
        
        if (window.showConfirmDialog) {
            window.showConfirmDialog(
                'Confirm',
                message,
                () => toggleTask(taskId, newStatus)
            );
        } else if (confirm(message)) {
            toggleTask(taskId, newStatus);
        }
    }
    
    function showRenameModal() {
        const modal = document.getElementById('renameTeamModal');
        if (modal) {
            document.getElementById('newTeamName').value = teamData.name;
            modal.classList.add('show');
        } else {
            const newName = prompt('Enter new team name:', teamData.name);
            if (newName && newName.trim()) {
                renameTeam(newName.trim());
            }
        }
    }
    
    function closeRenameModal() {
        const modal = document.getElementById('renameTeamModal');
        if (modal) {
            modal.classList.remove('show');
        }
    }
    
    async function confirmRename() {
        const newName = document.getElementById('newTeamName').value.trim();
        if (!newName) {
            alert('Please enter a team name');
            return;
        }
        
        await renameTeam(newName);
        closeRenameModal();
    }
    
    async function renameTeam(newName) {
        try {
            const response = await fetch(`/api/v1/team/`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id: teamId,
                    name: newName
                })
            });
            
            const result = await response.json();
            if (response.ok && result.status === 200) {
                teamData.name = newName;
                document.getElementById('teamName').textContent = newName;
                alert('Team renamed successfully');
            } else {
                alert(result.message || 'Failed to rename team');
            }
        } catch (error) {
            console.error('Error renaming team:', error);
            alert('Error renaming team');
        }
    }
    
    async function disbandTeam() {
        try {
            const response = await fetch(`/api/v1/team/${teamId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            if (response.ok && result.status === 200) {
                alert('Team disbanded successfully');
                window.location.href = '/group';
            } else {
                alert(result.message || 'Failed to disband team');
            }
        } catch (error) {
            console.error('Error disbanding team:', error);
            alert('Error disbanding team');
        }
    }
    
    async function leaveTeam() {
        if (isLeader) {
            showChooseNewLeaderModal();
        } else {
            const confirmAction = () => deleteMember(currentUserMember.id);
            if (typeof window.showConfirmDialog === 'function') {
                window.showConfirmDialog(
                    'Rời khỏi nhóm',
                    'Bạn có chắc chắn muốn rời khỏi nhóm này?',
                    confirmAction
                );
            } else if (confirm('Bạn có chắc chắn muốn rời khỏi nhóm này?')) {
                confirmAction();
            }
        }
    }
    
    async function deleteMember(memberId) {
        try {
            const response = await fetch(`/api/v1/member/${memberId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            if (response.ok && result.status === 200) {
                alert('Left team successfully');
                window.location.href = '/group';
            } else {
                alert(result.message || 'Failed to leave team');
            }
        } catch (error) {
            console.error('Error leaving team:', error);
            alert('Error leaving team');
        }
    }
    
</script>
@endpush
