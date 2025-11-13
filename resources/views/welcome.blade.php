@extends('layouts.app')

@section('title', 'Your Tasks Dashboard')

@push('modals')
    @include('modals.create_category')
    @include('modals.create_personal_task')
    @include('modals.personal_task_detail')
@endpush

@push('styles')
<style>
    /* Category Filter - Horizontal Scroll */
    .category-filter-container {
        margin-bottom: 12px;
        position: relative;
    }
    .category-list {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        overflow-y: hidden;
        padding-bottom: 8px;
        margin-bottom: -8px;
        scrollbar-width: thin;
        scrollbar-color: var(--border-color) transparent;
        -webkit-overflow-scrolling: touch;
    }
    .category-list::-webkit-scrollbar {
        height: 6px;
    }
    .category-list::-webkit-scrollbar-track {
        background: transparent;
    }
    .category-list::-webkit-scrollbar-thumb {
        background-color: var(--border-color);
        border-radius: 3px;
    }
    .category-chip {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
        white-space: nowrap;
        border: 2px solid transparent;
    }
    .category-chip.active {
        background-color: var(--accent-color);
        color: var(--text-primary);
        border-color: var(--accent-color);
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }
    .category-chip.inactive {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
    }
    .category-chip.inactive:hover {
        background-color: var(--hover-bg);
    }
    
    /* Date Picker - Horizontal Scroll với Infinite Scroll */
    .date-picker-container {
        margin-bottom: 12px;
    }
    .date-list {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        overflow-y: hidden;
        padding-bottom: 8px;
        margin-bottom: -8px;
        scrollbar-width: none;
        -ms-overflow-style: none;
        -webkit-overflow-scrolling: touch;
    }
    .date-list::-webkit-scrollbar {
        display: none;
    }
    .date-item {
        min-width: 80px;
        height: 100px;
        padding: 10px 15px;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
        border: 2px solid transparent;
        background-color: var(--bg-secondary);
    }
    .date-item:hover {
        background-color: var(--hover-bg);
    }
    .date-item.selected {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
    }
    .date-item.selected .date-day-name,
    .date-item.selected .date-day-number,
    .date-item.selected .date-month {
        color: var(--text-primary);
        transition: color 0.3s ease;
    }
    .date-day-name {
        font-size: 18px;
        font-weight: bold;
        color: var(--text-primary);
        margin-bottom: 4px;
    }
    .date-day-number {
        font-size: 16px;
        font-weight: bold;
        color: var(--text-primary);
        margin-bottom: 2px;
    }
    .date-month {
        font-size: 12px;
        color: var(--text-muted);
        opacity: 0.85;
    }
    
    /* Personal Task Card Styles */
    .personal-task-card {
        background-color: var(--card-bg);
        border-radius: 14px;
        padding: 14px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: background-color 0.3s ease;
        border-left: 6px solid;
    }
    .personal-task-card:hover {
        background-color: var(--hover-bg);
    }
    .personal-task-card.completed {
        border-left-color: #22c55e;
    }
    .personal-task-card.priority-high {
        border-left-color: #ef4444;
    }
    .personal-task-card.priority-medium {
        border-left-color: #f97316;
    }
    .personal-task-card.priority-low {
        border-left-color: #3b82f6;
    }
    .task-card-left {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        min-width: 0;
    }
    .task-checkbox {
        font-size: 24px;
        color: var(--text-primary);
        flex-shrink: 0;
        cursor: pointer;
    }
    .task-checkbox.completed {
        color: #22c55e;
    }
    .task-content {
        flex: 1;
        min-width: 0;
    }
    .task-title {
        font-size: 18px;
        font-weight: bold;
        color: var(--text-primary);
        margin: 0 0 6px 0;
        word-wrap: break-word;
    }
    .task-title.completed {
        text-decoration: line-through;
        opacity: 0.7;
    }
    .task-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .task-category {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: bold;
        background-color: var(--accent-color);
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .task-date {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 14px;
        font-weight: bold;
        color: var(--text-muted);
    }
    .task-date i {
        font-size: 20px;
    }
    .task-notification {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 14px;
        font-weight: bold;
        color: var(--text-muted);
    }
    .task-notification i {
        font-size: 20px;
    }
    .task-arrow {
        font-size: 16px;
        color: var(--text-muted);
        flex-shrink: 0;
        margin-left: 10px;
        cursor: pointer;
        transition: color 0.3s ease;
    }
    .task-arrow:hover {
        color: var(--text-primary);
    }
    
    .empty-state {
        min-height: 160px;
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.5;
    }
    
    .loading {
        text-align: center;
        padding: 40px;
        color: var(--text-muted);
    }
    
    .fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background-color: var(--accent-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--text-primary);
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        transition: background-color 0.3s ease, color 0.3s ease;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        z-index: 100;
        cursor: pointer;
    }
    .fab:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.5);
    }
    
    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }
    
    .modal.show {
        display: flex;
    }
    
    .modal-content {
        background-color: var(--bg-primary);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .modal-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: var(--text-secondary);
    }
    
    .modal-body {
        padding: 20px 24px;
    }
    
    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
