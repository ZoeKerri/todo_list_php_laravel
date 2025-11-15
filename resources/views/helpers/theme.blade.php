<script>
window.ThemeHelper = {
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
    
    authDarkTheme: {
        '--auth-bg': '#000',
        '--auth-text': '#fff',
        '--auth-text-muted': '#888',
        '--auth-input-bg': '#1e1e1e',
        '--auth-border': '#333',
        '--auth-accent': '#7f00ff',
        '--auth-secondary': '#fff'
    },
    
    authLightTheme: {
        '--auth-bg': '#ffffff',
        '--auth-text': '#212529',
        '--auth-text-muted': '#6c757d',
        '--auth-input-bg': '#f8f9fa',
        '--auth-border': '#dee2e6',
        '--auth-accent': '#7f00ff',
        '--auth-secondary': '#000'
    },
    
    applyTheme: function(isDark) {
        const root = document.documentElement;
        const colors = isDark ? this.darkTheme : this.lightTheme;
        
        Object.keys(colors).forEach(key => {
            root.style.setProperty(key, colors[key]);
        });
    },
    
    applyAuthTheme: function(isDark) {
        const root = document.documentElement;
        const colors = isDark ? this.authDarkTheme : this.authLightTheme;
        
        Object.keys(colors).forEach(key => {
            root.style.setProperty(key, colors[key]);
        });
    },
    
    loadTheme: function() {
        const darkMode = localStorage.getItem('dark_mode') !== 'false';
        this.applyTheme(darkMode);
    },
    
    loadAuthTheme: function() {
        const darkMode = localStorage.getItem('dark_mode') !== 'false';
        this.applyAuthTheme(darkMode);
    }
};
</script>

