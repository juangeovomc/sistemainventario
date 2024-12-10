<?php
// Configuración de la base de datos
$servername = "localhost"; // Cambiar a tu servidor de base de datos si no es localhost
$username = "root"; // Cambiar si usas un usuario distinto
$password = ""; // Cambiar si tu base de datos tiene una contraseña
$dbname = "sistema_inventario"; // Nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
