<div class="task-modal" id="createPersonalTaskModal">
    <div class="task-modal-content">
        <div class="task-modal-header">
            <h3>Create New Task</h3>
            <button type="button" class="task-modal-close" onclick="closeCreatePersonalTaskModal()" aria-label="Close">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="task-modal-body">
            <form id="createPersonalTaskForm">
                <div class="form-group">
                    <label for="personalTaskTitle" class="form-label">
                        Title <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="personalTaskTitle" 
                           name="title" 
                           class="form-input"
                           required
                           placeholder="Enter task title"
                           autofocus>
                </div>

                <div class="form-group">
                    <label for="personalTaskDescription" class="form-label">
                        Description
                    </label>
                    <textarea id="personalTaskDescription" 
                              name="description" 
                              class="form-input form-textarea"
                              rows="4"
                              placeholder="Enter task description (optional)"></textarea>
                </div>

                <div class="form-group">
                    <label for="personalTaskDueDate" class="form-label">
                        Due Date <span class="required">*</span>
                    </label>
                    <input type="date" 
                           id="personalTaskDueDate" 
                           name="due_date" 
                           class="form-input"
                           required
                           min="{{ date('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Category <span class="required">*</span>
                    </label>
                    <div class="category-selection-container">
                        <div id="categoryListContainer" class="category-list-container">
                        </div>
                    </div>
                    <input type="hidden" id="selectedCategoryIds" name="category_ids" value="">
                </div>

                <div class="form-group">
                    <label for="personalTaskPriority" class="form-label">
                        Priority
                    </label>
                    <select id="personalTaskPriority" 
                            name="priority" 
                            class="form-input form-select">
                        <option value="LOW">Low</option>
                        <option value="MEDIUM" selected>Medium</option>
                        <option value="HIGH">High</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="personalTaskNotificationTime" class="form-label">
                        Notification Time (optional)
                    </label>
                    <input type="time" 
                           id="personalTaskNotificationTime" 
                           name="notification_time"
                           class="form-input">
                </div>
            </form>
        </div>
        <div class="task-modal-footer">
            <button type="button" class="btn-secondary" onclick="closeCreatePersonalTaskModal()">Cancel</button>
            <button type="button" class="btn-primary" onclick="submitCreatePersonalTask()">
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

    .category-selection-container {
        position: relative;
    }

    .category-list-container {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding: 10px 5px;
        margin: -10px -5px;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .category-list-container::-webkit-scrollbar {
        display: none;
    }

    .category-chip {
        display: inline-flex;
        align-items: center;
        padding: 10px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
        white-space: nowrap;
        border: 2px solid var(--border-color);
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }

    .category-chip:hover {
        background-color: var(--hover-bg);
        border-color: var(--accent-color);
    }

    .category-chip.selected {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
        color: var(--text-primary);
    }

    .category-chip.add-category {
        background-color: transparent;
        border: 2px dashed var(--accent-color);
        color: var(--accent-color);
    }

    .category-chip.add-category:hover {
        background-color: var(--accent-color);
        color: var(--text-primary);
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
    (function () {
        function openCreatePersonalTaskModal() {
            const modal = document.getElementById('createPersonalTaskModal');
            if (modal) {
                modal.classList.add('show');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                
                const dueDateInput = document.getElementById('personalTaskDueDate');
                if (dueDateInput) {
                    dueDateInput.value = new Date().toISOString().split('T')[0];
                }
                
                if (typeof loadCategoriesForPersonalTask === 'function') {
                    loadCategoriesForPersonalTask();
                }
                
                const titleInput = document.getElementById('personalTaskTitle');
                if (titleInput) {
                    setTimeout(() => titleInput.focus(), 100);
                }
            } else {
                console.error('Modal createPersonalTaskModal not found');
            }
        }
        window.openCreatePersonalTaskModal = openCreatePersonalTaskModal;
    })();

    function closeCreatePersonalTaskModal() {
        const modal = document.getElementById('createPersonalTaskModal');
        if (modal) {
            modal.classList.remove('show');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            const form = document.getElementById('createPersonalTaskForm');
            if (form) form.reset();
            personalTaskSelectedCategoryIds = [];
            const chips = document.querySelectorAll('#categoryListContainer .category-chip');
            chips.forEach(chip => chip.classList.remove('selected'));
            const selectedIdsInput = document.getElementById('selectedCategoryIds');
            if (selectedIdsInput) selectedIdsInput.value = '';
        }
    }

    window.closeCreatePersonalTaskModal = closeCreatePersonalTaskModal;

    let personalTaskSelectedCategoryIds = [];

    async function loadCategoriesForPersonalTask() {
        const categoryContainer = document.getElementById('categoryListContainer');
        if (!categoryContainer) return;

        try {
            const apiToken = getApiToken();
            const response = await fetch('/api/v1/category', {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();
            if (response.ok && result.status === 200) {
                const categories = result.data || [];
                categoryContainer.innerHTML = '';

                const addCategoryChip = document.createElement('div');
                addCategoryChip.className = 'category-chip add-category';
                addCategoryChip.innerHTML = '<i class="fas fa-plus"></i> <span>Create New</span>';
                addCategoryChip.onclick = () => window.openCreateCategoryModal();
                categoryContainer.appendChild(addCategoryChip);

                categories.forEach(category => {
                    const chip = document.createElement('div');
                    chip.className = 'category-chip';
                    chip.dataset.categoryId = category.id;
                    chip.textContent = category.name || category.title || 'Unnamed';
                    
                    if (category.color) {
                        chip.dataset.originalColor = category.color;
                        chip.dataset.originalBorderColor = category.color;
                        const contrastColor = getContrastColor(category.color);
                        chip.dataset.originalTextColor = contrastColor;
                        chip.style.backgroundColor = category.color;
                        chip.style.color = contrastColor;
                        chip.style.borderColor = category.color;
                    } else {
                        chip.dataset.originalColor = 'var(--bg-secondary)';
                        chip.dataset.originalTextColor = 'var(--text-primary)';
                        chip.dataset.originalBorderColor = 'var(--border-color)';
                        chip.style.backgroundColor = 'var(--bg-secondary)';
                        chip.style.color = 'var(--text-primary)';
                        chip.style.borderColor = 'var(--border-color)';
                    }
                    
                    chip.onclick = () => togglePersonalTaskCategory(category.id, chip);
                    categoryContainer.appendChild(chip);
                });
            }
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }

    function togglePersonalTaskCategory(categoryId, chipElement) {
        const index = personalTaskSelectedCategoryIds.indexOf(categoryId);
        if (index > -1) {
            personalTaskSelectedCategoryIds.splice(index, 1);
            chipElement.classList.remove('selected');
            
            const originalColor = chipElement.dataset.originalColor || 'var(--bg-secondary)';
            const originalTextColor = chipElement.dataset.originalTextColor || 'var(--text-primary)';
            const originalBorderColor = chipElement.dataset.originalBorderColor || 'var(--border-color)';
            
            chipElement.style.backgroundColor = originalColor;
            chipElement.style.color = originalTextColor;
            chipElement.style.borderColor = originalBorderColor;
        } else {
            personalTaskSelectedCategoryIds = [];
            document.querySelectorAll('#categoryListContainer .category-chip.selected').forEach(c => {
                c.classList.remove('selected');
                const originalColor = c.dataset.originalColor || 'var(--bg-secondary)';
                const originalTextColor = c.dataset.originalTextColor || 'var(--text-primary)';
                const originalBorderColor = c.dataset.originalBorderColor || 'var(--border-color)';
                c.style.backgroundColor = originalColor;
                c.style.color = originalTextColor;
                c.style.borderColor = originalBorderColor;
            });

            personalTaskSelectedCategoryIds.push(categoryId);
            chipElement.classList.add('selected');
            chipElement.style.backgroundColor = 'var(--accent-color)';
            chipElement.style.color = 'var(--text-primary)';
            chipElement.style.borderColor = 'var(--accent-color)';
        }
        
        document.getElementById('selectedCategoryIds').value = personalTaskSelectedCategoryIds.join(',');
    }

    function getContrastColor(hexColor) {
        if (!hexColor) return '#000000';
        
        if (hexColor.startsWith('var(')) {
            return '#ffffff';
        }
        
        let hex = hexColor.replace('#', '');
        if (hex.length === 3) {
            hex = hex.split('').map(char => char + char).join('');
        }
        
        const r = parseInt(hex.substr(0, 2), 16);
        const g = parseInt(hex.substr(2, 2), 16);
        const b = parseInt(hex.substr(4, 2), 16);
        
        const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
        
        return luminance > 0.5 ? '#000000' : '#ffffff';
    }

    async function submitCreatePersonalTask() {
        const form = document.getElementById('createPersonalTaskForm');
        const formData = new FormData(form);

        const selectedCategoryIds = personalTaskSelectedCategoryIds;
        const prioritySelect = document.getElementById('personalTaskPriority');
        const selectedPriority = prioritySelect ? prioritySelect.value : 'MEDIUM';

        if (selectedCategoryIds.length === 0) {
            alert('Please select at least one category');
            return;
        }

        const data = {
            title: formData.get('title'),
            description: formData.get('description') || null,
            due_date: formData.get('due_date'),
            category_id: parseInt(selectedCategoryIds[0]) || null,
            priority: selectedPriority,
            notification_time: formData.get('notification_time') || null,
        };

        if (!data.title || !data.due_date || !data.category_id) {
            alert('Please fill in all required fields');
            return;
        }

        try {
            const apiToken = getApiToken();
            const response = await fetch('/api/v1/task', {
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
                alert('Task created successfully!');
                closeCreatePersonalTaskModal();
                if (typeof loadTasks === 'function') {
                    loadTasks();
                }
            } else {
                alert(result.message || 'Error creating task');
            }
        } catch (error) {
            console.error('Error creating task:', error);
            alert('Error creating task');
        }
    }

    document.addEventListener('click', function (e) {
        const modal = document.getElementById('createPersonalTaskModal');
        if (modal && e.target === modal) {
            closeCreatePersonalTaskModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('createPersonalTaskModal');
            if (modal && modal.classList.contains('show')) {
                closeCreatePersonalTaskModal();
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
