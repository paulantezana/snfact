(()=>{
    document.addEventListener('DOMContentLoaded',() => {
        let defaultThemes = {
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

        function buildTheme(selectTheme) {
            if (defaultThemes[selectTheme]){
                let currentTheme = defaultThemes[selectTheme];
                let rootStyles = document.documentElement.style;
                for (const cssVarName in currentTheme) {
                    if (currentTheme.hasOwnProperty(cssVarName)) {
                        let property = currentTheme[cssVarName];
                        rootStyles.setProperty(`--${cssVarName}`, property);
                    }
                }
                sessionStorage.setItem('snTheme', selectTheme);
            }
        }

        let snTheme = sessionStorage.getItem('snTheme');
        if (snTheme) {
            buildTheme(snTheme, false);
        }

        const themeMode = document.getElementById('themeMode');
        if (themeMode){
            themeMode.checked = snTheme === 'darck';
            themeMode.addEventListener('change', () => {
                if (themeMode.checked === true) {
                    buildTheme('darck');
                } else {
                    buildTheme('light');
                }
            });
        }
    });
})();