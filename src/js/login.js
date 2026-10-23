// Vista de inicio de sesión (login.php / include/contenedorlogin.php).

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.querySelector('form[name="login"]');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const loginError = document.getElementById('loginError');

    if (loginForm && usernameInput && passwordInput) {
        const mostrarErrorLogin = (mensaje) => {
            if (loginError) {
                loginError.textContent = mensaje;
                loginError.hidden = false;
            }
        };

        const limpiarErrorLogin = () => {
            if (loginError) {
                loginError.textContent = '';
                loginError.hidden = true;
            }
        };

        loginForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            limpiarErrorLogin();

            const username = usernameInput.value.trim();
            const password = passwordInput.value;

            if (username === '' || password === '') {
                mostrarErrorLogin('Debes ingresar usuario y contraseña.');
                usernameInput.focus();
                return;
            }

            const formData = new FormData();
            formData.append('username', username);
            formData.append('password', password);

            const submitBtn = loginForm.querySelector('button[type="submit"], input[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            try {
                const response = await fetch('auth.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Respuesta no válida del servidor (' + response.status + ').');
                }

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect || 'main2.php';
                } else {
                    mostrarErrorLogin(data.message || 'Usuario o contraseña incorrectos.');
                    passwordInput.value = '';
                    usernameInput.focus();
                }
            } catch (error) {
                console.error('Error al iniciar sesión:', error);
                mostrarErrorLogin('No se pudo conectar con el servidor. Inténtalo de nuevo.');
            } finally {
                if (submitBtn) submitBtn.disabled = false;
            }
        });
    }
});
