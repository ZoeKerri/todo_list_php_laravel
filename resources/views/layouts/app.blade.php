@vite(['resources/css/app.css', 'resources/js/app.js'])
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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $isLightTheme = App\Helpers\ThemeHelper::isLightTheme();
        $themeVars = App\Helpers\ThemeHelper::getThemeColors($isLightTheme);
    @endphp

    <!-- Theme + Auth Helpers -->
    <script>
        (function() {
            const AUTH_PAGES = ['/login', '/register', '/forgot-password', '/otp', '/reset-password'];
            const sessionCookieName = 'super-todo-list-session';

            const AuthManager = {
                isAuthPage(pathname) {
                    return AUTH_PAGES.some((route) => pathname.startsWith(route));
                },
                getToken() {
                    return localStorage.getItem('access_token');
                },
                clearToken() {
                    localStorage.removeItem('access_token');
                },
                async validateToken() {
                    const token = this.getToken();
                    if (!token) return false;

                    try {
                        const response = await fetch('/api/v1/auth/profile', {
                            method: 'GET',
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            window.__currentUserProfile = data?.data || null;
                            return true;
                        }

                        if (response.status === 401 || response.status === 403) {
                            this.clearToken();
                        }
                    } catch (error) {
                        console.error('Token validation failed:', error);
                    }

                    return false;
                }
            };

            window.AuthManager = AuthManager;

            // Load theme from localStorage immediately (before page renders)
            (function applyInitialTheme() {
                const darkMode = localStorage.getItem('dark_mode') !== 'false'; // default to dark mode
                
                const darkTheme = {
                    '--bg-primary': '#121212',
                    '--bg-secondary': '#1e1e1e',
                    '--bg-tertiary': '#2c2c2c',
                    '--text-primary': '#fff',
                    '--text-secondary': '#ccc',
                    '--text-muted': '#888',
                    '--accent-color': '#6a1b9a',
                    '--border-color': '#333',
                    '--hover-bg': '#333',
                    '--card-bg': '#1e1e1e',
                    '--input-bg': '#1e1e1e'
                };
                
                const lightTheme = {
                    '--bg-primary': '#ffffff',
                    '--bg-secondary': '#f8f9fa',
                    '--bg-tertiary': '#e9ecef',
                    '--text-primary': '#212529',
                    '--text-secondary': '#495057',
                    '--text-muted': '#6c757d',
                    '--accent-color': '#6a1b9a',
                    '--border-color': '#dee2e6',
                    '--hover-bg': '#e9ecef',
                    '--card-bg': '#ffffff',
                    '--input-bg': '#ffffff'
                };
                
                const colors = darkMode ? darkTheme : lightTheme;
                const root = document.documentElement;
                Object.keys(colors).forEach(key => {
                    root.style.setProperty(key, colors[key]);
                });
            })();

            // Redirect unauthenticated visitors
            (function enforceGuard() {
                try {
                    const hasSession = document.cookie?.includes(sessionCookieName);
                    const hasApiToken = !!AuthManager.getToken();
                    const isAuthPage = AuthManager.isAuthPage(window.location.pathname);

                    if (!hasSession && !hasApiToken && !isAuthPage) {
                        window.location.replace('/login');
                    }
                } catch (error) {
                    console.error('Auth guard error:', error);
                }
            })();

            // Post-load hooks
            document.addEventListener('DOMContentLoaded', function() {
                const isAuthPage = AuthManager.isAuthPage(window.location.pathname);

                if (AuthManager.getToken()) {
                    AuthManager.validateToken().then((isValid) => {
                        if (!isValid && !isAuthPage) {
                            window.location.replace('/login');
                        }
                    });
                }

                // Clear token on any logout form submit
                document.addEventListener('submit', function(event) {
                    const form = event.target;
                    if (!(form instanceof HTMLFormElement)) return;
                    const action = form.getAttribute('action') || '';
                    if (action.includes('/logout')) {
                        AuthManager.clearToken();
                    }
                }, true);

                window.handleLogout = function(event, formId = 'logout-form') {
                    if (event) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    if (window.AuthManager) {
                        window.AuthManager.clearToken();
                    } else {
                        localStorage.removeItem('access_token');
                    }
                    const form = document.getElementById(formId) || document.querySelector(`form[action*="/logout"]`);
                    if (form) {
                        form.submit();
                    } else {
                        window.location.href = '/logout';
                    }
                };

                document.querySelectorAll('[data-logout-trigger]').forEach(trigger => {
                    trigger.addEventListener('click', (event) => window.handleLogout(event, trigger.getAttribute('data-logout-form') || 'logout-form'));
                });
            });
        })();
    </script>

    <style>
        /* --- THEME VARIABLES --- */
        :root {
            @foreach($themeVars as $key => $value)
                {{ $key }}
                :
                    {{ $value }}
                ;
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

        .sidebar.expanded {
            width: 200px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin-top: 60px;
        }

        .sidebar li {
            color: var(--text-secondary);
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .sidebar li#setting {
            display: flex;
            align-items: center;
            padding: 15px 20px;
        }

        .sidebar li a {
            color: inherit;
            text-decoration: none;
            display: flex;
            align-items: center;
            width: 100%;
            padding: 15px 20px;
        }

        .sidebar li:hover {
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
        }

        .sidebar li.active {
            background-color: var(--accent-color);
            color: var(--text-primary);
        }

        .sidebar i {
            font-size: 18px;
            margin-right: 10px;
            color: inherit;
            transition: color 0.3s ease;
        }

        .sidebar span {
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar.expanded span {
            opacity: 1;
        }

        .toggle-btn {
            position: fixed;
            top: 15px;
            left: 10px;
            background-color: var(--accent-color);
            color: var(--text-primary);
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            z-index: 1100;
            transition: left 0.3s ease, background-color 0.3s ease, color 0.3s ease;
        }

        .sidebar.expanded .toggle-btn {
            left: 150px;
        }

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

        .top-navbar.expanded {
            left: 200px;
        }

        .navbar-logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--text-primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        /* *** THAY ĐỔI CSS: SỬA .navbar-user VÀ THÊM .dropdown-menu *** */
        .navbar-user {
            position: relative;
            /* Để neo dropdown */
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

        .navbar-user-trigger:hover,
        .navbar-user-trigger:focus {
            color: var(--text-primary);
        }

        .navbar-user-trigger i {
            font-size: 1.8rem;
        }

        /* Menu dropdown */
        .dropdown-menu {
            display: none;
            /* Ẩn mặc định */
            position: absolute;
            top: 110%;
            /* Hơi hở 1 chút */
            right: 0;
            background-color: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            min-width: 220px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            z-index: 1001;
            overflow: hidden;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .dropdown-menu.active {
            display: block;
            /* Hiện khi có class 'active' */
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
            color: var(--text-primary);
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

        .main-content.expanded {
            margin-left: 200px;
        }

        .scrollable-buttons {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            touch-action: pan-x;
            cursor: grab;
            position: relative;
        }

        .scrollable-buttons:active {
            cursor: grabbing;
        }

        .scrollable-buttons::-webkit-scrollbar {
            display: none;
        }

        .btn-purple {
            background-color: var(--accent-color);
            color: var(--text-primary);
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            white-space: nowrap;
            flex-shrink: 0;
            touch-action: manipulation;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(4px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 20px;
            transition: background-color 0.3s ease;
        }

        .modal-content {
            background-color: var(--bg-secondary);
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            max-width: 480px;
            position: relative;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
            transform: none;
            transition: background-color 0.3s ease;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .close {
            color: var(--text-secondary);
            font-size: 24px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .task-settings {
            display: grid;
            gap: 12px;
        }

        .setting-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px;
            background-color: var(--bg-tertiary);
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .meta {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1 1 auto;
            min-width: 0;
        }

        .setting-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: var(--text-primary);
            transition: color 0.3s ease;
        }

        .setting-title i {
            font-size: 18px;
            width: 20px;
            text-align: center;
            color: var(--text-secondary);
            transition: color 0.3s ease;
        }

        .setting-desc {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.92em;
            line-height: 1.1;
            white-space: normal;
            transition: color 0.3s ease;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
            flex-shrink: 0;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--border-color);
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: var(--text-primary);
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: var(--accent-color);
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }

        input:focus+.slider {
            box-shadow: 0 0 0 3px rgba(106, 27, 154, 0.15);
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin: 20px 0;
        }

        .calendar div {
            text-align: center;
            padding: 10px;
            background-color: var(--bg-tertiary);
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .calendar span {
            display: block;
            font-size: 1.2em;
            font-weight: bold;
        }

        @media (max-width: 520px) {
            .modal-content {
                max-width: 100%;
            }

            .setting-title {
                font-size: 0.98em;
            }

            .navbar-user span {
                display: none;
            }

            .top-navbar {
                padding: 0 15px;
            }
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
            transition: border-color 0.3s ease, color 0.3s ease;
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
            color: var(--text-primary);
        }

        .alert-danger {
            background-color: #dc3545;
            color: var(--text-primary);
        }

        .alert-warning {
            background-color: #ffc107;
            color: var(--text-primary);
        }

        .alert-info {
            background-color: #17a2b8;
            color: var(--text-primary);
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
                    @php
                        $user = Auth::user();
                        $avatarUrl = null;
                        if ($user->avatar) {
                            // Check if avatar is a URL (from Google login) or a storage path
                            if (filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                                $avatarUrl = $user->avatar;
                            } else {
                                $avatarUrl = asset('storage/' . $user->avatar);
                            }
                        }
                        $initials = strtoupper(substr($user->full_name ?? $user->email, 0, 1));
                    @endphp
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="Avatar"
                            style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div
                            style="width: 32px; height: 32px; border-radius: 50%; background-color: var(--accent-color); display: none; align-items: center; justify-content: center; color: var(--text-primary); font-weight: bold; font-size: 14px;">
                            {{ $initials }}
                        </div>
                    @else
                        <div
                            style="width: 32px; height: 32px; border-radius: 50%; background-color: var(--accent-color); display: flex; align-items: center; justify-content: center; color: var(--text-primary); font-weight: bold; font-size: 14px;">
                            {{ $initials }}
                        </div>
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
                    Profile
                </a>

                <form method="POST" action="{{ url('/logout') }}" id="logout-form">
                    @csrf
                    <a href="{{ url('/logout') }}" data-logout-trigger data-logout-form="logout-form">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </form>
            </div>
            @endif
        </div>
    </nav>

    <div id="main" class="main-content">
        @yield('content')
    </div>

    @include('modals/settings_modal')

    @include('helpers.theme')

    {{-- Modal Thêm Thể loại (Ẩn mặc định) --}}
    <div id="category-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
        {{-- Lớp phủ --}}
        <div id="category-modal-overlay" class="fixed inset-0" style="background-color: rgba(0, 0, 0, 0.75); backdrop-filter: blur(4px);"></div>

        {{-- Nội dung Modal --}}
        <div class="relative" style="background-color: var(--bg-secondary); width: 100%; max-width: 28rem; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border: 1px solid var(--border-color);">
            <h3 class="text-xl font-semibold mb-4" style="color: var(--text-primary);">Create New Category</h3>
            <form id="category-modal-form">
                <div>
                    <label for="category-name" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Category
                        Name</label>
                    <input type="text" id="category-name" class="form-input" required>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" id="category-modal-close"
                        style="padding: 0.5rem 1rem; background-color: var(--bg-tertiary); color: var(--text-primary); border-radius: 0.5rem; border: none; cursor: pointer; transition: background-color 0.3s ease;">Cancel</button>
                    <button type="submit" style="padding: 0.5rem 1rem; background-color: var(--accent-color); color: var(--text-primary); border-radius: 0.5rem; border: none; cursor: pointer; transition: background-color 0.3s ease;">Save
                        Category</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========= TASK MODAL (ADD/EDIT) ========= --}}
    {{-- Đặt ở cuối file app.blade.php (trước @stack('scripts')) --}}

    <div id="task-modal"
        class="fixed inset-0 z-50 hidden flex items-start justify-center pt-16 sm:pt-24 overflow-y-auto">

        {{-- Lớp phủ nền (Overlay) --}}
        <div id="task-modal-overlay" class="fixed inset-0" style="background-color: rgba(0, 0, 0, 0.75); backdrop-filter: blur(4px); cursor: pointer;"></div>

        {{-- Panel Nội dung Modal [5, 7] --}}
        <div class="relative w-full max-w-2xl p-6 sm:p-8 rounded-xl shadow-xl" style="background-color: var(--bg-secondary); border: 1px solid var(--border-color);">

            {{-- Tiêu đề Modal --}}
            <div class="flex justify-between items-center mb-6">
                <h3 id="task-modal-title" class="text-2xl font-semibold" style="color: var(--text-primary);">Add New Task</h3>
                <button id="task-modal-close" style="color: var(--text-secondary); transition: color 0.3s ease;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            {{-- Form (Dựa trên Image 2 & 3) --}}
            <form id="task-modal-form" class="space-y-5">
                {{-- Input ẩn để lưu Task ID (dùng cho việc Edit) --}}
                <input type="hidden" id="task-id">

                {{-- Task Title --}}
                <div>
                    <label for="task-title" class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Task Title</label>
                    <input type="text" id="task-title" placeholder="Enter task title" class="form-input" required>
                </div>

                {{-- Task Description --}}
                <div>
                    <label for="task-description" class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Task
                        Description</label>
                    <textarea id="task-description" rows="4" placeholder="Enter task description"
                        class="form-textarea"></textarea>
                </div>

                {{-- Bố cục Grid cho Ngày, Giờ, Mức độ ưu tiên --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Task Date --}}
                    <div>
                        <label for="task-date" class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Task Date</label>
                        <input type="date" id="task-date" class="form-input">
                    </div>

                    {{-- Notification Time --}}
                    <div>
                        <label for="task-time" class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Notification
                            Time</label>
                        <input type="time" id="task-time" class="form-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Priority (Dropdown, như Image 3) --}}
                    <div>
                        <label for="task-priority" class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Priority</label>
                        <select id="task-priority" class="form-select">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>

                    {{-- Category (Dropdown) --}}
                    <div>
                        <label for="task-category" class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Category</label>
                        <select id="task-category" class="form-select">
                            {{-- JS sẽ điền các <option> này từ /v1/categories API (đã đề xuất) --}}
                            <option value="">Select Category</option>
                            <option value="1">Personal</option>
                            <option value="2">Work</option>
                            {{--... --}}
                        </select>
                    </div>
                </div>

                {{-- Nút Hành động (Thay đổi động) --}}
                <div class="pt-4 flex justify-between items-center">
                    {{-- Nút Xóa (Chỉ hiển thị khi Edit) --}}
                    <button type="button" id="task-modal-delete-btn"
                        class="hidden px-5 py-2.5 text-sm font-medium rounded-lg focus:z-10 transition-colors" style="color: #e74c3c; background-color: transparent;" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                        Delete Task
                    </button>

                    {{-- Nút Hủy và Lưu/Cập nhật --}}
                    <div class="flex-grow flex justify-end gap-3">
                        <button type="button" id="task-modal-cancel-btn"
                            class="px-5 py-2.5 text-sm font-medium rounded-lg transition-colors" style="color: var(--text-secondary); background-color: var(--bg-tertiary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='var(--bg-tertiary)'">
                            Cancel
                        </button>

                        {{-- Nút Create (Mặc định) --}}
                        <button type="submit" id="task-modal-create-btn"
                            class="px-5 py-2.5 text-sm font-medium rounded-lg transition-colors" style="color: var(--text-primary); background-color: var(--accent-color);" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                            Create Task
                        </button>

                        {{-- Nút Update (Chỉ hiển thị khi Edit) --}}
                        <button type="submit" id="task-modal-update-btn"
                            class="hidden px-5 py-2.5 text-sm font-medium rounded-lg transition-colors" style="color: var(--text-primary); background-color: var(--accent-color);" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                            Update Task
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Thêm CSS chung cho các form (có thể đặt trong app.css) --}}
    <style>
        .form-input,
        .form-textarea,
        .form-select {
            display: block;
            width: 100%;
            background-color: var(--input-bg);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.3s ease, color 0.3s ease;
        }

        .form-input:focus,
        .form-textarea:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(106, 27, 154, 0.2);
        }
    </style>

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
            userMenuTrigger.addEventListener('click', function (e) {
                e.stopPropagation(); // Ngăn click lan ra window
                userDropdown.classList.toggle('active');
            });
        }
        // Click bên ngoài để đóng
        window.addEventListener('click', function (e) {
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
        document.addEventListener('DOMContentLoaded', function () {
            window.ThemeHelper.loadTheme();
        });

    </script>

    @stack('modals')
    @stack('scripts')
    @include('modals.create_personal_task')
</body>

</html>