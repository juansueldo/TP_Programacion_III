-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-06-2023 a las 18:03:04
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `comanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

CREATE TABLE `area` (
  `area_id` int(11) NOT NULL,
  `area_descripcion` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `area`
--

INSERT INTO `area` (`area_id`, `area_descripcion`) VALUES
(1, 'Barra de tragos'),
(2, 'Barra de choperas'),
(3, 'Cocina'),
(4, 'Candy Bar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `nombre` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `usuario_id`, `area_id`, `nombre`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 2, 3, 'Geronimo', '2023-01-01 17:50:55', NULL),
(2, 3, 2, 'Ricardo', '2023-06-19 08:04:17', NULL),
(4, 2, 3, 'Natalia', '2023-06-19 21:44:41', NULL),
(5, 4, 1, 'Julian', '2023-06-20 00:05:08', NULL),
(6, 5, 3, 'Ariel', '2023-06-20 00:05:51', NULL),
(7, 5, 4, 'Cristina', '2023-06-20 00:14:12', NULL),
(8, 2, 3, 'Julian', '2023-06-26 17:45:08', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `id` int(11) NOT NULL,
  `nro_pedido` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `mesa_puntaje` int(11) NOT NULL,
  `restaurante_puntaje` int(11) NOT NULL,
  `mozo_puntaje` int(11) NOT NULL,
  `cocinero_puntaje` float NOT NULL,
  `promedio` float NOT NULL,
  `comentarios` varchar(66) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `encuesta`
--

INSERT INTO `encuesta` (`id`, `nro_pedido`, `mesa_puntaje`, `restaurante_puntaje`, `mozo_puntaje`, `cocinero_puntaje`, `promedio`, `comentarios`) VALUES
(4, 'pd001', 8, 7, 10, 9, 8.5, 'Falta mejorar la estetica del lugar, muy anticuada'),
(5, 'pd002', 5, 7, 8, 6, 6.5, 'La comida ademas de estar fria, no era de lo mejor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_logins`
--

CREATE TABLE `historial_logins` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `usuario_nombre` varchar(40) COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_login` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `historial_logins`
--

INSERT INTO `historial_logins` (`id`, `usuario_id`, `usuario_nombre`, `fecha_login`) VALUES
(175, 1, 'Juan', '2023-06-19 01:07:52'),
(176, 1, 'Juan', '2023-06-19 01:10:06'),
(177, 1, 'Juan', '2023-06-19 01:21:24'),
(178, 1, 'Juan', '2023-06-19 01:22:08'),
(179, 1, 'Juan', '2023-06-19 01:24:19'),
(180, 1, 'Juan', '2023-06-19 01:40:41'),
(181, 1, 'Juan', '2023-06-19 01:45:00'),
(182, 1, 'Juan', '2023-06-19 01:50:10'),
(183, 1, 'Juan', '2023-06-19 02:00:40'),
(184, 1, 'Juan', '2023-06-19 02:19:04'),
(185, 1, 'Juan', '2023-06-19 02:23:26'),
(186, 1, 'Juan', '2023-06-19 03:26:54'),
(187, 1, 'Juan', '2023-06-19 03:51:17'),
(188, 1, 'Juan', '2023-06-19 04:52:38'),
(189, 1, 'Juan', '2023-06-19 04:54:36'),
(190, 2, 'User01', '2023-06-19 04:57:29'),
(191, 1, 'Juan', '2023-06-19 07:11:42'),
(192, 1, 'Juan', '2023-06-19 21:32:12'),
(193, 2, 'User01', '2023-06-19 21:35:04'),
(194, 3, 'User02', '2023-06-19 21:46:14'),
(195, 2, 'User01', '2023-06-19 21:46:56'),
(196, 1, 'Admin01', '2023-06-20 00:04:47'),
(197, 1, 'Admin01', '2023-06-20 00:25:20'),
(198, 2, 'User01', '2023-06-20 00:27:34'),
(199, 3, 'User02', '2023-06-20 00:37:40'),
(200, 5, 'User04', '2023-06-20 00:39:19'),
(201, 5, 'User04', '2023-06-26 03:08:55'),
(202, 2, 'User01', '2023-06-26 03:21:58'),
(203, 5, 'User04', '2023-06-26 03:41:59'),
(204, 3, 'User02', '2023-06-26 06:33:39'),
(205, 1, 'Admin01', '2023-06-26 07:44:15'),
(206, 2, 'User01', '2023-06-26 08:01:35'),
(207, 1, 'Admin01', '2023-06-26 08:19:45'),
(208, 2, 'User01', '2023-06-26 13:25:31'),
(209, 5, 'User04', '2023-06-26 15:05:13'),
(210, 3, 'User02', '2023-06-26 15:08:04'),
(211, 1, 'Admin01', '2023-06-26 15:14:15'),
(212, 2, 'User01', '2023-06-26 15:51:01'),
(213, 1, 'Admin01', '2023-06-26 17:59:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `numero_mesa` varchar(5) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `empleado_id` int(11) DEFAULT NULL,
  `estado` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `numero_mesa`, `empleado_id`, `estado`) VALUES
(1, 'M1001', 1, 'cerrada'),
(2, 'M1002', 4, 'cerrada'),
(3, 'M1003', 1, 'cerrada'),
(4, 'M1004', 4, 'cerrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `mesa_id` int(11) DEFAULT NULL,
  `nro_pedido` varchar(5) NOT NULL,
  `pedido_estado` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `cliente_nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `pedido_foto` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `pedido_costo` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `mesa_id`, `nro_pedido`, `pedido_estado`, `cliente_nombre`, `pedido_foto`, `pedido_costo`) VALUES
