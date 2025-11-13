<div class="task-detail-modal" id="teamTaskDetailModal">
    <div class="task-detail-modal-content">
        <div class="task-detail-modal-header">
            <h3>Team Task Detail</h3>
            <button type="button" class="task-detail-modal-close" onclick="closeTeamTaskDetailModal()" aria-label="Close">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="task-detail-modal-body">
            <div id="teamTaskDetailLoading" class="task-detail-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading task...</p>
            </div>
            
            <div id="teamTaskDetailContent" style="display: none;">
                <div class="task-detail-field">
                    <label class="task-detail-label">Title</label>
                    <div class="task-detail-value" id="teamTaskDetailTitle">-</div>
                </div>
                
                <div class="task-detail-field">
                    <label class="task-detail-label">Status</label>
                    <div class="task-detail-value">
                        <span id="teamTaskDetailStatus" class="task-status-badge"></span>
                    </div>
                </div>
                
                <div class="task-detail-field">
                    <label class="task-detail-label">Priority</label>
                    <div class="task-detail-value">
                        <span id="teamTaskDetailPriority" class="task-priority-badge"></span>
                    </div>
                </div>
                
                <div class="task-detail-field">
                    <label class="task-detail-label">Deadline</label>
                    <div class="task-detail-value" id="teamTaskDetailDeadline">-</div>
                </div>
                
                <div class="task-detail-field">
                    <label class="task-detail-label">Assigned To</label>
                    <div class="task-detail-value" id="teamTaskDetailAssignee">-</div>
                </div>
                
                <div class="task-detail-field">
                    <label class="task-detail-label">Created By</label>
                    <div class="task-detail-value" id="teamTaskDetailCreatedBy">-</div>
                </div>
                
                <div class="task-detail-field">
                    <label class="task-detail-label">Description</label>
                    <div class="task-detail-value task-detail-description" id="teamTaskDetailDescription">-</div>
                </div>
            </div>
        </div>
        <div class="task-detail-modal-footer">
            <button type="button" class="btn-secondary" onclick="closeTeamTaskDetailModal()">Close</button>
            <button type="button" class="btn-primary" id="teamTaskDetailEditBtn" onclick="editTeamTask()" style="display: none;">
                <i class="fas fa-edit"></i> Edit
            </button>
        </div>
    </div>
</div>

