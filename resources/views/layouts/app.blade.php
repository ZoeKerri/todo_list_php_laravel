<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Your Task App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@php
    $isLightTheme = App\Helpers\ThemeHelper::isLightTheme();
    $themeVars = App\Helpers\ThemeHelper::getThemeColors($isLightTheme);
@endphp

    <style>
        /* --- THEME VARIABLES --- */
        :root {
            @foreach($themeVars as $key => $value)
            {{ $key }}: {{ $value }};
            @endforeach
        }

        /* --- BODY VÀ SIDEBAR --- */
        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 60px;
            background-color: var(--bg-secondary);
            transition: width 0.3s ease, background-color 0.3s ease;
            overflow: hidden;
            z-index: 1000;
        }
        .sidebar.expanded { width: 200px; }
        .sidebar ul { list-style: none; padding: 0; margin-top: 60px; }
        .sidebar li { color: var(--text-secondary); cursor: pointer; transition: background-color 0.2s ease, color 0.2s ease; }
        .sidebar li#setting { display: flex; align-items: center; padding: 15px 20px; }
        .sidebar li a {
            color: inherit;
            text-decoration: none;
            display: flex;
            align-items: center;
            width: 100%;
            padding: 15px 20px;
        }
        .sidebar li:hover { background-color: var(--bg-tertiary); color: var(--text-primary); }
        .sidebar li.active { background-color: var(--accent-color); color: var(--text-primary); }
        .sidebar i { font-size: 18px; margin-right: 10px; color: inherit; transition: color 0.3s ease; }
        .sidebar span { white-space: nowrap; opacity: 0; transition: opacity 0.3s ease; }
        .sidebar.expanded span { opacity: 1; }
        .toggle-btn {
            position: fixed;
            top: 15px;
            left: 10px;
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            z-index: 1100;
            transition: left 0.3s ease, background-color 0.3s ease;
        }
        .sidebar.expanded .toggle-btn { left: 150px; }

        /* --- TOP NAVBAR --- */
        .top-navbar {
            position: fixed;
            top: 0;
            left: 60px;
            right: 0;
            height: 60px;
            background-color: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px; 
            z-index: 999;
            transition: left 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
        }
        .top-navbar.expanded { left: 200px; }
        .navbar-logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--text-primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        /* *** THAY ĐỔI CSS: SỬA .navbar-user VÀ THÊM .dropdown-menu *** */
        .navbar-user {
            position: relative; /* Để neo dropdown */
        }
        /* Nút bấm trigger */
        .navbar-user-trigger {
            background: none;
            border: none;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 5px;
            transition: color 0.3s ease;
        }
        .navbar-user-trigger:hover, .navbar-user-trigger:focus {
            color: var(--text-primary);
        }
        .navbar-user-trigger i {
            font-size: 1.8rem;
        }
        /* Menu dropdown */
        .dropdown-menu {
            display: none; /* Ẩn mặc định */
            position: absolute;
            top: 110%; /* Hơi hở 1 chút */
            right: 0;
            background-color: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            min-width: 220px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 1001;
            overflow: hidden;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .dropdown-menu.active {
            display: block; /* Hiện khi có class 'active' */
        }
        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .dropdown-menu a:hover {
            background-color: var(--accent-color);
            color: #fff;
        }
        .dropdown-menu i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }
        /* Phân cách cho form đăng xuất */
        .dropdown-menu form {
            border-top: 1px solid var(--border-color);
            transition: border-color 0.3s ease;
        }
        /* *** KẾT THÚC THAY ĐỔI CSS *** */


        /* --- MAIN CONTENT & CSS CŨ --- */
        .main-content {
            margin-left: 60px;
            margin-top: 60px;
            padding: 30px;
            transition: margin-left 0.3s ease;
            min-height: calc(100vh - 60px);
        }
        .main-content.expanded { margin-left: 200px; }
        .scrollable-buttons { display: flex; gap: 10px; overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none; touch-action: pan-x; cursor: grab; position: relative; }
        .scrollable-buttons:active { cursor: grabbing; }
        .scrollable-buttons::-webkit-scrollbar { display: none; }
        .btn-purple { background-color: #6a1b9a; color: white; border: none; padding: 10px 20px; border-radius: 20px; cursor: pointer; white-space: nowrap; flex-shrink: 0; touch-action: manipulation; }
        .modal { display: none; position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 2000; align-items: center; justify-content: center; padding: 20px; }
        .modal-content { background-color: var(--bg-secondary); padding: 20px; border-radius: 10px; width: 100%; max-width: 480px; position: relative; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6); transform: none; transition: background-color 0.3s ease; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
        .close { color: var(--text-secondary); font-size: 24px; cursor: pointer; transition: color 0.3s ease; }
        .task-settings { display: grid; gap: 12px; }
        .setting-item { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 10px; background-color: var(--bg-tertiary); border-radius: 8px; transition: background-color 0.3s ease; }
        .meta { display: flex; flex-direction: column; gap: 6px; flex: 1 1 auto; min-width: 0; }
        .setting-title { display: flex; align-items: center; gap: 10px; font-weight: 600; color: var(--text-primary); transition: color 0.3s ease; }
        .setting-title i { font-size: 18px; width: 20px; text-align: center; color: var(--text-secondary); transition: color 0.3s ease; }
        .setting-desc { margin: 0; color: var(--text-muted); font-size: 0.92em; line-height: 1.1; white-space: normal; transition: color 0.3s ease; }
        .switch { position: relative; display: inline-block; width: 60px; height: 34px; flex-shrink: 0; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #555; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked+.slider { background-color: #6a1b9a; }
        input:checked+.slider:before { transform: translateX(26px); }
        input:focus+.slider { box-shadow: 0 0 0 3px rgba(106, 27, 154, 0.15); }
        .calendar { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin: 20px 0; }
        .calendar div { text-align: center; padding: 10px; background-color: var(--bg-tertiary); border-radius: 5px; transition: background-color 0.3s ease; }
        .calendar span { display: block; font-size: 1.2em; font-weight: bold; }
        @media (max-width: 520px) {
            .modal-content { max-width: 100%; }
            .setting-title { font-size: 0.98em; }
            .navbar-user span { display: none; }
            .top-navbar { padding: 0 15px; }
        }

        /* --- ACCOUNT PAGES STYLES --- */
        .account-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .account-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        .account-header a {
            color: var(--text-primary);
            font-size: 1.5rem;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .account-header h2 {
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0;
            color: var(--text-primary);
            transition: color 0.3s ease;
        }
        .avatar-section {
            margin-bottom: 40px;
            position: relative;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .avatar-section img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: var(--border-color);
            flex-shrink: 0;
            transition: background-color 0.3s ease;
        }
        .avatar-section .edit-icon {
            position: absolute;
            bottom: 0;
            left: calc(50% + 30px);
            background-color: var(--accent-color);
            color: var(--text-primary);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--bg-primary);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .account-form-group {
            margin-bottom: 20px;
        }
        .account-form-group label {
            display: block;
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 8px;
            transition: color 0.3s ease;
        }
        .account-form-group .info-text,
        .account-form-group input {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 1rem;
            box-sizing: border-box;
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
        .account-form-group .info-text {
            background-color: var(--bg-tertiary);
        }
        .account-form-group i {
            margin-right: 10px;
            color: var(--text-muted);
            transition: color 0.3s ease;
        }
        .btn-account {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 15px;
        }
        .account-container .btn-primary {
            background-color: var(--accent-color);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .account-container .btn-secondary {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
        .account-container .btn-logout {
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: #e74c3c;
            transition: border-color 0.3s ease;
        }
        .large-icon {
            text-align: center;
            font-size: 4rem;
            color: var(--accent-color);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px auto;
            transition: color 0.3s ease;
        }
        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: opacity 0.5s ease;
        }
        .alert-success {
            background-color: #28a745;
            color: white;
        }
        .alert-danger {
            background-color: #dc3545;
            color: white;
        }
        .alert-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .alert-info {
            background-color: #17a2b8;
            color: white;
        }
    </style>

    @stack('styles')
</head>

<body>

    <div id="sidebar" class="sidebar">
        <button id="toggleBtn" class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <ul>
            <li class="{{ Request::is('tasks') || Request::is('/') ? 'active' : '' }}">
                <a href="{{ url('/tasks') }}">
                    <i class="fas fa-tasks"></i><span>Tasks</span>
                </a>
            </li>
            <li class="{{ Request::is('group*') ? 'active' : '' }}"> <a href="{{ url('/group') }}">
                    <i class="fas fa-users"></i><span>Group</span>
                </a>
            </li>
            <li class="{{ Request::is('statistics') ? 'active' : '' }}">
                <a href="{{ url('/statistics') }}">
                    <i class="fas fa-chart-pie"></i><span>Statistics</span>
                </a>
            </li>
            <li class="{{ Request::is('share') ? 'active' : '' }}">
                <a href="{{ url('/share') }}">
                    <i class="fas fa-share-alt"></i><span>Share</span>
                </a>
            </li>
            <li id="setting" onclick="showSettings()">
                <i class="fas fa-cog"></i><span>Setting</span>
            </li>
        </ul>
    </div>

    <nav id="topNav" class="top-navbar">
        <a href="{{ url('/tasks') }}" class="navbar-logo">
            <strong>MyLogo</strong>
        </a>

        <div class="navbar-user" id="user-menu-container">
            @if(Auth::check())
            <button type="button" class="navbar-user-trigger" id="user-menu-trigger">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                @else
                    <i class="fas fa-user-circle"></i>
                @endif
                <span>{{ Auth::user()->full_name ?? Auth::user()->email }}</span>
                <i class="fas fa-caret-down" style="font-size: 0.8em; margin-left: 5px;"></i>
            </button>
            @else
            <a href="{{ url('/login') }}" class="navbar-user-trigger" style="text-decoration: none; color: inherit;">
                <i class="fas fa-user-circle"></i>
                <span>Login</span>
            </a>
            @endif

            @if(Auth::check())
            <div class="dropdown-menu" id="user-dropdown">
                <a href="{{ url('/account-info') }}">
                    <i class="fas fa-user-cog"></i>
                    Thông tin chi tiết
                </a>

                <form method="POST" action="{{ url('/logout') }}" id="logout-form">
                    @csrf
                    <a href="{{ url('/logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        Đăng xuất
                    </a>
                </form>
            </div>
            @endif
        </div>
    </nav>

    <div id="main" class="main-content">
        @yield('content')
    </div>

    @include('partials/settings_modal')

    @include('helpers.theme')

<script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('main');
            const topNav = document.getElementById('topNav');
            sidebar.classList.toggle('expanded');
            main.classList.toggle('expanded');
            topNav.classList.toggle('expanded');
        }

        // JS cho kéo/thả (Giữ nguyên)
        let isDragging = false, startX, scrollLeft, initialTarget;
        function startDragging(e) {
            const container = document.getElementById('buttonContainer');
            if (!container) return; 
            initialTarget = e.target;
            if (initialTarget.classList && initialTarget.classList.contains('btn-purple')) {
                isDragging = true;
                startX = e.pageX - container.offsetLeft;
                scrollLeft = container.scrollLeft;
                container.style.cursor = 'grabbing';
                e.preventDefault();
            }
        }
        function drag(e) {
            if (!isDragging) return;
            e.preventDefault();
            const container = document.getElementById('buttonContainer');
            if (!container) return; 
            const x = e.pageX - container.offsetLeft;
            const walk = (x - startX) * 1.5;
            container.scrollLeft = scrollLeft - walk;
        }
        function stopDragging() {
            if (isDragging) {
                isDragging = false;
                const container = document.getElementById('buttonContainer');
                if (container) { container.style.cursor = 'grab'; }
                if (initialTarget && initialTarget.classList && initialTarget.classList.contains('btn-purple')) {
                    initialTarget = null;
                }
            }
        }
        document.getElementById('main').addEventListener('mousedown', startDragging);
        document.getElementById('main').addEventListener('mouseup', stopDragging);
        document.getElementById('main').addEventListener('mouseleave', stopDragging);
        document.getElementById('main').addEventListener('mousemove', drag);


        // JS cho Modal Settings (Giữ nguyên)
        function showSettings() {
            const modal = document.getElementById('settingsModal');
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
        function closeSettings() {
            const modal = document.getElementById('settingsModal');
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
        }

        
        // *** THAY ĐỔI JS: THÊM JS CHO DROPDOWN ***
        const userMenuTrigger = document.getElementById('user-menu-trigger');
        const userDropdown = document.getElementById('user-dropdown');
        const userMenuContainer = document.getElementById('user-menu-container');

        if (userMenuTrigger) {
            userMenuTrigger.addEventListener('click', function(e) {
                e.stopPropagation(); // Ngăn click lan ra window
                userDropdown.classList.toggle('active');
            });
        }
        // Click bên ngoài để đóng
        window.addEventListener('click', function(e) {
            if (userDropdown && userDropdown.classList.contains('active')) {
                if (!userMenuContainer.contains(e.target)) {
                    userDropdown.classList.remove('active');
                }
            }
            // Cũng đóng modal setting nếu click bên ngoài
            const modal = document.getElementById('settingsModal');
            if (event.target == modal) {
                closeSettings();
            }
        });
        // Đóng bằng phím Esc (gộp chung)
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const modal = document.getElementById('settingsModal');
                if (modal.style.display === 'flex') {
                    closeSettings();
                }
                if (userDropdown && userDropdown.classList.contains('active')) {
                    userDropdown.classList.remove('active');
                }
            }
        });
        // *** KẾT THÚC THAY ĐỔI JS ***

        // Load settings on page load
        document.addEventListener('DOMContentLoaded', function() {
            window.ThemeHelper.loadTheme();
        });

    </script>

    @stack('scripts')
</body>

</html>