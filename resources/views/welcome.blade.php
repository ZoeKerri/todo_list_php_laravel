@extends('layouts.app')

@section('title', 'Your Tasks Dashboard')

@section('content')
    {{-- Container chính với padding, kế thừa từ #main-content trong app.blade.php --}}
    <div class="p-6 md:p-8">

        {{-- Tiêu đề trang (Giữ nguyên từ code của bạn) --}}
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-200">
                Hi there, {{ Auth::check() ? Auth::user()->name : 'Guest' }}
            </h2>
            <h4 class="text-4xl font-bold text-white">Your Task</h4>
        </div>

        {{--
        BỐ CỤC GRID 2 CỘT CHO DESKTOP
        Sử dụng 'grid-cols-1' cho mobile (mặc định)
        Sử dụng 'lg:grid-cols-3' cho desktop (breakpoint 'lg' 1024px)
        --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- =================================== --}}
            {{-- CỘT CHÍNH (Nội dung Task) --}}
            {{-- Chiếm 2/3 không gian trên desktop --}}
            {{-- =================================== --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- 1. Thanh Lọc Thể loại (Sẽ thiết kế ở Phần 3) --}}
                <div id="category-filter-bar-container">
                    {{-- Đặt trong <div id="category-filter-bar-container"> của Phần 2 --}}
                        <div class="flex justify-between items-center bg-gray-800 p-4 rounded-lg shadow-lg border border-gray-700">

                            {{-- Vùng chứa các thể loại có thể cuộn --}}
                            {{-- 'overflow-x-auto' là chìa khóa --}}
                            {{-- 'scrollbar-thin' (tùy chọn) là một plugin Tailwind để làm đẹp thanh cuộn --}}
                            <div class="flex-grow overflow-x-auto whitespace-nowrap scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800"
                                style="padding-bottom: 8px; margin-bottom: -8px;"> {{-- Kỹ thuật ẩn thanh cuộn --}}

                                <div class="flex items-center gap-3">
                                    {{-- Nút "Tất cả" (Mặc định) --}}
                                    <a href="#"
                                        class="category-filter-btn active-category px-4 py-2 rounded-full text-sm font-medium flex-shrink-0 bg-purple-600 text-white transition-colors"
                                        data-category-id="all">
                                        All Tasks
                                    </a>

                                    {{--
                                    Vòng lặp các thể loại từ Database (Giả định)
                                    @foreach ($categories as $category)
                                    --}}
                                    {{-- Ví dụ các nút không được chọn --}}
                                    <a href="#"
                                        class="category-filter-btn inactive-category px-4 py-2 rounded-full text-sm font-medium flex-shrink-0 text-gray-300 bg-gray-700 hover:bg-gray-600 transition-colors"
                                        data-category-id="1" {{-- data-category-id="{{ $category->id }}" --}}>
                                        Personal {{-- {{ $category->name }} --}}
                                    </a>
                                    <a href="#" class="category-filter-btn inactive-category... (như trên)"
                                        data-category-id="2">
                                        Work
                                    </a>
                                    <a href="#" class="category-filter-btn inactive-category... (như trên)"
                                        data-category-id="3">
                                        Health
                                    </a>
                                    <a href="#" class="category-filter-btn inactive-category... (như trên)"
                                        data-category-id="4">
                                        Study
                                    </a>

                                    {{-- Thêm các nút giả để demo thanh cuộn (xóa khi có dữ liệu thật) --}}
                                    <a href="#" class="category-filter-btn inactive-category... (như trên)"
                                        data-category-id="5">
                                        Shopping
                                    </a>
                                    <a href="#" class="category-filter-btn inactive-category... (như trên)"
                                        data-category-id="6">
                                        Finance
                                    </a>
                                    <a href="#" class="category-filter-btn inactive-category... (như trên)"
                                        data-category-id="7">
                                        Urgent
                                    </a>
                                    {{-- @endforeach --}}
                                </div>
                            </div>

                            {{-- Nút "Thêm Thể loại Mới" (+) --}}
                            <button id="add-category-btn"
                                class="ml-4 flex-shrink-0 w-10 h-10 rounded-full bg-purple-600 hover:bg-purple-700 text-white flex items-center justify-center transition-colors"
                                title="Create new category">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v12m6-6H6"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- 2. Bộ lọc Ngày (Lấy cảm hứng từ Image 1) --}}
                    {{-- Đây là component 'calendar' từ code welcome.blade.php của bạn,
                    nhưng được làm responsive hơn --}}
                    <div class="bg-gray-800 rounded-lg shadow-lg p-4">
                        <div class="flex justify-around items-center">
                            {{-- Logic @foreach cho các ngày trong tuần --}}
                            <div class="text-center p-2 rounded-lg bg-purple-600 cursor-pointer">
                                <span class="text-sm font-medium text-purple-200">Sun</span>
                                <span class="block text-2xl font-bold text-white">8</span>
                                <span class="text-sm font-medium text-purple-200">Jun</span>
                            </div>
                            <div class="text-center p-2 rounded-lg hover:bg-gray-700 cursor-pointer">
                                <span class="text-sm font-medium text-gray-400">Mon</span>
                                <span class="block text-2xl font-bold text-white">9</span>
                                <span class="text-sm font-medium text-gray-400">Jun</span>
                            </div>
                            {{--... Thêm các ngày khác... --}}
                        </div>
                    </div>

                    {{-- 3. Danh sách Công việc (Sẽ thiết kế ở Phần 5) --}}
                    <div id="task-list-container" class="space-y-4">
                        {{-- Trạng thái tải (Loading state) --}}
                        <div class_l="text-center p-8 bg-gray-800 rounded-lg">
                            <p class="text-gray-400">Loading tasks...</p>
                        </div>
                        {{-- Mã nguồn cho các 'task-item' sẽ được JS chèn vào đây --}}
                    </div>
                </div>

                {{-- =================================== --}}
                {{-- CỘT PHỤ (Thông tin Ngữ cảnh) --}}
                {{-- Chiếm 1/3 không gian, chỉ hiển thị trên desktop --}}
                {{-- =================================== --}}
                <div class="lg:col-span-1 space-y-6 hidden lg:block">

                    {{-- Component Lịch (Ví dụ) --}}
                    <div class="bg-gray-800 p-4 rounded-lg shadow-lg border border-gray-700">
                        <h5 class="text-white font-semibold mb-4 text-lg">Full Calendar</h5>
                        {{--
                        Đây là nơi lý tưởng để nhúng một thư viện lịch JS
                        (ví dụ: FullCalendar.js, VCalendar)
                        để hiển thị tổng quan công việc
                        --}}
                        <div id="calendar-widget" class="h-64 bg-gray-700 rounded">
                            {{-- Calendar widget goes here --}}
                        </div>
                    </div>

                    {{-- Component Thống kê Nhanh (Ví dụ) --}}
                    <div class="bg-gray-800 p-4 rounded-lg shadow-lg border border-gray-700">
                        <h5 class="text-white font-semibold mb-4 text-lg">Stats Overview</h5>
                        {{-- Liên kết đến trang 'Stats' (từ app.blade.php) --}}
                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-300">
                                <span>Tasks Today:</span>
                                <span class="font-bold text-white">5 / 8</span>
                            </div>
                            <div class="flex justify-between text-gray-300">
                                <span>This Week:</span>
                                <span class="font-bold text-white">24 / 40</span>
                            </div>
                            <a href="/stats" class="block text-center text-purple-400 hover:text-purple-300 pt-2">
                                View Detailed Stats &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Nút "Add Task" (FAB) - Giờ đây được quản lý bởi JS --}}
        <button id="add-task-btn"
            class="fixed bottom-8 right-8 bg-purple-600 hover:bg-purple-700 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-lg z-40 transform transition-transform hover:scale-105">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
            </svg>
        </button>
@endsection