-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-01-2025 a las 00:26:34
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
(28, 'Camisetas', 'Ropa casual y cómoda', 'Camisetas de varios colores y estilos', '678d280ba2450-camisetas.jpg', '2025-01-19 11:27:55'),
(29, 'Pantalones', 'Ropa formal y casual', 'Pantalones para diferentes ocasiones', '678d2bd64455d-various-pile-of-denim-pants-on-white-background-photo.jpg', '2025-01-19 11:44:06'),
(30, 'Gorras', 'Accesorios de moda', 'Gorras deportivas y casuales', '678d2bca0cea1-TEJOBRINDE_BONE_HIT-20005.png', '2025-01-19 11:43:54'),
(31, 'Zapatos', 'Calzado para todos', 'Zapatos formales y deportivos', '678d2bbe114c6-co-11134201-23020-k5a292g0vvnvb5.jpeg', '2025-01-19 11:43:42'),
(34, 'Sudaderas', 'Ropa de abrigo', 'Sudaderas para el frío', '678d2a97b993e-a5eff5171ad380f4428982469f408b62.jpg', '2025-01-19 11:38:47'),
(35, 'Chaquetas', 'Ropa de abrigo', 'Chaquetas de diferentes estilos', '678d7cd638e94-678d2a905ea82-abrigo.png', '2025-01-19 17:29:42'),
(36, 'Sombreros', 'Accesorios de moda', 'Sombreros para protegerse del sol', '678d2a0dabaff-816eGEp3yOL._AC_UY1000_DpWeblab_.jpg', '2025-01-19 11:36:29'),
(37, 'Bufandas', 'Accesorios de moda', 'Bufandas de varios colores y materiales', '678d2a0624703-conjunto-diez-bufandas-hermosamente-tejidas-varios-colores-texturas-perfectas-agregar-toque-calor-estilo-cualquier-traje_1151-156902.png', '2025-01-19 11:36:22'),
(39, 'Calcetines', 'Ropa cómoda', 'Calcetines de algodón y lana', '678d29fb7402c-610SPP5C3_PACK_WEB_1200x.webp', '2025-01-19 11:36:11'),
(40, 'Cinturones', 'Accesorios de moda', 'Cinturones de cuero', '678d29e865144-646850693141f560952da8da-cinturon-de-hombre-de-vestir-cinturones.jpg', '2025-01-19 11:35:52'),
(45, 'Trajes', 'Ropa formal', 'Trajes para ocasiones especiales', '678d28f7b2ab6-traje-hombre-corte-slim-fit-varios-colores-rack-pack.jpg', '2025-01-19 11:31:51'),
(46, 'Bañadores', 'Ropa de baño', 'Bañadores para la playa y piscina', '678d284d93640-35a29f02692b3d2ef70883aaadbeb693.jpg', '2025-01-19 11:29:01'),
(47, 'Zapatillas', 'Calzado cómodo', 'Zapatillas deportivas y casuales', '678d27f537d62-Zapatos Tumblr.jpeg', '2025-01-19 11:27:33'),
(48, 'Abrigos', 'Ropa de abrigo', 'Abrigos para el invierno', '678d27e9d5932-muchos-abrigos-estan-frente-ventana-que-hombres-miren_674594-21434.jpg', '2025-01-19 11:27:21');

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
(4, '14785236', 'Luis', 'Alfaro', 'Chirinos', 'miguel@gmail.com', '2025-01-13 14:19:29'),
(6, '47859623', 'Luis F', 'Juarez', 'Peña', 'luis@gmail.com', '2025-01-13 14:19:43'),
(7, '41256378', 'Marco', 'Mejia', 'Llamocca', 'marco@gmail.com', '2025-01-13 14:18:29');

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
(13, 'Negro', '#000000', '2025-01-11 15:20:05'),
(16, 'Azul', '#004cff', '2025-01-11 15:40:11'),
(18, 'Rojo', '#ff0000', '2025-01-13 14:20:27'),
(19, 'Plomo', '#b3b3b3', '2025-01-13 14:20:42'),
(20, 'Amarillo', '#fbff00', '2025-01-13 14:21:03'),
(21, 'Celeste', '#05f5e5', '2025-01-13 15:38:54'),
(22, 'Verde', '#1fee11', '2025-01-14 08:13:29'),
(23, 'Púrpura', '#800080', '2025-01-14 08:14:39'),
(24, 'Rosa', '#ffc0cb', '2025-01-14 08:14:57'),
(25, 'Marrón', '#a52a2a', '2025-01-14 08:15:16'),
(26, 'Blanco', '#ffffff', '2025-01-14 08:15:53'),
(27, 'Cian', '#00ffff', '2025-01-14 08:16:45'),
(28, 'Magenta', '#ff00ff', '2025-01-14 08:17:04'),
(29, 'Lima', '#00ff00', '2025-01-14 08:17:19'),
(30, 'Turquesa', '#40e0d0', '2025-01-14 08:17:36'),
(31, 'Oliva', '#808000', '2025-01-14 08:17:52'),
(32, 'Marino', '#000080', '2025-01-14 08:18:10'),
(33, 'Dorado', '#ffd700', '2025-01-14 08:18:24'),
(34, 'Plateado', '#c0c0c0', '2025-01-14 08:18:39'),
(35, 'Caramelo', '#d2691d', '2025-01-14 08:19:17'),
(37, 'Ocre', '#cc7722', '2025-01-14 08:20:54'),
(42, 'ssssssssss', '#efebeb', '2025-01-15 20:01:38'),
(43, 'ss', '#f71d1d', '2025-01-15 20:02:32'),
(45, 'ffff', '#7e6363', '2025-01-15 20:04:53'),
(48, 'sssssssss', '#973535', '2025-01-15 20:13:18'),
(49, 'fffffffff', '#d79393', '2025-01-15 20:13:43');

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
(4, 'NIKE', '678d1f032be60-nike.png', '2025-01-07 07:39:35'),
(5, 'ADIDAS', '678d1efb33cb8-adidas.png', '2025-01-07 07:39:43'),
(21, 'GUCCI', '678d1eec32ffb-gucci.png', '2025-01-09 08:38:33'),
(27, 'PUMA', '678d1f20a3a1d-png-clipart-puma-logo-icons-logos-emojis-iconic-brands-thumbnail.png', '2025-01-19 10:49:52'),
(28, 'LEVIS', '678d1f7d18a19-678be59557d06-levis.png', '2025-01-19 10:51:25'),
(29, 'ZARA', '678d1f967a113-678be58f79d18-zara.png', '2025-01-19 10:51:50'),
(30, 'BILLABONG', '678d1fad16e8c-678be580ae50d-billabong.jpg', '2025-01-19 10:52:13'),
(31, 'BOSS', '678d21033fc7e-678be5a72b369-Hugo B.jpg', '2025-01-19 10:56:18'),
(32, 'CHANEL', '678d2140077ac-chanel.png', '2025-01-19 10:58:56'),
(33, 'CALVIN KLEIN', '678d217004a42-CK_Calvin_Klein_logo.png', '2025-01-19 10:59:44'),
(34, 'VERSACE', '678d21819edb7-12_mejores_logos_marcas_moda_versace.webp', '2025-01-19 11:00:01'),
(35, 'LACOSTE', '678d21a29b3eb-Mw0EhvOZSpXevAp0FSjfzqwa1w.png', '2025-01-19 11:00:34'),
(36, 'PRADA', '678d21b76587b-c5b13492bfd1e59def39990d5bde215c.jpg', '2025-01-19 11:00:55'),
(37, 'LOUIS VUITTON', '678d21e48b97f-Louis-Vuitton-logo.png', '2025-01-19 11:01:40'),
(38, 'UNDER ARMOUR', '678d21fe06e66-png-clipart-under-armour-t-shirt-clothing-logo-brand-t-shirt-logo-monochrome.png', '2025-01-19 11:02:06'),
(39, 'ARMANI JEANS', '678d222cf0014-735719b7a4d9a3e561ed706a649618f9.jpg', '2025-01-19 11:02:52'),
(40, 'DIOR', '678d22434c31a-01_mejores_logos_marcas_moda_dior.webp', '2025-01-19 11:03:15'),
(41, 'BULGARI', '678d226a4aea7-AqMvlAVwd4FU1tMSnaXUXlLl1M.png', '2025-01-19 11:03:54'),
(42, 'RUSH MODE', '678d228992985-component.webp', '2025-01-19 11:04:25'),
(43, 'CHAMPION', '678d229b4feb5-champion-brand-clothes-logo-symbol-with-name-design-sportwear-fashion-illustration-free-vector.jpg', '2025-01-19 11:04:43'),
(44, 'DOLCE GABANA', '678d22c483369-Dolce-Gabbana-Logo.png', '2025-01-19 11:05:24');

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
(1, 28, 4, 'Camiseta Deportiva', 'Camiseta de fútbol Nike', 'camiseta_deportiva.jpg', 'camiseta_deportiva2.jpg', '25.99', '2025-01-19 11:27:55'),
(2, 28, 4, 'Camiseta Básica', 'Camiseta de algodón de varios colores', 'camiseta_basica.jpg', 'camiseta_basica2.jpg', '15.99', '2025-01-19 11:30:00'),
(3, 28, 4, 'Camiseta Estampada', 'Camiseta con estampado gráfico', '678d85a83661b-5.jpg', '678d85a836a3e-6.jpg', '19.99', '2025-01-19 18:07:20'),
(4, 29, 5, 'Pantalón Jeans', 'Pantalón de mezclilla azul', '678d850ca29d4-1pa.webp', '678d850ca2c5d-2.jpeg', '25.50', '2025-01-19 18:04:44'),
(5, 29, 5, 'Pantalón Chino', 'Pantalón chino para ocasiones casuales', '678d84280200e-3pa.jpg', '678d842802ec6-4pa.jpg', '28.75', '2025-01-19 18:00:56'),
(6, 30, 21, 'Gorra Deportiva', 'Gorra con logo deportivo', '678d8402bb281-3g.jpg', '678d8402bcf74-4g.jpeg', '12.99', '2025-01-19 18:00:18'),
(7, 30, 21, 'Gorra Casual', 'Gorra de algodón, estilo urbano', '678d83d111c5d-1g.jpg', '678d83d111fb7-2g.jpg', '14.50', '2025-01-19 17:59:29'),
(8, 31, 27, 'Zapatos Deportivos', 'Zapatos deportivos para entrenamiento', '678d84db8f599-zapatillas-de-hombre-seul.jpg', '678d84db8f8b8-IMG_0934.jpg.webp', '45.00', '2025-01-19 18:03:55'),
(9, 31, 27, 'Zapatos Formales', 'Zapatos formales de cuero', '678d84bc7a3bf-19.webp', '678d84bc7a6fd-20.jpg', '60.00', '2025-01-19 18:03:24'),
(10, 34, 28, 'Sudadera con Capucha', 'Sudadera de algodón con capucha', '678d8472ccc61-21.webp', '678d8472ccf59-22.jpg', '35.00', '2025-01-19 18:02:10'),
(11, 34, 28, 'Sudadera Deportiva', 'Sudadera ligera para entrenamiento', '678d8450c3824-1pol.jpg', '678d8450c3acb-2pol.jpeg', '40.00', '2025-01-19 18:01:36'),
(12, 35, 29, 'Chaqueta de Invierno', 'Chaqueta de abrigo para invierno', 'chaqueta_invierno.jpg', 'chaqueta_invierno2.jpg', '80.00', '2025-01-19 17:30:00'),
(13, 35, 29, 'Chaqueta de Cuero', 'Chaqueta de cuero para estilo casual', 'chaqueta_cuero.jpg', 'chaqueta_cuero2.jpg', '120.00', '2025-01-19 17:31:00'),
(14, 36, 30, 'Sombrero de Sol', 'Sombrero para protegerse del sol', 'sombrero_sol.jpg', 'sombrero_sol2.jpg', '18.99', '2025-01-19 11:37:00'),
(15, 36, 30, 'Sombrero Estilo Fedora', 'Sombrero Fedora clásico', 'sombrero_fedora.jpg', 'sombrero_fedora2.jpg', '22.50', '2025-01-19 11:38:00'),
(16, 37, 31, 'Bufanda de Lana', 'Bufanda de lana para invierno', 'bufanda_lana.jpg', 'bufanda_lana2.jpg', '14.00', '2025-01-19 11:37:00'),
(17, 37, 31, 'Bufanda de Seda', 'Bufanda de seda para clima templado', 'bufanda_seda.jpg', 'bufanda_seda2.jpg', '25.00', '2025-01-19 11:38:00'),
(18, 39, 32, 'Calcetines de Algodón', 'Pack de 3 calcetines de algodón', 'calcetines_algodon.jpg', 'calcetines_algodon2.jpg', '9.99', '2025-01-19 11:37:00'),
(19, 39, 32, 'Calcetines de Lana', 'Pack de 2 calcetines de lana', 'calcetines_lana.jpg', 'calcetines_lana2.jpg', '12.50', '2025-01-19 11:38:00'),
(20, 40, 33, 'Cinturón de Cuero', 'Cinturón de cuero para traje', 'cinturon_cuero.jpg', 'cinturon_cuero2.jpg', '20.00', '2025-01-19 11:36:00'),
(21, 40, 33, 'Cinturón Deportivo', 'Cinturón deportivo para jeans', 'cinturon_deportivo.jpg', 'cinturon_deportivo2.jpg', '15.00', '2025-01-19 11:37:00'),
(22, 45, 34, 'Traje Formal', 'Traje de corte slim para eventos', 'traje_formal.jpg', 'traje_formal2.jpg', '120.00', '2025-01-19 11:32:00'),
(23, 45, 34, 'Traje Clásico', 'Traje clásico de dos piezas', 'traje_clasico.jpg', 'traje_clasico2.jpg', '150.00', '2025-01-19 11:33:00'),
(24, 46, 35, 'Bañador de Hombre', 'Bañador para piscina', 'banador_hombre.jpg', 'banador_hombre2.jpg', '25.00', '2025-01-19 11:30:00'),
(26, 47, 36, 'Zapatillas Deportivas', 'Zapatillas para correr', '678d84f654e41-12.jpeg', '678d84f655182-11.webp', '50.00', '2025-01-19 18:04:22'),
(27, 47, 36, 'Zapatillas Casuales', 'Zapatillas para uso diario', '678d8378ceb06-1.jpg', '678d8378cf9cf-2.jpg', '45.00', '2025-01-19 17:58:00'),
(28, 48, 37, 'Abrigo de Invierno', 'Abrigo largo para el frío extremo', 'abrigo_invierno.jpg', 'abrigo_invierno2.jpg', '150.00', '2025-01-19 11:28:00'),
(29, 48, 37, 'Abrigo Corto', 'Abrigo corto para clima templado', 'abrigo_corto.jpg', 'abrigo_corto2.jpg', '100.00', '2025-01-19 11:29:00'),
(30, 34, 5, 'Sudadera negra con diseño', 'Este es una Sudadera negra con diseño', '678d8630197ed-11pol.jpg', '678d8630197f5-12pol.jpeg', '66.00', '2025-01-19 18:09:36'),
(31, 34, 4, 'Sudadera blanca con deportiva', 'Sudadera blanca con deportiva de la seleccion peruana', '678d86a3701ed-23.webp', '678d86a3701f7-24.webp', '70.00', '2025-01-19 18:11:31'),
(32, 30, 27, 'Gorra blanca urbana', 'Esta es una Gorra blanca urbana', '678d8715ababb-7g.jpg', '678d8715abac4-8g.jpg', '50.00', '2025-01-19 18:13:25'),
(33, 30, 4, 'Gorra urbana plana', 'Esta es una gorra urbana plana', '678d874a21a7f-5g.webp', '678d874a21a88-6g.jpg', '40.00', '2025-01-19 18:14:18'),
(34, 34, 21, 'Sudadera azul con estampado', 'Esta es una Sudadera azul con estampado', '678d8789b5ef3-7pol.jpeg', '678d8789b5efc-8pol.jpg', '70.00', '2025-01-19 18:15:21'),
(35, 35, 30, 'Chaqueta blanca', 'Esta es una Chaqueta blanca', '678d87db1e501-30.webp', '678d87db1e50b-29.webp', '43.00', '2025-01-19 18:16:43'),
(36, 34, 30, 'Sudadera negra con capucha urbana', 'Esta es un Sudadera negra con capucha urbana', '678d88114b51e-28.jpeg', '678d88114b529-27.jpg', '55.00', '2025-01-19 18:17:37'),
(37, 34, 33, 'Sudadera con capucha urbana color piel', 'Esta es una Sudadera con capucha urbana color piel', '678d88424e82b-4pol.jpg', '678d88424e834-5pol.jpg', '66.00', '2025-01-19 18:18:26'),
(38, 47, 4, 'Zapatillas deportivas color amarillo', 'Esta es una Zapatillas deportivas color amarillo/negro', '678d887a5d621-4.jpg.webp', '678d887a5d629-3.jpg.webp', '150.00', '2025-01-19 18:19:22'),
(39, 47, 34, 'Zapatilla urbana color gris', 'Esta es una Zapatilla urbana color gris', '678d88a516b2a-9.webp', '678d88a516b32-10.webp', '200.00', '2025-01-19 18:20:05'),
(40, 28, 37, 'Camiseta basica color plomo', 'Esta es una Camiseta basica color plomo', '678d88e4236f4-8.jpeg', '678d88e423701-7.jpg', '30.00', '2025-01-19 18:21:08'),
(41, 29, 36, 'Pantalon stylo antigua', 'Este es un Pantalon stylo antigua', '678d891a7cfdb-9pa.jpg', '678d891a7cfe2-10pa.jpg', '88.00', '2025-01-19 18:22:02');

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
(7, 4, 'Este es una retroalimentacion para la seccion reseñas', '2025-01-13 14:51:27'),
(8, 3, 'Este es una retroalimentacion para la seccion reseñas 2', '2025-01-13 14:51:39'),
(9, 5, 'Este es una retroalimentacion para la seccion reseñas 3', '2025-01-13 14:51:51');

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

--
-- Volcado de datos para la tabla `talla`
--

INSERT INTO `talla` (`talId`, `talNombre`, `talFechaRegis`) VALUES
(1, 'M', '2025-01-08 09:32:46'),
(2, 'XL', '2025-01-08 09:32:46'),
(11, 'S', '2025-01-13 14:21:33'),
(12, 'X', '2025-01-13 14:21:37'),
(13, 'L', '2025-01-13 14:22:03'),
(14, 'XS', '2025-01-13 14:22:19');

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
(3, 4, '2025-01-11 16:34:08'),
(4, 7, '2025-01-13 14:49:36'),
(5, 6, '2025-01-13 14:49:46');

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
  MODIFY `catId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `cliId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `color`
--
ALTER TABLE `color`
  MODIFY `colId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

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
  MODIFY `marId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

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
  MODIFY `proId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `resenhas`
--
ALTER TABLE `resenhas`
  MODIFY `resId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `stoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `talla`
--
ALTER TABLE `talla`
  MODIFY `talId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `admId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `venId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
