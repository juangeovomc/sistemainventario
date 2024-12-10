<?php
// Incluir la configuración de la base de datos
include('C:\xampp\htdocs\sistemainventario\incluides\db.php');

// Inicializar variables
$mensaje = "";

// Verificar si se envió el formulario de ventas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_producto = $_POST['id_producto'];
    $cantidad_vendida = $_POST['cantidad_vendida'];

    // Obtener el precio del producto
    $query_producto = "SELECT * FROM productos WHERE id_producto = $id_producto";
    $result_producto = mysqli_query($conn, $query_producto);

    if (mysqli_num_rows($result_producto) > 0) {
        $producto = mysqli_fetch_assoc($result_producto);
        $precio = $producto['precio'];
        $stock_actual = $producto['stock'];

        // Verificar si hay suficiente stock
        if ($cantidad_vendida > $stock_actual) {
            $mensaje = "No hay suficiente stock para completar la venta.";
        } else {
            // Calcular el total de la venta
            $total = $precio * $cantidad_vendida;

            // Insertar la venta en la tabla de ventas
            $query_venta = "INSERT INTO ventas (id_producto, cantidad_vendida, total, fecha_venta) VALUES ('$id_producto', '$cantidad_vendida', '$total', NOW())";
            if (mysqli_query($conn, $query_venta)) {
                // Actualizar el stock del producto
                $nuevo_stock = $stock_actual - $cantidad_vendida;
                $query_update_stock = "UPDATE productos SET stock = $nuevo_stock WHERE id_producto = $id_producto";
                mysqli_query($conn, $query_update_stock);

                $mensaje = "Venta registrada exitosamente.";
            } else {
                $mensaje = "Error al registrar la venta: " . mysqli_error($conn);
            }
        }
    } else {
        $mensaje = "Producto no encontrado.";
    }
}

// Obtener la lista de productos para el formulario
$query_productos = "SELECT * FROM productos";
$result_productos = mysqli_query($conn, $query_productos);
?>

<?php include('C:\xampp\htdocs\sistemainventario\incluides\header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center">Registrar Venta</h2>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST" action="ventas.php">
        <div class="mb-3">
            <label for="id_producto" class="form-label">Producto</label>
            <select class="form-control" id="id_producto" name="id_producto" required>
                <option value="">Seleccione un producto</option>
                <?php while ($producto = mysqli_fetch_assoc($result_productos)): ?>
                    <option value="<?php echo $producto['id_producto']; ?>">
                        <?php echo $producto['nombre']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="cantidad_vendida" class="form-label">Cantidad</label>
            <input type="number" class="form-control" id="cantidad_vendida" name="cantidad_vendida" min="1" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Venta</button>
    </form>
</div>

<?php include('C:\xampp\htdocs\sistemainventario\incluides\footer.php'); ?>
