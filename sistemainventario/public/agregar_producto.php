<?php
// Incluir archivos necesarios
include 'C:\xampp\htdocs\sistemainventario\incluides\header.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $estado = $_POST['estado'];

    // Validar los datos (por ejemplo, asegurarse de que los valores son válidos)
    if (is_numeric($precio) && is_numeric($stock) && $stock >= 0 && $precio > 0) {
        // Insertar el nuevo producto en la base de datos
        $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, estado) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $stock, $estado);

        if ($stmt->execute()) {
            // Mensaje de éxito
            echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Producto agregado",
                        text: "El producto ha sido agregado correctamente al inventario.",
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
                        text: "Hubo un problema al agregar el producto. Intenta nuevamente.",
                    });
                  </script>';
        }
    } else {
        // Mensaje de validación si los datos no son válidos
        echo '<script>
                Swal.fire({
                    icon: "warning",
                    title: "Datos no válidos",
                    text: "Por favor, ingresa datos válidos (precio y stock deben ser números positivos).",
                });
              </script>';
    }
}
?>

<!-- Formulario para agregar un nuevo producto -->
<div class="container">
    <h2>Agregar Producto</h2>

    <form action="agregar_producto.php" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Producto</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" required>
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-control" id="estado" name="estado" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Agregar Producto</button>
    </form>
</div>

<?php
// Incluir el pie de página
include 'C:\xampp\htdocs\sistemainventario\incluides\footer.php';
?>