</style>
@endpush

@section('content')
<div style="padding: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 600; margin: 0; color: var(--text-primary);">
                Hi there, {{ Auth::check() ? (Auth::user()->full_name ?? Auth::user()->email) : 'Guest' }}
            </h2>
            <h4 style="font-size: 2.5rem; font-weight: bold; margin: 10px 0 0 0; color: var(--text-primary);">Your Tasks</h4>
        </div>
        
    </div>

    <!-- Filters -->
    <div class="category-filter-container">
        <div class="category-list" id="categoryList">
            <!-- Will be populated by JS -->
                                </div>
                            </div>

    <div class="date-picker-container">
        <div class="date-list" id="dateList">
            <!-- Will be populated by JS -->
                        </div>
                    </div>

    <!-- Task List -->
    <div id="taskListContainer">
        <div class="loading" id="loadingState">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading tasks...</p>
                            </div>
                        </div>
                    </div>

<!-- Floating Action Button -->
<a href="#" class="fab" id="addTaskBtn">+</a>

<!-- Modals -->
@include('modals.create_personal_task')

@endsection

@push('scripts')
<script>
    const userId = {{ Auth::id() ?? 'null' }};
    
    // Make getApiToken available globally for modal
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
    
    // Make getApiToken available globally
    window.getApiToken = getApiToken;
    
    // Function to close the modal
    function closePersonalTaskModal() {
        const modal = document.getElementById('createPersonalTaskModal');
        if (modal) {
            modal.classList.remove('show');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Re-enable scrolling
        }
    }

    // Add Task button click handler
    document.addEventListener('DOMContentLoaded', function() {
        const addTaskBtn = document.getElementById('addTaskBtn');
        const modal = document.getElementById('createPersonalTaskModal');
        
        // Open modal when clicking Add Task button
        if (addTaskBtn) {
            addTaskBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (modal) {
                    modal.classList.add('show');
                    modal.style.display = 'flex';
                    document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
                } else {
                    console.error('Create personal task modal not found');
                }
            });
        }
        
        // Close modal when clicking outside the modal content
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closePersonalTaskModal();
                }
            });
            
            // Close modal when clicking the close button
            const closeBtn = modal.querySelector('.modal-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', closePersonalTaskModal);
            }
        }
        
        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePersonalTaskModal();
            }
        });
    });
    
    const apiToken = getApiToken();
    let tasksData = [];
    let filteredTasksData = [];
    let categories = [];
    let selectedCategories = [];
    let selectedDate = new Date();
    let dateItems = [];
    let isLoadingDates = false;
    let minDateIndex = 0; // Track the minimum date index for infinite scroll
    
    const daysOfWeek = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
    const months = ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'];
    
    // Initialize dates around today (e.g., 7 days before to 14 days after)
    function initializeDates() {
        const dates = [];
        const today = new Date();
        // Start from 7 days before today
        for (let i = -7; i < 14; i++) {
            const date = new Date(today);
            date.setDate(today.getDate() + i);
            dates.push(date);
        }
        minDateIndex = -7;
        return dates;
    }
    
    function formatDateForDisplay(date) {
        const dayName = daysOfWeek[date.getDay()];
        const dayNumber = date.getDate();
        const month = months[date.getMonth()];
        return { dayName, dayNumber, month, dateStr: date.toISOString().split('T')[0] };
    }
    
    function renderDateItem(date, isSelected = false) {
        const { dayName, dayNumber, month, dateStr } = formatDateForDisplay(date);
        return `
            <div class="date-item ${isSelected ? 'selected' : ''}" 
                 data-date="${dateStr}"
                 data-date-index="${dateItems.length}"
                 onclick="selectDate('${dateStr}')">
                <div class="date-day-name">${dayName}</div>
                <div class="date-day-number">${dayNumber}</div>
                <div class="date-month">${month}</div>
                        </div>
        `;
    }
    
    function initializeDatePicker() {
        dateItems = initializeDates();
        const dateList = document.getElementById('dateList');
        if (!dateList) return;
        
        // Find today's index
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        let todayIndex = -1;
        dateItems.forEach((date, index) => {
            const dateOnly = new Date(date);
            dateOnly.setHours(0, 0, 0, 0);
            if (dateOnly.getTime() === today.getTime()) {
                todayIndex = index;
            }
        });
        
        // Render dates
        dateList.innerHTML = dateItems.map((date, index) => {
            const isSelected = index === (todayIndex >= 0 ? todayIndex : 0);
            return renderDateItem(date, isSelected);
        }).join('');
        
        // Select today by default
        if (todayIndex >= 0) {
            selectedDate = dateItems[todayIndex];
        } else {
            selectedDate = dateItems[0];
        }
        
        // Add infinite scroll listener for scroll events
        dateList.addEventListener('scroll', handleDateScroll);
        
        // Handle mouse wheel for infinite scroll
        let wheelTimeout = null;
        dateList.addEventListener('wheel', function(e) {
            // Clear existing timeout
            if (wheelTimeout) {
                clearTimeout(wheelTimeout);
            }
            
            // Check after a short delay to allow scroll to happen
            wheelTimeout = setTimeout(() => {
                const dateListEl = e.currentTarget;
                const scrollLeft = dateListEl.scrollLeft;
                const scrollWidth = dateListEl.scrollWidth;
                const clientWidth = dateListEl.clientWidth;
                
                // Load more dates when scrolling right (future)
                if (scrollLeft + clientWidth >= scrollWidth - 100 && !isLoadingDates) {
                    loadMoreFutureDates();
                }
                
                // Load more dates when scrolling left (past)
                if (scrollLeft <= 100 && !isLoadingDates) {
                    loadMorePastDates();
                }
            }, 150);
        }, { passive: true });
    }
    
    function handleDateScroll(e) {
        const dateList = e.target;
        const scrollLeft = dateList.scrollLeft;
        const scrollWidth = dateList.scrollWidth;
        const clientWidth = dateList.clientWidth;
        
        // Load more dates when scrolling right (future)
        if (scrollLeft + clientWidth >= scrollWidth - 100 && !isLoadingDates) {
            loadMoreFutureDates();
        }
        
        // Load more dates when scrolling left (past)
        if (scrollLeft <= 100 && !isLoadingDates) {
            loadMorePastDates();
        }
    }
    
    function loadMoreFutureDates() {
        if (isLoadingDates) return;
        isLoadingDates = true;
        
        const dateList = document.getElementById('dateList');
        const lastDate = dateItems[dateItems.length - 1];
        
        // Add 10 more days
        const newDates = [];
        for (let i = 1; i <= 10; i++) {
            const newDate = new Date(lastDate);
            newDate.setDate(lastDate.getDate() + i);
            newDates.push(newDate);
        }
        
        dateItems.push(...newDates);
        
        // Append new date items
        const fragment = document.createDocumentFragment();
        newDates.forEach(date => {
            const { dayName, dayNumber, month, dateStr } = formatDateForDisplay(date);
            const div = document.createElement('div');
            div.className = 'date-item';
            div.dataset.date = dateStr;
            div.dataset.dateIndex = dateItems.length - newDates.length + newDates.indexOf(date);
            div.onclick = () => selectDate(dateStr);
            div.innerHTML = `
                <div class="date-day-name">${dayName}</div>
                <div class="date-day-number">${dayNumber}</div>
                <div class="date-month">${month}</div>
            `;
            fragment.appendChild(div);
        });
        
        dateList.appendChild(fragment);
        isLoadingDates = false;
    }
    
    function loadMorePastDates() {
        if (isLoadingDates) return;
        isLoadingDates = true;
        
        const dateList = document.getElementById('dateList');
        const firstDate = dateItems[0];
        const scrollLeft = dateList.scrollLeft;
        
        // Add 10 more days in the past
        const newDates = [];
        for (let i = 10; i >= 1; i--) {
            const newDate = new Date(firstDate);
            newDate.setDate(firstDate.getDate() - i);
            newDates.unshift(newDate);
        }
        
        dateItems.unshift(...newDates);
        minDateIndex -= 10;
        
        // Prepend new date items
        const fragment = document.createDocumentFragment();
        newDates.forEach(date => {
            const { dayName, dayNumber, month, dateStr } = formatDateForDisplay(date);
            const div = document.createElement('div');
            div.className = 'date-item';
            div.dataset.date = dateStr;
            div.dataset.dateIndex = dateItems.indexOf(date);
            div.onclick = () => selectDate(dateStr);
            div.innerHTML = `
                <div class="date-day-name">${dayName}</div>
                <div class="date-day-number">${dayNumber}</div>
                <div class="date-month">${month}</div>
            `;
            fragment.appendChild(div);
        });
        
        // Update indices for existing items
        const existingItems = dateList.querySelectorAll('.date-item');
        existingItems.forEach((item, index) => {
            if (index >= newDates.length) {
                item.dataset.dateIndex = parseInt(item.dataset.dateIndex) + newDates.length;
            }
        });
        
        dateList.insertBefore(fragment, dateList.firstChild);
        
        // Restore scroll position
        setTimeout(() => {
            dateList.scrollLeft = scrollLeft + (newDates.length * 92); // 80px width + 12px gap
        }, 0);
        
        isLoadingDates = false;
    }
    
    function selectDate(dateString) {
        selectedDate = new Date(dateString);
        
        // Update UI
        document.querySelectorAll('.date-item').forEach(item => {
            item.classList.remove('selected');
            if (item.dataset.date === dateString) {
                item.classList.add('selected');
                // Scroll selected date into view
                item.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        });
        
        applyFilters();
    }
    
    function initializeCategoryFilter() {
        const categoryList = document.getElementById('categoryList');
        if (!categoryList) return;
        
        // Add "All" option
        categoryList.innerHTML = `
            <div class="category-chip active" data-category-id="all" onclick="toggleCategory('all')">
                Tất cả
                    </div>
        `;
        
        // Add "Create Category" button
        const createChip = document.createElement('div');
        createChip.className = 'category-chip';
        createChip.style.cssText = 'background-color: transparent; border: 2px dashed var(--accent-color); color: var(--accent-color); cursor: pointer;';
        createChip.innerHTML = '<i class="fas fa-plus"></i> <span>Tạo mới</span>';
        createChip.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Try to open modal, fallback to prompt if not available
            const openCategoryFunc = window.openCreateCategoryModal || openCreateCategoryModal;
            if (typeof openCategoryFunc === 'function') {
                openCategoryFunc();
            } else {
                // Try to manually open modal
                const modal = document.getElementById('createCategoryModal');
                if (modal) {
                    const nameInput = document.getElementById('newCategoryName');
                    if (nameInput) {
                        nameInput.value = '';
                    }
                    modal.classList.add('show');
                    setTimeout(() => {
                        if (nameInput) {
                            nameInput.focus();
                        }
                    }, 100);
                } else {
                    // Fallback to prompt
                    const categoryName = prompt('Nhập tên thể loại mới:');
                    if (categoryName && categoryName.trim()) {
                        if (typeof createCategoryQuick === 'function') {
                            createCategoryQuick(categoryName.trim());
                        } else {
                            alert('Vui lòng mở modal tạo task để tạo category');
                        }
                    }
                }
            }
        });
        categoryList.appendChild(createChip);
        
        // Add categories
        categories.forEach(category => {
            const chip = document.createElement('div');
            chip.className = 'category-chip inactive';
            chip.dataset.categoryId = category.id;
            chip.textContent = category.name || category.title || 'Unnamed';
            
            // Thêm style màu sắc cho chip
            if (category.color) {
                // Lưu màu gốc vào dataset
                chip.dataset.originalColor = category.color;
                chip.dataset.originalBorderColor = category.color;
                
                // Tính toán màu chữ tương phản
                const contrastColor = getContrastColor(category.color);
                chip.dataset.originalTextColor = contrastColor;
                
                // Áp dụng màu cho chip inactive
                chip.style.backgroundColor = category.color;
                chip.style.color = contrastColor;
                chip.style.borderColor = category.color;
            }
            
            chip.onclick = () => toggleCategory(category.id);
            categoryList.appendChild(chip);
        });
    }
    
    function toggleCategory(categoryId) {
        const categoryList = document.getElementById('categoryList');
        if (!categoryList) return;
        
        if (categoryId === 'all') {
            selectedCategories = [];
            categoryList.querySelectorAll('.category-chip').forEach(chip => {
                chip.classList.remove('active');
                chip.classList.add('inactive');
                
                // Restore màu gốc khi inactive
                const originalColor = chip.dataset.originalColor;
                if (originalColor) {
                    const originalTextColor = chip.dataset.originalTextColor || getContrastColor(originalColor);
                    chip.style.backgroundColor = originalColor;
                    chip.style.color = originalTextColor;
                    chip.style.borderColor = originalColor;
                }
            });
            categoryList.querySelector('[data-category-id="all"]').classList.add('active');
            categoryList.querySelector('[data-category-id="all"]').classList.remove('inactive');
        } else {
            const chip = categoryList.querySelector(`[data-category-id="${categoryId}"]`);
            if (!chip) return;
            
            const index = selectedCategories.indexOf(categoryId);
            if (index > -1) {
                selectedCategories.splice(index, 1);
                chip.classList.remove('active');
                chip.classList.add('inactive');
                
                // Restore màu gốc khi inactive
                const originalColor = chip.dataset.originalColor;
                if (originalColor) {
                    const originalTextColor = chip.dataset.originalTextColor || getContrastColor(originalColor);
                    chip.style.backgroundColor = originalColor;
                    chip.style.color = originalTextColor;
                    chip.style.borderColor = originalColor;
                }
            } else {
                selectedCategories.push(categoryId);
                chip.classList.add('active');
                chip.classList.remove('inactive');
                
                // Áp dụng accent color khi active
                chip.style.backgroundColor = 'var(--accent-color)';
                chip.style.color = 'var(--text-primary)';
                chip.style.borderColor = 'var(--accent-color)';
            }
            
            // Update "All" chip
            const allChip = categoryList.querySelector('[data-category-id="all"]');
            if (selectedCategories.length === 0) {
                allChip.classList.add('active');
                allChip.classList.remove('inactive');
            } else {
                allChip.classList.remove('active');
                allChip.classList.add('inactive');
            }
        }
        
        applyFilters();
    }
    
    async function loadCategories() {
        try {
            const response = await fetch('/api/v1/category', {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            if (response.ok && result.status === 200) {
                categories = result.data || [];
                initializeCategoryFilter();
            }
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }
    
    async function loadTasks() {
        if (!apiToken) {
            document.getElementById('loadingState').innerHTML = '<p>Please login to view tasks</p>';
            return;
        }
        
        try {
            const selectedDateStr = selectedDate.toISOString().split('T')[0];
            const response = await fetch(`/api/v1/task?date=${selectedDateStr}`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            if (response.ok && result.status === 200) {
                tasksData = result.data || [];
                applyFilters();
            }
        } catch (error) {
            console.error('Error loading tasks:', error);
            document.getElementById('loadingState').innerHTML = `<p>Error loading tasks: ${error.message}</p>`;
        }
    }
    
    function applyFilters() {
        const selectedDateStr = selectedDate.toISOString().split('T')[0];
        
        filteredTasksData = tasksData.filter(task => {
            // Date filter
            const taskDate = new Date(task.due_date || task.dueDate).toISOString().split('T')[0];
            const matchesDate = taskDate === selectedDateStr;
            
            // Category filter
            let matchesCategory = true;
            if (selectedCategories.length > 0) {
                matchesCategory = selectedCategories.includes(task.category_id || task.categoryId);
            }
            
            return matchesDate && matchesCategory;
        });
        
        displayTasks();
    }
    
    function getCategoryName(categoryId) {
        const category = categories.find(c => c.id === categoryId);
        return category ? (category.name || category.title || 'Unknown') : 'Unknown';
    }
    
    function getCategoryColor(categoryId) {
        const category = categories.find(c => c.id === categoryId);
        return category ? (category.color || null) : null;
    }
    
    function getContrastColor(hexColor) {
        if (!hexColor) return '#000000';
        
        // Xử lý cả CSS variable
        if (hexColor.startsWith('var(')) {
            return '#ffffff'; // Fallback cho CSS variable
        }
        
        // Xử lý hex color
        let hex = hexColor.replace('#', '');
        if (hex.length === 3) {
            hex = hex.split('').map(char => char + char).join('');
        }
        
        const r = parseInt(hex.substr(0, 2), 16);
        const g = parseInt(hex.substr(2, 2), 16);
        const b = parseInt(hex.substr(4, 2), 16);
        
        // Tính luminance
        const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
        
        // Trả về màu tương phản
        return luminance > 0.5 ? '#000000' : '#ffffff';
    }
    
    function renderTaskCard(task) {
        const priorityClass = `priority-${(task.priority || 'MEDIUM').toLowerCase()}`;
        const completedClass = task.completed || task.isCompleted ? 'completed' : '';
        const checkboxIcon = task.completed || task.isCompleted ? 'fas fa-check-circle' : 'far fa-circle';
        const titleClass = task.completed || task.isCompleted ? 'completed' : '';
        
        const dueDate = new Date(task.due_date || task.dueDate);
        const dueDateStr = `${dueDate.getDate()}/${dueDate.getMonth() + 1}/${dueDate.getFullYear()}`;
        
        const categoryId = task.category_id || task.categoryId;
        const categoryName = categoryId ? getCategoryName(categoryId) : '';
        const categoryColor = categoryId ? getCategoryColor(categoryId) : null;
        
        let categoryHtml = '';
        if (categoryName) {
            if (categoryColor) {
                const contrastColor = getContrastColor(categoryColor);
                categoryHtml = `<span class="task-category" style="background-color: ${categoryColor}; color: ${contrastColor}; border-color: ${categoryColor}; padding: 4px 8px; border-radius: 4px; font-size: 0.85em;">${escapeHtml(categoryName)}</span>`;
            } else {
                categoryHtml = `<span class="task-category">${escapeHtml(categoryName)}</span>`;
            }
        }
        
        const notificationHtml = task.notification_time ? `
            <span class="task-notification">
                <i class="fas fa-bell"></i>
                ${task.notification_time}
            </span>
        ` : '';
        
        return `
            <div class="personal-task-card ${completedClass} ${priorityClass}" 
                 onclick="toggleTaskComplete(${task.id})">
                <div class="task-card-left">
                    <i class="${checkboxIcon} task-checkbox ${completedClass}"></i>
                    <div class="task-content">
                        <h4 class="task-title ${titleClass}">${escapeHtml(task.title || 'Untitled')}</h4>
                        <div class="task-meta">
                            ${categoryHtml}
                            <span class="task-date">
                                <i class="fas fa-clock"></i>
                                ${dueDateStr}
                            </span>
                            ${notificationHtml}
                        </div>
                    </div>
                </div>
                <i class="fas fa-chevron-right task-arrow" onclick="event.stopPropagation(); viewTaskDetail(${task.id})"></i>
            </div>
        `;
    }
    
    // Get show completed tasks setting from localStorage
    function getShowCompletedTasks() {
        return localStorage.getItem('show_completed_tasks') === 'true';
    }

    function displayTasks() {
        const container = document.getElementById('taskListContainer');
        if (!container) return;
        
        // Hide loading
        const loadingState = document.getElementById('loadingState');
        if (loadingState) {
            loadingState.style.display = 'none';
        }
        
        // Filter tasks based on show_completed_tasks setting
        const showCompleted = getShowCompletedTasks();
        let tasksToDisplay = filteredTasksData;
        if (!showCompleted) {
            // Filter out completed tasks
            tasksToDisplay = filteredTasksData.filter(task => {
                const isCompleted = task.completed || task.isCompleted;
                return !isCompleted;
            });
        }
        
        // Sort tasks: incomplete first, then by priority, then by date
        const priorityOrder = { 'HIGH': 0, 'MEDIUM': 1, 'LOW': 2 };
        const sortedTasks = tasksToDisplay.slice().sort((a, b) => {
            const aCompleted = a.completed || a.isCompleted;
            const bCompleted = b.completed || b.isCompleted;
            if (aCompleted !== bCompleted) return aCompleted ? 1 : -1;
            
            const aPriority = priorityOrder[a.priority?.toUpperCase()] ?? 2;
            const bPriority = priorityOrder[b.priority?.toUpperCase()] ?? 2;
            if (aPriority !== bPriority) return aPriority - bPriority;
            
            const aDate = new Date(a.due_date || a.dueDate);
            const bDate = new Date(b.due_date || b.dueDate);
            return aDate - bDate;
        });
        
        if (sortedTasks.length > 0) {
            container.innerHTML = sortedTasks.map(task => renderTaskCard(task)).join('');
        } else {
            const showCompleted = getShowCompletedTasks();
            const emptyMessage = showCompleted 
                ? 'Không có task nào cho ngày đã chọn' 
                : 'Không có task chưa hoàn thành cho ngày đã chọn';
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-tasks"></i>
                    <p>${emptyMessage}</p>
                </div>
            `;
        }
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function setupEventListeners() {
        // FAB button click handler
        const fabBtn = document.getElementById('addTaskBtn');
        if (fabBtn) {
            fabBtn.addEventListener('click', function(e) {
                e.preventDefault();
                // Open create personal task modal
                const openFunc = window.openCreatePersonalTaskModal || openCreatePersonalTaskModal;
                if (typeof openFunc === 'function') {
                    openFunc();
                } else {
                    console.error('openCreatePersonalTaskModal not found');
                    // Try to manually open modal
                    const modal = document.getElementById('createPersonalTaskModal');
                    if (modal) {
                        const dueDateInput = document.getElementById('personalTaskDueDate');
                        if (dueDateInput) {
                            dueDateInput.value = new Date().toISOString().split('T')[0];
                        }
                        const categoryContainer = document.getElementById('categoryListContainer');
                        if (categoryContainer && categoryContainer.children.length === 0) {
                            if (typeof loadCategoriesForPersonalTask === 'function') {
                                loadCategoriesForPersonalTask();
                            }
                        }
                        modal.classList.add('show');
                    } else {
                        alert('Modal không tìm thấy. Vui lòng refresh trang.');
                    }
                }
            });
        }
    }
    
    async function createCategoryQuick(categoryName) {
        if (!categoryName) return;
        
        try {
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
                // Reload categories
                await loadCategories();
                // Select the newly created category
                const newCategoryId = result.data?.id;
                if (newCategoryId) {
                    setTimeout(() => {
                        toggleCategory(newCategoryId);
                    }, 100);
                }
            } else {
                alert(result.message || 'Có lỗi xảy ra khi tạo thể loại');
            }
        } catch (error) {
            console.error('Error creating category:', error);
            alert('Có lỗi xảy ra khi tạo thể loại');
        }
    }
    
    // Make function globally available
    window.createCategoryQuick = createCategoryQuick;
    
    async function toggleTaskComplete(taskId) {
        const task = tasksData.find(t => t.id === taskId);
        if (!task) return;
        const newStatus = !(task.completed);
        task.completed = newStatus;
        console.log(task);
        
        try {
            const response = await fetch(`/api/v1/task/${taskId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id: task.id,
                    title: task.title,
                    description: task.description,
                    due_date: task.dueDate,
                    priority: task.priority, 
                    category_id: task.categoryId,
                    completed: newStatus
                })
            });
            
            const result = await response.json();
            if (response.ok && result.status === 200) {
                task.completed = newStatus;
                applyFilters();
            } else {
                alert(result.message || 'Failed to update task');
            }
        } catch (error) {
            console.error('Error updating task:', error);
            alert('Error updating task');
        }
    }
    
    function viewTaskDetail(taskId) {
        // Open task detail modal
        if (typeof openPersonalTaskDetailModal === 'function') {
            openPersonalTaskDetailModal(taskId);
        } else {
            console.log('View task detail:', taskId);
            // Fallback: try to open modal directly
            const modal = document.getElementById('personalTaskDetailModal');
            if (modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
                if (typeof loadPersonalTaskDetail === 'function') {
                    loadPersonalTaskDetail(taskId);
                }
            }
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        if (!apiToken) {
            document.getElementById('loadingState').innerHTML = '<p>Please login to view tasks</p>';
            return;
        }
        
        // Listen for settings changes
        window.addEventListener('settingsChanged', function(e) {
            if (e.detail && 'show_completed_tasks' in e.detail) {
                // Re-apply filters to refresh task list with new completed filter
                if (typeof applyFilters === 'function') {
                    applyFilters();
                } else {
                    displayTasks(); // Fallback if applyFilters not available
                }
            }
        });
        
        // Initialize date picker
        initializeDatePicker();
        
        // Load categories and tasks
        loadCategories().then(() => {
            loadTasks();
        });
        
        // Setup event listeners
        setupEventListeners();
        function openCreateCategoryModalWelcome() {
            const modal = document.getElementById('createCategoryModal');
            if (modal) {
                const nameInput = document.getElementById('newCategoryName');
                if (nameInput) {
                    nameInput.value = '';
                }
                modal.classList.add('show');
            } else {
                // Fallback to prompt if modal doesn't exist
                const categoryName = prompt('Nhập tên thể loại mới:');
                if (categoryName && categoryName.trim()) {
                    createCategoryQuick(categoryName.trim());
                }
            }
        }
        
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('createCategoryModal');
            if (event.target === modal) {
                modal.style.display = 'none';
                modal.classList.remove('show');
                document.body.style.overflow = 'auto';
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            const modal = document.getElementById('createCategoryModal');
            if (event.key === 'Escape' && modal && modal.classList.contains('show')) {
                modal.style.display = 'none';
                modal.classList.remove('show');
                document.body.style.overflow = 'auto';
            }
        });
        
        // Make loadCategories and initializeCategoryFilter globally available
        window.loadCategories = loadCategories;
        window.initializeCategoryFilter = initializeCategoryFilter;
    });
</script>
@endpush
