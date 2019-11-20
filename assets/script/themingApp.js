class ThemingApp {
    constructor() {
        this.defaultThemes = {
            'darck': {
                snColorBg: 'var(--snColorDark)',
                snColorBgAlt: 'var(--snColorDarker)',
                snColorHover: 'var(--snColorDarkest)',

                snColorText: 'var(--snColorDarkInverse)',
                snColorTextAlt: '#94aab9',

                snColorBorder: 'var(--snColorDark)',

                // snColorDark: '#2A3B47',
                // snColorDarkAlt: 'hsl(208, 29%, 10%)',
                // snColorDarkInverse: '#b6bcc0',
            },
            'light': {
                snColorBg: '#FBFBFB',
                snColorBgAlt: '#FFFFFF',
                snColorHover: '#0000000d',

                snColorText: '#53575A',
                snColorTextAlt: '#BABDBF',

                snColorBorder: '#E0E1E1',

                // snColorDark: '#2A3B47',
                // snColorDarkAlt: 'hsl(208, 29%, 10%)',
                // snColorDarkInverse: '#b6bcc0',
            },
        };
        this.currentTheme = {};
        this.mode = 'light';
        this.themeName = '';
        this.listenActions();
        this.loadTheme();
    }

    saveTheme(theme) {
        sessionStorage.setItem('snTheme', JSON.stringify(theme));
    }

    loadTheme() {
        let snTheme = sessionStorage.getItem('snTheme');
        if (snTheme) {
            this.buildTheme(JSON.parse(snTheme), false);
        }
    }

    buildTheme(selectTheme, byName = true) {

        let newTheme = {};
        if (byName) {
            newTheme = this.defaultThemes[selectTheme] || {};
        } else {
            newTheme = selectTheme.currentTheme || {};
            this.mode = selectTheme.mode;
            this.themeName = selectTheme.themeName;
        }
        this.currentTheme = { ...this.currentTheme, ...newTheme };
        this.saveTheme({
            currentTheme: this.currentTheme,
            mode: this.mode,
            themeName: this.themeName,
        });

        // Set radio checket
        const changeTheme = document.getElementById(this.themeName);
        if(changeTheme){
            changeTheme.checked = true;
        }

        const themeMode = document.getElementById('themeMode');
        if (themeMode) {
            if(this.mode == 'light'){
                themeMode.checked = false;
            } else {
                themeMode.checked = true;
            }
        }


        // Set Styles DOM
        let rootStyles = document.documentElement.style;
        for (const cssVarName in this.currentTheme) {
            if (this.currentTheme.hasOwnProperty(cssVarName)) {
                let propertie = this.currentTheme[cssVarName];
                rootStyles.setProperty(`--${cssVarName}`, propertie);
            }
        }
    }

    listenActions() {
        const themeMode = document.getElementById('themeMode');
        if (themeMode) {
            themeMode.addEventListener('change', () => {
                if (themeMode.checked == true) {
                    this.mode = 'darck';
                    this.buildTheme('darck');
                } else {
                    this.mode = 'light';
                    this.buildTheme('light');
                }
            });
        }
    }

}

new ThemingApp();