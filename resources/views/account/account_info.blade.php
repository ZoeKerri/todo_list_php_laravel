@extends('layouts.app')

@section('content')

    {{-- 
        CÁC SCRIPT VÀ STYLE
        - ĐÃ THÊM FONT AWESOME
    --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    {{-- [QUAN TRỌNG] Link này để icon 'fas fa-camera' hoạt động --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <style>
        /* Ẩn thanh cuộn */
        ::-webkit-scrollbar {
            display: none;
        }

        .account-page {
            background-color: var(--bg-primary);
            color: var(--text-primary);
        }

        .account-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        }

        .text-primary-color {
            color: var(--text-primary);
        }

        .text-secondary-color {
            color: var(--text-secondary);
        }

        .text-muted-color {
            color: var(--text-muted);
        }

        .btn-accent {
            background-color: var(--accent-color);
            color: var(--text-primary);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .btn-accent:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-secondary-surface {
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .btn-secondary-surface:hover {
            background-color: var(--hover-bg);
            transform: translateY(-1px);
        }

        .btn-danger-outline {
            background: transparent;
            border: 1px solid #ef4444;
            color: #ef4444;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .btn-danger-outline:hover {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ff6b6b;
        }

        .account-tab-nav {
            border-color: var(--border-color);
        }

        .tab-link {
            color: var(--text-secondary);
            transition: color 0.2s ease, border-color 0.2s ease;
            border-bottom: 2px solid transparent;
        }

        .tab-link.active-tab {
            color: var(--text-primary);
            border-color: var(--accent-color);
            font-weight: 600;
        }

        .tab-link.inactive-tab:hover {
            color: var(--text-primary);
        }

        .chart-container {
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 16px;
        }

        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.75);
        }

        .modal-surface {
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
        }

        .input-surface {
            background-color: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .input-surface:focus {
            border-color: var(--accent-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(106, 27, 154, 0.2);
        }

        .badge-muted {
            background-color: var(--bg-tertiary);
            color: var(--text-muted);
        }

        .avatar-action-button {
            background-color: var(--accent-color);
            color: var(--text-primary);
            border: 4px solid var(--bg-secondary);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .avatar-action-button:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .icon-button {
            color: var(--text-muted);
            transition: color 0.2s ease;
        }

        .icon-button:hover {
            color: var(--text-primary);
        }
    </style>

    {{-- Nền chính (Đã đổi sang dark mode) --}}
    <div class="account-page font-sans h-[calc(83vh)] overflow-hidden flex flex-col">

        <main class="max-w-screen-xl mx-auto p-6 md:p-8 w-full flex-1 flex flex-col min-h-0">

            {{-- Card Header chứa thông tin Profile --}}
            <section class="flex-shrink-0 relative account-card rounded-lg p-8 shadow-sm overflow-hidden">
                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">

                        {{-- BỌC CHUNG AVATAR VÀ THÔNG TIN --}}
                        <div class="flex items-center space-x-6">

                            {{-- (Con 1) DIV BỌC AVATAR (Icon kế bên) --}}
                            <div class="relative w-24 h-24 md:w-32 md:h-32 flex-shrink-0">
                                
                                <a href="#" onclick="event.preventDefault(); document.getElementById('avatar-input').click()" class="cursor-pointer">
                                    <img id="avatar-image"
                                         src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://i.pravatar.cc/150?u=' . $user->email }}"
                                         alt="{{ $user->full_name ?? 'Avatar' }}"
                                         class="w-full h-full rounded-full border-4 border-white shadow-md object-cover">
                                </a>
                                <input type="file" id="avatar-input" accept="image/*" class="hidden">
                                <a href="#"
                                   onclick="event.preventDefault(); document.getElementById('avatar-input').click()"
                                   class="absolute z-20 bottom-0 right-0 w-10 h-10 rounded-full flex items-center justify-center avatar-action-button cursor-pointer">
                                     <i class="fas fa-camera text-sm"></i>
    </a>
    </div>

                            {{-- (Con 2) DIV BỌC THÔNG TIN (Tên, Email, Nút bấm) --}}
                            <div>
                                <h1 id="profile-name-display" class="text-3xl font-bold text-primary-color">{{ $user->full_name ?? 'N/A' }}
                                </h1>
                                <p class="text-secondary-color mt-1">{{ $user->email }}</p>

                                {{-- Các nút bấm --}}
                                <div class="mt-4 flex flex-wrap gap-3">
                                    <button id="open-edit-modal-button"
                                        class="btn-accent px-5 py-2 rounded-lg font-semibold text-sm cursor-pointer">Update</button>
                                    
                                    <button id="open-change-password-modal-button"
                                        class="btn-secondary-surface px-5 py-2 rounded-lg font-semibold text-sm cursor-pointer">Change
                                        password</button>
                                    
                                    <a href="{{ url('/logout') }}"
                                       data-logout-trigger
                                       data-logout-form="account-logout-form"
                                       class="btn-danger-outline px-5 py-2 rounded-lg font-semibold text-sm">Sign
                                        out</a>
    </div>
    </div>
    
                        </div> {{-- Hết bọc chung --}}


                        {{-- STATS (Phone, Member Since) --}}
                        <div class="mt-6 md:mt-0 flex space-x-8 text-center md:text-right">
                            <div>
                                <span id="profile-phone-display" class="text-2xl font-bold text-primary-color">{{ $user->phone ?? 'N/A' }}</span>
                                <p class="text-sm text-muted-color">Phone</p>
                            </div>
                            <div>
                                <span
                                    class="text-2xl font-bold text-primary-color">{{ $user->created_at ? $user->created_at->format('M Y') : 'N/A' }}</span>
                                <p class="text-sm text-muted-color">Member Since</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Form logout ẩn --}}
            <form id="account-logout-form" action="{{ url('/logout') }}" method="POST" class="hidden">
                @csrf
            </form>

            {{-- Nav Tab (Giữ nguyên) --}}
            <nav class="mt-8 border-b account-tab-nav flex-shrink-0">
                <div class="flex space-x-8">
                    <a href="#" class="tab-link active-tab py-3 px-1" data-tab="work">All</a>
                    <a href="#" class="tab-link inactive-tab py-3 px-1" data-tab="moodboards">Personal tasks</a>
                    <a href="#" class="tab-link inactive-tab py-3 px-1" data-tab="likes">Team tasks</a>
                </div>
            </nav>

            {{-- Nội dung Tab (Giữ nguyên HTML, `absolute` là OK) --}}
            <section id="tab-content-container" class="flex-1 min-h-0 relative py-8">
                
                <div id="work" class="tab-panel h-full w-full absolute top-8 left-0 chart-container">
                    <div id="accountWorkChart" class="h-full"></div>
                </div>

                <div id="moodboards" class="tab-panel hidden h-full w-full absolute top-8 left-0 chart-container">
                    <div id="accountMoodboardsChart" class="h-full"></div>
                </div>

                <div id="likes" class="tab-panel hidden h-full w-full absolute top-8 left-0 chart-container">
                    <div id="accountLikesChart" class="h-full"></div>
                </div>
            </section>

        </main>
    </div>
    
    {{-- =============================================== --}}
    {{--     HTML CỦA POPUP CHỈNH SỬA PROFILE       --}}
    {{-- =============================================== --}}
    <div id="edit-profile-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div id="modal-overlay" class="absolute inset-0 modal-overlay"></div>
        <div class="relative w-full max-w-lg p-6 modal-surface rounded-lg shadow-xl">
            <div class="flex items-center justify-between pb-4 border-b" style="border-color: var(--border-color);">
                <h3 class="text-xl font-semibold text-primary-color">Edit Profile</h3>
                <button id="close-edit-modal-button" class="icon-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div id="modal-errors-container" class="hidden mt-4 p-3 rounded-lg" style="background-color: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4);">
                <ul id="modal-errors-list" class="list-disc pl-5 text-sm" style="color: #fecaca;"></ul>
            </div>
            <form id="edit-profile-form" action="{{ url('/account-info/edit') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="full_name" class="block text-sm font-medium text-secondary-color">Name</label>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" required class="mt-1 block w-full input-surface rounded-lg py-2 px-3">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-secondary-color">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full input-surface rounded-lg py-2 px-3">
                </div>
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="btn-accent px-5 py-2 rounded-lg font-semibold text-sm">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- =============================================== --}}
    {{--           KẾT THÚC: POPUP CHỈNH SỬA        --}}
    {{-- =============================================== --}}


    {{-- =============================================== --}}
    {{--     [MỚI] HTML CỦA POPUP ĐỔI MẬT KHẨU       --}}
    {{-- =============================================== --}}
    <div id="change-password-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        
        {{-- Lớp phủ mờ (overlay) --}}
        <div id="change-password-modal-overlay" class="absolute inset-0 modal-overlay"></div>

        {{-- Nội dung Modal (Form) --}}
        <div class="relative w-full max-w-lg p-6 modal-surface rounded-lg shadow-xl">
            
            {{-- Header của Modal --}}
            <div class="flex items-center justify-between pb-4 border-b" style="border-color: var(--border-color);">
                <h3 class="text-xl font-semibold text-primary-color">Change Password</h3>
                <button id="close-change-password-modal-button" class="icon-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            {{-- Vùng hiển thị lỗi Validation --}}
            <div id="change-password-errors-container" class="hidden mt-4 p-3 rounded-lg" style="background-color: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4);">
                <ul id="change-password-errors-list" class="list-disc pl-5 text-sm" style="color: #fecaca;">
                </ul>
            </div>

            {{-- Vùng hiển thị thành công --}}
            <div id="change-password-success-container" class="hidden mt-4 p-3 rounded-lg" style="background-color: rgba(34, 197, 94, 0.2); border: 1px solid rgba(34, 197, 94, 0.4);">
                 <p class="text-sm" style="color: #a7f3d0;">Password changed successfully!</p>
            </div>

            {{-- Form đổi mật khẩu --}}
            <form id="change-password-form" action="{{ url('/account-info/change-password') }}" method="POST" class="mt-6 space-y-4">
    @csrf
                
                {{-- Old Password --}}
                <div>
                    <label for="old_password" class="block text-sm font-medium text-secondary-color">Old Password</label>
                    <input type="password" id="old_password" name="old_password" required
                           class="mt-1 block w-full input-surface rounded-lg py-2 px-3">
                </div>
                
                {{-- New Password --}}
                <div>
                    <label for="new_password" class="block text-sm font-medium text-secondary-color">New Password</label>
                    <input type="password" id="new_password" name="new_password" required
                           class="mt-1 block w-full input-surface rounded-lg py-2 px-3">
</div>

                {{-- Confirm New Password --}}
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-secondary-color">Confirm New Password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                           class="mt-1 block w-full input-surface rounded-lg py-2 px-3">
                </div>

                {{-- Nút bấm --}}
                <div class="pt-4 flex justify-end">
                    <button type="submit" 
                            class="btn-accent px-5 py-2 rounded-lg font-semibold text-sm">
                        Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- =============================================== --}}
    {{--           KẾT THÚC: POPUP ĐỔI MẬT KHẨU      --}}
    {{-- =============================================== --}}


