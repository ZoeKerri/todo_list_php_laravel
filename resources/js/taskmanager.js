class TaskManager {

    constructor() {
        this.apiBaseUrl = '/api/v1';
        this.token = this.getApiToken();
        if (!this.token) {
            console.error('TaskManager Error: No API token found. Redirecting to login.');
        }
        this.taskModal = document.getElementById('task-modal');
        this.taskModalTitle = document.getElementById('task-modal-title');
        this.taskModalForm = document.getElementById('task-modal-form');
        this.taskIdInput = document.getElementById('task-id');
        this.createBtn = document.getElementById('task-modal-create-btn');
        this.updateBtn = document.getElementById('task-modal-update-btn');
        this.deleteBtn = document.getElementById('task-modal-delete-btn');
        this.closeBtn = document.getElementById('task-modal-close');
        this.cancelBtn = document.getElementById('task-modal-cancel-btn');
        this.overlay = document.getElementById('task-modal-overlay');
        this.addTaskFab = document.getElementById('add-task-btn');
        this.taskListContainer = document.getElementById('task-list-container');
        this.addCategoryBtn = document.getElementById('add-category-btn');
        this.categoryModal = document.getElementById('category-modal');
        this.categoryModalForm = document.getElementById('category-modal-form');
        this.categoryModalClose = document.getElementById('category-modal-close');
        this.categoryModalOverlay = document.getElementById('category-modal-overlay');
        this.categoryMap = new Map();
        this.categorySelect = document.getElementById('task-category');
    }

    escapeHtml(text) {
        if (text === null || text === undefined) return '';
        return text.toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    getApiToken() {
        const token = localStorage.getItem('access_token');
        if (token) {
            console.log('Using token from localStorage (access_token)');
            return token;
        }
        console.error('TaskManager Error: No API token found in localStorage. Please log in.');

        return null;
    }

    async fetchCategories() {
        try {
            const response = await this.apiClient('GET', '/category');
            if (response.success && response.data.data) {
                this.categoryMap.clear();
                let optionsHtml = '<option value="">Select Category</option>';
                response.data.data.forEach(category => {
                    this.categoryMap.set(category.id, category.name);
                    optionsHtml += `<option value="${category.id}">${this.escapeHtml(category.name)}</option>`;
                });
                if (this.categorySelect) {
                    this.categorySelect.innerHTML = optionsHtml;
                }
            }
        } catch (error) {
            console.error("Failed to load categories", error);
        }
    }

    async apiClient(method, endpoint, body = null) {
        const url = `${this.apiBaseUrl}${endpoint}`;

        const headers = {
            'Authorization': `Bearer ${this.token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        };

        const options = {
            method: method.toUpperCase(),
            headers: headers
        };

        if (body) {
            options.body = JSON.stringify(body);
        }

        try {
            const response = await fetch(url, options);
            if (!response.ok) {
                if (response.status === 401) {
                    console.error('API Error: Token unauthorized or expired.');
                    alert('Your session has expired. Please log in again.');
                }
                const errorData = await response.json();
                console.error(`API Error ${response.status}:`, errorData);
                alert(`Error: ${errorData.message || 'Something went wrong'}`);
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            if (response.status === 204 || response.headers.get("content-length") === "0") {
                return { success: true, data: null };
            }
            return { success: true, data: await response.json() };
        } catch (error) {
            console.error('Fetch Error:', error);
            return { success: false, error: error };
        }
    }

    init() {
        document.addEventListener('DOMContentLoaded', async () => {
            this.addTaskFab?.addEventListener('click', () => {
                this.openModalForCreate();
            });
            this.taskModalForm?.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleFormSubmit();
            });
            this.closeBtn?.addEventListener('click', () => this.closeModal());
            this.overlay?.addEventListener('click', () => this.closeModal());
            this.cancelBtn?.addEventListener('click', () => this.closeModal());
            this.taskListContainer?.addEventListener('click', (e) => {
                const taskItem = e.target.closest('.task-item');
                if (!taskItem) return;
                const taskId = taskItem.dataset.taskId;
                if (e.target.closest('.edit-task-btn')) {
                    e.stopPropagation();
                    this.openModalForEdit(taskId);
                }
                else if (e.target.closest('[data-action="edit"]')) {
                    this.openModalForEdit(taskId);
                }
                else if (e.target.closest('.task-complete-cb')) {
                    const isChecked = e.target.checked;
                    this.toggleTaskStatus(taskId, isChecked);
                }
            });
            this.deleteBtn?.addEventListener('click', () => {
                const taskId = this.taskIdInput.value;
                if (taskId && confirm('Are you sure you want to delete this task?')) {
                    this.deleteTask(taskId);
                }
            });
            this.addCategoryBtn?.addEventListener('click', () => {
                this.openCategoryModal();
            });
            this.categoryModalForm?.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleCategoryFormSubmit();
            });
            this.categoryModalClose?.addEventListener('click', () => this.closeCategoryModal());
            this.categoryModalOverlay?.addEventListener('click', () => this.closeCategoryModal());
            await this.fetchCategories();
            await this.fetchTasks();
        });
    }

    openModal() { this.taskModal?.classList.remove('hidden'); }

    closeModal() { this.taskModal?.classList.add('hidden'); }

    openModalForCreate() {
        this.taskModalForm.reset();
        this.taskIdInput.value = '';
        this.taskModalTitle.textContent = 'Add New Task';
        this.createBtn.classList.remove('hidden');
        this.updateBtn.classList.add('hidden');
        this.deleteBtn.classList.add('hidden');
        this.openModal();
    }

    async openModalForEdit(taskId) {
        this.taskModalForm.reset();
        this.taskModalTitle.textContent = 'Loading Task Details...';
        this.openModal();
        const response = await this.apiClient('GET', `/task/${taskId}`);
        if (response.success && response.data.data) {
            const task = response.data.data;
            this.taskModalTitle.textContent = 'Edit Task Details';
            this.taskIdInput.value = task.id;
            document.getElementById('task-title').value = task.title || '';
            document.getElementById('task-description').value = task.description || '';
            document.getElementById('task-date').value = task.dueDate || '';
            document.getElementById('task-time').value = task.notificationTime || '';
            document.getElementById('task-category').value = task.categoryId || '';
            if (task.priority) {
                priorityValue = task.priority.charAt(0).toUpperCase() + task.priority.slice(1);
            }
            document.getElementById('task-priority').value = priorityValue;
            this.createBtn.classList.add('hidden');
            this.updateBtn.classList.remove('hidden');
            this.deleteBtn.classList.remove('hidden');
        } else {
            alert('Failed to load task details.');
            this.closeModal();
        }
    }

    openCategoryModal() {
        this.categoryModalForm.reset();
        this.categoryModal?.classList.remove('hidden');
    }

    closeCategoryModal() {
        this.categoryModal?.classList.add('hidden');
    }

    handleFormSubmit() {
        const taskId = this.taskIdInput.value;
        const formData = {
            title: document.getElementById('task-title').value,
            description: document.getElementById('task-description').value,
            priority: document.getElementById('task-priority').value.toLowerCase(),
            due_date: document.getElementById('task-date').value || null,
            notification_time: document.getElementById('task-time').value || null,
            category_id: document.getElementById('task-category').value || null,
        };
        if (formData.category_id === '') formData.category_id = null;
        if (formData.due_date === '') formData.due_date = null;
        if (formData.notification_time === '') formData.notification_time = null;
        if (taskId) {
            const taskItem = this.taskListContainer.querySelector(`[data-task-id="${taskId}"]`);
            const checkbox = taskItem?.querySelector('.task-complete-cb');
            formData.completed = checkbox ? checkbox.checked : false;
            this.onUpdate(taskId, formData);
        } else {
            formData.completed = false;
            this.createTask(formData);
        }
    }

    async handleCategoryFormSubmit() {
        const categoryNameInput = document.getElementById('category-name');
        const categoryName = categoryNameInput.value.trim();
        if (!categoryName) {
            alert('Please enter a category name.');
            return;
        }
        console.log('Creating new category:', categoryName);
        const response = await this.apiClient('POST', '/category', { name: categoryName });
        if (response.success) {
            alert('Category created successfully!');
            this.closeCategoryModal();
            await this.fetchCategories();
        } else {
            console.error('Failed to create category.');
        }
    }

    async createTask(data) {
        console.log('Creating task...', data);
        const response = await this.apiClient('POST', '/task', data);
        if (response.success) {
            alert('Task created successfully!');
            this.closeModal();
            this.fetchTasks();
        }
    }

    async onUpdate(taskId, data) {
        console.log(`Updating task ${taskId}...`, data);
        const endpoint = `/task/${taskId}`;
        const response = await this.apiClient('PUT', endpoint, data);
        if (response.success) {
            if (data.title) {
                alert('Task updated successfully!');
                this.closeModal();
            }
            this.fetchTasks();
        } else {
            console.error('Update failed. API client returned an error.');
            if (data.hasOwnProperty('completed')) {
                alert('Failed to update task status. Please try again.');
                const taskItem = this.taskListContainer.querySelector(`[data-task-id="${taskId}"]`);
                const checkbox = taskItem?.querySelector('.task-complete-cb');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                }
            }
        }
    }

    async deleteTask(taskId) {
        console.log(`Deleting task ${taskId}...`);
        const response = await this.apiClient('DELETE', `/task/${taskId}`);
        if (response.success) {
            alert('Task deleted successfully!');
            this.closeModal();
            this.fetchTasks();
        }
    }

    async toggleTaskStatus(taskId, isCompleted) {
        const taskItem = this.taskListContainer.querySelector(`[data-task-id="${taskId}"]`);
        if (!taskItem) return;
        const taskTitleEl = taskItem.querySelector('p.text-lg');
        if (taskTitleEl) {
            taskTitleEl.classList.toggle('line-through', isCompleted);
            taskTitleEl.classList.toggle('text-gray-500', isCompleted);
        }
        const taskData = {
            title: taskItem.dataset.title,
            priority: taskItem.dataset.priority,
            completed: isCompleted,
            category_id: taskItem.dataset.categoryId || null,
            due_date: taskItem.dataset.dueDate || null,
            notification_time: taskItem.dataset.notificationTime || null,
        };
        if (taskData.category_id === '') taskData.category_id = null;
        if (taskData.due_date === '') taskData.due_date = null;
        if (taskData.notification_time === '') taskData.notification_time = null;
        await this.onUpdate(taskId, taskData);
    }

    async fetchTasks() {
        this.taskListContainer.innerHTML = `<p class="text-gray-400 p-8 text-center">Loading tasks...</p>`;
        const response = await this.apiClient('GET', '/task');
        if (response.success && response.data.data) {
            this.renderTasks(response.data.data);
        } else {
            this.taskListContainer.innerHTML = `<p class="text-red-400 p-8 text-center">Failed to load tasks. Please check your connection or log in again.</p>`;
        }
    }

    renderTasks(tasks) {
        if (tasks.length === 0) {
            this.taskListContainer.innerHTML = `
                <div class="text-center p-12 bg-gray-800 rounded-lg border border-gray-700">
                    <h4 class="text-xl text-white font-semibold">No tasks yet</h4>
                    <p class="text-gray-400 mt-2">Add your first task using the '+' button to get started!</p>
                </div>`;
            return;
        }
        let html = '';
        html += `<h3 class="text-xl font-semibold text-white mt-4 mb-3">${tasks.length} Tasks Found</h3>`;
        tasks.forEach(task => {
            const isCompleted = task.completed;
            const titleClass = isCompleted ? 'line-through text-gray-500' : 'text-white';
            const checked = isCompleted ? 'checked' : '';
            const categoryName = this.categoryMap.get(task.categoryId) || 'No Category';
            const taskTitle = task.title || '';
            const taskPriority = task.priority || 'medium';
            const taskCategoryId = task.categoryId || '';
            const taskDueDate = task.dueDate || '';
            const taskNotificationTime = task.notificationTime || '';
            html += `
            <div class="task-item flex items-center p-4 bg-gray-800 rounded-lg shadow-lg border border-gray-700 hover:border-purple-500 transition-all duration-200" 
                 data-task-id="${task.id}"
                 data-title="${this.escapeHtml(taskTitle)}" 
                 data-priority="${taskPriority}"
                 data-category-id="${taskCategoryId}"
                 data-due-date="${taskDueDate}" 
                 data-notification-time="${taskNotificationTime}"
                 >
                <div class="flex-shrink-0">
                    <input type="checkbox" 
                           class="task-complete-cb w-6 h-6 bg-gray-700 border-gray-600 rounded-md text-purple-600 focus:ring-purple-500 cursor-pointer"
                           data-task-id="${task.id}" ${checked}>
                </div>
                <div class="flex-grow mx-4 cursor-pointer min-w-0" data-action="edit" data-task-id="${task.id}">
                    <p class="text-lg font-medium ${titleClass} truncate">
                        ${this.escapeHtml(taskTitle)}
                    </p>
                </div>
                <div class="flex-shrink-0 hidden md:flex items-center gap-3">
                     <span class="px-3 py-1 text-xs font-medium rounded-full ${task.categoryId ? 'bg-blue-600 text-blue-100' : 'bg-gray-600 text-gray-100'}">
                        ${this.escapeHtml(categoryName)} 
                     </span>
                     <span class="text-sm text-gray-400">
                        ${task.dueDate ? new Date(task.dueDate).toLocaleDateString() : ''}
                     </span>
                </div>
                <div class="ml-auto pl-4 flex-shrink-0 flex items-center gap-2">
                    <button class="edit-task-btn p-2 text-gray-400 hover:text-white rounded-full hover:bg-gray-700 transition-colors" 
                            title="Edit Task" data-task-id="${task.id}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                </div>
            </div>
            `;
        });
        this.taskListContainer.innerHTML = html;
    }
}

const taskApp = new TaskManager();
taskApp.init();