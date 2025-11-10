// Initialize charts when document is ready
document.addEventListener('DOMContentLoaded', function () {
    // 1. Initialize Charts
    initCharts();

    // 2. Initialize Tab Switching
    initTabs();

    // 3. Initialize Avatar Upload
    initAvatarUpload();

    // 4. Initialize Edit Profile Modal
    initEditProfileModal();

    // 5. Initialize Change Password Modal
    initChangePasswordModal();
});

// 1. Initialize Charts
function initCharts() {
    // Work Chart
    if (document.querySelector("#workChart")) {
        const workOptions = {
            series: [{
                name: 'Projects',
                data: [30, 40, 45, 50, 49, 60, 70, 91, 125]
            }],
            chart: {
                type: 'bar',
                height: '100%',
                toolbar: { show: false },
                background: 'transparent'
            },
            theme: { mode: 'dark' },
            title: {
                text: 'Project Views (Last 9 months)',
                align: 'left'
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep']
            },
            colors: ['#3B82F6']
        };
        new ApexCharts(document.querySelector("#workChart"), workOptions).render();
    }

    // Moodboards Chart (lazy load)
    window.moodboardsChart = null;
    if (document.querySelector("#moodboardsChart")) {
        const moodboardsOptions = {
            series: [{
                name: 'New Moodboards',
                data: [10, 15, 8, 12, 7, 18, 14]
            }],
            chart: {
                type: 'area',
                height: '100%',
                toolbar: { show: false },
                background: 'transparent'
            },
            theme: { mode: 'dark' },
            title: {
                text: 'Moodboard Creation Rate',
                align: 'left'
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth' },
            xaxis: {
                categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
            },
            colors: ['#EC4899']
        };
        window.moodboardsChart = new ApexCharts(document.querySelector("#moodboardsChart"), moodboardsOptions);
    }

    // Likes Chart (lazy load)
    window.likesChart = null;
    if (document.querySelector("#likesChart")) {
        const likesOptions = {
            series: [44, 55, 13, 33],
            chart: {
                type: 'donut',
                height: '100%',
                background: 'transparent'
            },
            theme: { mode: 'dark' },
            title: {
                text: 'Like Distribution',
                align: 'left'
            },
            labels: ['UI Design', 'Branding', 'Web App', 'Mobile App'],
            colors: ['#F59E0B', '#10B981', '#6366F1', '#8B5CF6']
        };
        window.likesChart = new ApexCharts(document.querySelector("#likesChart"), likesOptions);
    }
}

// 2. Initialize Tab Switching
function initTabs() {
    const activeClasses = ['border-b-2', 'border-blue-500', 'text-white', 'font-semibold'];
    const inactiveClasses = ['text-gray-400', 'hover:text-white'];
    const tabs = document.querySelectorAll('.tab-link');
    const panels = document.querySelectorAll('.tab-panel');

    // Function to activate a tab
    const activateTab = (tab, targetPanelId) => {
        // Update active tab
        tabs.forEach(t => {
            t.classList.remove(...activeClasses);
            t.classList.add(...inactiveClasses);
        });
        tab.classList.add(...activeClasses);
        tab.classList.remove(...inactiveClasses);

        // Show target panel
        panels.forEach(panel => {
            panel.classList.add('hidden');
        });

        const targetPanel = document.getElementById(targetPanelId);
        if (targetPanel) {
            targetPanel.classList.remove('hidden');
        }

        // Lazy render charts when tab is activated
        if (targetPanelId === 'moodboards' && window.moodboardsChart) {
            if (!window.moodboardsChart.w.globals.rendered) {
                window.moodboardsChart.render();
            }
        }

        if (targetPanelId === 'likes' && window.likesChart) {
            if (!window.likesChart.w.globals.rendered) {
                window.likesChart.render();
            }
        }
    };

    // Set up click handlers for all tabs
    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            const targetTab = e.currentTarget;
            const targetPanelId = targetTab.dataset.tab;
            activateTab(targetTab, targetPanelId);
        });
    });

    // Activate the first tab by default if no tab is active
    const activeTab = document.querySelector('.tab-link.border-blue-500');
    if (!activeTab && tabs.length > 0) {
        const firstTab = tabs[0];
        const firstPanelId = firstTab.dataset.tab;
        activateTab(firstTab, firstPanelId);
    }
}

// 3. Initialize Avatar Upload
function initAvatarUpload() {
    const avatarInput = document.getElementById('avatar-input');
    const avatarImage = document.getElementById('avatar-image');

    if (avatarInput && avatarImage) {
        avatarInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('avatar', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                avatarImage.style.opacity = '0.5';

                fetch('/account-info/upload-avatar', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.avatar_url) {
                            avatarImage.src = data.avatar_url;
                            alert('Avatar updated successfully!');
                        } else {
                            alert(data.message || 'Failed to update avatar');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error updating avatar');
                    })
                    .finally(() => {
                        avatarImage.style.opacity = '1';
                    });
            }
        });
    }
}

