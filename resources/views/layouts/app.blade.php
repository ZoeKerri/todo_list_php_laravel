<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Your Task App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/css/account.css', 'resources/js/app.js'])
    <style>
        /* --- BODY VÀ SIDEBAR --- */
        body {
            background-color: #121212;
            color: #fff;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 60px;
            background-color: #1e1e1e;
            transition: width 0.3s ease;
            overflow: hidden;
            z-index: 1000;
        }
        .sidebar.expanded { width: 200px; }
        .sidebar ul { list-style: none; padding: 0; margin-top: 60px; }
        .sidebar li { color: #ccc; cursor: pointer; transition: background-color 0.2s ease; }
        .sidebar li#setting { display: flex; align-items: center; padding: 15px 20px; }
        .sidebar li a {
            color: inherit;
            text-decoration: none;
            display: flex;
            align-items: center;
            width: 100%;
            padding: 15px 20px;
        }
        .sidebar li:hover { background-color: #333; color: #fff; }
        .sidebar li.active { background-color: #6a1b9a; color: #fff; }
        .sidebar i { font-size: 18px; margin-right: 10px; }
        .sidebar span { white-space: nowrap; opacity: 0; transition: opacity 0.3s ease; }
        .sidebar.expanded span { opacity: 1; }
        .toggle-btn {
            position: fixed;
            top: 15px;
            left: 10px;
            background-color: #6a1b9a;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            z-index: 1100;
            transition: left 0.3s ease;
        }
        .sidebar.expanded .toggle-btn { left: 150px; }

        /* --- TOP NAVBAR --- */
        .top-navbar {
            position: fixed;
            top: 0;
            left: 60px;
            right: 0;
            height: 60px;
            background-color: #1e1e1e;
            border-bottom: 1px solid #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px; 
            z-index: 999;
            transition: left 0.3s ease;
        }
        .top-navbar.expanded { left: 200px; }
        .navbar-logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff;
            text-decoration: none;
        }

        /* *** THAY ĐỔI CSS: SỬA .navbar-user VÀ THÊM .dropdown-menu *** */
        .navbar-user {
            position: relative; /* Để neo dropdown */
        }
        /* Nút bấm trigger */
        .navbar-user-trigger {
            background: none;
            border: none;
            color: #ccc;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 5px;
        }
        .navbar-user-trigger:hover, .navbar-user-trigger:focus {
            color: #fff;
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
            background-color: #2c2c2c;
            border: 1px solid #333;
            border-radius: 8px;
            min-width: 220px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 1001;
            overflow: hidden;
        }
        .dropdown-menu.active {
            display: block; /* Hiện khi có class 'active' */
        }
        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            color: #ccc;
            text-decoration: none;
            font-size: 0.95rem;
        }
        .dropdown-menu a:hover {
            background-color: #6a1b9a;
            color: #fff;
        }
        .dropdown-menu i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }
        /* Phân cách cho form đăng xuất */
        .dropdown-menu form {
            border-top: 1px solid #444;
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
        .modal-content { background-color: #1e1e1e; padding: 20px; border-radius: 10px; width: 100%; max-width: 480px; position: relative; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6); transform: none; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
        .close { color: #ccc; font-size: 24px; cursor: pointer; }
        .task-settings { display: grid; gap: 12px; }
        .setting-item { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 10px; background-color: #2c2c2c; border-radius: 8px; }
        .meta { display: flex; flex-direction: column; gap: 6px; flex: 1 1 auto; min-width: 0; }
        .setting-title { display: flex; align-items: center; gap: 10px; font-weight: 600; color: #fff; }
        .setting-title i { font-size: 18px; width: 20px; text-align: center; color: #cfcfcf; }
        .setting-desc { margin: 0; color: #bdbdbd; font-size: 0.92em; line-height: 1.1; white-space: normal; }
        .switch { position: relative; display: inline-block; width: 60px; height: 34px; flex-shrink: 0; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #555; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked+.slider { background-color: #6a1b9a; }
        input:checked+.slider:before { transform: translateX(26px); }
        input:focus+.slider { box-shadow: 0 0 0 3px rgba(106, 27, 154, 0.15); }
        .calendar { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin: 20px 0; }
        .calendar div { text-align: center; padding: 10px; background-color: #333; border-radius: 5px; }
        .calendar span { display: block; font-size: 1.2em; font-weight: bold; }
        @media (max-width: 520px) {
            .modal-content { max-width: 100%; }
            .setting-title { font-size: 0.98em; }
            .navbar-user span { display: none; }
            .top-navbar { padding: 0 15px; }
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
            <button type="button" class="navbar-user-trigger" id="user-menu-trigger">
                <i class="fas fa-user-circle"></i>
                <span>Huỳnh Công Tiến</span>
                <i class="fas fa-caret-down" style="font-size: 0.8em; margin-left: 5px;"></i>
            </button>

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
        </div>
    </nav>

    <div id="main" class="main-content">
        @yield('content')
    </div>

    @include('partials/settings_modal')


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

    </script>

    @stack('scripts')
</body>

</html>