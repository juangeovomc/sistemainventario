<?php
// Incluir la configuración de la base de datos
include('C:\xampp\htdocs\sistemainventario\incluides\db.php');  

// Verificar si se pasó un ID de producto en la URL
if (isset($_GET['id'])) {
    $id_producto = $_GET['id'];

    // Obtener los detalles del producto desde la base de datos
    $query = "SELECT * FROM productos WHERE id_producto = $id_producto";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $producto = mysqli_fetch_assoc($result);
    } else {
        // Si no se encuentra el producto, redirigir a la lista de productos
        header("Location: index.php");
        exit();
    }
} else {
    // Si no se pasa el ID del producto, redirigir a la lista de productos
    header("Location: index.php");
    exit();
}

// Verificar si se envió el formulario de edición
if (isset($_POST['actualizar'])) {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $estado = $_POST['estado'];

    // Actualizar los datos del producto en la base de datos
    $update_query = "UPDATE productos SET nombre = '$nombre', descripcion = '$descripcion', precio = '$precio', stock = '$stock', estado = '$estado' WHERE id_producto = $id_producto";
    
    if (mysqli_query($conn, $update_query)) {
        // Si la actualización es exitosa, redirigir al índice de productos
        // Además, actualizar el estado a 'inactivo' si el stock es 0
        if ($stock == 0) {
            $update_estado_query = "UPDATE productos SET estado = 'inactivo' WHERE id_producto = $id_producto";
            mysqli_query($conn, $update_estado_query);
        }

        // Redirigir al índice de productos después de la actualización
        header("Location: index.php");
        exit();
    } else {
        // Mostrar un mensaje de error si la actualización falla
        $error_message = "Error al actualizar el producto: " . mysqli_error($conn);
    }
}
?>

<?php include('C:\xampp\htdocs\sistemainventario\incluides\header.php'); ?> <!-- Usar ruta relativa -->

<!-- Formulario de edición de producto -->
<div class="container mt-5">
    <h2 class="text-center">Editar Producto</h2>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form action="editar_producto.php?id=<?php echo $id_producto; ?>" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Producto</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $producto['nombre']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" required><?php echo $producto['descripcion']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" class="form-control" id="precio" name="precio" value="<?php echo $producto['precio']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $producto['stock']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-control" id="estado" name="estado" required>
                <option value="activo" <?php echo $producto['estado'] == 'activo' ? 'selected' : ''; ?>>Activo</option>
                <option value="inactivo" <?php echo $producto['estado'] == 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>
        <button type="submit" name="actualizar" class="btn btn-primary">Actualizar Producto</button>
    </form>
</div>

<?php include('C:\xampp\htdocs\sistemainventario\incluides\footer.php'); ?> <!-- Usar ruta relativa -->
