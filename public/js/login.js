function mostrarError(mensaje) {
    const caja = document.getElementById('mensaje-error');
    caja.textContent = mensaje;
    caja.classList.remove('hidden');
}

function validar(correo, password) {
    if (correo === '') {
        mostrarError('Debes ingresar tu correo');
        return false;
    }
    if (!correo.includes('@')) {
        mostrarError('El correo no tiene un formato válido');
        return false;
    }
    if (password === '') {
        mostrarError('Debes ingresar tu contraseña');
        return false;
    }
    return true;
}

document.getElementById('form-login').addEventListener('submit', async (evento) => {
    evento.preventDefault();

    const correo   = document.getElementById('correo').value.trim();
    const password = document.getElementById('password').value;

    if (!validar(correo, password)) {
        return;
    }

    try {
        const respuesta = await fetch('api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ correo, password })
        });
        const resultado = await respuesta.json();

        if (resultado.success) {
            window.location.href = 'admin.html';
        } else {
            mostrarError(resultado.message);
        }
    } catch (error) {
        mostrarError('No se pudo conectar con el servidor');
    }
});