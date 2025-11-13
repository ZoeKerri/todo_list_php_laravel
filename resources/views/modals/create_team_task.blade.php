<div class="modal" id="createTeamTaskModal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Tạo Task Team</h3>
            <button class="modal-close" onclick="closeCreateTeamTaskModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="createTeamTaskForm">
                <input type="hidden" id="teamTaskTeamId" name="team_id">
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Tiêu đề *</label>
                    <input type="text" id="teamTaskTitle" name="title" required
                           style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem;"
                           placeholder="Nhập tiêu đề task">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Mô tả</label>
                    <textarea id="teamTaskDescription" name="description" rows="4"
                              style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem; resize: vertical;"
                              placeholder="Nhập mô tả task (tùy chọn)"></textarea>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Ngày hết hạn *</label>
                    <input type="date" id="teamTaskDeadline" name="deadline" required
                           style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem;"
                           min="{{ date('Y-m-d') }}">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Giao cho thành viên *</label>
                    <select id="teamTaskMember" name="member_id" required
                            style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem;">
                        <option value="">Chọn thành viên</option>
                    </select>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Độ ưu tiên</label>
                    <select id="teamTaskPriority" name="priority"
                            style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem;">
                        <option value="LOW">Thấp</option>
                        <option value="MEDIUM" selected>Trung bình</option>
                        <option value="HIGH">Cao</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeCreateTeamTaskModal()">Hủy</button>
            <button class="btn-primary" onclick="submitCreateTeamTask()">Tạo Task</button>
        </div>
    </div>
</div>

<script>
function openCreateTeamTaskModal(teamId) {
    const modal = document.getElementById('createTeamTaskModal');
    if (modal) {
        document.getElementById('teamTaskTeamId').value = teamId;
        // Load team members
        loadTeamMembersForTask(teamId);
        // Set default date to today
        document.getElementById('teamTaskDeadline').value = new Date().toISOString().split('T')[0];
        modal.classList.add('show');
    } else {
        console.error('Modal createTeamTaskModal not found');
    }
}

// Make function globally available
window.openCreateTeamTaskModal = openCreateTeamTaskModal;

function closeCreateTeamTaskModal() {
    const modal = document.getElementById('createTeamTaskModal');
    if (modal) {
        modal.classList.remove('show');
        document.getElementById('createTeamTaskForm').reset();
    }
}

async function loadTeamMembersForTask(teamId) {
    const memberSelect = document.getElementById('teamTaskMember');
    if (!memberSelect) return;
    
    try {
        const apiToken = getApiToken();
        const response = await fetch(`/api/v1/team/detail/${teamId}`, {
            headers: {
                'Authorization': `Bearer ${apiToken}`,
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        if (response.ok && result.status === 200) {
            memberSelect.innerHTML = '<option value="">Chọn thành viên</option>';
            const teamMembers = result.data.teamMembers || [];
            teamMembers.forEach(member => {
                const option = document.createElement('option');
                option.value = member.id; // member_id
                const userName = member.user ? (member.user.name || member.user.fullName || member.user.email) : 'Unknown';
                option.textContent = `${userName} (${member.role})`;
                memberSelect.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error loading team members:', error);
    }
}

async function submitCreateTeamTask() {
    const form = document.getElementById('createTeamTaskForm');
    const formData = new FormData(form);
    
    // Get values
    const data = {
        team_id: parseInt(formData.get('team_id')),
        title: formData.get('title'),
        description: formData.get('description') || null,
        deadline: formData.get('deadline'),
        member_id: parseInt(formData.get('member_id')) || null,
        priority: formData.get('priority') || 'MEDIUM',
    };
    
    // Validation
    if (!data.title || !data.deadline || !data.member_id || !data.team_id) {
        alert('Vui lòng điền đầy đủ các trường bắt buộc');
        return;
    }
    
    try {
        const apiToken = getApiToken();
        const response = await fetch('/api/v1/team-task', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${apiToken}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        if (response.ok && (result.status === 200 || result.status === 201)) {
            alert('Tạo task team thành công!');
            closeCreateTeamTaskModal();
            if (typeof loadTasks === 'function') {
                loadTasks();
            }
            // Reload page if in group details page
            if (window.location.pathname.includes('/group/')) {
                window.location.reload();
            }
        } else {
            alert(result.message || 'Có lỗi xảy ra khi tạo task');
        }
    } catch (error) {
        console.error('Error creating team task:', error);
        alert('Có lỗi xảy ra khi tạo task');
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('createTeamTaskModal');
    if (modal && e.target === modal) {
        closeCreateTeamTaskModal();
    }
});
</script>

