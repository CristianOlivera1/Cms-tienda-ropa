-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-01-2025 a las 01:37:13
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tiendacms`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `carId` int(11) NOT NULL,
  `carNombre` varchar(40) DEFAULT NULL,
  `carFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`carId`, `carNombre`, `carFechaRegis`) VALUES
(1, 'Gerente', '2024-12-30 11:21:50'),
(2, 'Administrador', '2024-12-30 11:21:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `catId` int(11) NOT NULL,
  `catNombre` varchar(100) DEFAULT NULL,
  `catDescripcion` varchar(255) DEFAULT NULL,
  `catDetalle` varchar(100) DEFAULT NULL,
  `catImg` varchar(255) DEFAULT NULL,
  `catFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`catId`, `catNombre`, `catDescripcion`, `catDetalle`, `catImg`, `catFechaRegis`) VALUES
(11, 'Casacas', NULL, NULL, NULL, '2025-01-07 07:38:02'),
(12, 'Gorras', NULL, NULL, NULL, '2025-01-07 07:38:21'),
(14, 'POLOS2', NULL, NULL, NULL, '2025-01-08 14:25:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `cliId` int(11) NOT NULL,
  `cliDni` char(8) NOT NULL,
  `cliNombre` varchar(100) DEFAULT NULL,
  `cliApellidoPaterno` varchar(100) DEFAULT NULL,
  `cliApellidoMaterno` varchar(100) DEFAULT NULL,
  `cliCorreo` varchar(150) DEFAULT NULL,
  `cliFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`cliId`, `cliDni`, `cliNombre`, `cliApellidoPaterno`, `cliApellidoMaterno`, `cliCorreo`, `cliFechaRegis`) VALUES
