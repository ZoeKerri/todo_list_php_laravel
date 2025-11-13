@extends('layouts.app')

@section('title', 'Team Task Detail')

@push('styles')
<style>
    .task-detail-container {
        padding: 20px;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
        display: block;
    }
    
    .form-input {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: var(--bg-color);
        color: var(--text-primary);
        font-size: 16px;
        transition: border-color 0.3s ease;
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }
    
    .form-input:read-only {
        background-color: var(--item-bg-color);
        cursor: not-allowed;
        opacity: 0.7;
    }
    
    .form-textarea {
        min-height: 120px;
        resize: vertical;
        font-family: inherit;
    }
    
    .checkbox-container {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: var(--item-bg-color);
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    
    .checkbox-container:hover {
        background-color: var(--hover-bg);
    }
    
    .checkbox-container input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: var(--accent-color);
    }
    
    .checkbox-container input[type="checkbox"]:disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }
    
    .checkbox-label {
        font-size: 16px;
        font-weight: 500;
        color: var(--text-primary);
        cursor: pointer;
        user-select: none;
    }
    
    .select-wrapper {
        position: relative;
    }
    
    .form-select {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: var(--bg-color);
        color: var(--text-primary);
        font-size: 16px;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 12px;
        padding-right: 40px;
    }
    
    .form-select:disabled {
        background-color: var(--item-bg-color);
        cursor: not-allowed;
        opacity: 0.7;
    }
    
    .form-select:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }
    
    .date-input-wrapper {
        position: relative;
    }
    
    .date-input-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--accent-color);
        pointer-events: none;
    }
    
    .button-group {
        display: flex;
        gap: 16px;
        justify-content: center;
        margin-top: 32px;
        flex-wrap: wrap;
    }
    
    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 2px solid transparent;
    }
    
    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .btn-primary {
        background-color: var(--accent-color);
        color: white;
        border-color: var(--accent-color);
    }
    
    .btn-primary:hover:not(:disabled) {
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
    }
    
    .btn-danger {
        background-color: transparent;
        color: #ef4444;
        border-color: #ef4444;
    }
    
    .btn-danger:hover:not(:disabled) {
        background-color: #ef4444;
        color: white;
    }
    
    .loading {
        text-align: center;
        padding: 40px;
        color: var(--text-muted);
    }
    
    .error-message {
        background-color: #fee;
        color: #c33;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #fcc;
    }
    
    .success-message {
        background-color: #efe;
        color: #2c5;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #cfc;
    }
    
    .priority-high {
        color: #ef4444;
    }
    
    .priority-medium {
        color: #f97316;
    }
    
    .priority-low {
        color: #3b82f6;
    }
</style>
@endpush

