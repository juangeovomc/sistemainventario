<?php
$servername = "localhost";
$username = "root";  // Tu nombre de usuario de MySQL
$password = "";  // Tu contraseña de MySQL
$dbname = "sistema_inventario";  // El nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
