<script>
// Theme management helper
window.ThemeHelper = {
    // Dark theme colors
    darkTheme: {
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
    },
    
    // Light theme colors
    lightTheme: {
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
    },
    
    // Auth dark theme colors
    authDarkTheme: {
        '--auth-bg': '#000',
        '--auth-text': '#fff',
        '--auth-text-muted': '#888',
        '--auth-input-bg': '#1e1e1e',
        '--auth-border': '#333',
        '--auth-accent': '#7f00ff',
        '--auth-secondary': '#fff'
    },
    
    // Auth light theme colors
    authLightTheme: {
        '--auth-bg': '#ffffff',
        '--auth-text': '#212529',
        '--auth-text-muted': '#6c757d',
        '--auth-input-bg': '#f8f9fa',
        '--auth-border': '#dee2e6',
        '--auth-accent': '#7f00ff',
        '--auth-secondary': '#000'
    },
    
    /**
     * Apply theme to the page
     * @param {boolean} isDark - true for dark theme, false for light theme
     */
    applyTheme: function(isDark) {
        const root = document.documentElement;
        const colors = isDark ? this.darkTheme : this.lightTheme;
        
        Object.keys(colors).forEach(key => {
            root.style.setProperty(key, colors[key]);
        });
    },
    
    /**
     * Apply auth theme to the page
     * @param {boolean} isDark - true for dark theme, false for light theme
     */
    applyAuthTheme: function(isDark) {
        const root = document.documentElement;
        const colors = isDark ? this.authDarkTheme : this.authLightTheme;
        
        Object.keys(colors).forEach(key => {
            root.style.setProperty(key, colors[key]);
        });
    },
    
    /**
     * Load and apply theme from localStorage
     */
    loadTheme: function() {
        // Load from localStorage (default to true/dark mode)
        const darkMode = localStorage.getItem('dark_mode') !== 'false';
        this.applyTheme(darkMode);
    },
    
    /**
     * Load and apply auth theme from localStorage
     */
    loadAuthTheme: function() {
        // Load from localStorage (default to true/dark mode)
        const darkMode = localStorage.getItem('dark_mode') !== 'false';
        this.applyAuthTheme(darkMode);
    }
};
</script>