(1, '', 'Crond', 'ccccccccccccc', 'ssssssssssssss', 'cccccccc@gmail.com', '2025-01-07 08:34:14'),
(4, '', 'Luis', 'Alfaro', 'Chirinos', 'miguel@gmail.com', '2025-01-08 14:34:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `color`
--

CREATE TABLE `color` (
  `colId` int(11) NOT NULL,
  `colNombre` varchar(50) NOT NULL,
  `colCodigoHex` varchar(30) DEFAULT NULL,
  `colFechaRegis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `color`
--

INSERT INTO `color` (`colId`, `colNombre`, `colCodigoHex`, `colFechaRegis`) VALUES
(13, 'negro', '#000000', '2025-01-11 15:20:05'),
(16, 'azul', '#004cff', '2025-01-11 15:40:11'),
(17, 'ddd', '#ffffff', '2025-01-11 15:53:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto`
--

CREATE TABLE `contacto` (
  `conId` int(11) NOT NULL,
  `conTelefono` char(9) DEFAULT NULL,
  `conEmail` varchar(100) DEFAULT NULL,
  `conFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contacto`
--

INSERT INTO `contacto` (`conId`, `conTelefono`, `conEmail`, `conFechaRegis`) VALUES
(1, '987654320', '2211@ddddd.com', '2025-01-04 16:34:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleventa`
--

CREATE TABLE `detalleventa` (
  `detVenId` int(11) NOT NULL,
  `venId` int(11) DEFAULT NULL,
  `stoId` int(11) DEFAULT NULL,
  `detVenCantidad` int(11) DEFAULT NULL,
  `detVenPrecio` decimal(8,2) DEFAULT NULL,
  `detVenFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `estId` int(11) NOT NULL,
  `estDisponible` tinyint(1) DEFAULT NULL,
  `estFechaRegis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`estId`, `estDisponible`, `estFechaRegis`) VALUES
(1, 1, '2025-01-08 09:30:25'),
(2, 0, '2025-01-08 09:30:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `marId` int(11) NOT NULL,
  `marNombre` varchar(50) DEFAULT NULL,
  `marImg` varchar(255) NOT NULL,
  `marFechaRegis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`marId`, `marNombre`, `marImg`, `marFechaRegis`) VALUES
(4, 'Nike', '', '2025-01-07 07:39:35'),
(5, 'Adidas', '', '2025-01-07 07:39:43'),
(21, 'sssssssss', '', '2025-01-09 08:38:33'),
(23, 'saaaaaa', '', '2025-01-09 08:43:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oferta`
--

CREATE TABLE `oferta` (
  `ofeId` int(11) NOT NULL,
  `stoId` int(11) DEFAULT NULL,
  `ofePorcentaje` int(11) DEFAULT NULL,
  `ofeTiempo` datetime DEFAULT NULL,
  `ofeFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `oferta`
--

INSERT INTO `oferta` (`ofeId`, `stoId`, `ofePorcentaje`, `ofeTiempo`, `ofeFechaRegis`) VALUES
(1, 4, 10, '2025-01-30 17:41:37', '2025-01-11 17:42:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `portada`
--

CREATE TABLE `portada` (
  `porId` int(11) NOT NULL,
  `porTitulo` varchar(70) DEFAULT NULL,
  `porDescripcion` varchar(200) DEFAULT NULL,
  `porFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `portada`
--

INSERT INTO `portada` (`porId`, `porTitulo`, `porDescripcion`, `porFechaRegis`) VALUES
(1, 'Cms tienda de ropas para todos', 'Este es un sistio wew similar a un ecommerce diseñaado para una tienda de prendas de vestir.', '2024-12-30 20:08:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `proId` int(11) NOT NULL,
  `catId` int(11) DEFAULT NULL,
  `marId` int(11) DEFAULT NULL,
  `proNombre` varchar(50) NOT NULL,
  `proDescripcion` varchar(150) DEFAULT NULL,
  `proImg` varchar(255) DEFAULT NULL,
  `proImg2` varchar(255) DEFAULT NULL,
  `proPrecio` decimal(8,2) DEFAULT NULL,
  `proFechaRegistro` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`proId`, `catId`, `marId`, `proNombre`, `proDescripcion`, `proImg`, `proImg2`, `proPrecio`, `proFechaRegistro`) VALUES
(1, 12, 4, '', 'gorra nike ...', 'dddddddddddddd', 'sssssssssssss', '50.00', '2025-01-11 16:42:24'),
(2, 11, 5, '', 'sssssssssssssssssssssssssd', 'ddddddddddddddddddd', 'sssssssssssssssssssssssssssssss', '100.00', '2025-01-08 09:31:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenhas`
--

CREATE TABLE `resenhas` (
  `resId` int(11) NOT NULL,
  `venId` int(11) DEFAULT NULL,
  `resMensaje` varchar(255) DEFAULT NULL,
  `resFechaRegis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `resenhas`
--

INSERT INTO `resenhas` (`resId`, `venId`, `resMensaje`, `resFechaRegis`) VALUES
(3, NULL, 'ESTA ES OTRA PRUEBA DE RESEÑASSS2', '2025-01-08 09:35:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `stoId` int(11) NOT NULL,
  `proId` int(11) DEFAULT NULL,
  `estId` int(11) DEFAULT NULL,
  `colId` int(11) DEFAULT NULL,
  `talId` int(11) DEFAULT NULL,
  `stoCantidad` int(11) DEFAULT NULL,
  `stoFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`stoId`, `proId`, `estId`, `colId`, `talId`, `stoCantidad`, `stoFechaRegis`) VALUES
(4, 1, 1, 13, 2, 100, '2025-01-11 17:08:47'),
(8, 1, 1, 13, 1, 102, '2025-01-11 17:17:02'),
(9, 1, 2, 16, 2, 120, '2025-01-11 17:41:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talla`
--

CREATE TABLE `talla` (
  `talId` int(11) NOT NULL,
  `talNombre` varchar(30) DEFAULT NULL,
  `talFechaRegis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `talla`
--

INSERT INTO `talla` (`talId`, `talNombre`, `talFechaRegis`) VALUES
(1, 'dddd', '2025-01-08 09:32:46'),
(2, 'XL', '2025-01-08 09:32:46'),
(4, 'S', '2025-01-08 09:41:37'),
(6, 'aaa', '2025-01-09 08:49:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `admId` int(11) NOT NULL,
  `admUser` varchar(100) DEFAULT NULL,
  `admPassword` varchar(60) DEFAULT NULL,
  `admFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `carId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`admId`, `admUser`, `admPassword`, `admFechaRegis`, `carId`) VALUES
(3, 'admin', '$2y$10$XC4T3j7LdBJtZdrbMRqjq.BcYi6K1011PtNIXiY1NtoCjfpmJ3r6i', '2025-01-04 16:34:54', 2),
(4, 'pruebagerente', '$2y$10$eWM5d7YzzQIY.k9DDsG/kez9DUneGuqqHx19TixxrRdBpk6rcaVEm', '2024-12-30 19:51:33', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `venId` int(11) NOT NULL,
  `cliId` int(11) DEFAULT NULL,
  `venFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`venId`, `cliId`, `venFechaRegis`) VALUES
(3, 4, '2025-01-11 16:34:08');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`carId`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`catId`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cliId`);

--
-- Indices de la tabla `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`colId`);

--
-- Indices de la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`conId`);

--
-- Indices de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD PRIMARY KEY (`detVenId`),
  ADD KEY `venId` (`venId`),
  ADD KEY `stoId` (`stoId`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`estId`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`marId`);

--
-- Indices de la tabla `oferta`
--
ALTER TABLE `oferta`
  ADD PRIMARY KEY (`ofeId`),
  ADD KEY `stoId` (`stoId`);

--
-- Indices de la tabla `portada`
--
ALTER TABLE `portada`
  ADD PRIMARY KEY (`porId`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`proId`),
  ADD KEY `catId` (`catId`),
  ADD KEY `marId` (`marId`);

--
-- Indices de la tabla `resenhas`
--
ALTER TABLE `resenhas`
  ADD PRIMARY KEY (`resId`),
  ADD KEY `venId` (`venId`);

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stoId`),
  ADD KEY `proId` (`proId`),
  ADD KEY `estId` (`estId`),
  ADD KEY `colId` (`colId`),
  ADD KEY `talId` (`talId`);

--
-- Indices de la tabla `talla`
--
ALTER TABLE `talla`
  ADD PRIMARY KEY (`talId`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`admId`),
  ADD KEY `fk_admi_cargo` (`carId`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`venId`),
  ADD KEY `cliId` (`cliId`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `carId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `catId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `cliId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `color`
--
ALTER TABLE `color`
  MODIFY `colId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `contacto`
--
ALTER TABLE `contacto`
  MODIFY `conId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  MODIFY `detVenId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `estId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `marId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `oferta`
--
ALTER TABLE `oferta`
  MODIFY `ofeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `portada`
--
ALTER TABLE `portada`
  MODIFY `porId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `proId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `resenhas`
--
ALTER TABLE `resenhas`
  MODIFY `resId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `stoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `talla`
--
ALTER TABLE `talla`
  MODIFY `talId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `admId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `venId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD CONSTRAINT `detalleventa_ibfk_1` FOREIGN KEY (`venId`) REFERENCES `ventas` (`venId`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalleventa_ibfk_2` FOREIGN KEY (`stoId`) REFERENCES `stock` (`stoId`);

--
-- Filtros para la tabla `oferta`
--
ALTER TABLE `oferta`
  ADD CONSTRAINT `oferta_ibfk_1` FOREIGN KEY (`stoId`) REFERENCES `stock` (`stoId`) ON DELETE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`catId`) REFERENCES `categoria` (`catId`),
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`marId`) REFERENCES `marca` (`marId`);

--
-- Filtros para la tabla `resenhas`
--
ALTER TABLE `resenhas`
  ADD CONSTRAINT `resenhas_ibfk_1` FOREIGN KEY (`venId`) REFERENCES `ventas` (`venId`) ON DELETE CASCADE;

--
-- Filtros para la tabla `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`proId`) REFERENCES `producto` (`proId`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`estId`) REFERENCES `estado` (`estId`),
  ADD CONSTRAINT `stock_ibfk_3` FOREIGN KEY (`colId`) REFERENCES `color` (`colId`),
  ADD CONSTRAINT `stock_ibfk_4` FOREIGN KEY (`talId`) REFERENCES `talla` (`talId`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_admi_cargo` FOREIGN KEY (`carId`) REFERENCES `cargo` (`carId`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliId`) REFERENCES `cliente` (`cliId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