@section('content')
<div class="task-detail-container">
    <div id="loadingState" class="loading">
        <i class="fas fa-spinner fa-spin"></i>
        <p>Loading task detail...</p>
    </div>
    
    <div id="contentArea" style="display: none;">
        <div id="errorMessage" class="error-message" style="display: none;"></div>
        <div id="successMessage" class="success-message" style="display: none;"></div>
        
        <form id="taskForm">
            <!-- Title -->
            <div class="form-group">
                <label class="form-label" for="taskTitle">Tiêu đề</label>
                <input 
                    type="text" 
                    id="taskTitle" 
                    class="form-input" 
                    placeholder="Nhập tiêu đề task"
                    readonly
                />
            </div>
            
            <!-- Completed Checkbox -->
            <div class="form-group">
                <div class="checkbox-container" id="completedContainer">
                    <input 
                        type="checkbox" 
                        id="taskCompleted" 
                        disabled
                    />
                    <label for="taskCompleted" class="checkbox-label">Đã hoàn thành</label>
                </div>
            </div>
            
            <!-- Priority -->
            <div class="form-group">
                <label class="form-label" for="taskPriority">Độ ưu tiên</label>
                <div class="select-wrapper">
                    <select id="taskPriority" class="form-select" disabled>
                        <option value="LOW">Thấp</option>
                        <option value="MEDIUM">Trung bình</option>
                        <option value="HIGH">Cao</option>
                    </select>
                </div>
            </div>
            
            <!-- Deadline -->
            <div class="form-group">
                <label class="form-label" for="taskDeadline">Ngày hết hạn</label>
                <div class="date-input-wrapper">
                    <input 
                        type="date" 
                        id="taskDeadline" 
                        class="form-input" 
                        readonly
                    />
                    <i class="fas fa-calendar-alt date-input-icon"></i>
                </div>
            </div>
            
            <!-- Description -->
            <div class="form-group">
                <label class="form-label" for="taskDescription">Mô tả</label>
                <textarea 
                    id="taskDescription" 
                    class="form-input form-textarea" 
                    placeholder="Nhập mô tả task"
                    readonly
                ></textarea>
            </div>
            
            <!-- Action Buttons -->
            <div class="button-group">
                <button type="button" id="updateBtn" class="btn btn-primary" style="display: none;">
                    <i class="fas fa-save"></i>
                    <span>Cập nhật</span>
                </button>
                <button type="button" id="deleteBtn" class="btn btn-danger" style="display: none;">
                    <i class="fas fa-trash"></i>
                    <span>Xóa</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const teamId = {{ $teamId ?? 'null' }};
    const taskId = {{ $taskId ?? 'null' }};
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
    let taskData = null;
    let isLeader = false;
    let canEdit = false;
    let currentUserMember = null;
    
    // Priority labels
    const priorityLabels = {
        'LOW': 'Thấp',
        'MEDIUM': 'Trung bình',
        'HIGH': 'Cao'
    };
    
    document.addEventListener('DOMContentLoaded', function() {
        if (!teamId || !taskId || !apiToken) {
            alert('Invalid team ID, task ID or not authenticated');
            window.location.href = '/group';
            return;
        }
        
        loadTaskDetail();
        setupEventListeners();
    });
    
    async function loadTaskDetail() {
        try {
            // Load team detail to check permissions
            const teamResponse = await fetch(`/api/v1/team/detail/${teamId}`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            const teamResult = await teamResponse.json();
            if (!teamResponse.ok || teamResult.status !== 200) {
                throw new Error(teamResult.message || 'Failed to load team');
            }
            
            const teamData = teamResult.data;
            isLeader = teamData.teamMembers.some(m => m.userId === userId && m.role === 'LEADER');
            currentUserMember = teamData.teamMembers.find(m => m.userId === userId);
            
            // Load task detail
            // Note: We need to get task from tasks list or create a show endpoint
            const tasksResponse = await fetch(`/api/v1/team-task/by-team/${teamId}`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            const tasksResult = await tasksResponse.json();
            if (!tasksResponse.ok || tasksResult.status !== 200) {
                throw new Error(tasksResult.message || 'Failed to load tasks');
            }
            
            const tasks = tasksResult.data || [];
            taskData = tasks.find(t => t.id === parseInt(taskId));
            
            if (!taskData) {
                throw new Error('Task not found');
            }
            
            // Check if user can edit this task
            canEdit = isLeader || (currentUserMember && taskData.memberId === currentUserMember.id);
            
            // Populate form
            populateForm();
            
            // Set permissions
            setPermissions();
            
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('contentArea').style.display = 'block';
        } catch (error) {
            console.error('Error loading task detail:', error);
            document.getElementById('loadingState').innerHTML = `
                <i class="fas fa-exclamation-triangle"></i>
                <p>Error loading task: ${error.message}</p>
                <button onclick="window.location.href='/group/${teamId}'" style="margin-top: 16px; padding: 8px 16px; border-radius: 4px; background-color: var(--accent-color); color: white; border: none; cursor: pointer;">
                    Quay lại
                </button>
            `;
        }
    }
    
    function populateForm() {
        document.getElementById('taskTitle').value = taskData.title || '';
        document.getElementById('taskCompleted').checked = taskData.isCompleted || false;
        document.getElementById('taskPriority').value = taskData.priority || 'MEDIUM';
        document.getElementById('taskDescription').value = taskData.description || '';
        
        // Format deadline for date input (YYYY-MM-DD)
        if (taskData.deadline) {
            const deadlineDate = new Date(taskData.deadline);
            const formattedDate = deadlineDate.toISOString().split('T')[0];
            document.getElementById('taskDeadline').value = formattedDate;
        }
    }
    
    function setPermissions() {
        const canEditTitle = isLeader;
        const canEditDeadline = isLeader;
        const canEditPriority = isLeader;
        const canEditDescription = canEdit;
        
        document.getElementById('taskTitle').readOnly = !canEditTitle;
        document.getElementById('taskDeadline').readOnly = !canEditDeadline;
        document.getElementById('taskPriority').disabled = !canEditPriority;
        document.getElementById('taskDescription').readOnly = !canEditDescription;
        document.getElementById('taskCompleted').disabled = !canEdit;
        
        // Show/hide buttons
        if (canEdit) {
            document.getElementById('updateBtn').style.display = 'inline-flex';
        }
        if (isLeader) {
            document.getElementById('deleteBtn').style.display = 'inline-flex';
        }
    }
    
    function setupEventListeners() {
        // Update button
        document.getElementById('updateBtn').addEventListener('click', handleUpdateTask);
        
        // Delete button
        document.getElementById('deleteBtn').addEventListener('click', handleDeleteTask);
    }
    
    async function handleUpdateTask() {
        try {
            const title = document.getElementById('taskTitle').value.trim();
            const isCompleted = document.getElementById('taskCompleted').checked;
            const priority = document.getElementById('taskPriority').value;
            const deadline = document.getElementById('taskDeadline').value;
            const description = document.getElementById('taskDescription').value.trim();
            
            if (!title) {
                showError('Vui lòng nhập tiêu đề task');
                return;
            }
            
            if (!deadline) {
                showError('Vui lòng chọn ngày hết hạn');
                return;
            }
            
            const updateData = {
                id: taskId,
                title: title,
                description: description || null,
                deadline: deadline,
                priority: priority,
                is_completed: isCompleted
            };
            
            const response = await fetch(`/api/v1/team-task/`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(updateData)
            });
            
            const result = await response.json();
            if (response.ok && result.status === 200) {
                showSuccess('Cập nhật task thành công!');
                taskData = result.data;
                populateForm();
                // Reload after 1 second
                setTimeout(() => {
                    window.location.href = `/group/${teamId}`;
                }, 1000);
            } else {
                showError(result.message || 'Có lỗi xảy ra khi cập nhật task');
            }
        } catch (error) {
            console.error('Error updating task:', error);
            showError('Có lỗi xảy ra khi cập nhật task: ' + error.message);
        }
    }
    
    async function handleDeleteTask() {
        if (!confirm('Bạn có chắc chắn muốn xóa task này?')) {
            return;
        }
        
        try {
            const response = await fetch(`/api/v1/team-task/${taskId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            if (response.ok && result.status === 200) {
                showSuccess('Xóa task thành công!');
                setTimeout(() => {
                    window.location.href = `/group/${teamId}`;
                }, 1000);
            } else {
                showError(result.message || 'Có lỗi xảy ra khi xóa task');
            }
        } catch (error) {
            console.error('Error deleting task:', error);
            showError('Có lỗi xảy ra khi xóa task: ' + error.message);
        }
    }
    
    function showError(message) {
        const errorDiv = document.getElementById('errorMessage');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 5000);
    }
    
    function showSuccess(message) {
        const successDiv = document.getElementById('successMessage');
        successDiv.textContent = message;
        successDiv.style.display = 'block';
        setTimeout(() => {
            successDiv.style.display = 'none';
        }, 3000);
    }
</script>
@endpush

