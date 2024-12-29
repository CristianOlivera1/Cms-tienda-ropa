-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-12-2024 a las 21:41:19
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
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `admId` int(11) NOT NULL,
  `admUser` varchar(100) DEFAULT NULL,
  `admPass` varchar(60) DEFAULT NULL,
  `admFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`admId`, `admUser`, `admPass`, `admFechaRegis`) VALUES
(1, 'admin', '$2y$10$eWM5d7YzzQIY.k9DDsG/kez9DUneGuqqHx19TixxrRdBpk6rcaVEm', '2024-12-28 15:40:50');

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `cliId` int(11) NOT NULL,
  `cliNombre` varchar(100) DEFAULT NULL,
  `cliApellidoPaterno` varchar(100) DEFAULT NULL,
  `cliApellidoMaterno` varchar(100) DEFAULT NULL,
  `cliCorreo` varchar(150) DEFAULT NULL,
  `cliFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `color`
--

CREATE TABLE `color` (
  `colId` int(11) NOT NULL,
  `colNombre` varchar(30) DEFAULT NULL,
  `colFechaRegis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `marId` int(11) NOT NULL,
  `marNombre` varchar(50) DEFAULT NULL,
  `marFechaRegis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `proId` int(11) NOT NULL,
  `catId` int(11) DEFAULT NULL,
  `marId` int(11) DEFAULT NULL,
  `proDes` varchar(150) DEFAULT NULL,
  `proImg` varchar(255) DEFAULT NULL,
  `proImg2` varchar(255) DEFAULT NULL,
  `proPrecio` decimal(8,2) DEFAULT NULL,
  `proFechaRegistro` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talla`
--

CREATE TABLE `talla` (
  `talId` int(11) NOT NULL,
  `talNombre` varchar(30) DEFAULT NULL,
  `talFechaRegis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `venId` int(11) NOT NULL,
  `stoId` int(11) DEFAULT NULL,
  `cliId` int(11) DEFAULT NULL,
  `venFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`admId`);

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
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`venId`),
  ADD KEY `stoId` (`stoId`),
  ADD KEY `cliId` (`cliId`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `admId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `catId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `cliId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `color`
--
ALTER TABLE `color`
  MODIFY `colId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contacto`
--
ALTER TABLE `contacto`
  MODIFY `conId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  MODIFY `detVenId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `estId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `marId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `oferta`
--
ALTER TABLE `oferta`
  MODIFY `ofeId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `portada`
--
ALTER TABLE `portada`
  MODIFY `porId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `proId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `resenhas`
--
ALTER TABLE `resenhas`
  MODIFY `resId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `stoId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `talla`
--
ALTER TABLE `talla`
  MODIFY `talId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `venId` int(11) NOT NULL AUTO_INCREMENT;

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
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`stoId`) REFERENCES `stock` (`stoId`),
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliId`) REFERENCES `cliente` (`cliId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
