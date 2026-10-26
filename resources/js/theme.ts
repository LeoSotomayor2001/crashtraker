const themeToggle = document.querySelector<HTMLButtonElement>('[data-theme-toggle]')

if (themeToggle) {
    const themeLabel = themeToggle.querySelector<HTMLElement>('[data-theme-label]')

    const updateThemeLabel = () => {
        const nextTheme = document.documentElement.classList.contains('dark') ? 'claro' : 'oscuro'
        themeToggle.setAttribute('aria-label', `Activar modo ${nextTheme}`)

        if (themeLabel) {
            themeLabel.textContent = `Modo ${nextTheme}`
        }
    }

    updateThemeLabel()

    themeToggle.addEventListener('click', () => {
        const isDarkTheme = document.documentElement.classList.toggle('dark')
        updateThemeLabel()

        try {
            localStorage.setItem('theme', isDarkTheme ? 'dark' : 'light')
        } catch (error) {
            console.error('No se pudo guardar la preferencia de tema.', error)
        }
    })
}
