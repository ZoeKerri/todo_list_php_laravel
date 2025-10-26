<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Your Task App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
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

        .sidebar.expanded {
            width: 200px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin-top: 60px;
        }

        .sidebar li {
            color: #ccc;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .sidebar li#setting {
            display: flex;
            align-items: center;
            padding: 15px 20px;
        }

        /* ĐÃ SỬA: Bọc link <a> để chuyển trang */
        .sidebar li a {
            color: inherit;
            text-decoration: none;
            display: flex;           /* <-- GIỮ NGUYÊN HOẶC THÊM VÀO */
            align-items: center;     /* <-- THÊM DÒNG NÀY */
            width: 100%;
            padding: 15px 20px;      /* <-- THÊM DÒNG NÀY (lấy từ li) */
        }

        .sidebar li:hover {
            background-color: #333;
            color: #fff;
        }

        .sidebar li.active {
            background-color: #6a1b9a;
            color: #fff;
        }

        .sidebar i {
            font-size: 18px;
            margin-right: 10px;
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
            background-color: #6a1b9a;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            z-index: 1100;
            transition: left 0.3s ease;
        }

        .sidebar.expanded .toggle-btn {
            left: 150px;
        }

        .main-content {
            margin-left: 60px;
            padding: 30px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: 200px;
        }

        /* ... (Toàn bộ CSS còn lại của bạn cho modal, switch, v.v...) ... */
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
            background-color: #6a1b9a;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            white-space: nowrap;
            flex-shrink: 0;
            touch-action: manipulation;
        }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            max-width: 480px;
            position: relative;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
            transform: none;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .close {
            color: #ccc;
            font-size: 24px;
            cursor: pointer;
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
            background-color: #2c2c2c;
            border-radius: 8px;
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
            color: #fff;
        }

        .setting-title i {
            font-size: 18px;
            width: 20px;
            text-align: center;
            color: #cfcfcf;
        }

        .setting-desc {
            margin: 0;
            color: #bdbdbd;
            font-size: 0.92em;
            line-height: 1.1;
            white-space: normal;
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
            background-color: #555;
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
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #6a1b9a;
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
            background-color: #333;
            border-radius: 5px;
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
        }
    </style>

    @stack('styles')
</head>

<body>

    <div id="sidebar" class="sidebar">
        <button id="toggleBtn" class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <ul>
            <li class="{{ Request::is('tasks') ? 'active' : '' }}">
                <a href="{{ url('/tasks') }}">
                    <i class="fas fa-tasks"></i><span>Tasks</span>
                </a>
            </li>
            <li class="{{ Request::is('group') ? 'active' : '' }}">
                <a href="{{ url('/group') }}">
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

    <div id="main" class="main-content">

        @yield('content')

    </div>

    @include('partials/settings-modal')

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('main');
            sidebar.classList.toggle('expanded');
            main.classList.toggle('expanded');
        }

        // Giữ nguyên JS cho kéo/thả
        let isDragging = false, startX, scrollLeft, initialTarget;
function startDragging(e) {
            const container = document.getElementById('buttonContainer');
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
            const x = e.pageX - container.offsetLeft;
            const walk = (x - startX) * 1.5;
            container.scrollLeft = scrollLeft - walk;
        }

        function stopDragging() {
            if (isDragging) {
                isDragging = false;
                document.getElementById('buttonContainer').style.cursor = 'grab';
                if (initialTarget && initialTarget.classList && initialTarget.classList.contains('btn-purple')) {
                    initialTarget = null;
                }
            }
        }

        // Giữ nguyên JS cho Modal
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
        window.onclick = function (event) {
            const modal = document.getElementById('settingsModal');
            if (event.target == modal) {
                closeSettings();
            }
        }
        window.addEventListener('keydown', (e) => {
            const modal = document.getElementById('settingsModal');
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                closeSettings();
            }
        });
    </script>

    @stack('scripts')
</body>

</html>