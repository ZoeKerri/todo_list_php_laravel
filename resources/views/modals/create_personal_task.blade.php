<div class="modal" id="createPersonalTaskModal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Tạo Task Mới</h3>
            <button class="modal-close" onclick="closeCreatePersonalTaskModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="createPersonalTaskForm">
                <div style="margin-bottom: 20px;">
                    <label
                        style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">
                        Title *
                    </label>
                    <input type="text" id="personalTaskTitle" name="title" required
                        style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem;"
                        placeholder="Enter task title">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">
                        Description
                    </label>
                    <textarea id="personalTaskDescription" name="description" rows="4"
                        style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem; resize: vertical;"
                        placeholder="Enter task description (optional)"></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label
                        style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">
                        Due Date *
                    </label>
                    <input type="date" id="personalTaskDueDate" name="due_date" required
                        style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem;"
                        min="{{ date('Y-m-d') }}">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 12px; font-weight: 600; color: var(--text-primary);">
                        Category *
                    </label>
                    <div style="position: relative;">
                        <div id="categoryListContainer" style="display: flex; gap: 10px; overflow-x: auto; padding: 10px 5px; margin: -10px -5px; scrollbar-width: none; -ms-overflow-style: none;">
                        </div>
                    </div>
                    <input type="hidden" id="selectedCategoryIds" name="category_ids" value="">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">
                        Priority
                    </label>
                    <select id="personalTaskPriority" name="priority"
                        style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem;">
                        <option value="LOW">Low</option>
                        <option value="MEDIUM" selected>Medium</option>
                        <option value="HIGH">High</option>
                    </select>
                </div>

                <div style="margin-bottom: 20px;">
                    <label
                        style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">
                        Notification Time (optional)
                    </label>
                    <input type="time" id="personalTaskNotificationTime" name="notification_time"
                        style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem;">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closeCreatePersonalTaskModal()">Cancel</button>
            <button type="button" class="btn-primary" style="background-color: var(--accent-color); color: white; border-radius: 8px; padding:10px; font-weight: bold;" onclick="submitCreatePersonalTask()">Create Task</button>
        </div>
    </div>
</div>

<script>
    // ----- FUNCTIONS FOR "CREATE PERSONAL TASK" MODAL -----

    (function () {
        function openCreatePersonalTaskModal() {
            const modal = document.getElementById('createPersonalTaskModal');
            if (modal) {
                // Show the modal
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                
                // Set default date to today
                const dueDateInput = document.getElementById('personalTaskDueDate');
                if (dueDateInput) {
                    dueDateInput.value = new Date().toISOString().split('T')[0];
                }
                
                // Load categories
                if (typeof loadCategoriesForPersonalTask === 'function') {
                    loadCategoriesForPersonalTask();
                }
                
                // Focus on the title input when modal opens
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
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            const form = document.getElementById('createPersonalTaskForm');
            if (form) form.reset();
            // Reset selected categories
            personalTaskSelectedCategoryIds = [];
            const chips = document.querySelectorAll('#categoryListContainer .category-chip');
            chips.forEach(chip => chip.classList.remove('selected'));
            const selectedIdsInput = document.getElementById('selectedCategoryIds');
            if (selectedIdsInput) selectedIdsInput.value = '';
        }
    }

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
                categoryContainer.innerHTML = ''; // Clear container

                // Add "Add Category" button first
                const addCategoryChip = document.createElement('div');
                addCategoryChip.className = 'category-chip add-category';
                addCategoryChip.innerHTML = '<i class="fas fa-plus"></i> <span>Tạo mới</span>';
                
                // CHÚ Ý: Dòng này sẽ gọi hàm openCreateCategoryModal() (nền tối)
                // mà bạn đã định nghĩa ở file kia.
                addCategoryChip.onclick = () => window.openCreateCategoryModal(); 
                
                categoryContainer.appendChild(addCategoryChip);

                // Add categories as chips
                categories.forEach(category => {
                    const chip = document.createElement('div');
                    chip.className = 'category-chip';
                    chip.dataset.categoryId = category.id;
                    chip.textContent = category.name || category.title || 'Unnamed';
                    
                    // Thêm style màu sắc cho chip
                    if (category.color) {
                        chip.style.backgroundColor = category.color;
                        // Tính toán màu chữ tương phản (nếu cần)
                        // chip.style.color = getContrastColor(category.color); 
                        // Bạn cần hàm getContrastColor từ file kia nếu muốn dùng
                        chip.style.color = 'white'; // Tạm thời để màu trắng
                        chip.style.borderColor = category.color;
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
            
            // Reset style khi bỏ chọn
            chipElement.style.backgroundColor = chipElement.dataset.originalColor || 'var(--bg-secondary)';
            chipElement.style.color = chipElement.dataset.originalTextColor || 'var(--text-primary)';
            chipElement.style.borderColor = chipElement.dataset.originalBorderColor || 'var(--border-color)';

        } else {
            // Chỉ cho chọn 1 category (nếu API chỉ nhận 1)
            // Bỏ chọn tất cả các chip khác
            personalTaskSelectedCategoryIds = [];
            document.querySelectorAll('#categoryListContainer .category-chip.selected').forEach(c => {
                c.classList.remove('selected');
                c.style.backgroundColor = 'var(--bg-secondary)'; // Màu nền mặc định
                c.style.color = 'var(--text-primary)';
                c.style.borderColor = 'var(--border-color)';
            });

            // Chọn chip mới
            personalTaskSelectedCategoryIds.push(categoryId);
            chipElement.classList.add('selected');

            // Áp dụng style 'selected' (thay vì dùng class .selected cho màu)
            chipElement.style.backgroundColor = 'var(--accent-color)';
            chipElement.style.color = 'white';
            chipElement.style.borderColor = 'var(--accent-color)';
        }
        
        // Update hidden input
        document.getElementById('selectedCategoryIds').value = personalTaskSelectedCategoryIds.join(',');
    }

    async function submitCreatePersonalTask() {
        const form = document.getElementById('createPersonalTaskForm');
        const formData = new FormData(form);

        const selectedCategoryIds = personalTaskSelectedCategoryIds;
        const prioritySelect = document.getElementById('personalTaskPriority');
        const selectedPriority = prioritySelect ? prioritySelect.value : 'MEDIUM';

        if (selectedCategoryIds.length === 0) {
            alert('Vui lòng chọn ít nhất một thể loại');
            return;
        }

        const data = {
            title: formData.get('title'),
            description: formData.get('description') || null,
            due_date: formData.get('due_date'),
            category_id: parseInt(selectedCategoryIds[0]) || null, // API chỉ nhận 1
            priority: selectedPriority,
            notification_time: formData.get('notification_time') || null,
        };

        if (!data.title || !data.due_date || !data.category_id) {
            alert('Vui lòng điền đầy đủ các trường bắt buộc');
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
            if (response.ok && result.status === 200 || result.status === 201) {
                alert('Tạo task thành công!');
                closeCreatePersonalTaskModal();
                if (typeof loadTasks === 'function') {
                    loadTasks();
                }
            } else {
                alert(result.message || 'Có lỗi xảy ra khi tạo task');
            }
        } catch (error) {
            console.error('Error creating task:', error);
            alert('Có lỗi xảy ra khi tạo task');
        }
    }

    document.addEventListener('click', function (e) {
        const modal = document.getElementById('createPersonalTaskModal');
        if (modal && e.target === modal) {
            closeCreatePersonalTaskModal();
        }
    });

    
</script>