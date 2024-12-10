// Mostrar una alerta de éxito después de agregar un producto
function mostrarAlertaExitoAgregar() {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: 'El producto ha sido agregado correctamente.',
        confirmButtonText: 'Aceptar'
    });
}

// Mostrar una alerta de éxito después de editar un producto
function mostrarAlertaExitoEditar() {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: 'El producto ha sido actualizado correctamente.',
        confirmButtonText: 'Aceptar'
    });
}

// Mostrar una alerta de éxito después de eliminar un producto
function mostrarAlertaExitoEliminar() {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: 'El producto ha sido eliminado correctamente.',
        confirmButtonText: 'Aceptar'
    });
}

// Mostrar una alerta de error si algo sale mal
function mostrarAlertaError() {
    Swal.fire({
        icon: 'error',
        title: '¡Error!',
        text: 'Hubo un problema al realizar la acción. Por favor, inténtalo nuevamente.',
        confirmButtonText: 'Aceptar'
    });
}

// Confirmar la eliminación de un producto
function confirmarEliminacionProducto(idProducto) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Este producto será eliminado permanentemente!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirigir al archivo de eliminación del producto
            window.location.href = 'eliminar_producto.php?id=' + idProducto;
        }
    });
}

// Función para generar un reporte y mostrar una alerta al generarlo
function generarReporte() {
    Swal.fire({
        icon: 'info',
        title: 'Generando reporte...',
        text: 'Por favor, espere mientras generamos el reporte de ventas.',
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Aquí puedes realizar el proceso de generación del reporte, como enviar una solicitud AJAX para generarlo

    // Cuando el reporte haya sido generado, mostrar un mensaje de éxito
    setTimeout(function() {
        Swal.fire({
            icon: 'success',
            title: '¡Reporte Generado!',
            text: 'El reporte de ventas se ha generado correctamente.',
            confirmButtonText: 'Aceptar'
        });
    }, 3000);  // Simulación de un tiempo de espera de 3 segundos
}

// Función para mostrar una alerta si el stock es bajo
function verificarStockBajo(stock) {
    if (stock < 5) {
        Swal.fire({
            icon: 'warning',
            title: '¡Stock Bajo!',
            text: 'El stock de este producto está por debajo del mínimo.',
            confirmButtonText: 'Aceptar'
        });
    }
}

// Función para mostrar un mensaje de éxito cuando se actualiza el stock
function mostrarAlertaExitoStock() {
    Swal.fire({
        icon: 'success',
        title: '¡Stock Actualizado!',
        text: 'El stock del producto ha sido actualizado correctamente.',
        confirmButtonText: 'Aceptar'
    });
}

// Función que se activa al enviar un formulario de producto (por ejemplo, al agregar o editar)
function procesarFormulario(event) {
    event.preventDefault();  // Prevenir el envío del formulario para procesarlo manualmente

    // Aquí podrías validar los campos del formulario

    // Si todo es correcto, simular la acción exitosa
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: 'El producto ha sido guardado correctamente.',
        confirmButtonText: 'Aceptar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirigir a otra página o actualizar la vista
            window.location.href = 'productos.php';
        }
    });
}

// Ejemplo de llamada a la función de generar reporte
// Puedes ejecutar esta función al hacer clic en un botón de "Generar Reporte"
document.getElementById('btnGenerarReporte').addEventListener('click', generarReporte);
