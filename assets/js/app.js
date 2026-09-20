document.addEventListener('DOMContentLoaded', async () => {
    try {
        await cargarSweetAlert();
        mostrarAlertaDeUrl();
        mostrarAlertasDePagina();
        configurarConfirmaciones();
    } catch (error) {
        console.error('No fue posible cargar las alertas.', error);
    }
});

function cargarSweetAlert() {
    if (window.Swal) {
        return Promise.resolve();
    }

    return new Promise((resolver, rechazar) => {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        script.onload = resolver;
        script.onerror = rechazar;
        document.head.appendChild(script);
    });
}

function mostrarAlertaDeUrl() {
    const alerta = document.getElementById('mensaje-alerta');

    if (!alerta || !alerta.dataset.origen) {
        return;
    }

    const parametros = new URLSearchParams(window.location.search);
    const error = parametros.get('error');
    const mensajes = {
        sesion: {
            campos: 'El correo y la contraseña son obligatorios.',
            correo: 'El correo electrónico no es válido.',
            credenciales: 'El correo o la contraseña son incorrectos.',
            inactivo: 'El usuario se encuentra inactivo.',
            acceso: 'Acceso no permitido.',
            rol: 'El rol de usuario no es válido.',
            registro: 'Tu cuenta fue creada correctamente. Ya puedes iniciar sesión.'
        },
        registro: {
            acceso: 'Acceso no permitido.',
            campos: 'Todos los campos obligatorios deben estar completos.',
            correo: 'El correo electrónico no es válido.',
            password: 'La contraseña debe tener al menos 6 caracteres.',
            duplicado: 'El correo o el número de documento ya están registrados.'
        }
    };

    const codigo = alerta.dataset.origen === 'sesion' && parametros.get('registro') === 'exitoso'
        ? 'registro'
        : error;
    const texto = mensajes[alerta.dataset.origen]?.[codigo];

    if (!texto) {
        return;
    }

    mostrarAlerta({
        icon: codigo === 'registro' ? 'success' : 'error',
        title: codigo === 'registro' ? 'Registro exitoso' : 'Revisa la información',
        text: texto,
        parametrosUrl: codigo === 'registro' ? 'registro' : 'error'
    });
}

function mostrarAlertasDePagina() {
    document.querySelectorAll('[data-swal]').forEach((mensaje) => {
        mostrarAlerta({
            icon: mensaje.dataset.swal,
            title: mensaje.dataset.swalTitulo || 'Información',
            text: mensaje.textContent.trim(),
            parametrosUrl: mensaje.dataset.parametrosUrl
        });

        mensaje.remove();
    });
}

function mostrarAlerta({ icon, title, text, parametrosUrl }) {
    Swal.fire({
        icon,
        title,
        text,
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#4b2e05',
        timer: 4000,
        timerProgressBar: true
    }).then(() => limpiarParametrosDeUrl(parametrosUrl));
}

function configurarConfirmaciones() {
    document.querySelectorAll('form[data-confirmacion]').forEach((formulario) => {
        formulario.addEventListener('submit', (evento) => {
            evento.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: formulario.dataset.confirmacion,
                text: 'Esta acción se aplicará al confirmar.',
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#777'
            }).then((resultado) => {
                if (resultado.isConfirmed) {
                    HTMLFormElement.prototype.submit.call(formulario);
                }
            });
        });
    });
}

function limpiarParametrosDeUrl(parametros) {
    if (!parametros) {
        return;
    }

    const url = new URL(window.location.href);

    parametros.split(',').forEach((parametro) => {
        url.searchParams.delete(parametro);
    });

    window.history.replaceState({}, document.title, url.pathname + url.search);
}
