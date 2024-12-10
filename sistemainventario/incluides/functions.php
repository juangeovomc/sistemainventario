<?php
// Función para obtener todos los productos
function obtenerProductos($conn) {
    $sql = "SELECT * FROM productos";
    $result = $conn->query($sql);
    return $result;
}

// Función para obtener un producto por su ID
function obtenerProductoPorId($conn, $id_producto) {
    $sql = "SELECT * FROM productos WHERE id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Función para agregar un nuevo producto
function agregarProducto($conn, $nombre, $stock, $precio) {
    $sql = "INSERT INTO productos (nombre, stock, precio) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sid", $nombre, $stock, $precio);
    
    return $stmt->execute();
}

// Función para actualizar el stock de un producto
function actualizarStock($conn, $id_producto, $stock) {
    $sql = "UPDATE productos SET stock = ? WHERE id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $stock, $id_producto);
    
    return $stmt->execute();
}

// Función para eliminar un producto
function eliminarProducto($conn, $id_producto) {
    $sql = "DELETE FROM productos WHERE id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_producto);
    
    return $stmt->execute();
}

// Función para obtener reportes de ventas
function generarReporteVentas($conn) {
    $sql = "SELECT * FROM ventas";
    $result = $conn->query($sql);
    return $result;
}

// Función para obtener los productos con stock bajo
function obtenerProductosBajoStock($conn) {
    $sql = "SELECT * FROM productos WHERE stock < 5";
    $result = $conn->query($sql);
    return $result;
}

// Función para procesar una venta y actualizar el stock
function procesarVenta($conn, $id_producto, $cantidad) {
    // Primero, obtener el stock actual del producto
    $producto = obtenerProductoPorId($conn, $id_producto);
    $nuevo_stock = $producto['stock'] - $cantidad;
    
    // Actualizar el stock del producto
    actualizarStock($conn, $id_producto, $nuevo_stock);
    
    // Registrar la venta
    $sql = "INSERT INTO ventas (id_producto, cantidad, fecha) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_producto, $cantidad);
    
    return $stmt->execute();
}

// Función para generar un reporte en CSV de los productos
function generarReporteProductos($conn) {
    $sql = "SELECT * FROM productos";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $filename = "reporte_productos_" . date("Y-m-d") . ".csv";
        $file = fopen($filename, "w");
        
        // Escribir los encabezados de las columnas
        $columns = ['ID', 'Nombre', 'Stock', 'Precio'];
        fputcsv($file, $columns);
        
        // Escribir los datos de los productos
        while ($row = $result->fetch_assoc()) {
            fputcsv($file, $row);
        }
        
        fclose($file);
        return $filename;
    }
    return false;
}

// Función para generar un reporte de ventas en CSV
function generarReporteVentasCSV($conn) {
    $sql = "SELECT * FROM ventas";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $filename = "reporte_ventas_" . date("Y-m-d") . ".csv";
        $file = fopen($filename, "w");
        
        // Escribir los encabezados de las columnas
        $columns = ['ID Venta', 'ID Producto', 'Cantidad', 'Fecha'];
        fputcsv($file, $columns);
        
        // Escribir los datos de las ventas
        while ($row = $result->fetch_assoc()) {
            fputcsv($file, $row);
        }
        
        fclose($file);
        return $filename;
    }
    return false;
}
?>
