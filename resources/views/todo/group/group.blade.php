@extends('layouts.app')

@section('title', 'Your Groups')

@push('styles')
<style>
    /* Header màu tím */
    .group-header {
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: background-color 0.3s ease;
    }
    .group-header .icon {
        font-size: 1.5rem;
        background-color: rgba(255, 255, 255, 0.2);
        padding: 10px 15px;
        border-radius: 8px;
        color: white;
    }
    .group-header h2, .group-header p {
        margin: 0;
        color: white;
    }
    .group-header h2 {
        font-size: 1.5rem;
        transition: color 0.3s ease;
    }
    .group-header p {
        font-size: 0.9rem;
        opacity: 0.9;
        transition: opacity 0.3s ease;
    }
    
    /* Section headers */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
        padding: 12px 16px;
        margin: 16px 0 8px 0;
        background-color: var(--bg-secondary);
        border-radius: 8px;
        transition: background-color 0.2s ease;
    }
    .section-header:hover {
        background-color: var(--bg-tertiary);
    }
    .section-header .section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: var(--text-primary);
    }
    .section-header .section-icon {
        transition: transform 0.3s ease;
        color: var(--text-secondary);
    }
    .section-header.collapsed .section-icon {
        transform: rotate(-90deg);
    }
    .section-content {
        overflow: hidden;
        transition: max-height 0.3s ease, opacity 0.3s ease;
        max-height: 5000px;
        opacity: 1;
    }
    .section-content.collapsed {
        max-height: 0;
        opacity: 0;
        margin: 0;
    }
    
    /* Danh sách nhóm */
    .group-list {
        margin-bottom: 100px;
    }
    .group-item {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease;
        cursor: pointer;
    }
    .group-item:hover {
        background-color: var(--hover-bg);
        transform: translateX(5px);
    }
    .group-item-meta {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .group-item-meta .avatar {
        width: 40px;
        height: 40px;
        background-color: var(--border-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease;
    }
    .group-item-meta .avatar.leader {
        background-color: #7c3aed;
        color: white;
    }
    .group-item-meta h4, .group-item-meta p {
        margin: 0;
    }
    .group-item-meta h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        transition: color 0.3s ease;
    }
    .group-item-meta p {
        font-size: 0.85rem;
        color: var(--text-muted);
        transition: color 0.3s ease;
    }
    .group-item .arrow {
        font-size: 1.2rem;
        color: var(--text-muted);
        transition: color 0.3s ease;
    }
    
    /* Empty state */
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
    
    /* Nút Thêm (FAB) */
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
        color: #fff;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        transition: background-color 0.3s ease, transform 0.2s ease;
        border: none;
        cursor: pointer;
        z-index: 1000;
    }
    .fab:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.5);
    }
    
    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        overflow: auto;
    }
    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background-color: var(--card-bg);
        margin: auto;
        padding: 30px;
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
        margin-bottom: 20px;
    }
    .modal-header h3 {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.5rem;
    }
    .close {
        color: var(--text-muted);
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        border: none;
        background: none;
    }
    .close:hover {
        color: var(--text-primary);
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-primary);
        font-weight: 500;
    }
    .form-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: var(--bg-color);
        color: var(--text-primary);
        font-size: 1rem;
        box-sizing: border-box;
    }
    .form-group input:focus {
        outline: none;
        border-color: var(--accent-color);
    }
    .members-list {
        max-height: 300px;
        overflow-y: auto;
        margin-top: 15px;
    }
    .member-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px;
        background-color: var(--bg-color);
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .member-item .member-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .member-item .member-info .avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    .member-item .member-info .avatar.leader {
        background-color: #7c3aed;
        color: white;
    }
    .member-item .remove-btn {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        font-size: 1.2rem;
        padding: 5px 10px;
    }
    .member-item .remove-btn:hover {
        color: #c82333;
    }
    .search-results {
        position: absolute;
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        max-height: 200px;
        overflow-y: auto;
        width: 100%;
        z-index: 10;
        margin-top: 5px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .search-result-item {
        padding: 12px;
        cursor: pointer;
        border-bottom: 1px solid var(--border-color);
    }
    .search-result-item:hover {
        background-color: var(--hover-bg);
    }
    .search-result-item:last-child {
        border-bottom: none;
    }
    .search-loading {
        padding: 12px;
        text-align: center;
        color: var(--text-muted);
    }
    .btn-primary {
        width: 100%;
        padding: 12px;
        background-color: var(--accent-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        margin-top: 20px;
    }
    .btn-primary:hover {
        opacity: 0.9;
    }
    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')

<div class="group-header">
    <div class="icon">H</div> 
    <div>
        <p>Hi there, {{ Auth::check() ? (Auth::user()->full_name ?? Auth::user()->email) : 'Guest' }}</p>
        <h2>Your groups</h2>
    </div>
</div>

<div class="group-list" id="groupList">
    <div style="text-align: center; padding: 40px;">
        <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--text-muted);"></i>
        <p style="margin-top: 15px; color: var(--text-muted);">Loading groups...</p>
    </div>
</div>

<a href="#" class="fab" id="createGroupBtn" onclick="openCreateModal(); return false;">
    <i class="fas fa-plus"></i>
</a>

<!-- Modal Create Group -->
<div id="createGroupModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Create New Group</h3>
            <button class="close" onclick="closeCreateModal()">&times;</button>
        </div>
        <form id="createGroupForm">
            <div class="form-group">
                <label for="teamName">Group Name *</label>
                <input type="text" id="teamName" name="teamName" required placeholder="Enter group name">
            </div>
            
            <div class="form-group">
                <label>Members</label>
                <div style="position: relative;">
                    <input type="text" id="searchMember" placeholder="Search by email..." autocomplete="off">
                    <div id="searchResults" class="search-results" style="display: none;"></div>
                </div>
                <div class="members-list" id="membersList">
                    <!-- Members will be added here -->
                </div>
            </div>
            
            <button type="submit" class="btn-primary" id="createBtn">Create Group</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const userId = {{ Auth::id() ?? 'null' }};
    
    // Lấy token từ session hoặc localStorage
    function getApiToken() {
        // Thử lấy từ session (PHP) - cho login thông thường
        const sessionToken = '{{ session("jwt_token") }}';
        if (sessionToken && sessionToken !== '' && sessionToken !== 'null') {
            console.log('Using token from session');
            return sessionToken;
        }
        
        // Fallback: lấy từ localStorage (nếu login bằng Google)
        const localToken = localStorage.getItem('access_token');
        if (localToken) {
            console.log('Using token from localStorage');
            return localToken;
        }
        
        // Nếu không có token
        console.error('No API token found in session or localStorage');
        return null;
    }
    
    const apiToken = getApiToken();
    
    // Debug info
    console.log('User ID:', userId);
    console.log('API Token available:', apiToken ? 'Yes (' + apiToken.substring(0, 20) + '...)' : 'No');
    
    if (!apiToken) {
        console.warn('Warning: No API token available. Some features may not work.');
    }
    
    let searchTimeout = null;
    let currentMembers = [];
    
    // Load teams on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadTeams();
        
        // Setup search debounce
        document.getElementById('searchMember').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const prefix = e.target.value.trim();
            
            if (prefix.length === 0) {
                document.getElementById('searchResults').style.display = 'none';
                return;
            }
            
            // Debounce: wait 0.8 seconds before searching (prefix search)
            searchTimeout = setTimeout(() => {
                searchUsersByEmailPrefix(prefix);
            }, 800);
        });
        
        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#searchMember') && !e.target.closest('#searchResults')) {
                document.getElementById('searchResults').style.display = 'none';
            }
        });
        
        // Form submit
        document.getElementById('createGroupForm').addEventListener('submit', function(e) {
            e.preventDefault();
            createGroup();
        });
    });
    
    // Load teams from API
    async function loadTeams() {
        if (!apiToken) {
            showError('Please login to view groups');
            return;
        }
        
        if (!userId) {
            showError('User not authenticated');
            return;
        }
        
        try {
            console.log('Loading teams for user:', userId);
            const response = await fetch(`/api/v1/team/${userId}`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            console.log('Response status:', response.status);
            const data = await response.json();
            console.log('Response data:', data);
            
            if (response.ok && data.status === 200) {
                displayTeams(data.data);
            } else {
                if (response.status === 401) {
                    showError('Session expired. Please login again.');
                    // Có thể redirect về login
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                } else {
                    showError(data.message || 'Failed to load groups');
                }
            }
        } catch (error) {
            console.error('Error loading teams:', error);
            showError('Error loading groups: ' + error.message);
        }
    }
    
    // Display teams in 2 sections
    function displayTeams(teams) {
        const container = document.getElementById('groupList');
        
        if (teams.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>No groups yet. Create your first group!</p>
                </div>
            `;
            return;
        }
        
        // Separate leader and member teams
        const leaderTeams = teams.filter(team => {
            return team.teamMembers.some(member => 
                member.userId === userId && member.role === 'LEADER'
            );
        });
        
        const memberTeams = teams.filter(team => {
            return team.teamMembers.some(member => 
                member.userId === userId && member.role !== 'LEADER'
            );
        });
        
        let html = '';
        
        // Leader section
        if (leaderTeams.length > 0) {
            html += `
                <div class="section-wrapper" data-section="leader">
                    <h3 class="section-header" onclick="toggleSection('leader')">
                        <span class="section-title">
                            <i class="fas fa-chevron-down section-icon"></i>
                            <span>Leader of Teams (${leaderTeams.length})</span>
                        </span>
                    </h3>
                    <div class="section-content" id="section-leader">
            `;
            leaderTeams.forEach(team => {
                const leader = team.teamMembers.find(m => m.role === 'LEADER');
                html += createTeamItem(team, leader, true);
            });
            html += '</div></div>';
        }
        
        // Member section
        if (memberTeams.length > 0) {
            html += `
                <div class="section-wrapper" data-section="member">
                    <h3 class="section-header" onclick="toggleSection('member')">
                        <span class="section-title">
                            <i class="fas fa-chevron-down section-icon"></i>
                            <span>Member of Teams (${memberTeams.length})</span>
                        </span>
                    </h3>
                    <div class="section-content" id="section-member">
            `;
            memberTeams.forEach(team => {
                const leader = team.teamMembers.find(m => m.role === 'LEADER');
                html += createTeamItem(team, leader, false);
            });
            html += '</div></div>';
        }
        
        container.innerHTML = html;
    }
    
    // Helper function to get avatar URL
    function getAvatarUrl(avatar) {
        if (!avatar) return null;
        // Check if avatar is a URL (from Google login) or a storage path
        if (avatar.startsWith('http://') || avatar.startsWith('https://')) {
            return avatar;
        }
        return `/storage/${avatar}`;
    }
    
    // Helper function to get initials from name
    function getInitials(name, email) {
        if (name && name.length > 0) {
            return name[0].toUpperCase();
        }
        if (email && email.length > 0) {
            return email[0].toUpperCase();
        }
        return 'U';
    }
    
    // Helper function to render avatar with fallback
    function renderAvatar(avatar, name, email, size = 32) {
        const avatarUrl = getAvatarUrl(avatar);
        const initials = getInitials(name, email);
        
        if (avatarUrl) {
            return `
                <img src="${avatarUrl}" alt="Avatar" 
                     style="width: ${size}px; height: ${size}px; border-radius: 50%; object-fit: cover;" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div style="width: ${size}px; height: ${size}px; border-radius: 50%; background-color: var(--accent-color); display: none; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: ${size * 0.4}px;">${initials}</div>
            `;
        } else {
            return `
                <div style="width: ${size}px; height: ${size}px; border-radius: 50%; background-color: var(--accent-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: ${size * 0.4}px;">${initials}</div>
            `;
        }
    }
    
    // Create team item HTML
    function createTeamItem(team, leader, isLeader) {
        const leaderName = leader?.user?.name || leader?.user?.email || 'Unknown';
        const leaderEmail = leader?.user?.email || '';
        const leaderAvatar = leader?.user?.avatar || null;
        const iconClass = isLeader ? 'fas fa-user-shield' : 'fas fa-users';
        const avatarClass = isLeader ? 'leader' : '';
        
        return `
            <a href="/group-detail/${team.id}" class="group-item">
        <div class="group-item-meta">
                    <div class="avatar ${avatarClass}" style="position: relative;">
                        ${renderAvatar(leaderAvatar, leaderName, leaderEmail, 40)}
                    </div>
            <div>
                        <h4>${escapeHtml(team.name)}</h4>
                        <p>${escapeHtml(leaderName)}</p>
            </div>
        </div>
        <i class="fas fa-chevron-right arrow"></i>
    </a>
        `;
    }
    
    // Search users by email prefix
    async function searchUsersByEmailPrefix(prefix) {
        if (!apiToken) {
            alert('Please login to search users');
            return;
        }
        
        const resultsDiv = document.getElementById('searchResults');
        resultsDiv.innerHTML = '<div class="search-loading">Searching...</div>';
        resultsDiv.style.display = 'block';
        
        try {
            console.log('Searching users by prefix:', prefix);
            const response = await fetch(`/api/v1/user/search?prefix=${encodeURIComponent(prefix)}&limit=10`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            console.log('Search response status:', response.status);
            const data = await response.json();
            console.log('Search response data:', data);
            
            if (response.ok && data.status === 200) {
                const users = data.data || [];
                
                if (users.length === 0) {
                    resultsDiv.innerHTML = '<div class="search-loading">No users found</div>';
                    return;
                }
                
                // Filter out already added members and current user
                const filteredUsers = users.filter(user => {
                    if (currentMembers.some(m => m.id === user.id)) {
                        return false; // Already added
                    }
                    if (user.id === userId) {
                        return false; // Current user (automatically leader)
                    }
                    return true;
                });
                
                if (filteredUsers.length === 0) {
                    resultsDiv.innerHTML = '<div class="search-loading">All matching users are already added</div>';
                    return;
                }
                
                // Display list of users
                let html = '';
                filteredUsers.forEach(user => {
                    html += `
                        <div class="search-result-item" onclick="addMember(${user.id}, '${escapeHtml(user.name)}', '${escapeHtml(user.email)}', ${user.avatar ? `'${escapeHtml(user.avatar)}'` : 'null'})">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                ${renderAvatar(user.avatar, user.name, user.email, 32)}
                                <div style="flex: 1;">
                                    <div style="font-weight: bold; color: var(--text-primary);">${escapeHtml(user.name)}</div>
                                    <div style="font-size: 0.85rem; color: var(--text-muted);">${escapeHtml(user.email)}</div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                resultsDiv.innerHTML = html;
            } else {
                if (response.status === 401) {
                    resultsDiv.innerHTML = '<div class="search-loading">Session expired. Please refresh page.</div>';
                } else {
                    resultsDiv.innerHTML = '<div class="search-loading">' + (data.message || 'Error searching users') + '</div>';
                }
            }
        } catch (error) {
            console.error('Error searching users:', error);
            resultsDiv.innerHTML = '<div class="search-loading">Error: ' + error.message + '</div>';
        }
    }
    
    // Add member to list
    function addMember(id, name, email, avatar = null) {
        // Check if already added
        if (currentMembers.some(m => m.id === id)) {
            alert('User already added');
            return;
        }
        
        // Check if user is current user
        if (id === userId) {
            alert('You are automatically the leader');
            return;
        }
        
        currentMembers.push({ id, name, email, avatar });
        updateMembersList();
        document.getElementById('searchMember').value = '';
        document.getElementById('searchResults').style.display = 'none';
    }
    
    // Remove member from list
    function removeMember(id) {
        currentMembers = currentMembers.filter(m => m.id !== id);
        updateMembersList();
    }
    
    // Update members list display
    function updateMembersList() {
        const listDiv = document.getElementById('membersList');
        
        if (currentMembers.length === 0) {
            listDiv.innerHTML = '<p style="color: var(--text-muted); text-align: center; padding: 20px;">No members added yet</p>';
            return;
        }
        
        let html = '';
        currentMembers.forEach(member => {
            html += `
                <div class="member-item">
                    <div class="member-info">
                        ${renderAvatar(member.avatar, member.name, member.email, 32)}
            <div>
                            <div style="font-weight: bold; color: var(--text-primary);">${escapeHtml(member.name)}</div>
                            <div style="font-size: 0.85rem; color: var(--text-muted);">${escapeHtml(member.email)}</div>
            </div>
        </div>
                    <button type="button" class="remove-btn" onclick="removeMember(${member.id})">
                        <i class="fas fa-times"></i>
                    </button>
</div>
            `;
        });
        
        listDiv.innerHTML = html;
    }
    
    // Create group
    async function createGroup() {
        if (!apiToken) {
            alert('Please login to create a group');
            return;
        }
        
        if (!userId) {
            alert('User not authenticated');
            return;
        }
        
        const teamName = document.getElementById('teamName').value.trim();
        const createBtn = document.getElementById('createBtn');
        
        if (!teamName) {
            alert('Please enter a group name');
            return;
        }
        
        createBtn.disabled = true;
        createBtn.textContent = 'Creating...';
        
        try {
            const teamMembers = currentMembers.map(m => ({
                userId: m.id,
                role: 'MEMBER'
            }));
            
            console.log('Creating group:', { name: teamName, members: teamMembers });
            const response = await fetch(`/api/v1/team/${userId}`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: teamName,
                    teamMembers: teamMembers
                })
            });
            
            console.log('Create response status:', response.status);
            const data = await response.json();
            console.log('Create response data:', data);
            
            if (response.ok && data.status === 201) {
                closeCreateModal();
                loadTeams();
                alert('Group created successfully!');
            } else {
                if (response.status === 401) {
                    alert('Session expired. Please login again.');
                } else {
                    alert(data.message || 'Failed to create group');
                }
            }
        } catch (error) {
            console.error('Error creating group:', error);
            alert('Error creating group: ' + error.message);
        } finally {
            createBtn.disabled = false;
            createBtn.textContent = 'Create Group';
        }
    }
    
    // Toggle section collapse/expand
    function toggleSection(sectionName) {
        const header = document.querySelector(`[data-section="${sectionName}"] .section-header`);
        const content = document.getElementById(`section-${sectionName}`);
        
        if (!header || !content) return;
        
        header.classList.toggle('collapsed');
        content.classList.toggle('collapsed');
    }
    
    // Modal functions
    function openCreateModal() {
        document.getElementById('createGroupModal').classList.add('show');
        currentMembers = [];
        updateMembersList();
        document.getElementById('createGroupForm').reset();
    }
    
    function closeCreateModal() {
        document.getElementById('createGroupModal').classList.remove('show');
        currentMembers = [];
        document.getElementById('searchMember').value = '';
        document.getElementById('searchResults').style.display = 'none';
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('createGroupModal');
        if (event.target === modal) {
            closeCreateModal();
        }
    }
    
    // Utility functions
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function showError(message) {
        const container = document.getElementById('groupList');
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-exclamation-triangle"></i>
                <p>${escapeHtml(message)}</p>
                <button onclick="loadTeams()" style="margin-top: 15px; padding: 10px 20px; background-color: var(--accent-color); color: white; border: none; border-radius: 8px; cursor: pointer;">
                    Retry
                </button>
            </div>
        `;
    }
</script>
@endpush