<style>
    .task-detail-modal {
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

    .task-detail-modal.show {
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

    .task-detail-modal-content {
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

    .task-detail-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 28px;
        border-bottom: 1px solid var(--border-color);
        background: linear-gradient(to bottom, var(--bg-secondary), var(--bg-tertiary));
    }

    .task-detail-modal-header h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.02em;
    }

    .task-detail-modal-close {
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

    .task-detail-modal-close:hover {
        background: rgba(0, 0, 0, 0.1);
        color: var(--text-primary);
        transform: rotate(90deg);
    }

    .task-detail-modal-body {
        padding: 28px;
        overflow-y: auto;
        flex: 1;
    }

    .task-detail-modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .task-detail-modal-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .task-detail-modal-body::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 4px;
    }

    .task-detail-modal-body::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.3);
    }

    .task-detail-loading {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
    }

    .task-detail-loading i {
        font-size: 2rem;
        margin-bottom: 15px;
        color: var(--accent-color);
    }

    .task-detail-field {
        margin-bottom: 24px;
    }

    .task-detail-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .task-detail-value {
        font-size: 1rem;
        color: var(--text-primary);
        padding: 12px 16px;
        background-color: var(--bg-tertiary);
        border-radius: 10px;
        border: 1px solid var(--border-color);
        min-height: 20px;
        word-wrap: break-word;
    }

    .task-detail-description {
        min-height: 80px;
        white-space: pre-wrap;
        line-height: 1.6;
    }

    .task-status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .task-status-badge.completed {
        background-color: rgba(34, 197, 94, 0.2);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .task-status-badge.pending {
        background-color: rgba(251, 191, 36, 0.2);
        color: #fbbf24;
        border: 1px solid rgba(251, 191, 36, 0.3);
    }

    .task-category-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .task-priority-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .task-priority-badge.high {
        background-color: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .task-priority-badge.medium {
        background-color: rgba(251, 146, 60, 0.2);
        color: #fb923c;
        border: 1px solid rgba(251, 146, 60, 0.3);
    }

    .task-priority-badge.low {
        background-color: rgba(59, 130, 246, 0.2);
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .task-detail-modal-footer {
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
</style>

<script>
    (function() {
        let currentTeamTaskId = null;
        let currentTeamId = null;

        // Define functions immediately and make them globally available
        function openTeamTaskDetailModal(taskId, teamId) {
            currentTeamTaskId = taskId;
            currentTeamId = teamId;
            const modal = document.getElementById('teamTaskDetailModal');
            if (modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
                loadTeamTaskDetail(taskId, teamId);
            } else {
                console.error('Team task detail modal not found');
            }
        }

        function closeTeamTaskDetailModal() {
            const modal = document.getElementById('teamTaskDetailModal');
            if (modal) {
                modal.classList.remove('show');
                document.body.style.overflow = 'auto';
            }
            currentTeamTaskId = null;
            currentTeamId = null;
        }

        // Make functions globally available immediately (before DOM is ready)
        window.openTeamTaskDetailModal = openTeamTaskDetailModal;
        window.closeTeamTaskDetailModal = closeTeamTaskDetailModal;

        async function loadTeamTaskDetail(taskId, teamId) {
            const loadingDiv = document.getElementById('teamTaskDetailLoading');
            const contentDiv = document.getElementById('teamTaskDetailContent');
            
            loadingDiv.style.display = 'block';
            contentDiv.style.display = 'none';

            try {
                let apiToken = null;
                if (typeof window.getApiToken === 'function') {
                    apiToken = window.getApiToken();
                } else {
                    apiToken = localStorage.getItem('access_token');
                }
                
                if (!apiToken) {
                    alert('Please login to view task details');
                    closeTeamTaskDetailModal();
                    return;
                }

                const response = await fetch(`/api/v1/team-task/by-team/${teamId}`, {
                    headers: {
                        'Authorization': `Bearer ${apiToken}`,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                
                if (response.ok && result.status === 200) {
                    const tasks = result.data || [];
                    const task = tasks.find(t => t.id === taskId);
                    
                    if (task) {
                        displayTeamTaskDetail(task);
                    } else {
                        alert('Task not found');
                        closeTeamTaskDetailModal();
                    }
                } else {
                    alert(result.message || 'Error loading task detail');
                    closeTeamTaskDetailModal();
                }
            } catch (error) {
                console.error('Error loading task detail:', error);
                alert('Error loading task detail');
                closeTeamTaskDetailModal();
            } finally {
                loadingDiv.style.display = 'none';
                contentDiv.style.display = 'block';
            }
        }

        function displayTeamTaskDetail(task) {
            document.getElementById('teamTaskDetailTitle').textContent = task.title || 'Untitled';

            const statusBadge = document.getElementById('teamTaskDetailStatus');
            if (task.isCompleted) {
                statusBadge.textContent = 'Completed';
                statusBadge.className = 'task-status-badge completed';
            } else {
                statusBadge.textContent = 'Pending';
                statusBadge.className = 'task-status-badge pending';
            }

            const priorityBadge = document.getElementById('teamTaskDetailPriority');
            const priority = (task.priority || 'MEDIUM').toUpperCase();
            priorityBadge.textContent = priority === 'HIGH' ? 'High' : priority === 'MEDIUM' ? 'Medium' : 'Low';
            priorityBadge.className = `task-priority-badge ${priority.toLowerCase()}`;

            if (task.deadline) {
                const deadline = new Date(task.deadline);
                const formattedDate = `${deadline.getDate()}/${deadline.getMonth() + 1}/${deadline.getFullYear()}`;
                document.getElementById('teamTaskDetailDeadline').textContent = formattedDate;
            } else {
                document.getElementById('teamTaskDetailDeadline').textContent = 'No deadline';
            }

            // Assigned To - try multiple possible structures
            let assigneeName = 'Not assigned';
            if (task.teamMember && task.teamMember.user) {
                const user = task.teamMember.user;
                assigneeName = user.name || user.fullName || user.email || 'Unknown';
            } else if (task.assignedTo) {
                assigneeName = task.assignedTo.name || task.assignedTo.fullName || task.assignedTo.email || 'Unknown';
            } else if (task.memberId && typeof allMembers !== 'undefined' && Array.isArray(allMembers)) {
                const member = allMembers.find(m => m.id === task.memberId);
                if (member && member.user) {
                    assigneeName = member.user.name || member.user.fullName || member.user.email || 'Unknown';
                }
            }
            document.getElementById('teamTaskDetailAssignee').textContent = assigneeName;

            // Created By - try multiple possible structures
            let createdByName = '-';
            if (task.created && task.created.by) {
                createdByName = task.created.by;
            } else if (task.createdBy) {
                createdByName = task.createdBy;
            } else if (task.created_by) {
                createdByName = task.created_by;
            } else if (task.created && typeof task.created === 'string') {
                createdByName = task.created;
            }
            document.getElementById('teamTaskDetailCreatedBy').textContent = createdByName;

            const description = task.description || 'No description';
            document.getElementById('teamTaskDetailDescription').textContent = description;

            const editBtn = document.getElementById('teamTaskDetailEditBtn');
            if (typeof isLeader !== 'undefined' && isLeader) {
                editBtn.style.display = 'inline-flex';
            } else if (typeof currentUserMember !== 'undefined' && task.memberId === currentUserMember?.id) {
                editBtn.style.display = 'inline-flex';
            } else {
                editBtn.style.display = 'none';
            }
        }

        function editTeamTask() {
            if (currentTeamTaskId && currentTeamId) {
                closeTeamTaskDetailModal();
                setTimeout(() => {
                    window.location.href = `/group/${currentTeamId}/task/${currentTeamTaskId}`;
                }, 300);
            }
        }

        // Close modal when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('teamTaskDetailModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeTeamTaskDetailModal();
                    }
                });
            }
        });

        // Close with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('teamTaskDetailModal');
                if (modal && modal.classList.contains('show')) {
                    closeTeamTaskDetailModal();
                }
            }
        });
    })();
</script>