@endsection

{{-- =============================================== --}}
{{--           [SỬA LỚN] KHỐI SCRIPT                --}}
{{-- =============================================== --}}
@push('scripts')
<script>
// Biến toàn cục để lưu trữ các đối tượng chart
window.accountCharts = {
    work: null,
    moodboards: null,
    likes: null
};
// Biến toàn cục để lưu trữ options (chứa data từ fetch)
window.chartOptionsStore = {
    work: null,
    moodboards: null,
    likes: null
};

// Initialize charts when document is ready
document.addEventListener('DOMContentLoaded', function () {
    // 1. [MỚI] Chỉ fetch data và chuẩn bị options
    prepareChartData();

    // 2. Initialize Tab Switching (sẽ được sửa để render chart)
    initTabs();

    // 3. Initialize Avatar Upload
    initAvatarUpload();

    // 4. Initialize Edit Profile Modal
    initEditProfileModal();

    // 5. Initialize Change Password Modal
    initChangePasswordModal();
});

// 1. [SỬA LỚN] Đổi tên từ initCharts -> prepareChartData
// Hàm này chỉ fetch data và tạo options, KHÔNG render
function prepareChartData() {
    fetch('/statistics/monthly-json', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(stats => {
        const categories = stats.labels || [];

        // Chart "All"
        window.chartOptionsStore.work = {
            series: [
                { name: 'Completed', data: (stats.all?.completed || []) },
                { name: 'Pending', data: (stats.all?.pending || []) }
            ],
            chart: { type: 'bar', height: '100%', toolbar: { show: false }, background: 'transparent', stacked: true },
            theme: { mode: 'dark' },
            xaxis: { categories },
            yaxis: {
                labels: {
                    formatter: function (val) { return val.toFixed(0); }
                },
                tickAmount: 5 // Đảm bảo trục Y có chia vạch
            },
            colors: ['#10B981', '#F59E0B']
        };

        // Chart "Personal"
        window.chartOptionsStore.moodboards = {
            series: [
                { name: 'Completed', data: (stats.personal?.completed || []) },
                { name: 'Pending', data: (stats.personal?.pending || []) }
            ],
            chart: { type: 'bar', height: '100%', toolbar: { show: false }, background: 'transparent', stacked: true },
            theme: { mode: 'dark' },
            xaxis: { categories },
            yaxis: {
                labels: {
                    formatter: function (val) { return val.toFixed(0); }
                },
                tickAmount: 5
            },
            colors: ['#3B82F6', '#F59E0B']
        };

        // Chart "Team"
        window.chartOptionsStore.likes = {
            series: [
                { name: 'Completed', data: (stats.team?.completed || []) },
                { name: 'Pending', data: (stats.team?.pending || []) }
            ],
            chart: { type: 'bar', height: '100%', toolbar: { show: false }, background: 'transparent', stacked: true },
            theme: { mode: 'dark' },
            xaxis: { categories },
            yaxis: {
                labels: {
                    formatter: function (val) { return val.toFixed(0); }
                },
                tickAmount: 5
            },
            colors: ['#8B5CF6', '#F59E0B']
        };

        // [QUAN TRỌNG] Sau khi fetch xong, render chart cho tab đang active
        // Kích hoạt lại tab active ban đầu để trigger render
        const activeTab = document.querySelector('.tab-link.active-tab');
        if (activeTab) {
            activateTab(activeTab, activeTab.dataset.tab, true); // true = force render
        }

    })
    .catch(() => {});
}

// 2. [SỬA LỚN] Hàm initTabs sẽ chứa logic render
function initTabs() {
    const tabs = document.querySelectorAll('.tab-link');
    
    // Set up click handlers for all tabs
    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            const targetTab = e.currentTarget;
            const targetPanelId = targetTab.dataset.tab;
            activateTab(targetTab, targetPanelId, false); // false = don't force
        });
    });

    // Kích hoạt tab đầu tiên (nếu data chưa về, nó sẽ được gọi lại trong prepareChartData)
    const activeTab = document.querySelector('.tab-link.active-tab');
    if (activeTab) {
         activateTab(activeTab, activeTab.dataset.tab, false);
    }
}

