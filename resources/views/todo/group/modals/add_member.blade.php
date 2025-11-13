<div class="modal" id="addMemberModal">
    <div class="modal-content add-member-modal">
        <div class="modal-header add-member-header">
            <div>
                <h3>Thêm thành viên</h3>
                <p class="modal-subtitle">Mời đồng đội vào nhóm để cùng cộng tác</p>
            </div>
            <button class="modal-close" onclick="closeAddMemberModal()">&times;</button>
        </div>
        <div class="modal-body add-member-body">
            <div class="form-group">
                <label for="searchMemberEmail">Tìm qua email</label>
                <div class="input-with-icon">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchMemberEmail" placeholder="Nhập email hoặc tên...">
                </div>
                <p class="helper-text">Hệ thống sẽ tự động loại trừ thành viên đã có trong nhóm.</p>
                <div id="searchResults" class="search-results card"></div>
            </div>
            <div class="members-list card" id="membersToAddList">
                <div class="empty-state compact">
                    <i class="fas fa-user-plus"></i>
                    <p>Chưa có ai trong danh sách mời</p>
                    <span>Nhập email để thêm thành viên</span>
                </div>
            </div>
        </div>
        <div class="modal-footer add-member-footer">
            <button class="btn-secondary ghost" onclick="closeAddMemberModal()">Huỷ</button>
            <button class="btn-primary" id="addMembersBtn" onclick="addMembers()">
                <i class="fas fa-paper-plane"></i> Gửi lời mời
            </button>
        </div>
    </div>
</div>