(1, 1, 'pd001', 'con cliente comiendo', 'Cliente_habitue', './PedidoFoto/1.png', 16560),
(2, 2, 'pd002', 'con cliente esperando pedido', 'Juan', 'PedidoFoto/Juan.png', 18230),
(3, 2, 'pd003', 'con cliente esperando pedido', 'Ricardo', 'PedidoFoto/Ricardo.png', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `producto_area` int(11) NOT NULL,
  `pedido_asociado` varchar(5) DEFAULT NULL,
  `estado` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `costo` float NOT NULL,
  `tiempo_inicio` datetime NOT NULL,
  `tiempo_fin` datetime DEFAULT NULL,
  `tiempo_para_finalizar` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `producto_area`, `pedido_asociado`, `estado`, `descripcion`, `costo`, `tiempo_inicio`, `tiempo_fin`, `tiempo_para_finalizar`) VALUES
(1, 3, 'pd001', 'Listo Para Servir', 'Pollo al curry', 3550, '2023-06-14 20:30:10', '2023-06-14 21:00:33', 30),
(2, 3, 'pd001', 'Listo Para Servir', 'Milanesa a caballo', 3190, '2023-06-19 20:26:59', '2023-06-19 20:46:59', 20),
(3, 3, 'pd001', 'Listo Para Servir', 'Hamburguesa de garbanzo', 2810, '2023-06-19 21:31:00', '2023-06-19 21:41:00', 10),
(4, 3, 'pd001', 'Listo Para Servir', 'Hamburguesa de garbanzo', 2810, '2023-06-19 21:32:51', NULL, NULL),
(5, 2, 'pd001', 'Listo Para Servir', 'Corona', 2100, '2023-06-19 21:34:07', '2023-06-19 21:39:07', 5),
(6, 1, 'pd001', 'Listo Para Servir', 'Daikiri', 2100, '2023-06-19 21:34:20', NULL, NULL),
(7, 3, 'pd002', 'en preparacion', 'Hamburguesas de garbanzo', 2890, '2023-06-26 13:35:52', NULL, NULL),
(8, 3, 'pd002', 'en preparacion', 'Ravioles de verduras', 2120, '2023-06-26 13:48:58', NULL, NULL),
(9, 3, 'pd002', 'en preparacion', 'Estofado de carne', 1920, '2023-06-26 14:35:37', NULL, NULL),
(10, 3, 'pd002', 'en preparacion', 'Filet de merluza', 2300, '2023-06-26 14:42:41', NULL, NULL),
(11, 3, 'pd002', 'en preparacion', 'Filet de merluza', 2300, '2023-06-26 14:46:36', NULL, NULL),
(12, 3, 'pd002', 'en preparacion', 'Filet de merluza', 2300, '2023-06-26 14:48:24', NULL, NULL),
(13, 3, 'pd002', 'Listo Para Servir', 'Guiso de lentejas', 2700, '2023-06-26 14:51:19', '2023-06-26 15:11:19', 20),
(14, 2, 'pd002', 'Listo Para Servir', 'Cerveza corona', 1700, '2023-06-26 14:54:33', '2023-06-26 15:04:33', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario_nombre` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `clave` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `esSocio` tinyint(1) NOT NULL,
  `usuario_tipo` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estado` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario_nombre`, `clave`, `esSocio`, `usuario_tipo`, `estado`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 'Admin01', 'T3sT$JWT', 1, 'Socio', 'Activo', '2022-11-01 10:30:00', NULL),
(2, 'User01', 'n4t1', 0, 'Mozo', 'Activo', '2020-06-05 10:20:10', NULL),
(3, 'User02', '10gos', 0, 'Cervecero', 'Activo', '2021-07-01 10:30:10', NULL),
(4, 'User03', '1ncu3us', 0, 'Bartender', 'Activo', '2023-01-15 18:30:00', NULL),
(5, 'User04', 'ch3fk', 0, 'Cocinero', 'Activo', '2023-05-15 14:15:11', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`area_id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_empleado_area` (`area_id`),
  ADD KEY `FK_empleado_usuario` (`usuario_id`);

--
-- Indices de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fo_encuesta_pedido` (`nro_pedido`);

--
-- Indices de la tabla `historial_logins`
--
ALTER TABLE `historial_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_usuario_login` (`usuario_id`) USING BTREE;

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_mesa` (`numero_mesa`),
  ADD KEY `FK_mesa_empleado_id` (`empleado_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Mesa_Pedido` (`mesa_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_producto_pedido_asociado` (`pedido_asociado`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_nombre` (`usuario_nombre`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `area`
--
ALTER TABLE `area`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `historial_logins`
--
ALTER TABLE `historial_logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
