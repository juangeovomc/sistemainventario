<?php
// Incluir la configuración de la base de datos
include('C:\xampp\htdocs\sistemainventario\incluides\db.php');

// Verificar si el formulario ha sido enviado
if (isset($_POST['agregar'])) {
    // Obtener los datos del formulario
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $rol = mysqli_real_escape_string($conn, $_POST['rol']);
    
    // Encriptar la contraseña antes de almacenarla
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insertar los datos del usuario en la base de datos
    $insert_query = "INSERT INTO usuarios (nombre, correo, password, rol) 
                     VALUES ('$nombre', '$correo', '$password_hash', '$rol')";
    
    if (mysqli_query($conn, $insert_query)) {
        // Si la inserción es exitosa, redirigir al listado de usuarios
        header("Location: usuarios.php");
        exit();
    } else {
        // Mostrar un mensaje de error si la inserción falla
        $error_message = "Error al agregar el usuario: " . mysqli_error($conn);
    }
}

?>

<?php include('C:\xampp\htdocs\sistemainventario\incluides\header.php'); ?> <!-- Usar ruta relativa -->

<!-- Formulario para agregar un nuevo usuario -->
<div class="container mt-5">
    <h2 class="text-center">Agregar Nuevo Usuario</h2>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form action="agregar_usuario.php" method="POST">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Usuario</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select class="form-control" id="rol" name="rol" required>
                <option value="admin">Administrador</option>
                <option value="usuario">Usuario</option>
            </select>
        </div>
        <button type="submit" name="agregar" class="btn btn-primary">Agregar Usuario</button>
    </form>
</div>

<?php include('C:\xampp\htdocs\sistemainventario\incluides\footer.php'); ?> <!-- Usar ruta relativa -->
