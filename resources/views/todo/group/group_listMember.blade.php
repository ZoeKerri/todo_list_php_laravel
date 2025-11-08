@extends('layouts.app')

@section('title', 'Team Members')

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
        gap: 15px;
    }
    .content-header .header-actions a,
    .content-header .header-actions button {
        color: var(--text-primary);
        font-size: 1.2rem;
        text-decoration: none;
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: background-color 0.2s ease;
    }
    .content-header .header-actions a:hover,
    .content-header .header-actions button:hover {
        background-color: var(--bg-tertiary);
    }
    .search-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
    }
    .search-input {
        flex: 1;
        position: relative;
        display: flex;
        align-items: center;
        background-color: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 10px 14px;
        gap: 10px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .search-input:focus-within {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
    }
    .search-input i {
        color: var(--text-muted);
        font-size: 0.95rem;
    }
    .search-input input {
        border: none;
        outline: none;
        background: transparent;
        width: 100%;
        color: var(--text-primary);
        font-size: 1rem;
    }
    .search-meta {
        font-size: 0.85rem;
        color: var(--text-muted);
        white-space: nowrap;
    }
    
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 25px 0 15px 0;
    }
    
    .member-item {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background-color 0.3s ease;
    }
    .member-item:hover {
        background-color: var(--bg-tertiary);
    }
    .member-info {
        display: flex;
        align-items: center;
        gap: 15px;
        flex: 1;
    }
    .member-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1rem;
        color: white;
        background-color: var(--accent-color);
        position: relative;
        overflow: hidden;
    }
    .member-avatar.has-image .avatar-initials {
        display: none;
    }
    .member-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    .member-avatar .avatar-initials {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }
    .member-details h4 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0 0 4px 0;
        color: var(--text-primary);
    }
    .member-details p {
        margin: 0;
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    .member-role {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        background-color: #7c3aed;
        color: white;
        margin-left: 8px;
    }
    .remove-btn {
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: color 0.2s ease, background-color 0.2s ease;
    }
    .remove-btn:hover {
        color: #e74c3c;
        background-color: var(--bg-tertiary);
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
    }
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.5;
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
    <h2 class="title" id="teamName">Loading...</h2>
    <div class="header-actions">
        <button id="addMemberBtn" style="display: none;"><i class="fas fa-plus"></i></button>
        <button id="searchBtn"><i class="fas fa-search"></i></button>
    </div>
</div>

<div class="search-wrapper">
    <div class="search-input">
        <i class="fas fa-search"></i>
        <input type="text" id="memberSearchInput" placeholder="Tìm kiếm theo tên hoặc email thành viên">
    </div>
    <div class="search-meta" id="searchMeta" style="display: none;"></div>
</div>

<div id="loadingState" class="loading">
    <i class="fas fa-spinner fa-spin"></i>
    <p>Loading members...</p>
</div>

<div id="contentArea" style="display: none;">
    <h3 class="section-title" id="leaderTitle" style="display: none;">Leader</h3>
    <div id="leaderList"></div>
    
    <h3 class="section-title" id="membersTitle" style="display: none;">Members</h3>
    <div id="membersList"></div>
</div>

@include('todo.group.modals.add_member')
@include('todo.group.modals.confirm_dialog')

<style>
    /* Modal styles - same as group_details */
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
        color: white;
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
    .search-results {
        position: absolute;
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        margin-top: 5px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        width: 100%;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .search-result-item {
        padding: 12px;
        cursor: pointer;
        border-bottom: 1px solid var(--border-color);
        transition: background-color 0.2s ease;
    }
    .search-result-item:last-child {
        border-bottom: none;
    }
    .search-result-item:hover {
        background-color: var(--bg-tertiary);
    }
    .search-loading {
        padding: 12px;
        text-align: center;
        color: var(--text-muted);
    }
</style>

@endsection

@push('scripts')
<script>
    const teamId = {{ $id ?? 'null' }};
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
    
    const apiToken = getApiToken();
    let teamData = null;
    let membersData = [];
    let filteredMembers = [];
    let allMembers = [];
    let isLeader = false;
    let searchDebounce = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        if (!teamId || !apiToken) {
            alert('Invalid team ID or not authenticated');
            window.location.href = '/group';
            return;
        }
        
        loadTeamData();
        loadMembers();
        setupEventListeners();

        const searchInput = document.getElementById('memberSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const value = e.target.value.trim().toLowerCase();
                clearTimeout(searchDebounce);
                searchDebounce = setTimeout(() => {
                    applyMemberFilter(value);
                }, 200);
            });
        }
    });
    
    async function loadTeamData() {
        try {
            const response = await fetch(`/api/v1/team/detail/${teamId}`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            if (response.ok && result.status === 200) {
                teamData = result.data;
                isLeader = teamData.teamMembers.some(m => m.userId === userId && m.role === 'LEADER');
                document.getElementById('teamName').textContent = teamData.name;
                if (isLeader) {
                    document.getElementById('addMemberBtn').style.display = 'block';
                }
            }
        } catch (error) {
            console.error('Error loading team:', error);
        }
    }
    
    async function loadMembers() {
        try {
            const response = await fetch(`/api/v1/member/by-team/${teamId}`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            if (response.ok && result.status === 200) {
                membersData = result.data || [];
                allMembers = membersData;
                filteredMembers = [...membersData];
                displayMembers(filteredMembers);
                updateSearchMeta();
                document.getElementById('loadingState').style.display = 'none';
                document.getElementById('contentArea').style.display = 'block';
            } else {
                throw new Error(result.message || 'Failed to load members');
            }
        } catch (error) {
            console.error('Error loading members:', error);
            document.getElementById('loadingState').innerHTML = `
                <i class="fas fa-exclamation-triangle"></i>
                <p>Error loading members: ${error.message}</p>
            `;
        }
    }
    
    function displayMembers(list = membersData, query = document.getElementById('memberSearchInput')?.value.trim()) {
        if (!Array.isArray(list) || list.length === 0) {
            document.getElementById('leaderTitle').style.display = 'none';
            document.getElementById('membersTitle').style.display = 'none';
            document.getElementById('leaderList').innerHTML = '';
            document.getElementById('membersList').innerHTML = `
                <div class="empty-state" style="padding: 30px 20px;">
                    <i class="fas fa-user-slash"></i>
                    <p>${query ? 'Không tìm thấy thành viên phù hợp' : 'Nhóm chưa có thành viên nào'}</p>
                </div>
            `;
            return;
        }

        const leader = list.find(m => m.role === 'LEADER');
        const members = list.filter(m => m.role !== 'LEADER');
        
        // Display leader
        if (leader) {
            document.getElementById('leaderTitle').style.display = 'block';
            document.getElementById('leaderList').innerHTML = createMemberHTML(leader, true);
        } else {
            document.getElementById('leaderTitle').style.display = 'none';
            document.getElementById('leaderList').innerHTML = '';
        }
        
        // Display members
        if (members.length > 0) {
            document.getElementById('membersTitle').style.display = 'block';
            document.getElementById('membersList').innerHTML = members.map(m => createMemberHTML(m, false)).join('');
        } else {
            document.getElementById('membersTitle').style.display = 'none';
            document.getElementById('membersList').innerHTML = '';
        }
    }

    function applyMemberFilter(query) {
        if (!query) {
            filteredMembers = [...membersData];
        } else {
            filteredMembers = membersData.filter(member => {
                const name = (member.user?.name || '').toLowerCase();
                const email = (member.user?.email || '').toLowerCase();
                return name.includes(query) || email.includes(query);
            });
        }

        displayMembers(filteredMembers, query);
        updateSearchMeta(query);
    }

    function updateSearchMeta(query = '') {
        const meta = document.getElementById('searchMeta');
        if (!meta) return;

        if (!query) {
            meta.style.display = 'none';
            meta.textContent = '';
            return;
        }

        const count = filteredMembers.length;
        meta.style.display = 'block';
        meta.textContent = count === 0
            ? 'Không tìm thấy thành viên phù hợp'
            : `Tìm thấy ${count} thành viên`;
    }
    
    function createMemberHTML(member, isLeaderRole) {
        const user = member.user || {};
        const name = user.name || user.email || 'Unknown';
        const email = user.email || '';
        const avatar = user.avatar || null;
        const initials = name ? name[0].toUpperCase() : 'U';
        const canRemove = isLeader && !isLeaderRole && member.userId !== userId;
        
        let avatarHTML = '';
        if (avatar) {
            const avatarUrl = avatar.startsWith('http') ? avatar : `/storage/${avatar}`;
            avatarHTML = `
                <div class="member-avatar has-image">
                    <img src="${avatarUrl}" alt="Avatar" onerror="this.remove(); this.closest('.member-avatar').classList.remove('has-image');">
                    <span class="avatar-initials">${initials}</span>
                </div>
            `;
        } else {
            avatarHTML = `
                <div class="member-avatar">
                    <span class="avatar-initials">${initials}</span>
                </div>
            `;
        }
        
        return `
            <div class="member-item">
                <div class="member-info">
                    ${avatarHTML}
                    <div class="member-details">
                        <h4>${escapeHtml(name)} ${isLeaderRole ? '<span class="member-role">LEADER</span>' : ''}</h4>
                        <p>${escapeHtml(email)}</p>
                    </div>
                </div>
                ${canRemove ? `<button class="remove-btn" onclick="removeMember(${member.id}, '${escapeHtml(name)}')"><i class="fas fa-times"></i></button>` : ''}
            </div>
        `;
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function setupEventListeners() {
        document.getElementById('addMemberBtn').addEventListener('click', function() {
            if (typeof showAddMemberModal === 'function') {
                showAddMemberModal();
            } else {
                document.getElementById('addMemberModal').classList.add('show');
            }
        });
        
        const searchInput = document.getElementById('memberSearchInput');
        document.getElementById('searchBtn').addEventListener('click', function() {
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        });
    }
    
    async function removeMember(memberId, memberName) {
        const confirmAction = async () => {
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
                alert('Member removed successfully');
                loadMembers();
            } else {
                alert(result.message || 'Failed to remove member');
            }
        } catch (error) {
            console.error('Error removing member:', error);
            alert('Error removing member');
        }
        };

        if (typeof window.showConfirmDialog === 'function') {
            window.showConfirmDialog(
                'Loại bỏ thành viên',
                `Bạn có chắc chắn muốn xoá ${memberName} khỏi nhóm?`,
                confirmAction
            );
        } else if (confirm(`Are you sure you want to remove ${memberName} from the team?`)) {
            await confirmAction();
        }
    }
</script>
@endpush

