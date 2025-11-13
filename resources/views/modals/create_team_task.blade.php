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
                        style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Tiêu
                        đề *</label>
                    <input type="text" id="personalTaskTitle" name="title" required
                        style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem;"
                        placeholder="Nhập tiêu đề task">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Mô
                        tả</label>
                    <textarea id="personalTaskDescription" name="description" rows="4"
                        style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem; resize: vertical;"
                        placeholder="Nhập mô tả task (tùy chọn)"></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label
                        style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Ngày
                        hết hạn *</label>
                    <input type="date" id="personalTaskDueDate" name="due_date" required
                        style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem;"
                        min="{{ date('Y-m-d') }}">
                </div>

                <div style="margin-bottom: 20px;">
                    <label
                        style="display: block; margin-bottom: 12px; font-weight: 600; color: var(--text-primary);">Thể
                        loại *</label>
                    <div style="position: relative;">
                        <div id="categoryListContainer"
                            style="display: flex; gap: 10px; overflow-x: auto; padding: 10px 5px; margin: -10px -5px; scrollbar-width: none; -ms-overflow-style: none;">
                            <!-- Categories will be populated here -->
                        </div>
                        <style>
                            #categoryListContainer::-webkit-scrollbar {
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
                                color: white;
                            }

                            .category-chip.add-category {
                                background-color: transparent;
                                border: 2px dashed var(--accent-color);
                                color: var(--accent-color);
                            }

                            .category-chip.add-category:hover {
                                background-color: var(--accent-color);
                                color: white;
                            }

                            #categoryListContainer input[type="checkbox"] {
                                display: none;
                            }
                        </style>
                    </div>
                    <input type="hidden" id="selectedCategoryIds" name="category_ids" value="">
                </div>

                <!-- Modal tạo category mới -->
                <div class="modal" id="createCategoryModal" style="z-index: 10001;">
                    <div class="modal-content" style="max-width: 400px;">
                        <div class="modal-header">
                            <h3>Tạo Thể loại Mới</h3>
                            <button class="modal-close" onclick="closeCreateCategoryModal()">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div style="margin-bottom: 20px;">
                                <label
                                    style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Tên
                                    thể loại *</label>
                                <input type="text" id="newCategoryName"
                                    style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem;"
                                    placeholder="Nhập tên thể loại">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn-secondary" onclick="closeCreateCategoryModal()">Hủy</button>
                            <button class="btn-primary" onclick="submitCreateCategory()">Tạo Thể loại</button>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Độ
                        ưu tiên</label>
                    <select id="personalTaskPriority" name="priority"
                        style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem;">
                        <option value="LOW">Thấp</option>
                        <option value="MEDIUM" selected>Trung bình</option>
                        <option value="HIGH">Cao</option>
                    </select>
                </div>

                <div style="margin-bottom: 20px;">
                    <label
                        style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Thời
                        gian thông báo (tùy chọn)</label>
                    <input type="time" id="personalTaskNotificationTime" name="notification_time"
                        style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem;">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeCreatePersonalTaskModal()">Hủy</button>
            <button class="btn-primary" onclick="submitCreatePersonalTask()">Tạo Task</button>
        </div>
    </div>
</div>

