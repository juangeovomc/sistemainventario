<?php
// Incluir archivos necesarios
include 'includes/header.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $id_producto = $_POST['id_producto'];
    $nuevo_stock = $_POST['nuevo_stock'];

    // Validar que el stock es un número positivo
    if (is_numeric($nuevo_stock) && $nuevo_stock >= 0) {
        // Actualizar el stock en la base de datos
        $sql = "UPDATE productos SET stock = ? WHERE id_producto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $nuevo_stock, $id_producto);

        if ($stmt->execute()) {
            // Mensaje de éxito
            echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Stock actualizado",
                        text: "El stock del producto ha sido actualizado correctamente.",
                    }).then(function() {
                        window.location = "productos.php"; // Redirigir a la lista de productos
                    });
                  </script>';
        } else {
            // Mensaje de error
            echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Hubo un problema al actualizar el stock. Intenta nuevamente.",
                    });
                  </script>';
        }
    } else {
        // Mensaje de validación si el stock no es válido
        echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "Stock no válido",
                    text: "Por favor, ingresa un número válido para el stock.",
                });
              </script>';
    }
}

?>

<!-- Formulario para actualizar el stock -->
<div class="container">
    <h2>Actualizar Stock del Producto</h2>

    <form action="actualizar_stock.php" method="POST">
        <div class="mb-3">
            <label for="id_producto" class="form-label">ID Producto</label>
            <input type="number" class="form-control" id="id_producto" name="id_producto" required>
        </div>

        <div class="mb-3">
            <label for="nuevo_stock" class="form-label">Nuevo Stock</label>
            <input type="number" class="form-control" id="nuevo_stock" name="nuevo_stock" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Stock</button>
    </form>
</div>

<?php
// Incluir el pie de página
include 'includes/footer.php';
?>