// [SỬA LỚN] Hàm activateTab, giờ là trung tâm xử lý
// forceRender = true nghĩa là render lại ngay cả khi chart đã tồn tại
function activateTab(tab, targetPanelId, forceRender = false) {
    const activeClasses = ['active-tab'];
    const inactiveClasses = ['inactive-tab'];
    const allTabs = document.querySelectorAll('.tab-link');
    const allPanels = document.querySelectorAll('.tab-panel');

    // 1. Update active tab
    allTabs.forEach(t => {
        t.classList.remove(...activeClasses);
        t.classList.add(...inactiveClasses);
    });
    tab.classList.add(...activeClasses);
    tab.classList.remove(...inactiveClasses);

    // 2. Show target panel
    allPanels.forEach(panel => {
        if (panel.id === targetPanelId) {
            panel.classList.remove('hidden');
        } else {
            panel.classList.add('hidden');
        }
    });

    // 3. [SỬA] Logic render chart
    // targetPanelId là 'work', 'moodboards', 'likes'
    // chartId là 'accountWorkChart', 'accountMoodboardsChart', 'accountLikesChart'
    const chartMapping = {
        work: { id: 'accountWorkChart', options: window.chartOptionsStore.work, chart: window.accountCharts.work },
        moodboards: { id: 'accountMoodboardsChart', options: window.chartOptionsStore.moodboards, chart: window.accountCharts.moodboards },
        likes: { id: 'accountLikesChart', options: window.chartOptionsStore.likes, chart: window.accountCharts.likes }
    };

    const targetChart = chartMapping[targetPanelId];

    if (targetChart) {
        // Chỉ render NẾU:
        // 1. Data đã về (options tồn tại)
        // 2. Chart chưa được khởi tạo (chart === null) HOẶC bị ép render (forceRender)
        if (targetChart.options && (!targetChart.chart || forceRender)) {
            
            // Nếu chart đã tồn tại (forceRender), hủy nó đi
            if (targetChart.chart) {
                targetChart.chart.destroy();
            }

            // Lấy div
            const chartEl = document.querySelector("#" + targetChart.id);
            if (chartEl) {
                // Tạo chart mới
                const newChart = new ApexCharts(chartEl, targetChart.options);
                newChart.render();
                
                // Lưu lại
                window.accountCharts[targetPanelId] = newChart;
            }
        }
    }
}