<script>
    // Define function in global scope immediately
    (function () {
        function openCreatePersonalTaskModal() {
            const modal = document.getElementById('createPersonalTaskModal');
            if (modal) {
                // Set default date to today
                const dueDateInput = document.getElementById('personalTaskDueDate');
                if (dueDateInput) {
                    dueDateInput.value = new Date().toISOString().split('T')[0];
                }
                // Load categories only if category list is empty
                const categoryContainer = document.getElementById('categoryListContainer');
                if (categoryContainer && categoryContainer.children.length === 0) {
                    if (typeof loadCategoriesForPersonalTask === 'function') {
                        loadCategoriesForPersonalTask();
                    }
                }
                modal.classList.add('show');
            } else {
                console.error('Modal createPersonalTaskModal not found');
            }
        }

        // Make function globally available immediately
        window.openCreatePersonalTaskModal = openCreatePersonalTaskModal;
    })();

    function closeCreatePersonalTaskModal() {
        const modal = document.getElementById('createPersonalTaskModal');
        if (modal) {
            modal.classList.remove('show');
            document.getElementById('createPersonalTaskForm').reset();
            // Reset selected categories
            personalTaskSelectedCategoryIds = [];
            const chips = document.querySelectorAll('#categoryListContainer .category-chip');
            chips.forEach(chip => chip.classList.remove('selected'));
            document.getElementById('selectedCategoryIds').value = '';
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

                // Clear container
                categoryContainer.innerHTML = '';

                // Add "Add Category" button first
                const addCategoryChip = document.createElement('div');
                addCategoryChip.className = 'category-chip add-category';
                addCategoryChip.innerHTML = '<i class="fas fa-plus"></i> <span>Tạo mới</span>';
                addCategoryChip.onclick = () => openCreateCategoryModal();
                categoryContainer.appendChild(addCategoryChip);

                // Add categories as chips
                categories.forEach(category => {
                    const chip = document.createElement('div');
                    chip.className = 'category-chip';
                    chip.dataset.categoryId = category.id;
                    chip.textContent = category.name || category.title || 'Unnamed';
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
        } else {
            personalTaskSelectedCategoryIds.push(categoryId);
            chipElement.classList.add('selected');
        }

        // Update hidden input (use first selected category for API compatibility)
        document.getElementById('selectedCategoryIds').value = personalTaskSelectedCategoryIds.join(',');
    }

    // Define function in global scope immediately
    (function () {
        function openCreateCategoryModal() {
            const modal = document.getElementById('createCategoryModal');
            if (modal) {
                const nameInput = document.getElementById('newCategoryName');
                if (nameInput) {
                    nameInput.value = '';
                }
                modal.classList.add('show');
                // Focus on input
                setTimeout(() => {
                    if (nameInput) {
                        nameInput.focus();
                    }
                }, 100);
            } else {
                console.error('Modal createCategoryModal not found');
                // Fallback to prompt if modal doesn't exist
                const categoryName = prompt('Nhập tên thể loại mới:');
                if (categoryName && categoryName.trim()) {
                    // Try to use createCategoryQuick if available
                    if (typeof window.createCategoryQuick === 'function') {
                        window.createCategoryQuick(categoryName.trim());
                    } else {
                        // Direct create if function not available
                        submitCreateCategoryDirect(categoryName.trim());
                    }
                }
            }
        }

        // Make function globally available immediately
        window.openCreateCategoryModal = openCreateCategoryModal;
    })();

    async function submitCreateCategoryDirect(categoryName) {
        if (!categoryName) return;

        try {
            const apiToken = getApiToken();
            const response = await fetch('/api/v1/category', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: categoryName
                })
            });

            const result = await response.json();
            if (response.ok && (result.status === 200 || result.status === 201)) {
                alert('Tạo thể loại thành công!');
                // Reload categories in modal
                if (typeof loadCategoriesForPersonalTask === 'function') {
                    await loadCategoriesForPersonalTask();
                }
                // Reload categories in welcome page if available
                if (typeof window.loadCategories === 'function') {
                    await window.loadCategories();
                }
            } else {
                alert(result.message || 'Có lỗi xảy ra khi tạo thể loại');
            }
        } catch (error) {
            console.error('Error creating category:', error);
            alert('Có lỗi xảy ra khi tạo thể loại');
        }
    }

    function closeCreateCategoryModal() {
        const modal = document.getElementById('createCategoryModal');
        if (modal) {
            modal.classList.remove('show');
            document.getElementById('newCategoryName').value = '';
        }
    }

    async function submitCreateCategory() {
        const categoryName = document.getElementById('newCategoryName').value.trim();

        if (!categoryName) {
            alert('Vui lòng nhập tên thể loại');
            return;
        }

        try {
            const apiToken = getApiToken();
            const response = await fetch('/api/v1/category', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: categoryName
                })
            });

            const result = await response.json();
            if (response.ok && (result.status === 200 || result.status === 201)) {
                alert('Tạo thể loại thành công!');
                closeCreateCategoryModal();
                // Reload categories and select the new one
                await loadCategoriesForPersonalTask();
                // Select the newly created category
                const newCategoryId = result.data?.id;
                if (newCategoryId) {
                    // Find and click the new category chip
                    const newChip = document.querySelector(`[data-category-id="${newCategoryId}"]`);
                    if (newChip) {
                        togglePersonalTaskCategory(newCategoryId, newChip);
                    }
                }
                // Also reload categories in welcome.blade.php if available
                if (typeof window.loadCategories === 'function') {
                    await window.loadCategories();
                    if (typeof window.initializeCategoryFilter === 'function') {
                        window.initializeCategoryFilter();
                    }
                }
            } else {
                alert(result.message || 'Có lỗi xảy ra khi tạo thể loại');
            }
        } catch (error) {
            console.error('Error creating category:', error);
            alert('Có lỗi xảy ra khi tạo thể loại');
        }
    }

    // Close category modal when clicking outside
    document.addEventListener('click', function (e) {
        const modal = document.getElementById('createCategoryModal');
        if (modal && e.target === modal) {
            closeCreateCategoryModal();
        }
    });

    async function submitCreatePersonalTask() {
        const form = document.getElementById('createPersonalTaskForm');
        const formData = new FormData(form);

        // Get values
        const selectedCategoryIds = personalTaskSelectedCategoryIds;

        if (selectedCategoryIds.length === 0) {
            alert('Vui lòng chọn ít nhất một thể loại');
            return;
        }

        const data = {
            title: formData.get('title'),
            description: formData.get('description') || null,
            due_date: formData.get('due_date'),
            category_id: parseInt(selectedCategoryIds[0]) || null, // Use first selected for API compatibility
            priority: formData.get('priority') || 'MEDIUM',
            notification_time: formData.get('notification_time') || null,
        };

        // Validation
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

    // Close modal when clicking outside
    document.addEventListener('click', function (e) {
        const modal = document.getElementById('createPersonalTaskModal');
        if (modal && e.target === modal) {
            closeCreatePersonalTaskModal();
        }
    });
</script>