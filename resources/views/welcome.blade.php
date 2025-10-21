<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Your Task (Settings Centered)</title>
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
            padding: 15px 20px;
            color: #ccc;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.2s ease;
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

        .scrollable-buttons {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
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

        /* Modal: always centered */
        .modal {
            display: none;
            /* ẩn mặc định */
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
            /* remove legacy transforms */
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

        /* Setting item layout improved */
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

        /* Left block (icon + title + description) */
        .meta {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1 1 auto;
            min-width: 0;
            /* allow text truncation */
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

        /* Switch (right side) */
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

        /* Responsive tweaks */
        @media (max-width: 520px) {
            .modal-content {
                max-width: 100%;
            }

            .setting-title {
                font-size: 0.98em;
            }
        }
    </style>
</head>

<body>

    <div id="sidebar" class="sidebar">
        <button id="toggleBtn" class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <ul>
            <li id="tasks" onclick="showContent('tasks')" class="active"><i class="fas fa-tasks"></i><span>Tasks</span>
            </li>
            <li id="group" onclick="showContent('group')"><i class="fas fa-users"></i><span>Group</span></li>
            <li id="share" onclick="showContent('share')"><i class="fas fa-share-alt"></i><span>Share</span></li>
            <li id="setting" onclick="showSettings()"><i class="fas fa-cog"></i><span>Setting</span></li>
        </ul>
    </div>

    <div id="main" class="main-content">
        <div id="tasks-content" class="tasks-content active-content">
            <h2>Hi there,</h2>
            <h4>Your Task</h4>
            <div id="buttonContainer" class="scrollable-buttons" onmousedown="startDragging(event)"
                onmouseup="stopDragging()" onmouseleave="stopDragging()" onmousemove="drag(event)">
                <button type="button" class="btn btn-purple">Personal</button>
                <button type="button" class="btn btn-purple">Work</button>
                <button type="button" class="btn btn-purple">Health</button>
                <button type="button" class="btn btn-purple">Study</button>
                <button type="button" class="btn btn-purple">Personal</button>
                <button type="button" class="btn btn-purple">Work</button>
                <button type="button" class="btn btn-purple">Health</button>
                <button type="button" class="btn btn-purple">Study</button>
                <button type="button" class="btn btn-purple">Personal</button>
                <button type="button" class="btn btn-purple">Work</button>
                <button type="button" class="btn btn-purple">Health</button>
                <button type="button" class="btn btn-purple">Study</button>
                <button type="button" class="btn btn-purple">Personal</button>
                <button type="button" class="btn btn-purple">Work</button>
                <button type="button" class="btn btn-purple">Health</button>
                <button type="button" class="btn btn-purple">Study</button>
                <button type="button" class="btn btn-purple">Personal</button>
                <button type="button" class="btn btn-purple">Work</button>
                <button type="button" class="btn btn-purple">Health</button>
                <button type="button" class="btn btn-purple">Study</button>
                <button type="button" class="btn btn-purple">Personal</button>
                <button type="button" class="btn btn-purple">Work</button>
                <button type="button" class="btn btn-purple">Health</button>
                <button type="button" class="btn btn-purple">Study</button>
            </div>
            <div class="calendar">
                <div>Sun <span>8</span> Jun</div>
                <div>Mon <span>9</span> Jun</div>
                <div>Tue <span>10</span> Jun</div>
                <div>Wed <span>11</span> Jun</div>
            </div>
            <p>0 Tasks For 08/06/2025</p>
            <p>No tasks yet</p>
            <p>Add your first task to get started</p>
            <button class="btn btn-purple">Add Task</button>
        </div>

        <div id="setting-content" class="settings-content">
            <!-- Placeholder for any inline settings content if needed -->
        </div>
    </div>

    <div id="settingsModal" class="modal" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="modal-content" role="document">
            <div class="modal-header">
                <h2>Setting</h2>
                <span class="close" onclick="closeSettings()">&times;</span>
            </div>
            <div class="task-settings">
                <div class="setting-item">
                    <div class="meta">
                        <div class="setting-title"><i class="fas fa-eye"></i><span>Completed Tasks</span></div>
                        <p class="setting-desc">Display completed tasks in your task list</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" aria-label="Show completed tasks">
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="setting-item">
                    <div class="meta">
                        <div class="setting-title"><i class="fas fa-bell"></i><span>Notifications</span></div>
                        <p class="setting-desc">Enable or disable notifications</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" aria-label="Notifications">
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="setting-item">
                    <div class="meta">
                        <div class="setting-title"><i class="fas fa-user-shield"></i><span>Account</span></div>
                        <p class="setting-desc">Information about your account</p>
                    </div>
                    <!-- No toggle for account -->
                    <div style="width:60px; text-align:right; color:#bdbdbd; font-size:0.9em;">View</div>
                </div>

                <div class="setting-item">
                    <div class="meta">
                        <div class="setting-title"><i class="fas fa-moon"></i><span>Dark Mode</span></div>
                        <p class="setting-desc">Enable dark theme for the app</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" checked aria-label="Dark mode">
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="setting-item">
                    <div class="meta">
                        <div class="setting-title"><i class="fas fa-th-large"></i><span>Default View</span></div>
                        <p class="setting-desc">Choose your default task view</p>
                    </div>
                    <div style="width:60px; text-align:right; color:#bdbdbd; font-size:0.9em;">Open</div>
                </div>

                <div class="setting-item">
                    <div class="meta">
                        <div class="setting-title"><i class="fas fa-globe"></i><span>Language</span></div>
                        <p class="setting-desc">Change the app language</p>
                    </div>
                    <div style="width:60px; text-align:right; color:#bdbdbd; font-size:0.9em;">Edit</div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('main');
            sidebar.classList.toggle('expanded');
            main.classList.toggle('expanded');
        }

        function showContent(contentId) {
            const items = document.querySelectorAll('.sidebar li');
            items.forEach(item => item.classList.remove('active'));
            document.getElementById(contentId).classList.add('active');

            const contents = document.querySelectorAll('.main-content > div');
            contents.forEach(content => content.classList.remove('active-content'));
            const target = document.getElementById(contentId + '-content');
            if (target) target.classList.add('active-content');
        }

        let isDragging = false;
        let startX;
        let scrollLeft;
        let initialTarget;

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

        // Close modal when clicking outside
        window.onclick = function (event) {
            const modal = document.getElementById('settingsModal');
            if (event.target == modal) {
                closeSettings();
            }
        }

        // Close with Esc key
        window.addEventListener('keydown', (e) => {
            const modal = document.getElementById('settingsModal');
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                closeSettings();
            }
        });
    </script>

</body>

</html>