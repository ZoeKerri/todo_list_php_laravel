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
                <a href="/account-info" style="width:60px; text-align:right; color:#6a1b9a; font-size:0.9em; text-decoration: none;">View</a>
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
document.addEventListener('DOMContentLoaded', function() {
    // Load settings when modal opens
    function loadSettings() {
        fetch('/settings', {
            credentials: 'include' // Important: include cookies in the request
        })
            .then(response => response.json())
            .then(settings => {
                document.getElementById('show-completed-tasks').checked = settings.show_completed_tasks;
                document.getElementById('notifications').checked = settings.notifications;
                document.getElementById('dark-mode').checked = settings.dark_mode;
            })
            .catch(error => console.error('Error loading settings:', error));
    }

    // Save settings when toggles change
    function saveSettings() {
        const settings = {
            show_completed_tasks: document.getElementById('show-completed-tasks').checked,
            notifications: document.getElementById('notifications').checked,
            dark_mode: document.getElementById('dark-mode').checked,
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        fetch('/settings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': settings._token
            },
            credentials: 'include', // Important: include cookies in the request
            body: JSON.stringify(settings)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Apply theme change immediately using ThemeHelper
                if (window.ThemeHelper) {
                    window.ThemeHelper.applyTheme(settings.dark_mode);
                }
                console.log('Settings saved successfully');
            }
        })
        .catch(error => console.error('Error saving settings:', error));
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