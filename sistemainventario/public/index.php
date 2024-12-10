<?php
include('C:\xampp\htdocs\sistemainventario\incluides\header.php');  // Ajusta la ruta a la carpeta 'includes'
?>

<!-- Contenido principal de la página -->
<div class="container mt-5">
    <h2 class="text-center">Sistema de Gestión de Inventario</h2>
    
    <!-- Mensaje de bienvenida o instrucciones -->
    <div class="alert alert-info text-center mt-3">
        <strong>Bienvenido al sistema de gestión de inventarios</strong> - Aquí puedes administrar productos, ventas y generar reportes.
    </div>

    <!-- Tabla de productos -->
    <div class="table-responsive mt-4">
        <h4 class="section-header">Lista de Productos</h4>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID Producto</th>
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
                // Conectar a la base de datos
                include('C:\xampp\htdocs\sistemainventario\incluides\db.php');  

                $query = "SELECT * FROM productos";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['id_producto']}</td>
                                <td>{$row['nombre']}</td>
                                <td>{$row['descripcion']}</td>
                                <td>{$row['precio']}</td>
                                <td>{$row['stock']}</td>
                                <td>{$row['estado']}</td>
                                <td>
                                    <a href='editar_producto.php?id={$row['id_producto']}' class='btn btn-warning btn-sm'>Editar</a>
                                    <a href='eliminar_producto.php?id={$row['id_producto']}' class='btn btn-danger btn-sm'>Eliminar</a>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No se encontraron productos</td></tr>";
                }

                // Cerrar la conexión a la base de datos
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>

    <!-- Botón para agregar un nuevo producto -->
    <div class="text-center mt-4">
        <a href="agregar_producto.php" class="btn btn-success">Agregar Producto</a>
    </div>
</div>

<!-- Footer -->
<?php
include('C:\xampp\htdocs\sistemainventario\incluides\footer.php');  // Ajusta la ruta a la carpeta 'includes'
?>
