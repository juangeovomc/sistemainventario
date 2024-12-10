-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-12-2024 a las 22:38:26
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_inventario`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizar_producto` (IN `id` INT, IN `nombre` VARCHAR(100), IN `descripcion` TEXT, IN `precio` DECIMAL(10,2), IN `stock` INT)   BEGIN
    UPDATE productos 
    SET nombre = nombre, descripcion = descripcion, precio = precio, stock = stock
    WHERE id_producto = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `agregar_producto` (IN `nombre` VARCHAR(100), IN `descripcion` TEXT, IN `precio` DECIMAL(10,2), IN `stock` INT)   BEGIN
    INSERT INTO productos (nombre, descripcion, precio, stock) 
    VALUES (nombre, descripcion, precio, stock);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_venta` (IN `id_producto` INT, IN `cantidad` INT)   BEGIN
    DECLARE precio_unitario DECIMAL(10, 2);
    SELECT precio INTO precio_unitario FROM productos WHERE id_producto = id_producto;

    INSERT INTO ventas (id_producto, cantidad, total)
    VALUES (id_producto, cantidad, precio_unitario * cantidad);

    UPDATE productos
    SET stock = stock - cantidad
    WHERE id_producto = id_producto;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alertas`
--

CREATE TABLE `alertas` (
  `id_alerta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_stock`
--

CREATE TABLE `historial_stock` (
  `id_historial` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cambio` int(11) NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_stock`
--

INSERT INTO `historial_stock` (`id_historial`, `id_producto`, `cambio`, `fecha`) VALUES
(50, 7, 0, '2024-12-10 16:36:43'),
(51, 10, 0, '2024-12-10 16:36:49'),
(52, 10, 0, '2024-12-10 16:36:49'),
(53, 9, 0, '2024-12-10 16:37:03'),
(54, 9, -1, '2024-12-10 16:37:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs_bajo_inventario`
--

CREATE TABLE `logs_bajo_inventario` (
  `id_log` int(11) NOT NULL,
  `nombre_producto` varchar(100) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `precio`, `stock`, `estado`, `fecha_creacion`) VALUES
(7, 'Play 5', 'Consola de videjuegos', 1800000.00, 30, 'activo', '2024-12-10 21:35:09'),
(8, 'Tv', 'Electrodomestico', 1500000.00, 10, 'activo', '2024-12-10 21:35:31'),
(9, 'Nevera ', 'Electrodomestico', 2000000.00, 4, 'activo', '2024-12-10 21:36:02'),
(10, 'Lavadora', 'Electrodomestico', 1450000.00, 0, 'inactivo', '2024-12-10 21:36:36');

--
-- Disparadores `productos`
--
DELIMITER $$
CREATE TRIGGER `actualizar_estado_producto` AFTER UPDATE ON `productos` FOR EACH ROW BEGIN
    -- Verificar si el stock es 0 y el estado no es 'inactivo'
    IF NEW.stock = 0 AND NEW.estado != 'inactivo' THEN
        -- Cambiar el estado del producto a 'inactivo'
        UPDATE productos 
        SET estado = 'inactivo'
        WHERE id_producto = NEW.id_producto;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `alerta_bajo_inventario` AFTER UPDATE ON `productos` FOR EACH ROW BEGIN
    IF NEW.stock < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Advertencia: El stock es bajo para el producto.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `antes_de_actualizar_producto` BEFORE UPDATE ON `productos` FOR EACH ROW BEGIN
    -- Cambiar el estado a 'inactivo' si el stock es 0 y el estado no es ya 'inactivo'
    IF NEW.stock = 0 AND OLD.estado != 'inactivo' THEN
        SET NEW.estado = 'inactivo';  -- Cambiar el estado antes de guardar
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `registrar_cambio_stock` AFTER UPDATE ON `productos` FOR EACH ROW BEGIN
    DECLARE cambio_stock INT;
    SET cambio_stock = NEW.stock - OLD.stock;

    INSERT INTO historial_stock (id_producto, cambio, fecha)
    VALUES (NEW.id_producto, cambio_stock, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes_ventas_semanales`
--

CREATE TABLE `reportes_ventas_semanales` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `fecha_venta` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','usuario') NOT NULL DEFAULT 'usuario',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha_venta` timestamp NOT NULL DEFAULT current_timestamp(),
  `cantidad_vendida` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_producto`, `cantidad`, `total`, `fecha_venta`, `cantidad_vendida`, `fecha`) VALUES
(8, 9, 0, 2000000.00, '2024-12-10 21:37:03', 1, '2024-12-10 16:37:03');

--
-- Disparadores `ventas`
--
DELIMITER $$
CREATE TRIGGER `registrar_cambios_stock` AFTER INSERT ON `ventas` FOR EACH ROW BEGIN
    UPDATE productos
    SET stock = stock - NEW.cantidad
    WHERE id_producto = NEW.id_producto;
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alertas`
--
ALTER TABLE `alertas`
  ADD PRIMARY KEY (`id_alerta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `historial_stock`
--
ALTER TABLE `historial_stock`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `historial_stock_ibfk_1` (`id_producto`);

--
-- Indices de la tabla `logs_bajo_inventario`
--
ALTER TABLE `logs_bajo_inventario`
  ADD PRIMARY KEY (`id_log`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `reportes_ventas_semanales`
--
ALTER TABLE `reportes_ventas_semanales`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alertas`
--
ALTER TABLE `alertas`
  MODIFY `id_alerta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_stock`
--
ALTER TABLE `historial_stock`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `logs_bajo_inventario`
--
ALTER TABLE `logs_bajo_inventario`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `reportes_ventas_semanales`
--
ALTER TABLE `reportes_ventas_semanales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alertas`
--
ALTER TABLE `alertas`
  ADD CONSTRAINT `alertas_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `historial_stock`
--
ALTER TABLE `historial_stock`
  ADD CONSTRAINT `historial_stock_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `verificar_bajo_inventario` ON SCHEDULE EVERY 1 DAY STARTS '2024-12-10 02:19:02' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    INSERT INTO logs_bajo_inventario (nombre_producto, stock)
    SELECT nombre, stock 
    FROM productos 
    WHERE stock < 5;
END$$

CREATE DEFINER=`root`@`localhost` EVENT `generar_reporte_semanal` ON SCHEDULE EVERY 1 WEEK STARTS '2024-12-10 02:20:46' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    INSERT INTO reportes_ventas_semanales (id_venta, id_producto, cantidad, total, fecha_venta)
    SELECT id_venta, id_producto, cantidad, total, fecha_venta
    FROM ventas
    WHERE fecha_venta >= NOW() - INTERVAL 1 WEEK;
END$$

CREATE DEFINER=`root`@`localhost` EVENT `limpiar_productos_obsoletos` ON SCHEDULE EVERY 1 MONTH STARTS '2024-12-10 02:21:27' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    DELETE FROM productos WHERE stock = 0 AND estado = 'inactivo';
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
