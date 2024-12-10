<?php
// Incluir la configuración de la base de datos
include('C:\xampp\htdocs\sistemainventario\incluides\db.php');

// Verificar si se pasó un ID de producto en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_producto = (int)$_GET['id'];

    // Verificar si el producto existe en la base de datos
    $check_query = "SELECT * FROM productos WHERE id_producto = $id_producto";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Iniciar una transacción para garantizar consistencia
        mysqli_begin_transaction($conn);

        try {
            // Eliminar dependencias en la tabla ventas
            $delete_ventas_query = "DELETE FROM ventas WHERE id_producto = $id_producto";
            mysqli_query($conn, $delete_ventas_query);

            // Eliminar dependencias en la tabla historial_stock
            $delete_historial_query = "DELETE FROM historial_stock WHERE id_producto = $id_producto";
            mysqli_query($conn, $delete_historial_query);

            // Eliminar el producto
            $delete_producto_query = "DELETE FROM productos WHERE id_producto = $id_producto";
            mysqli_query($conn, $delete_producto_query);

            // Confirmar la transacción
            mysqli_commit($conn);

            // Redirigir con mensaje de éxito
            header("Location: index.php?mensaje=producto_eliminado");
            exit();
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            mysqli_rollback($conn);

            // Mostrar un mensaje de error
            $error_message = "Error al eliminar el producto: " . $e->getMessage();
        }
    } else {
        // Si el producto no existe, redirigir al índice
        header("Location: index.php?mensaje=producto_no_encontrado");
        exit();
    }
} else {
    // Si no se pasa un ID válido, redirigir al índice
    header("Location: index.php?mensaje=id_no_proporcionado");
    exit();
}
?>

<?php include('C:\xampp\htdocs\sistemainventario\incluides\header.php'); ?>

<!-- Si hay un mensaje de error, mostrarlo -->
<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<!-- Mensaje de confirmación -->
<div class="container mt-5">
    <h2 class="text-center">¿Estás seguro de eliminar este producto?</h2>
    <p class="text-center">Esta acción no se puede deshacer.</p>

    <form action="eliminar_producto.php?id=<?php echo $id_producto; ?>" method="POST" class="text-center">
        <button type="submit" name="confirmar" class="btn btn-danger">Eliminar Producto</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include('C:\xampp\htdocs\sistemainventario\incluides\footer.php'); ?>
