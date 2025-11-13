<div class="task-modal" id="createTeamTaskModal">
    <div class="task-modal-content">
        <div class="task-modal-header">
            <h3>Create Team Task</h3>
            <button type="button" class="task-modal-close" onclick="closeCreateTeamTaskModal()" aria-label="Close">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="task-modal-body">
            <form id="createTeamTaskForm">
                <div class="form-group">
                    <label for="teamTaskTitle" class="form-label">
                        Title <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="teamTaskTitle" 
                           name="title" 
                           class="form-input"
                           required
                           placeholder="Enter task title"
                           autofocus>
                </div>
                
                <div class="form-group">
                    <label for="teamTaskDescription" class="form-label">
                        Description
                    </label>
                    <textarea id="teamTaskDescription" 
                              name="description" 
                              class="form-input form-textarea"
                              rows="4"
                              placeholder="Enter task description (optional)"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="teamTaskDeadline" class="form-label">
                        Deadline <span class="required">*</span>
                    </label>
                    <input type="date" 
                           id="teamTaskDeadline" 
                           name="deadline" 
                           class="form-input"
                           required
                           min="{{ date('Y-m-d') }}">
                </div>
                
                <div class="form-group">
                    <label for="teamTaskPriority" class="form-label">
                        Priority <span class="required">*</span>
                    </label>
                    <select id="teamTaskPriority" 
                            name="priority" 
                            class="form-input form-select"
                            required>
                        <option value="LOW">Low</option>
                        <option value="MEDIUM" selected>Medium</option>
                        <option value="HIGH">High</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="teamTaskMember" class="form-label">
                        Assign To <span class="required">*</span>
                    </label>
                    <select id="teamTaskMember" 
                            name="member_id" 
                            class="form-input form-select"
                            required>
                        <option value="">Select a member</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="task-modal-footer">
            <button type="button" class="btn-secondary" onclick="closeCreateTeamTaskModal()">Cancel</button>
            <button type="button" class="btn-primary" onclick="submitCreateTeamTask()">
                <i class="fas fa-plus"></i>
                <span>Create Task</span>
            </button>
        </div>
    </div>
</div>

<style>
    .task-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.2s ease-out;
    }

    .task-modal.show {
        display: flex;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .task-modal-content {
        background-color: var(--bg-secondary);
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        animation: slideUp 0.3s ease-out;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .task-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 28px;
        border-bottom: 1px solid var(--border-color);
        background: linear-gradient(to bottom, var(--bg-secondary), var(--bg-tertiary));
    }

    .task-modal-header h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.02em;
    }

    .task-modal-close {
        background: rgba(0, 0, 0, 0.05);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        color: var(--text-secondary);
        padding: 8px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        width: 36px;
        height: 36px;
    }

    .task-modal-close:hover {
        background: rgba(0, 0, 0, 0.1);
        color: var(--text-primary);
        transform: rotate(90deg);
    }

    .task-modal-body {
        padding: 28px;
        overflow-y: auto;
        flex: 1;
    }

    .task-modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .task-modal-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .task-modal-body::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 4px;
    }

    .task-modal-body::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.3);
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--text-primary);
    }

    .required {
        color: #ef4444;
        margin-left: 4px;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        background-color: var(--bg-color);
        color: var(--text-primary);
        font-size: 1rem;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(106, 27, 154, 0.1);
    }

    .form-input::placeholder {
        color: var(--text-muted);
    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
        font-family: inherit;
    }

    .form-select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        padding-right: 40px;
    }

    .task-modal-footer {
        display: flex;
        justify-content: flex-end;
        padding: 20px 28px;
        border-top: 1px solid var(--border-color);
        gap: 12px;
        background-color: var(--bg-tertiary);
    }

    .btn-secondary,
    .btn-primary {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-secondary {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
        border: 2px solid var(--border-color);
    }

    .btn-secondary:hover {
        background-color: var(--hover-bg);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background: var(--accent-color);
        color: var(--text-primary);
        box-shadow: 0 4px 12px rgba(106, 27, 154, 0.3);
    }

    .btn-primary:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(106, 27, 154, 0.4);
    }

    .btn-primary:active {
        transform: translateY(0);
    }
</style>

