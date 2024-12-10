<?php
// Incluir la configuración de la base de datos
include 'config.php';

// Obtener todos los productos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);

// Incluir archivo de cabecera
include 'C:\xampp\htdocs\sistemainventario\incluides\header.php';
?>

<div class="container mt-4">
    <h2>Gestión de Productos</h2>

    <!-- Mostrar una tabla con los productos -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Mostrar los productos
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id_producto'] . "</td>";
                    echo "<td>" . $row['nombre'] . "</td>";
                    echo "<td>" . $row['descripcion'] . "</td>";
                    echo "<td>" . number_format($row['precio'], 2) . "</td>";
                    echo "<td>" . $row['stock'] . "</td>";
                    echo "<td>" . ($row['estado'] == 1 ? 'Activo' : 'Inactivo') . "</td>";
                    echo "<td>
                            <a href='editar_producto.php?id=" . $row['id_producto'] . "' class='btn btn-warning btn-sm'>Editar</a>
                            <a href='eliminar_producto.php?id=" . $row['id_producto'] . "' class='btn btn-danger btn-sm'>Eliminar</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No hay productos en el inventario.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Botón para agregar un nuevo producto -->
    <a href="agregar_producto.php" class="btn btn-success mt-3">Agregar Nuevo Producto</a>
</div>

<?php
// Incluir el pie de página
include 'C:\xampp\htdocs\sistemainventario\incluides\footer.php';
?>
