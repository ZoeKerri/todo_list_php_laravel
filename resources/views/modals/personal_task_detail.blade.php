<div class="task-detail-modal" id="personalTaskDetailModal">
    <div class="task-detail-modal-content">
        <div class="task-detail-modal-header">
            <h3>Task Detail</h3>
            <button type="button" class="task-detail-modal-close" onclick="closePersonalTaskDetailModal()" aria-label="Close">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="task-detail-modal-body">
            <div id="taskDetailLoading" class="task-detail-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading task...</p>
            </div>
            
            <div id="taskDetailContent" style="display: none;">
                <!-- Title -->
                <div class="task-detail-field">
                    <label class="task-detail-label">Title</label>
                    <div class="task-detail-value" id="taskDetailTitle">-</div>
                </div>
                
                <!-- Status -->
                <div class="task-detail-field">
                    <label class="task-detail-label">Status</label>
                    <div class="task-detail-value">
                        <span id="taskDetailStatus" class="task-status-badge"></span>
                    </div>
                </div>
                
                <!-- Category -->
                <div class="task-detail-field">
                    <label class="task-detail-label">Category</label>
                    <div class="task-detail-value">
                        <span id="taskDetailCategory" class="task-category-badge">-</span>
                    </div>
                </div>
                
                <!-- Priority -->
                <div class="task-detail-field">
                    <label class="task-detail-label">Priority</label>
                    <div class="task-detail-value">
                        <span id="taskDetailPriority" class="task-priority-badge"></span>
                    </div>
                </div>
                
                <!-- Due Date -->
                <div class="task-detail-field">
                    <label class="task-detail-label">Due Date</label>
                    <div class="task-detail-value" id="taskDetailDueDate">-</div>
                </div>
                
                <!-- Notification Time -->
                <div class="task-detail-field" id="taskDetailNotificationField" style="display: none;">
                    <label class="task-detail-label">Notification Time</label>
                    <div class="task-detail-value" id="taskDetailNotificationTime">-</div>
                </div>
                
                <!-- Description -->
                <div class="task-detail-field">
                    <label class="task-detail-label">Description</label>
                    <div class="task-detail-value task-detail-description" id="taskDetailDescription">-</div>
                </div>
            </div>
        </div>
        <div class="task-detail-modal-footer">
            <button type="button" class="btn-secondary" onclick="closePersonalTaskDetailModal()">Close</button>
            <button type="button" class="btn-primary" id="taskDetailEditBtn" onclick="editPersonalTask()" style="display: none;">
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
    let currentTaskId = null;

    function openPersonalTaskDetailModal(taskId) {
        currentTaskId = taskId;
        const modal = document.getElementById('personalTaskDetailModal');
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            loadPersonalTaskDetail(taskId);
        }
    }

    function closePersonalTaskDetailModal() {
        const modal = document.getElementById('personalTaskDetailModal');
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
        currentTaskId = null;
    }

    async function loadPersonalTaskDetail(taskId) {
        const loadingDiv = document.getElementById('taskDetailLoading');
        const contentDiv = document.getElementById('taskDetailContent');
        
        loadingDiv.style.display = 'block';
        contentDiv.style.display = 'none';

        try {
            // Get API token - try multiple methods
            let apiToken = null;
            if (typeof getApiToken === 'function') {
                apiToken = getApiToken();
            } else if (typeof window.getApiToken === 'function') {
                apiToken = window.getApiToken();
            } else {
                // Fallback: try to get from localStorage or session
                apiToken = localStorage.getItem('access_token');
            }
            
            if (!apiToken) {
                alert('Please login to view task details');
                closePersonalTaskDetailModal();
                return;
            }

            const response = await fetch(`/api/v1/task/${taskId}`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            
            if (response.ok && result.status === 200) {
                const task = result.data;
                displayTaskDetail(task);
            } else {
                alert(result.message || 'Error loading task detail');
                closePersonalTaskDetailModal();
            }
        } catch (error) {
            console.error('Error loading task detail:', error);
            alert('Error loading task detail');
            closePersonalTaskDetailModal();
        } finally {
            loadingDiv.style.display = 'none';
            contentDiv.style.display = 'block';
        }
    }

    function updateCategoryBadge(badgeElement, name, color) {
        badgeElement.textContent = name;
        if (color) {
            const contrastColor = typeof getContrastColor === 'function' ? getContrastColor(color) : '#ffffff';
            badgeElement.style.backgroundColor = color;
            badgeElement.style.color = contrastColor;
            badgeElement.style.borderColor = color;
        } else {
            badgeElement.style.backgroundColor = 'var(--bg-secondary)';
            badgeElement.style.color = name === 'No category' ? 'var(--text-muted)' : 'var(--text-primary)';
            badgeElement.style.borderColor = 'var(--border-color)';
        }
    }

    async function loadCategoryForTask(categoryId) {
        try {
            let apiToken = null;
            if (typeof window.getApiToken === 'function') {
                apiToken = window.getApiToken();
            } else {
                apiToken = localStorage.getItem('access_token');
            }
            
            if (!apiToken) return null;
            
            const response = await fetch(`/api/v1/category`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            if (response.ok && result.status === 200) {
                const categories = result.data || [];
                return categories.find(c => c.id === categoryId) || null;
            }
        } catch (error) {
            console.error('Error loading category:', error);
        }
        return null;
    }

    function displayTaskDetail(task) {
        // Title
        document.getElementById('taskDetailTitle').textContent = task.title || 'Untitled';

        // Status
        const statusBadge = document.getElementById('taskDetailStatus');
        if (task.completed) {
            statusBadge.textContent = 'Completed';
            statusBadge.className = 'task-status-badge completed';
        } else {
            statusBadge.textContent = 'Pending';
            statusBadge.className = 'task-status-badge pending';
        }

        // Category
        const categoryBadge = document.getElementById('taskDetailCategory');
        if (task.category_id || task.categoryId) {
            const categoryId = task.category_id || task.categoryId;
            let categoryName = 'No category';
            let categoryColor = null;
            
            // First try: check if category data is in task object
            if (task.category) {
                categoryName = task.category.name || task.category.title || 'Unknown';
                categoryColor = task.category.color || null;
                updateCategoryBadge(categoryBadge, categoryName, categoryColor);
            }
            // Second try: get category from global categories array
            else if (typeof categories !== 'undefined' && Array.isArray(categories)) {
                const category = categories.find(c => c.id === categoryId);
                if (category) {
                    categoryName = category.name || category.title || 'Unknown';
                    categoryColor = category.color || null;
                }
                updateCategoryBadge(categoryBadge, categoryName, categoryColor);
            }
            // Third try: use helper functions if available
            else if (typeof getCategoryName === 'function') {
                categoryName = getCategoryName(categoryId);
                if (typeof getCategoryColor === 'function') {
                    categoryColor = getCategoryColor(categoryId);
                }
                updateCategoryBadge(categoryBadge, categoryName, categoryColor);
            }
            // Fourth try: load category from API
            else {
                loadCategoryForTask(categoryId).then(category => {
                    if (category) {
                        const name = category.name || category.title || 'Unknown';
                        const color = category.color || null;
                        updateCategoryBadge(categoryBadge, name, color);
                    } else {
                        updateCategoryBadge(categoryBadge, 'No category', null);
                    }
                }).catch(() => {
                    updateCategoryBadge(categoryBadge, 'No category', null);
                });
            }
        } else {
            updateCategoryBadge(categoryBadge, 'No category', null);
        }

        // Priority
        const priorityBadge = document.getElementById('taskDetailPriority');
        const priority = (task.priority || 'MEDIUM').toUpperCase();
        priorityBadge.textContent = priority === 'HIGH' ? 'High' : priority === 'MEDIUM' ? 'Medium' : 'Low';
        priorityBadge.className = `task-priority-badge ${priority.toLowerCase()}`;

        // Due Date
        if (task.due_date || task.dueDate) {
            const dueDate = new Date(task.due_date || task.dueDate);
            const formattedDate = `${dueDate.getDate()}/${dueDate.getMonth() + 1}/${dueDate.getFullYear()}`;
            document.getElementById('taskDetailDueDate').textContent = formattedDate;
        } else {
            document.getElementById('taskDetailDueDate').textContent = 'No due date';
        }

        // Notification Time
        if (task.notification_time) {
            document.getElementById('taskDetailNotificationTime').textContent = task.notification_time;
            document.getElementById('taskDetailNotificationField').style.display = 'block';
        } else {
            document.getElementById('taskDetailNotificationField').style.display = 'none';
        }

        // Description
        const description = task.description || 'No description';
        document.getElementById('taskDetailDescription').textContent = description;

        // Edit button (always show for now, can add permission check later)
        document.getElementById('taskDetailEditBtn').style.display = 'inline-flex';
    }

    function editPersonalTask() {
        if (currentTaskId && typeof openCreatePersonalTaskModal === 'function') {
            // Close detail modal
            closePersonalTaskDetailModal();
            
            // TODO: Load task data into edit modal
            // For now, just open the create modal
            // You can enhance this to load task data and populate the form
            setTimeout(() => {
                openCreatePersonalTaskModal();
            }, 300);
        }
    }

    // Make functions globally available immediately
    window.openPersonalTaskDetailModal = openPersonalTaskDetailModal;
    window.closePersonalTaskDetailModal = closePersonalTaskDetailModal;

    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('personalTaskDetailModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closePersonalTaskDetailModal();
                }
            });
        }
    });

    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('personalTaskDetailModal');
            if (modal && modal.classList.contains('show')) {
                closePersonalTaskDetailModal();
            }
        }
    });
</script>

