<script>
    try {
        const savedTheme = localStorage.getItem('theme');
        const useDarkTheme = savedTheme
            ? savedTheme === 'dark'
            : window.matchMedia('(prefers-color-scheme: dark)').matches;

        document.documentElement.classList.toggle('dark', useDarkTheme);
    } catch (error) {
        console.error('No se pudo cargar la preferencia de tema.', error);
    }
</script>