// 4. Initialize Edit Profile Modal
function initEditProfileModal() {
    const modal = document.getElementById('edit-profile-modal');
    const openModalButton = document.getElementById('open-edit-modal-button');
    const closeModalButton = document.getElementById('close-edit-modal-button');
    const modalOverlay = document.getElementById('modal-overlay');
    const editForm = document.getElementById('edit-profile-form');
    const errorsContainer = document.getElementById('modal-errors-container');
    const errorsList = document.getElementById('modal-errors-list');
    const profileNameDisplay = document.getElementById('profile-name-display');
    const profilePhoneDisplay = document.getElementById('profile-phone-display');
    const formInputName = document.getElementById('full_name');
    const formInputPhone = document.getElementById('phone');

    if (!modal || !openModalButton) return;

    const openModal = () => {
        formInputName.value = profileNameDisplay.childNodes[0].nodeValue.trim();
        formInputPhone.value = (profilePhoneDisplay.textContent === 'N/A') ? '' : profilePhoneDisplay.textContent;
        modal.classList.remove('hidden');
    };

    const closeModal = () => {
        modal.classList.add('hidden');
        errorsContainer.classList.add('hidden');
        errorsList.innerHTML = '';
    };

    openModalButton.addEventListener('click', openModal);

    if (closeModalButton) {
        closeModalButton.addEventListener('click', closeModal);
    }

    if (modalOverlay) {
        modalOverlay.addEventListener('click', closeModal);
    }

    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();

            errorsContainer.classList.add('hidden');
            errorsList.innerHTML = '';

            const formData = new FormData(editForm);

            fetch(editForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update displayed values
                        profileNameDisplay.childNodes[0].nodeValue = ' ' + data.full_name;
                        if (data.phone) {
                            profilePhoneDisplay.textContent = data.phone;
                        }

                        // Show success message
                        const successMessage = document.createElement('div');
                        successMessage.className = 'fixed top-20 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[9999] transform translate-x-0 transition-all duration-300';
                        successMessage.style.position = 'fixed';
                        successMessage.style.zIndex = '9999';
                        successMessage.textContent = 'Cập nhật thông tin thành công!';
                        document.body.appendChild(successMessage);

                        // Add fade out effect when removing
                        setTimeout(() => {
                            successMessage.style.opacity = '0';
                            successMessage.style.transform = 'translateX(100%)';
                            setTimeout(() => {
                                successMessage.remove();
                                closeModal();
                            }, 300);
                        }, 2000);
                    } else if (data.errors) {
                        // Display validation errors
                        errorsContainer.classList.remove('hidden');
                        Object.values(data.errors).forEach(error => {
                            const li = document.createElement('li');
                            li.textContent = Array.isArray(error) ? error[0] : error;
                            errorsList.appendChild(li);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Show error message
                    const errorMessage = document.createElement('div');
                    errorMessage.className = 'fixed top-20 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-[9999] transform translate-x-0 transition-all duration-300';
                    errorMessage.style.position = 'fixed';
                    errorMessage.style.zIndex = '9999';
                    errorMessage.textContent = 'Có lỗi xảy ra. Vui lòng thử lại sau.';
                    document.body.appendChild(errorMessage);

                    setTimeout(() => {
                        errorMessage.style.opacity = '0';
                        errorMessage.style.transform = 'translateX(100%)';
                        setTimeout(() => {
                            errorMessage.remove();
                        }, 300);
                    }, 3000);
                });
        });
    }
}

// 5. Initialize Change Password Modal
function initChangePasswordModal() {
    const pwModal = document.getElementById('change-password-modal');
    const openPwModalButton = document.getElementById('open-change-password-modal-button');
    const closePwModalButton = document.getElementById('close-change-password-modal-button');
    const pwModalOverlay = document.getElementById('change-password-modal-overlay');
    const pwForm = document.getElementById('change-password-form');
    const pwErrorsContainer = document.getElementById('change-password-errors-container');
    const pwErrorsList = document.getElementById('change-password-errors-list');
    const pwSuccessContainer = document.getElementById('change-password-success-container');

    if (!pwModal || !openPwModalButton) return;

    const openPwModal = () => {
        pwModal.classList.remove('hidden');
        pwErrorsContainer.classList.add('hidden');
        pwSuccessContainer.classList.add('hidden');
        pwForm.reset();
    };

    const closePwModal = () => {
        pwModal.classList.add('hidden');
    };

    openPwModalButton.addEventListener('click', openPwModal);

    if (closePwModalButton) {
        closePwModalButton.addEventListener('click', closePwModal);
    }

    if (pwModalOverlay) {
        pwModalOverlay.addEventListener('click', closePwModal);
    }

    // In your initChangePasswordModal function
    if (pwForm) {
        pwForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            pwErrorsContainer.classList.add('hidden');
            pwSuccessContainer.classList.add('hidden');
            pwErrorsList.innerHTML = '';

            const formData = new FormData(pwForm);

            try {
                const response = await fetch(pwForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Show success message
                    const successMessage = document.createElement('div');
                    // Replace these lines:
                    successMessage.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[9999]';
                    successMessage.textContent = data.message || 'Update password successfully!';
                    document.body.appendChild(successMessage);

                    // With this:
                    successMessage.className = 'fixed top-20 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[9999]';
                    successMessage.textContent = data.message || 'Update password fail!';
                    document.body.appendChild(successMessage);

                    // Add fade out effect when removing
                    setTimeout(() => {
                        successMessage.style.opacity = '0';
                        successMessage.style.transition = 'opacity 0.3s ease-out';
                        setTimeout(() => {
                            successMessage.remove();
                            closePwModal();
                            pwForm.reset();
                        }, 300);
                    }, 2000);

                    // Remove the message after 3 seconds
                    setTimeout(() => {
                        successMessage.remove();
                        closePwModal();
                        pwForm.reset();
                    }, 2000);
                } else if (data.errors) {
                    pwErrorsContainer.classList.remove('hidden');
                    Object.entries(data.errors).forEach(([field, messages]) => {
                        const li = document.createElement('li');
                        li.textContent = Array.isArray(messages) ? messages[0] : messages;
                        pwErrorsList.appendChild(li);
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                pwErrorsContainer.classList.remove('hidden');
                const li = document.createElement('li');
                li.textContent = 'Lỗi kết nối. Vui lòng thử lại sau.';
                pwErrorsList.appendChild(li);
            }
        });
    }
}