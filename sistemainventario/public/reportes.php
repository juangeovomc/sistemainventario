<?php
// Incluir la configuración de la base de datos
include('C:\xampp\htdocs\sistemainventario\incluides\db.php');  

// Inicializar el mensaje de error
$error_message = "";

// Verificar si el formulario fue enviado para obtener el rango de fechas
if (isset($_POST['generar_reporte'])) {
    // Obtener las fechas del formulario
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    // Validar si las fechas están presentes
    if (empty($fecha_inicio) || empty($fecha_fin)) {
        $error_message = "Por favor, ingrese ambas fechas.";
    } else {
        // Consultar las ventas en el rango de fechas
        $query = "SELECT v.id_venta, p.nombre AS nombre_producto, v.cantidad_vendida, v.fecha_venta, v.total
                  FROM ventas v
                  JOIN productos p ON v.id_producto = p.id_producto
                  WHERE v.fecha_venta BETWEEN '$fecha_inicio' AND '$fecha_fin'
                  ORDER BY v.fecha_venta ASC";
        $result = mysqli_query($conn, $query);

        // Verificar si hay resultados
        if (mysqli_num_rows($result) > 0) {
            $ventas = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $error_message = "No se encontraron ventas en el rango de fechas proporcionado.";
        }
    }
}

?>

<?php include('C:\xampp\htdocs\sistemainventario\incluides\header.php'); ?> <!-- Usar ruta relativa -->

<div class="container mt-5">
    <h2 class="text-center">Generar Reporte de Ventas</h2>

    <!-- Mostrar mensajes de error si existen -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Formulario para seleccionar fechas -->
    <form method="POST" action="reportes.php">
        <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
        </div>
        <div class="mb-3">
            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
        </div>
        <button type="submit" name="generar_reporte" class="btn btn-primary">Generar Reporte</button>
    </form>

    <!-- Mostrar el reporte de ventas si existen resultados -->
    <?php if (isset($ventas) && count($ventas) > 0): ?>
        <h3 class="text-center mt-4">Reporte de Ventas</h3>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Producto</th>
                    <th>Cantidad Vendida</th>
                    <th>Fecha</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventas as $venta): ?>
                    <tr>
                        <td><?php echo $venta['id_venta']; ?></td>
                        <td><?php echo $venta['nombre_producto']; ?></td> <!-- Nombre del producto -->
                        <td><?php echo $venta['cantidad_vendida']; ?></td>
                        <td><?php echo $venta['fecha_venta']; ?></td>
                        <td><?php echo $venta['total']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include('C:\xampp\htdocs\sistemainventario\incluides\footer.php'); ?> <!-- Usar ruta relativa -->
