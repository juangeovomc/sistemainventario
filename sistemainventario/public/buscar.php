<?php
// Incluir la configuración de la base de datos
include('C:\xampp\htdocs\sistemainventario\incluides\db.php'); 

// Inicializar variables
$resultados = [];
$error_message = "";

// Verificar si se envió el formulario de búsqueda
if (isset($_POST['buscar'])) {
    $criterio = mysqli_real_escape_string($conn, $_POST['criterio']);
    $termino = mysqli_real_escape_string($conn, $_POST['termino']);

    // Verificar que no esté vacío
    if (!empty($criterio) && !empty($termino)) {
        // Construir la consulta según el criterio
        $query = "SELECT * FROM productos WHERE $criterio LIKE '%$termino%'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Obtener los resultados
            $resultados = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $error_message = "Error en la búsqueda: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Por favor, selecciona un criterio y escribe un término de búsqueda.";
    }
}

?>

<?php include('C:\xampp\htdocs\sistemainventario\incluides\header.php'); ?> <!-- Usar ruta relativa -->

<div class="container mt-5">
    <h2 class="text-center">Buscar Productos</h2>

    <!-- Formulario de búsqueda -->
    <form action="buscar.php" method="POST" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <select name="criterio" class="form-control" required>
                    <option value="">Seleccionar criterio</option>
                    <option value="nombre">Nombre</option>
                    <option value="descripcion">Descripción</option>
                    <option value="estado">Estado</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="termino" class="form-control" placeholder="Escribe el término de búsqueda" required>
            </div>
            <div class="col-md-2">
                <button type="submit" name="buscar" class="btn btn-primary">Buscar</button>
            </div>
        </div>
    </form>

    <!-- Mostrar errores -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Resultados de la búsqueda -->
    <?php if (!empty($resultados)): ?>
        <h3 class="text-center">Resultados:</h3>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultados as $producto): ?>
                    <tr>
                        <td><?php echo $producto['id_producto']; ?></td>
                        <td><?php echo $producto['nombre']; ?></td>
                        <td><?php echo $producto['descripcion']; ?></td>
                        <td><?php echo $producto['precio']; ?></td>
                        <td><?php echo $producto['stock']; ?></td>
                        <td><?php echo $producto['estado']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif (isset($_POST['buscar']) && empty($resultados)): ?>
        <div class="alert alert-warning text-center">No se encontraron resultados para la búsqueda.</div>
    <?php endif; ?>
</div>

<?php include('C:\xampp\htdocs\sistemainventario\incluides\footer.php'); ?> <!-- Usar ruta relativa -->
