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
                    <input type="checkbox" id="show-completed-tasks" aria-label="Show completed tasks">
                    <span class="slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div class="meta">
                    <div class="setting-title"><i class="fas fa-bell"></i><span>Notifications</span></div>
                    <p class="setting-desc">Enable or disable notifications</p>
                </div>
                <label class="switch">
                    <input type="checkbox" id="notifications" aria-label="Notifications">
                    <span class="slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div class="meta">
                    <div class="setting-title"><i class="fas fa-user-shield"></i><span>Account</span></div>
                    <p class="setting-desc">Information about your account</p>
                </div>
                <a href="/account-info" style="width:60px; text-align:right; color:var(--accent-color); font-size:0.9em; text-decoration: none; transition: color 0.3s ease;">View</a>
            </div>

            <div class="setting-item">
                <div class="meta">
                    <div class="setting-title"><i class="fas fa-moon"></i><span>Dark Mode</span></div>
                    <p class="setting-desc">Enable dark theme for the app</p>
                </div>
                <label class="switch">
                    <input type="checkbox" id="dark-mode" aria-label="Dark mode">
                    <span class="slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div class="meta">
                    <div class="setting-title"><i class="fas fa-th-large"></i><span>Default View</span></div>
                    <p class="setting-desc">Choose your default task view</p>
                </div>
                <div style="width:60px; text-align:right; color:var(--text-muted); font-size:0.9em;">Open</div>
            </div>

            <div class="setting-item">
                <div class="meta">
                    <div class="setting-title"><i class="fas fa-globe"></i><span>Language</span></div>
                    <p class="setting-desc">Change the app language</p>
                </div>
                <div style="width:60px; text-align:right; color:var(--text-muted); font-size:0.9em;">Edit</div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load settings from localStorage
    function loadSettings() {
        // Load show_completed_tasks from localStorage
        const showCompleted = localStorage.getItem('show_completed_tasks') === 'true';
        document.getElementById('show-completed-tasks').checked = showCompleted;
        
        // Load notifications from localStorage
        const notifications = localStorage.getItem('notifications') !== 'false'; // default true
        document.getElementById('notifications').checked = notifications;
        
        // Load dark_mode from localStorage
        const darkMode = localStorage.getItem('dark_mode') !== 'false'; // default true
        document.getElementById('dark-mode').checked = darkMode;
    }

    // Save settings to localStorage
    function saveSettings() {
        const showCompleted = document.getElementById('show-completed-tasks').checked;
        const notifications = document.getElementById('notifications').checked;
        const darkMode = document.getElementById('dark-mode').checked;
        
        // Save to localStorage
        localStorage.setItem('show_completed_tasks', showCompleted);
        localStorage.setItem('notifications', notifications);
        localStorage.setItem('dark_mode', darkMode);
        
        // Apply theme change immediately using ThemeHelper
        if (window.ThemeHelper) {
            window.ThemeHelper.applyTheme(darkMode);
        }
        
        // Trigger custom event to notify other pages
        window.dispatchEvent(new CustomEvent('settingsChanged', {
            detail: {
                show_completed_tasks: showCompleted,
                notifications: notifications,
                dark_mode: darkMode
            }
        }));
        
        console.log('Settings saved to localStorage');
    }

    // Add event listeners
    document.getElementById('show-completed-tasks').addEventListener('change', saveSettings);
    document.getElementById('notifications').addEventListener('change', saveSettings);
    document.getElementById('dark-mode').addEventListener('change', saveSettings);

    // Load settings when modal is opened
    const settingsModal = document.getElementById('settingsModal');
    
    // Override the showSettings function if it exists
    if (typeof window.showSettings === 'function') {
        const originalShowSettings = window.showSettings;
        window.showSettings = function() {
            originalShowSettings();
            loadSettings();
        };
    } else {
        // Define showSettings if it doesn't exist
        window.showSettings = function() {
            settingsModal.style.display = 'block';
            loadSettings();
        };
    }

    // Load settings on page load
    loadSettings();
});
</script>