<style>
    .add-member-modal {
        padding: 28px;
        border-radius: 16px;
        background: var(--card-bg);
    }
    .add-member-header {
        align-items: flex-start;
        gap: 16px;
        border-bottom: none;
        padding: 0;
        margin-bottom: 20px;
    }
    .add-member-header h3 {
        margin: 0;
        font-size: 1.4rem;
        font-weight: 600;
        color: var(--text-primary);
    }
    .modal-subtitle {
        margin: 6px 0 0 0;
        font-size: 0.95rem;
        color: var(--text-muted);
    }
    .add-member-body {
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .add-member-body .form-group {
        margin: 0;
    }
    .input-with-icon {
        position: relative;
        display: flex;
        align-items: center;
        background-color: var(--bg-color);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 10px 14px;
        gap: 10px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .input-with-icon:focus-within {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1);
    }
    .input-with-icon i {
        color: var(--text-muted);
        font-size: 0.95rem;
    }
    .input-with-icon input {
        border: none;
        outline: none;
        background: transparent;
        width: 100%;
        color: var(--text-primary);
        font-size: 1rem;
    }
    .helper-text {
        margin: 8px 0 0;
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    .card {
        background-color: var(--bg-secondary);
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }
    #searchResults.card {
        position: absolute;
        width: calc(100% - 2px);
        max-height: 240px;
        overflow-y: auto;
        margin-top: 8px;
        padding: 8px 0;
        display: none;
        z-index: 50;
    }
    .search-result-item {
        padding: 12px 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: background-color 0.2s ease;
    }
    .search-result-item:hover {
        background-color: var(--hover-bg);
    }
    .search-result-content {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
    }
    .search-result-meta {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .search-result-name {
        font-weight: 600;
        color: var(--text-primary);
    }
    .search-result-email {
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    .members-list.card {
        min-height: 120px;
        max-height: 280px;
        overflow-y: auto;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .empty-state.compact {
        padding: 20px 10px;
        color: var(--text-muted);
    }
    .empty-state.compact p {
        margin: 10px 0 4px;
        font-weight: 600;
    }
    .empty-state.compact span {
        font-size: 0.85rem;
    }
    .member-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        background-color: var(--card-bg);
        border-radius: 10px;
        border: 1px solid var(--border-color);
        gap: 12px;
    }
    .member-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .member-info > div:last-child {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .member-info .name {
        font-weight: 600;
        color: var(--text-primary);
    }
    .member-info .email {
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    .remove-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background-color: var(--bg-tertiary);
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.2s ease, color 0.2s ease;
    }
    .remove-btn:hover {
        background-color: rgba(231, 76, 60, 0.12);
        color: #e74c3c;
    }
    .add-member-footer {
        padding: 24px 0 0;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    .btn-secondary.ghost {
        background: none;
        border: 1px solid var(--border-color);
        color: var(--text-primary);
    }
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--accent-color);
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .btn-primary:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    @media (max-width: 640px) {
        .add-member-modal {
            padding: 20px;
        }
        .members-list.card {
            max-height: 240px;
        }
    }
</style>

<script>
    let searchTimeout = null;
    let membersToAdd = [];
    
    document.getElementById('searchMemberEmail').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const prefix = e.target.value.trim();
        
        if (prefix.length === 0) {
            document.getElementById('searchResults').style.display = 'none';
            return;
        }
        
        searchTimeout = setTimeout(() => {
            searchUsersByEmailPrefix(prefix);
        }, 800);
    });
    
    async function searchUsersByEmailPrefix(prefix) {
        const apiToken = getApiToken();
        const resultsDiv = document.getElementById('searchResults');
        resultsDiv.innerHTML = '<div class="search-loading">Đang tìm kiếm...</div>';
        resultsDiv.style.display = 'block';
        
        try {
            const response = await fetch(`/api/v1/user/search?prefix=${encodeURIComponent(prefix)}&limit=10`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            if (response.ok && data.status === 200) {
                const users = data.data || [];
                const existingMemberIds = allMembers.map(m => m.userId);
                const filteredUsers = users.filter(user => 
                    !existingMemberIds.includes(user.id) && 
                    user.id !== userId &&
                    !membersToAdd.some(m => m.id === user.id)
                );
                
                if (filteredUsers.length === 0) {
                    resultsDiv.innerHTML = '<div class="search-loading">Không tìm thấy người phù hợp</div>';
                    return;
                }
                
                resultsDiv.innerHTML = filteredUsers.map(user => `
                    <div class="search-result-item" onclick="addMemberToAddList(${user.id}, '${escapeHtml(user.name)}', '${escapeHtml(user.email)}', ${user.avatar ? `'${escapeHtml(user.avatar)}'` : 'null'})">
                        <div class="search-result-content">
                            ${renderAvatar(user.avatar, user.name, user.email, 36)}
                            <div class="search-result-meta">
                                <span class="search-result-name">${escapeHtml(user.name)}</span>
                                <span class="search-result-email">${escapeHtml(user.email)}</span>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Error searching users:', error);
            resultsDiv.innerHTML = '<div class="search-loading">Tìm kiếm thất bại</div>';
        }
    }
    
    function addMemberToAddList(id, name, email, avatar) {
        if (membersToAdd.some(m => m.id === id)) {
            return;
        }
        membersToAdd.push({ id, name, email, avatar });
        updateMembersToAddList();
        document.getElementById('searchMemberEmail').value = '';
        document.getElementById('searchResults').style.display = 'none';
    }
    
    function removeMemberFromAddList(id) {
        membersToAdd = membersToAdd.filter(m => m.id !== id);
        updateMembersToAddList();
    }
    
    function updateMembersToAddList() {
        const listDiv = document.getElementById('membersToAddList');
        if (membersToAdd.length === 0) {
            listDiv.innerHTML = `
                <div class="empty-state compact">
                    <i class="fas fa-user-plus"></i>
                    <p>Chưa có ai trong danh sách mời</p>
                    <span>Nhập email để thêm thành viên</span>
                </div>
            `;
            return;
        }
        
        listDiv.innerHTML = membersToAdd.map(member => `
            <div class="member-item">
                <div class="member-info">
                    ${renderAvatar(member.avatar, member.name, member.email, 36)}
                    <div>
                        <span class="name">${escapeHtml(member.name)}</span>
                        <span class="email">${escapeHtml(member.email)}</span>
                    </div>
                </div>
                <button class="remove-btn" onclick="removeMemberFromAddList(${member.id})"><i class="fas fa-times"></i></button>
            </div>
        `).join('');
    }
    
    async function addMembers() {
        if (membersToAdd.length === 0) {
            alert('Vui lòng chọn ít nhất một thành viên');
            return;
        }
        
        const apiToken = getApiToken();
        const btn = document.getElementById('addMembersBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
        
        try {
            for (const member of membersToAdd) {
                const response = await fetch(`/api/v1/member/`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${apiToken}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        team_id: teamId,
                        user_id: member.id,
                        role: 'MEMBER'
                    })
                });
                
                const result = await response.json();
                if (!response.ok || result.status !== 201) {
                    throw new Error(result.message || 'Không thể thêm thành viên');
                }
            }
            
            alert('Đã gửi lời mời thành công');
            closeAddMemberModal();
            if (typeof loadMembers === 'function') {
                loadMembers();
            } else {
                window.location.reload();
            }
        } catch (error) {
            console.error('Error adding members:', error);
            alert('Thêm thành viên thất bại: ' + error.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Gửi lời mời';
        }
    }
    
    function closeAddMemberModal() {
        document.getElementById('addMemberModal').classList.remove('show');
        membersToAdd = [];
        document.getElementById('searchMemberEmail').value = '';
        document.getElementById('searchResults').style.display = 'none';
        updateMembersToAddList();
    }
    
    // Helper functions from group.blade.php
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
    
    function renderAvatar(avatar, name, email, size) {
        const avatarUrl = avatar ? (avatar.startsWith('http') ? avatar : `/storage/${avatar}`) : null;
        const initials = name ? name[0].toUpperCase() : (email ? email[0].toUpperCase() : 'U');
        
        if (avatarUrl) {
            return `
                <div class="avatar-wrapper" style="width: ${size}px; height: ${size}px;">
                    <img src="${avatarUrl}" alt="Avatar" style="width: ${size}px; height: ${size}px; border-radius: 50%; object-fit: cover;" onerror="this.remove(); this.closest('.avatar-wrapper').querySelector('.avatar-fallback').style.display='flex';">
                    <div class="avatar-fallback" style="width: ${size}px; height: ${size}px; border-radius: 50%; background-color: var(--accent-color); display: none; align-items: center; justify-content: center; color: var(--text-primary); font-weight: bold; font-size: ${size * 0.4}px;">${initials}</div>
                </div>
            `;
        } else {
            return `
                <div class="avatar-wrapper" style="width: ${size}px; height: ${size}px;">
                    <div class="avatar-fallback" style="width: ${size}px; height: ${size}px; border-radius: 50%; background-color: var(--accent-color); display: flex; align-items: center; justify-content: center; color: var(--text-primary); font-weight: bold; font-size: ${size * 0.4}px;">${initials}</div>
                </div>
            `;
        }
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>