<script>
    let currentTeamId = null;
    let teamMembers = [];

    (function () {
        function openCreateTeamTaskModal(teamId) {
            currentTeamId = teamId;
            const modal = document.getElementById('createTeamTaskModal');
            if (modal) {
                document.getElementById('createTeamTaskForm').reset();
                
                const deadlineInput = document.getElementById('teamTaskDeadline');
                if (deadlineInput) {
                    deadlineInput.value = new Date().toISOString().split('T')[0];
                }

                loadTeamMembers(teamId);

                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
            } else {
                console.error('Modal createTeamTaskModal not found');
            }
        }

        window.openCreateTeamTaskModal = openCreateTeamTaskModal;
    })();

    function closeCreateTeamTaskModal() {
        const modal = document.getElementById('createTeamTaskModal');
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
            document.getElementById('createTeamTaskForm').reset();
            currentTeamId = null;
            teamMembers = [];
        }
    }

    async function loadTeamMembers(teamId) {
        const memberSelect = document.getElementById('teamTaskMember');
        if (!memberSelect) {
            console.error('Member select element not found');
            return;
        }

        try {
            const apiToken = getApiToken();
            if (!apiToken) {
                alert('Please login to create team task');
                closeCreateTeamTaskModal();
                return;
            }

            const response = await fetch(`/api/v1/team/detail/${teamId}`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            console.log('Team detail response:', result);
            
            if (result.status === 200 && result.data) {
                memberSelect.innerHTML = '<option value="">Select a member</option>';
                
                const teamData = result.data;
                teamMembers = Array.isArray(teamData.teamMembers) ? teamData.teamMembers : [];
                
                if (teamMembers.length === 0) {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No members available';
                    option.disabled = true;
                    memberSelect.appendChild(option);
                    return;
                }
                
                teamMembers.forEach(member => {
                    if (!member || !member.id) {
                        console.warn('Invalid member data:', member);
                        return;
                    }
                    
                    const option = document.createElement('option');
                    option.value = member.id;
                    
                    let userName = 'Unknown';
                    let userEmail = '';
                    
                    if (member.user) {
                        userName = member.user.name || member.user.fullName || member.user.email || 'Unknown';
                        userEmail = member.user.email || '';
                    } else if (member.userId) {
                        userName = `User ${member.userId}`;
                    }
                    
                    option.textContent = userEmail ? `${userName} (${userEmail})` : userName;
                    memberSelect.appendChild(option);
                });
            } else {
                throw new Error(result.message || 'Invalid response format');
            }
        } catch (error) {
            console.error('Error loading team members:', error);
            const errorMsg = error.message || 'Error loading team members';
            alert(errorMsg);
            
            const memberSelect = document.getElementById('teamTaskMember');
            if (memberSelect) {
                memberSelect.innerHTML = '<option value="">Error loading members</option>';
            }
        }
    }

    async function submitCreateTeamTask() {
        if (!currentTeamId) {
            alert('Team ID is missing');
            return;
        }

        const form = document.getElementById('createTeamTaskForm');
        if (!form) {
            alert('Form not found');
            return;
        }

        const formData = new FormData(form);

        const title = (formData.get('title') || '').trim();
        const description = (formData.get('description') || '').trim() || null;
        const deadline = formData.get('deadline');
        const priority = formData.get('priority') || 'MEDIUM';
        const memberId = formData.get('member_id');

        if (!title) {
            alert('Please enter a task title');
            document.getElementById('teamTaskTitle')?.focus();
            return;
        }

        if (!deadline) {
            alert('Please select a deadline');
            document.getElementById('teamTaskDeadline')?.focus();
            return;
        }

        if (!memberId) {
            alert('Please select a team member to assign this task');
            document.getElementById('teamTaskMember')?.focus();
            return;
        }

        try {
            const apiToken = getApiToken();
            if (!apiToken) {
                alert('Please login to create team task');
                closeCreateTeamTaskModal();
                return;
            }

            const data = {
                team_id: parseInt(currentTeamId),
                title: title,
                description: description,
                deadline: deadline,
                priority: priority.toUpperCase(),
                member_id: parseInt(memberId)
            };

            console.log('Submitting team task:', data);

            const response = await fetch('/api/v1/team-task', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error('HTTP error response:', response.status, errorText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            console.log('Create task response:', result);

            if (result.status === 200 || result.status === 201) {
                alert('Task created successfully!');
                closeCreateTeamTaskModal();
                
                if (typeof loadTeamTasks === 'function') {
                    loadTeamTasks();
                } else if (typeof loadTasks === 'function') {
                    loadTasks();
                } else if (typeof window.location !== 'undefined') {
                    window.location.reload();
                }
            } else {
                const errorMsg = result.message || result.error || 'Error creating task';
                console.error('API error:', result);
                alert(errorMsg);
            }
        } catch (error) {
            console.error('Error creating team task:', error);
            const errorMsg = error.message || 'Error creating task. Please try again.';
            alert(errorMsg);
        }
    }

    document.addEventListener('click', function (e) {
        const modal = document.getElementById('createTeamTaskModal');
        if (modal && e.target === modal) {
            closeCreateTeamTaskModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('createTeamTaskModal');
            if (modal && modal.classList.contains('show')) {
                closeCreateTeamTaskModal();
            }
        }
    });

    function getApiToken() {
        if (typeof window.getApiToken === 'function' && window.getApiToken !== getApiToken) {
            return window.getApiToken();
        }
        return localStorage.getItem('access_token');
    }
</script>
