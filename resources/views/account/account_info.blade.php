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
        /* Thêm style cho input file để dùng class 'hidden' của Tailwind */
        .hidden {
            display: none;
        }
    </style>

    {{-- Nền chính (Đã đổi sang dark mode) --}}
    <div class="bg-gray-800 font-sans h-[calc(83vh)] overflow-hidden flex flex-col">

        <main class="max-w-screen-xl mx-auto p-6 md:p-8 w-full flex-1 flex flex-col min-h-0">

            {{-- Card Header chứa thông tin Profile --}}
            <section class="flex-shrink-0 relative bg-gray-700 rounded-lg p-8 shadow-sm overflow-hidden">
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
                                   class="absolute z-20 bottom-0 right-0 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white border-4 border-gray-700 hover:bg-blue-700 transition-colors duration-200 cursor-pointer">
                                    <i class="fas fa-camera text-sm"></i>
        </a>
    </div>

                            {{-- (Con 2) DIV BỌC THÔNG TIN (Tên, Email, Nút bấm) --}}
                            <div>
                                <h1 id="profile-name-display" class="text-3xl font-bold text-white">{{ $user->full_name ?? 'N/A' }}
                                </h1>
                                <p class="text-gray-300 mt-1">{{ $user->email }}</p>

                                {{-- Các nút bấm --}}
                                <div class="mt-4 flex flex-wrap gap-3">
                                    <button id="open-edit-modal-button"
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold text-sm cursor-pointer">Update</button>
                                    
                                    {{-- [SỬA] Đổi <a> thành <button> và thêm id --}}
                                    <button id="open-change-password-modal-button"
                                       class="bg-gray-600 hover:bg-gray-500 text-white px-5 py-2 rounded-lg font-semibold text-sm cursor-pointer">Change
                                        password</button>
                                    
                                    <a href="#"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                       class="bg-red-600 hover:bg-red-500 text-white px-5 py-2 rounded-lg font-semibold text-sm">Sign
                                        out</a>
        </div>
    </div>
    
                        </div> {{-- Hết bọc chung --}}


                        {{-- STATS (Phone, Member Since) --}}
                        <div class="mt-6 md:mt-0 flex space-x-8 text-center md:text-right">
                            <div>
                                <span id="profile-phone-display" class="text-2xl font-bold text-white">{{ $user->phone ?? 'N/A' }}</span>
                                <p class="text-sm text-gray-400">Phone</p>
                            </div>
                            <div>
                                <span
                                    class="text-2xl font-bold text-white">{{ $user->created_at ? $user->created_at->format('M Y') : 'N/A' }}</span>
                                <p class="text-sm text-gray-400">Member Since</p>
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
            <nav class="mt-8 border-b border-gray-600 flex-shrink-0">
                <div class="flex space-x-8">
                    <a href="#" class="tab-link active-tab py-3 px-1" data-tab="work">Work <span
                            class="ml-1 bg-gray-600 text-gray-200 text-xs px-2 py-0.5 rounded-full">34</span></a>
                    <a href="#" class="tab-link py-3 px-1" data-tab="moodboards">Moodboards</a>
                    <a href="#" class="tab-link py-3 px-1" data-tab="likes">Likes</a>
                </div>
            </nav>

            {{-- Nội dung Tab (Giữ nguyên) --}}
            <section id="tab-content-container" class="flex-1 min-h-0 relative py-8">
                <div id="work" class="tab-panel h-full w-full">
                    <div id="workChart" class="h-full"></div>
                </div>
                <div id="moodboards" class="tab-panel hidden h-full w-full absolute top-8 left-0">
                    <div id="moodboardsChart" class="h-full"></div>
                </div>
                <div id="likes" class="tab-panel hidden h-full w-full absolute top-8 left-0">
                    <div id="likesChart" class="h-full"></div>
        </div>
            </section>

        </main>
    </div>
    
    {{-- =============================================== --}}
    {{--         HTML CỦA POPUP CHỈNH SỬA PROFILE     --}}
    {{-- =============================================== --}}
    <div id="edit-profile-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div id="modal-overlay" class="absolute inset-0 bg-black bg-opacity-75"></div>
        <div class="relative w-full max-w-lg p-6 bg-gray-700 rounded-lg shadow-xl">
            <div class="flex items-center justify-between pb-4 border-b border-gray-600">
                <h3 class="text-xl font-semibold text-white">Edit Profile</h3>
                <button id="close-edit-modal-button" class="text-gray-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div id="modal-errors-container" class="hidden mt-4 p-3 bg-red-800 border border-red-700 rounded-lg">
                <ul id="modal-errors-list" class="list-disc pl-5 text-red-200 text-sm"></ul>
            </div>
            <form id="edit-profile-form" action="{{ url('/account-info/edit') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-300">Name</label>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" required class="mt-1 block w-full bg-gray-600 border border-gray-500 rounded-lg shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-300">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full bg-gray-600 border border-gray-500 rounded-lg shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold text-sm">
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
        <div id="change-password-modal-overlay" class="absolute inset-0 bg-black bg-opacity-75"></div>

        {{-- Nội dung Modal (Form) --}}
        <div class="relative w-full max-w-lg p-6 bg-gray-700 rounded-lg shadow-xl">
            
            {{-- Header của Modal --}}
            <div class="flex items-center justify-between pb-4 border-b border-gray-600">
                <h3 class="text-xl font-semibold text-white">Change Password</h3>
                <button id="close-change-password-modal-button" class="text-gray-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            {{-- Vùng hiển thị lỗi Validation --}}
            <div id="change-password-errors-container" class="hidden mt-4 p-3 bg-red-800 border border-red-700 rounded-lg">
                <ul id="change-password-errors-list" class="list-disc pl-5 text-red-200 text-sm">
                    {{-- Lỗi sẽ được JS chèn vào đây --}}
                </ul>
    </div>

            {{-- Vùng hiển thị thành công --}}
            <div id="change-password-success-container" class="hidden mt-4 p-3 bg-green-800 border border-green-700 rounded-lg">
                 <p class="text-green-200 text-sm">Password changed successfully!</p>
            </div>

            {{-- Form đổi mật khẩu --}}
            <form id="change-password-form" action="{{ url('/account-info/change-password') }}" method="POST" class="mt-6 space-y-4">
        @csrf
                
                {{-- Old Password --}}
                <div>
                    <label for="old_password" class="block text-sm font-medium text-gray-300">Old Password</label>
                    <input type="password" id="old_password" name="old_password" required
                           class="mt-1 block w-full bg-gray-600 border border-gray-500 rounded-lg shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                {{-- New Password --}}
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-300">New Password</label>
                    <input type="password" id="new_password" name="new_password" required
                           class="mt-1 block w-full bg-gray-600 border border-gray-500 rounded-lg shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
</div>

                {{-- Confirm New Password --}}
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-300">Confirm New Password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                           class="mt-1 block w-full bg-gray-600 border border-gray-500 rounded-lg shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Nút bấm --}}
                <div class="pt-4 flex justify-end">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold text-sm">
                        Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- =============================================== --}}
    {{--           KẾT THÚC: POPUP ĐỔI MẬT KHẨU     --}}
    {{-- =============================================== --}}


    {{-- Include the JavaScript file --}}
    @vite(['resources/js/authentication/account.js'])
@endsection