// 3. Initialize Avatar Upload (Hàm này giữ nguyên)
function initAvatarUpload() {
    const avatarInput = document.getElementById('avatar-input');
    const avatarImage = document.getElementById('avatar-image');
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : null;

    if (avatarInput && avatarImage && csrfToken) {
        avatarInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('avatar', file);
                formData.append('_token', csrfToken); 

                avatarImage.style.opacity = '0.5';

                fetch('/account-info/upload-avatar', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json' 
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.avatar_url) {
                            avatarImage.src = data.avatar_url;
                        } else {
                            alert(data.message || 'Failed to update avatar');
                        }
                    })
                    .catch(error => {
                        if (error.errors && error.errors.avatar) {
                            alert(error.errors.avatar[0]); 
                        } else {
                            console.error('Error:', error);
                            alert('Error updating avatar. Check console.');
                        }
                    })
                    .finally(() => {
                        avatarImage.style.opacity = '1';
                        avatarInput.value = ''; 
                    });
            }
        });
    } else if (!csrfToken) {
        console.error("CSRF token meta tag not found!");
    }
}

// 4. Initialize Edit Profile Modal (Hàm này giữ nguyên)
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
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : null;

    if (!modal || !openModalButton || !csrfToken) return;

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
                    'X-CSRF-TOKEN': csrfToken 
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        profileNameDisplay.childNodes[0].nodeValue = data.full_name; 
                        if (data.phone) {
                            profilePhoneDisplay.textContent = data.phone;
                        } else {
                            profilePhoneDisplay.textContent = 'N/A'; 
                        }

                        const successMessage = document.createElement('div');
                        successMessage.className = 'fixed top-20 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[9999] transform translate-x-0 transition-all duration-300';
                        successMessage.style.position = 'fixed';
                        successMessage.style.zIndex = '9999';
                        successMessage.textContent = data.message || 'Cập nhật thông tin thành công!';
                        document.body.appendChild(successMessage);

                        setTimeout(() => {
                            successMessage.style.opacity = '0';
                            successMessage.style.transform = 'translateX(100%)';
                            setTimeout(() => {
                                successMessage.remove();
                                closeModal();
                            }, 300);
                        }, 2000);
                    } else if (data.errors) {
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

// 5. Initialize Change Password Modal (Hàm này giữ nguyên)
function initChangePasswordModal() {
    const pwModal = document.getElementById('change-password-modal');
    const openPwModalButton = document.getElementById('open-change-password-modal-button');
    const closePwModalButton = document.getElementById('close-change-password-modal-button');
    const pwModalOverlay = document.getElementById('change-password-modal-overlay');
    const pwForm = document.getElementById('change-password-form');
    const pwErrorsContainer = document.getElementById('change-password-errors-container');
    const pwErrorsList = document.getElementById('change-password-errors-list');
    const pwSuccessContainer = document.getElementById('change-password-success-container'); 
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : null;

    if (!pwModal || !openPwModalButton || !csrfToken) return;

    const openPwModal = () => {
        pwModal.classList.remove('hidden');
        pwErrorsContainer.classList.add('hidden');
        pwSuccessContainer.classList.add('hidden'); 
        pwErrorsList.innerHTML = ''; 
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
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();

                if (data.success) {
                    const successMessage = document.createElement('div');
                    successMessage.className = 'fixed top-20 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[9999] transform translate-x-0 transition-all duration-300';
                    successMessage.style.position = 'fixed';
                    successMessage.style.zIndex = '9999';
                    successMessage.textContent = data.message || 'Update password successfully!';
                    document.body.appendChild(successMessage);

                    setTimeout(() => {
                        successMessage.style.opacity = '0';
                        successMessage.style.transform = 'translateX(100%)';
                        setTimeout(() => {
                            successMessage.remove();
                            closePwModal();
                            pwForm.reset();
                        }, 300);
                    }, 2000);

                } else if (data.errors) {
                    pwErrorsContainer.classList.remove('hidden');
                    Object.entries(data.errors).forEach(([field, messages]) => {
                        const li = document.createElement('li');
                        li.textContent = Array.isArray(messages) ? messages[0] : messages;
                        pwErrorsList.appendChild(li);
                    });
                } else {
                     pwErrorsContainer.classList.remove('hidden');
                     const li = document.createElement('li');
                     li.textContent = data.message || 'An unknown error occurred.';
                     pwErrorsList.appendChild(li);
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
</script>
@endpush