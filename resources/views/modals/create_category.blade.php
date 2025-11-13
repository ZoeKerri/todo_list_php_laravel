<div class="category-modal" id="createCategoryModal">
    <div class="category-modal-content" style="max-width: 500px;">
        <div class="category-modal-header">
            <h3>Create New Category</h3>
            <button type="button" class="category-modal-close" onclick="closeCreateCategoryModal()">&times;</button>
        </div>
        <div class="category-modal-body">
            <form id="createCategoryForm">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Category Name *</label>
                    <input type="text" id="newCategoryName" required
                           style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-color); color: var(--text-primary); font-size: 1rem;"
                           placeholder="Enter category name"
                           autofocus>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-primary);">Color</label>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <div class="category-color-option selected" data-color="#FF6B6B" style="background: #FF6B6B;" onclick="selectCategoryColor(this, '#FF6B6B')">
                            <svg class="category-checkmark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3">
                                <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="category-color-option" data-color="#4ECDC4" style="background: #4ECDC4;" onclick="selectCategoryColor(this, '#4ECDC4')">
                            <svg class="category-checkmark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3">
                                <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="category-color-option" data-color="#45B7D1" style="background: #45B7D1;" onclick="selectCategoryColor(this, '#45B7D1')">
                            <svg class="category-checkmark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3">
                                <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="category-color-option" data-color="#96CEB4" style="background: #96CEB4;" onclick="selectCategoryColor(this, '#96CEB4')">
                            <svg class="category-checkmark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3">
                                <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="category-color-option" data-color="#FFEEAD" style="background: #FFEEAD;" onclick="selectCategoryColor(this, '#FFEEAD')">
                            <svg class="category-checkmark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="3">
                                <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="category-color-option" data-color="#D4A5A5" style="background: #D4A5A5;" onclick="selectCategoryColor(this, '#D4A5A5')">
                            <svg class="category-checkmark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3">
                                <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <input type="hidden" id="selectedCategoryColor" value="#FF6B6B">
                </div>
            </form>
        </div>
        <div class="category-modal-footer">
            <button type="button" class="btn-secondary" onclick="closeCreateCategoryModal()">Cancel</button>
            <button type="button" class="btn-primary" onclick="submitCreateCategory()">Create</button>
        </div>
    </div>
</div>

<style>
    .category-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
    }

    .category-modal.show {
        display: flex;
    }

    .category-modal-content {
        background-color: var(--bg-color);
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .category-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
    }

    .category-modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .category-modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--text-secondary);
        padding: 0;
        line-height: 1;
    }

    .category-modal-close:hover {
        color: var(--text-primary);
    }

    .category-modal-body {
        padding: 20px;
        max-height: 70vh;
        overflow-y: auto;
    }

    .category-modal-footer {
        display: flex;
        justify-content: flex-end;
        padding: 16px 20px;
        border-top: 1px solid var(--border-color);
        gap: 12px;
    }

    .category-color-option {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s, box-shadow 0.2s;
        border: 2px solid transparent;
    }

    .category-color-option:hover {
        transform: scale(1.1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .category-color-option.selected {
        border-color: var(--text-primary);
        transform: scale(1.1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .category-checkmark {
        opacity: 0;
        transition: opacity 0.2s;
    }

    .category-color-option.selected .category-checkmark {
        opacity: 1;
    }
</style>

<script>
    // Global variable to store the selected color
    let selectedColor = '#FF6B6B'; // Default color

    // Modal control functions
    function openCreateCategoryModal() {
        const modal = document.getElementById('createCategoryModal');
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            
            // Set focus to the input field when modal opens
            const input = document.getElementById('newCategoryName');
            if (input) {
                input.focus();
            }
        }
    };

    // Close category modal
    function closeCreateCategoryModal() {
        const modal = document.getElementById('createCategoryModal');
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
    }
    
    // Handle color selection
    function selectCategoryColor(element, color) {
        // Remove selection from all options
        document.querySelectorAll('#createCategoryModal .category-color-option').forEach(option => {
            option.classList.remove('selected');
            option.querySelector('.category-checkmark').style.opacity = '0';
        });
        
        // Add selection to clicked option
        if (element) {
            element.classList.add('selected');
            element.querySelector('.checkmark').style.display = 'block';
        }
        
        // Update selected value
        selectedColor = color;
        const hiddenInput = document.getElementById('selectedCategoryColor');
        if (hiddenInput) {
            hiddenInput.value = color;
        }
    }

    // Submit new category
    async function submitCreateCategory() {
        const nameInput = document.getElementById('newCategoryName');
        const colorInput = document.getElementById('selectedCategoryColor');
        
        const categoryName = nameInput ? nameInput.value.trim() : '';
        const categoryColor = colorInput ? colorInput.value : '#FF6B6B'; // Get selected color

        if (!categoryName) {
            alert('Please enter category name');
            if (nameInput) nameInput.focus();
            return;
        }

        // Call API
        try {
            if (typeof getApiToken === 'undefined') {
                console.error("Error: getApiToken() function not found.");
                alert("Authentication error: getApiToken function not found.");
                return;
            }
            
            const apiToken = getApiToken(); 
            const response = await fetch('/api/v1/category', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: categoryName,
                    color: categoryColor 
                })
            });

            const result = await response.json();
            
            if (response.ok && (result.status === 200 || result.status === 201)) {
                alert('Category created successfully!');
                
                closeCreateCategoryModal(); 

                // Update the task creation modal
                if (typeof loadCategoriesForPersonalTask === 'function') {
                    await loadCategoriesForPersonalTask();
                }

                const newCategoryId = result.data?.id;
                if (newCategoryId) {
                    const newChip = document.querySelector(`#createPersonalTaskModal [data-category-id="${newCategoryId}"]`);
                    
                    if (newChip && typeof togglePersonalTaskCategory === 'function') {
                        togglePersonalTaskCategory(newCategoryId, newChip);
                    }
                }
                
                if (typeof window.loadCategories === 'function') {
                    await window.loadCategories();
                    if (typeof window.initializeCategoryFilter === 'function') {
                        window.initializeCategoryFilter();
                    }
                }

            } else {
                alert(result.message || 'Error creating category');
            }
        } catch (error) {
            console.error('Error creating category:', error);
            alert('Error creating category');
        }
    }

    // Hàm getContrastColor không thay đổi, giữ nguyên nếu bạn cần
    function getContrastColor(hexColor) {
        if (!hexColor) return '#000000';
        const r = parseInt(hexColor.substr(1, 2), 16);
        const g = parseInt(hexColor.substr(3, 2), 16);
        const b = parseInt(hexColor.substr(5, 2), 16);
        const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
        return luminance > 0.5 ? '#000000' : '#ffffff';
    }

    // --- CÁC EVENT LISTENER CHO MODAL NÀY ---
    
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('createCategoryModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeCreateCategoryModal();
                }
            });
        }
        
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('createCategoryModal');
            if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                closeCreateCategoryModal();
            }
        });
    });
</script>