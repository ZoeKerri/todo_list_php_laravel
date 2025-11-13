<div class="category-modal" id="createCategoryModal">
    <div class="category-modal-content">
        <div class="category-modal-header">
            <h3>Create New Category</h3>
            <button type="button" class="category-modal-close" onclick="closeCreateCategoryModal()" aria-label="Close">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="category-modal-body">
            <form id="createCategoryForm">
                <div class="form-group">
                    <label for="newCategoryName" class="form-label">
                        Category Name <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="newCategoryName" 
                           class="form-input"
                           required
                           placeholder="Enter category name"
                           autofocus>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Color Selection</label>
                    
                    <!-- Preset Colors -->
                    <div class="color-presets-section">
                        <p class="color-section-label">Preset Colors</p>
                        <div class="color-presets">
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
                            <div class="category-color-option" data-color="#FFA07A" style="background: #FFA07A;" onclick="selectCategoryColor(this, '#FFA07A')">
                                <svg class="category-checkmark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3">
                                    <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="category-color-option" data-color="#98D8C8" style="background: #98D8C8;" onclick="selectCategoryColor(this, '#98D8C8')">
                                <svg class="category-checkmark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="3">
                                    <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Color Picker -->
                    <div class="custom-color-section">
                        <p class="color-section-label">Custom Color</p>
                        <div class="custom-color-wrapper">
                            <input type="color" 
                                   id="customColorPicker" 
                                   class="custom-color-picker"
                                   value="#FF6B6B"
                                   onchange="selectCustomColor(this.value)">
                            <div class="custom-color-preview" id="customColorPreview" style="background-color: #FF6B6B;">
                                <span class="custom-color-hex" id="customColorHex">#FF6B6B</span>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" id="selectedCategoryColor" value="#FF6B6B">
                </div>
            </form>
        </div>
        <div class="category-modal-footer">
            <button type="button" class="btn-secondary" onclick="closeCreateCategoryModal()">Cancel</button>
            <button type="button" class="btn-primary" onclick="submitCreateCategory()">
                <span>Create Category</span>
            </button>
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
        background-color: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.2s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .category-modal.show {
        display: flex;
    }

    .category-modal-content {
        background-color: var(--bg-color, #ffffff);
        border-radius: 16px;
        width: 90%;
        max-width: 550px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        animation: slideUp 0.3s ease-out;
        transform: translateY(0);
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

    .category-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 28px;
        border-bottom: 1px solid var(--border-color, #e5e7eb);
        background: linear-gradient(to bottom, var(--bg-color, #ffffff), var(--bg-color, #f9fafb));
    }

    .category-modal-header h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary, #111827);
        letter-spacing: -0.02em;
    }

    .category-modal-close {
        background: rgba(0, 0, 0, 0.05);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        color: var(--text-secondary, #6b7280);
        padding: 8px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        width: 36px;
        height: 36px;
    }

    .category-modal-close:hover {
        background: rgba(0, 0, 0, 0.1);
        color: var(--text-primary, #111827);
        transform: rotate(90deg);
    }

    .category-modal-body {
        padding: 28px;
        max-height: 75vh;
        overflow-y: auto;
    }

    .category-modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .category-modal-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .category-modal-body::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 4px;
    }

    .category-modal-body::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.3);
    }

    .category-modal-footer {
        display: flex;
        justify-content: flex-end;
        padding: 20px 28px;
        border-top: 1px solid var(--border-color, #e5e7eb);
        gap: 12px;
        background-color: var(--bg-color, #f9fafb);
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--text-primary, #111827);
    }

    .form-label .required {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid var(--border-color, #e5e7eb);
        border-radius: 10px;
        background-color: var(--bg-color, #ffffff);
        color: var(--text-primary, #111827);
        font-size: 1rem;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-input::placeholder {
        color: var(--text-secondary, #9ca3af);
    }

    /* Color Selection Styles */
    .color-presets-section {
        margin-bottom: 24px;
    }

    .color-section-label {
        margin: 0 0 12px 0;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-secondary, #6b7280);
    }

    .color-presets {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .category-color-option {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: 3px solid transparent;
        position: relative;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .category-color-option:hover {
        transform: scale(1.1) translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .category-color-option.selected {
        border-color: var(--text-primary, #111827);
        transform: scale(1.15) translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
    }

    .category-checkmark {
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .category-color-option.selected .category-checkmark {
        opacity: 1;
    }

    /* Custom Color Picker Styles */
    .custom-color-section {
        margin-top: 8px;
    }

    .custom-color-wrapper {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: var(--bg-color, #f9fafb);
        border-radius: 12px;
        border: 2px solid var(--border-color, #e5e7eb);
    }

    .custom-color-picker {
        width: 60px;
        height: 60px;
        border: 3px solid var(--border-color, #e5e7eb);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: none;
        padding: 0;
    }

    .custom-color-picker:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .custom-color-picker::-webkit-color-swatch-wrapper {
        padding: 0;
    }

    .custom-color-picker::-webkit-color-swatch {
        border: none;
        border-radius: 8px;
    }

    .custom-color-preview {
        flex: 1;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--border-color, #e5e7eb);
        transition: all 0.2s ease;
    }

    .custom-color-hex {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--text-primary);
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        padding: 6px 12px;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 6px;
        backdrop-filter: blur(4px);
        transition: color 0.3s ease;
    }

    /* Button Styles */
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
    }

    .btn-secondary {
        background-color: var(--bg-color, #f3f4f6);
        color: var(--text-primary, #111827);
        border: 2px solid var(--border-color, #e5e7eb);
    }

    .btn-secondary:hover {
        background-color: var(--bg-color, #e5e7eb);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background: var(--accent-color);
        color: var(--text-primary);
        box-shadow: 0 4px 12px rgba(106, 27, 154, 0.3);
        transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
    }

    .btn-primary:hover {
        background: var(--accent-color);
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(106, 27, 154, 0.4);
    }

    .btn-primary:active {
        transform: translateY(0);
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
            
            // Reset form
            const form = document.getElementById('createCategoryForm');
            if (form) {
                form.reset();
            }
            
            // Reset to default color
            selectedColor = '#FF6B6B';
            updateSelectedColor('#FF6B6B');
            
            // Reset preset color selection
            document.querySelectorAll('#createCategoryModal .category-color-option').forEach((option, index) => {
                option.classList.remove('selected');
                const checkmark = option.querySelector('.category-checkmark');
                if (checkmark) {
                    checkmark.style.opacity = '0';
                }
                // Select first color option by default
                if (index === 0) {
                    option.classList.add('selected');
                    const checkmark = option.querySelector('.category-checkmark');
                    if (checkmark) {
                        checkmark.style.opacity = '1';
                    }
                }
            });
            
            // Set focus to the input field when modal opens
            const input = document.getElementById('newCategoryName');
            if (input) {
                setTimeout(() => input.focus(), 100);
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
    
    // Handle preset color selection
    function selectCategoryColor(element, color) {
        // Remove selection from all preset options
        document.querySelectorAll('#createCategoryModal .category-color-option').forEach(option => {
            option.classList.remove('selected');
            const checkmark = option.querySelector('.category-checkmark');
            if (checkmark) {
                checkmark.style.opacity = '0';
            }
        });
        
        // Add selection to clicked option
        if (element) {
            element.classList.add('selected');
            const checkmark = element.querySelector('.category-checkmark');
            if (checkmark) {
                checkmark.style.opacity = '1';
            }
        }
        
        // Update selected value
        selectedColor = color;
        updateSelectedColor(color);
    }

    // Handle custom color selection
    function selectCustomColor(color) {
        // Remove selection from all preset options
        document.querySelectorAll('#createCategoryModal .category-color-option').forEach(option => {
            option.classList.remove('selected');
            const checkmark = option.querySelector('.category-checkmark');
            if (checkmark) {
                checkmark.style.opacity = '0';
            }
        });
        
        // Update selected value
        selectedColor = color;
        
        // Update hidden input
        const hiddenInput = document.getElementById('selectedCategoryColor');
        if (hiddenInput) {
            hiddenInput.value = color;
        }
        
        // Update custom color preview
        const colorPreview = document.getElementById('customColorPreview');
        const colorHex = document.getElementById('customColorHex');
        if (colorPreview) {
            colorPreview.style.backgroundColor = color;
        }
        if (colorHex) {
            colorHex.textContent = color.toUpperCase();
            // Update text color based on background brightness
            const contrastColor = getContrastColor(color);
            colorHex.style.color = contrastColor;
        }
    }

    // Update selected color in hidden input and sync with custom picker
    function updateSelectedColor(color) {
        const hiddenInput = document.getElementById('selectedCategoryColor');
        if (hiddenInput) {
            hiddenInput.value = color;
        }
        
        // Sync custom color picker (only if it exists and value is different to avoid infinite loop)
        const colorPicker = document.getElementById('customColorPicker');
        if (colorPicker && colorPicker.value !== color) {
            colorPicker.value = color;
        }
        
        // Update custom color preview
        const colorPreview = document.getElementById('customColorPreview');
        const colorHex = document.getElementById('customColorHex');
        if (colorPreview) {
            colorPreview.style.backgroundColor = color;
        }
        if (colorHex) {
            colorHex.textContent = color.toUpperCase();
            const contrastColor = getContrastColor(color);
            colorHex.style.color = contrastColor;
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
            if (e.key === 'Escape' && modal && modal.classList.contains('show')) {
                closeCreateCategoryModal();
            }
        });
        
        // Initialize custom color preview
        const colorPreview = document.getElementById('customColorPreview');
        const colorHex = document.getElementById('customColorHex');
        if (colorPreview && colorHex) {
            const defaultColor = '#FF6B6B';
            colorPreview.style.backgroundColor = defaultColor;
            colorHex.textContent = defaultColor.toUpperCase();
            const contrastColor = getContrastColor(defaultColor);
            colorHex.style.color = contrastColor;
        }
    });
</script>