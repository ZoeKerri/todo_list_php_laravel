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
                                    
                                    {{-- [SỬA] Đổi <a> thành <button> và thêm id --}}
                                    <button id="open-change-password-modal-button"
                                       class="btn-secondary-surface px-5 py-2 rounded-lg font-semibold text-sm cursor-pointer">Change
                                        password</button>
                                    
                                    <a href="#"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
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
            <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="hidden">
                @csrf
            </form>

            {{-- Nav Tab (Giữ nguyên) --}}
            <nav class="mt-8 border-b account-tab-nav flex-shrink-0">
                <div class="flex space-x-8">
                    <a href="#" class="tab-link active-tab py-3 px-1" data-tab="work">Work <span
                            class="ml-1 badge-muted text-xs px-2 py-0.5 rounded-full">34</span></a>
                    <a href="#" class="tab-link inactive-tab py-3 px-1" data-tab="moodboards">Moodboards</a>
                    <a href="#" class="tab-link inactive-tab py-3 px-1" data-tab="likes">Likes</a>
                </div>
            </nav>

            {{-- Nội dung Tab (Giữ nguyên) --}}
            <section id="tab-content-container" class="flex-1 min-h-0 relative py-8">
                <div id="work" class="tab-panel h-full w-full chart-container">
                    <div id="workChart" class="h-full"></div>
                </div>
                <div id="moodboards" class="tab-panel hidden h-full w-full absolute top-8 left-0 chart-container">
                    <div id="moodboardsChart" class="h-full"></div>
                </div>
                <div id="likes" class="tab-panel hidden h-full w-full absolute top-8 left-0 chart-container">
                    <div id="likesChart" class="h-full"></div>
        </div>
            </section>

        </main>
    </div>
    
    {{-- =============================================== --}}
    {{--         HTML CỦA POPUP CHỈNH SỬA PROFILE     --}}
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
    {{--         [MỚI] HTML CỦA POPUP ĐỔI MẬT KHẨU     --}}
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
                    {{-- Lỗi sẽ được JS chèn vào đây --}}
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
    {{--           KẾT THÚC: POPUP ĐỔI MẬT KHẨU     --}}
    {{-- =============================================== --}}


@endsection