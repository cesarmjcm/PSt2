// Menú de usuario del encabezado (include/header.php).
// Se carga en todas las vistas que muestran el encabezado.

document.addEventListener('DOMContentLoaded', () => {
    const userMenuButton = document.getElementById('userMenuButton');
    const userMenu = document.getElementById('userMenu');
    const logoutBtn = document.getElementById('logoutBtn');
    const configBtn = document.getElementById('configBtn');

    if (userMenuButton && userMenu) {
        userMenuButton.addEventListener('click', (ev) => {
            ev.stopPropagation();
            const expanded = userMenuButton.getAttribute('aria-expanded') === 'true';
            userMenuButton.setAttribute('aria-expanded', String(!expanded));
            if (userMenu.hasAttribute('hidden')) userMenu.removeAttribute('hidden');
            else userMenu.setAttribute('hidden', '');
        });

        document.addEventListener('click', (ev) => {
            if (!userMenu.contains(ev.target) && ev.target !== userMenuButton) {
                userMenu.setAttribute('hidden', '');
                userMenuButton.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', (ev) => {
            if (ev.key === 'Escape') {
                userMenu.setAttribute('hidden', '');
                userMenuButton.setAttribute('aria-expanded', 'false');
            }
        });
    }

    if (logoutBtn) {
        logoutBtn.addEventListener('click', (ev) => {
            ev.preventDefault();
            if (userMenu) userMenu.setAttribute('hidden', '');
            if (userMenuButton) userMenuButton.setAttribute('aria-expanded', 'false');
           window.location.href = 'logout.php';
        });
    }

    if (configBtn) {
        configBtn.addEventListener('click', (ev) => {
        });
    }
});
