-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-02-2025 a las 01:48:22
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
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `actId` int(11) NOT NULL,
  `usuarioId` int(11) NOT NULL,
  `nombreTabla` varchar(30) NOT NULL,
  `actividad` varchar(30) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`actId`, `usuarioId`, `nombreTabla`, `actividad`, `descripcion`, `fecha`) VALUES
(13, 4, '0', 'Update', 'Actualizó los datos de contacto: Teléfono - 954306631, Email - 221181@unamba.com.', '2025-02-08 18:54:41'),
(14, 4, '0', 'Update', 'Actualizó la categoría: Nombre - dddddddddda, Descripción - sssssssssssssa, Detalle - sssasssssssssssa.', '2025-02-08 18:56:18'),
(23, 4, 'Categoria', 'Insert', 'Inserto la categoría: Nombre - aaaaaaaaaa, Descripción - adddddddddddddddd, Detalle - ssssssssssssssssss.', '2025-02-09 07:19:28'),
(24, 4, 'Categoria', 'Update', 'Actualizó la categoría: Nombre - aaaaaaaaaa2, Descripción - adddddddddddddddd, Detalle - ssssssssssssssssss.', '2025-02-09 07:19:45'),
(25, 4, 'Categoria', 'Delete', 'Eliminó la categoría con ID: 76.', '2025-02-09 07:19:53'),
(26, 4, 'Categoria', 'Insert', 'Inserto la categoría: Nombre - aaaaaa, Descripción - ssssss, Detalle - dddddd.', '2025-02-09 07:21:27'),
(27, 4, 'Categoria', 'Insert', 'Inserto la categoría: Nombre - aaaaaaa, Descripción - aaaaaaaa, Detalle - aaaaaaaaaa.', '2025-02-09 07:23:16'),
(28, 4, 'Categoria', 'Delete', 'Eliminó la categoría con ID: 78.', '2025-02-09 07:23:29'),
(29, 4, 'Categoria', 'Insert', 'Inserto la categoría: Nombre - ssssss, Descripción - ddddd, Detalle - ssssss.', '2025-02-09 07:23:39'),
(31, 4, 'Categoria', 'Insert', 'Inserto la categoría: Nombre - ssssssssssss, Descripción - ssssssssss, Detalle - sssssssssssssss.', '2025-02-09 07:32:28'),
(32, 4, 'Categoria', 'Update', 'Actualizó la categoría: Nombre - ssssssssssss, Descripción - aaaaaaaaaa, Detalle - sssssssssssssss.', '2025-02-09 07:32:44'),
(33, 4, 'Categoria', 'Insert', 'Inserto la categoría: Nombre - ssssssssssssss, Descripción - sssssss, Detalle - sssssssssss.', '2025-02-09 07:34:08'),
(34, 4, 'Categoria', 'Delete', 'Eliminó la categoría con ID: 81.', '2025-02-09 07:34:11'),
(35, 4, 'Categoria', 'Insert', 'Inserto la categoría: Nombre - ssssssss, Descripción - ssssssssss, Detalle - ssssssssss.', '2025-02-09 07:34:55'),
(36, 4, 'Categoria', 'Delete', 'Eliminó la categoría con ID: 82.', '2025-02-09 07:34:58'),
(37, 4, 'Categoria', 'Insert', 'Inserto la categoría: Nombre - sssssssss, Descripción - ssssssssss, Detalle - sssssssssss.', '2025-02-09 07:36:18'),
(38, 4, 'Categoria', 'Delete', 'Eliminó la categoría con ID: 83.', '2025-02-09 07:36:21'),
(39, 4, 'Categoria', 'Insert', 'Inserto la categoría: Nombre - ssssssss, Descripción - dddddddd, Detalle - ddssssssssssssssssssss.', '2025-02-09 07:44:52'),
(40, 4, 'Categoria', 'Delete', 'Eliminó la categoría con ID: 84.', '2025-02-09 07:46:09'),
(41, 4, 'Color', 'Update', 'Actualizó el color: Nombre - ddddddddddddd2, Código Hexadecimal - #5b2020.', '2025-02-09 07:46:18'),
(42, 4, 'Color', 'Delete', 'Eliminó el color con ID: 53.', '2025-02-09 07:46:26'),
(43, 4, 'Color', 'Delete', 'Eliminó el color con ID: 54.', '2025-02-09 07:46:33'),
(44, 4, 'Color', 'Insert', 'Inserto el color: Nombre - ssssssssssssssss, Código Hexadecimal - #ce8383.', '2025-02-09 07:46:37'),
(45, 4, 'Color', 'Delete', 'Eliminó el color con ID: 55.', '2025-02-09 07:46:45'),
(46, 4, 'Marca', 'Insert', 'Inserto la marca: Nombre - AAAAAAAAAAAAA', '2025-02-09 07:52:25'),
(47, 4, 'Marca', 'Update', 'Actualizó la marca: Nombre - AAAAAAAAAAAAA2', '2025-02-09 07:52:35'),
(48, 4, 'Marca', 'Delete', 'Eliminó la marca con ID: 45.', '2025-02-09 07:52:42'),
(49, 4, 'Talla', 'Insert', 'Inserto la talla: Nombre - aaaaaaaaaaaa.', '2025-02-09 07:56:10'),
(50, 4, 'Talla', 'Update', 'Actualizó la talla: Nombre - aaaaaaaaaaaa2.', '2025-02-09 07:56:18'),
(51, 4, 'Talla', 'Delete', 'Eliminó la talla con ID: 27.', '2025-02-09 07:56:25'),
(52, 4, 'Producto', 'Insert', 'Inserto el producto: Nombre - aaaaaaaaa, Categoria - 28, Precio - 11.', '2025-02-09 08:01:48'),
(53, 4, 'Producto', 'Update', 'Actualizó el producto: Nombre - aaaaaaaaa, Descripción - aaaaaaaaaaaaaaa2, Precio - 11, Categoría ID - 28, Marca ID - 5.', '2025-02-09 08:02:01'),
(54, 4, 'Producto', 'Delete', 'Eliminó el producto con ID: 63.', '2025-02-09 08:02:40'),
(55, 4, 'Stock', 'Insert', 'Registró el stock: Producto ID - 19, Estado ID - 1, Color ID - 27, Talla ID - 12, Cantidad - 10.', '2025-02-09 08:05:42'),
(56, 4, 'Stock', 'Update', 'Actualizó el stock: Producto ID - 19, Estado ID - 1, Color ID - 27, Talla ID - 12, Cantidad - 11.', '2025-02-09 08:06:01'),
(57, 4, 'Stock', 'Delete', 'Eliminó el stock con ID: 113.', '2025-02-09 08:06:14'),
(58, 4, 'Usuario', 'Insert', 'Inserto el usuario: Nombre de Usuario - aaaaaaaaaaa, Cargo ID - 2.', '2025-02-09 08:09:02'),
(60, 4, 'Usuario', 'Insert', 'Inserto el usuario: Nombre de Usuario - aaaaaaaa, Cargo ID - 2.', '2025-02-09 08:10:19'),
(61, 4, 'Usuario', 'Delete', 'Eliminó el usuario con ID: 10.', '2025-02-09 08:10:23'),
(62, 3, 'Talla', 'Insert', 'Inserto la talla: Nombre - ssssssssss.', '2025-02-09 08:37:25'),
(63, 4, 'Producto', 'Insert', 'Inserto el producto: Nombre - zapatos con stylo, Categoria - 31, Precio - 50.', '2025-02-12 14:10:27'),
(64, 4, 'Stock', 'Insert', 'Registró el stock: Producto ID - 64, Estado ID - 1, Color ID - 13, Talla ID - 15, Cantidad - 10.', '2025-02-12 14:11:22');

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
  `cliFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cliFechaNacimiento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`cliId`, `cliDni`, `cliNombre`, `cliApellidoPaterno`, `cliApellidoMaterno`, `cliCorreo`, `cliFechaRegis`, `cliFechaNacimiento`) VALUES
(4, '14785236', 'Luis', 'Alfaro', 'Chirinos', 'miguel@gmail.com', '2025-01-13 14:19:29', NULL),
(6, '47859623', 'Luis F', 'Juarez', 'Peña', 'luis@gmail.com', '2025-01-13 14:19:43', NULL),
(7, '41256378', 'Marco', 'Mejia', 'Llamocca', 'marco@gmail.com', '2025-01-13 14:18:29', NULL),
(8, '63289575', 'Cristian', 'Olivera', 'Chavez', 'oliverachavezcristian@gmail.com', '2025-01-31 14:58:34', '2004-01-31');

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
(37, 'Ocre', '#cc7722', '2025-01-14 08:20:54');

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
(1, '954306631', '221181@unamba.com', '2025-02-08 18:45:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleventa`
--

CREATE TABLE `detalleventa` (
  `detVenId` int(11) NOT NULL,
  `venId` varchar(4) DEFAULT NULL,
  `stoId` int(11) DEFAULT NULL,
  `detVenCantidad` int(11) DEFAULT NULL,
  `detVenPrecio` decimal(8,2) DEFAULT NULL,
  `detVenFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalleventa`
--

INSERT INTO `detalleventa` (`detVenId`, `venId`, `stoId`, `detVenCantidad`, `detVenPrecio`, `detVenFechaRegis`) VALUES
(15, 'A001', 43, 1, '100.00', '2025-02-11 20:15:02'),
(16, 'A002', 43, 3, '150.00', '2025-02-11 20:23:40'),
(41, 'A001', 43, 2, '150.00', '2025-02-12 09:05:08'),
(42, 'A005', 44, 2, '150.00', '2025-02-12 09:06:43'),
(46, 'A009', 71, 1, '35.00', '2025-02-12 12:19:13'),
(47, 'A010', 54, 4, '11.00', '2025-02-12 12:28:56'),
(48, 'A012', 111, 1, '66.00', '2025-02-12 12:35:32'),
(49, 'A013', 107, 1, '66.00', '2025-02-12 12:37:09'),
(50, 'A013', 81, 1, '150.00', '2025-02-12 12:37:09'),
(51, 'A013', 43, 1, '25.00', '2025-02-12 12:37:09'),
(52, 'A013', 57, 2, '25.00', '2025-02-12 12:37:09'),
(53, 'A014', 104, 2, '55.00', '2025-02-12 12:38:58'),
(54, 'A015', 111, 1, '66.00', '2025-02-12 12:42:45'),
(55, 'A016', 111, 1, '66.00', '2025-02-12 14:13:20'),
(56, 'A016', 58, 1, '12.00', '2025-02-12 14:13:20'),
(57, 'A016', 112, 2, '44.00', '2025-02-12 14:13:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `estId` int(11) NOT NULL,
  `estDisponible` varchar(20) DEFAULT NULL,
  `estFechaRegis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`estId`, `estDisponible`, `estFechaRegis`) VALUES
(1, 'Disponible', '2025-01-08 09:30:25'),
(2, 'No disponible', '2025-01-08 09:30:25'),
(3, 'En oferta', '2025-01-28 11:32:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadoventa`
--

CREATE TABLE `estadoventa` (
  `estVenId` int(11) NOT NULL,
  `estVenNombre` varchar(50) NOT NULL,
  `estVenDescripcion` varchar(255) DEFAULT NULL,
  `estVenFechaRegis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estadoventa`
--

INSERT INTO `estadoventa` (`estVenId`, `estVenNombre`, `estVenDescripcion`, `estVenFechaRegis`) VALUES
(1, 'Completada', 'La venta ha sido creada pero no se ha procesado aún.', '2025-02-09 06:23:02'),
(2, 'Pendiente', 'La venta ha sido procesada exitosamente y los productos han sido enviados.', '2025-02-09 06:23:02'),
(3, 'Cancelada', 'La venta ha sido cancelada por el cliente o el sistema.', '2025-02-09 06:23:02');

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

--
-- Volcado de datos para la tabla `oferta`
--

INSERT INTO `oferta` (`ofeId`, `stoId`, `ofePorcentaje`, `ofeTiempo`, `ofeFechaRegis`) VALUES
(29, 56, 50, '2025-02-22 15:14:00', '2025-02-08 15:14:27'),
(30, 54, 30, '2025-02-23 15:14:00', '2025-02-08 15:14:51'),
(31, 44, 20, '2025-02-12 15:15:00', '2025-02-08 15:15:22'),
(33, 42, 10, '2025-02-28 12:38:47', '2025-02-11 12:39:10');

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
(1, 'Vistiendo a Todos: Moda para Cada Estilo', 'Descubre moda para todos los gustos en nuestra plataforma de ecommerce, con ropa de calidad y estilo para todas las edades y ocasiones.', '2025-02-08 18:08:16');

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
(1, 28, 4, 'Camiseta Deportiva', 'Camiseta de fútbol Nike', '678e3df0327b6-d2bd8030-73fc-4f9b-bb51-89bb0c1e8c19.webp', '678e3df033f83-2edc10b5-a98f-4fcc-a4ed-73621f952ff4.webp', '25.99', '2025-01-20 07:13:36'),
(2, 28, 4, 'Camiseta Básica', 'Camiseta de algodón de varios colores', '679652b78d569-ce0600a4-1827-4d76-8995-bbc0b4e20de6.webp', '679652b78ee0f-88894263-053f-445f-9e26-4f5e2a89bc09.webp', '15.99', '2025-01-26 10:20:23'),
(3, 28, 4, 'Camiseta Estampada', 'Camiseta con estampado gráfico', '678d85a83661b-5.jpg', '678d85a836a3e-6.jpg', '19.99', '2025-01-19 18:07:20'),
(4, 29, 5, 'Pantalón Jeans', 'Pantalón de mezclilla azul', '678d850ca29d4-1pa.webp', '678d850ca2c5d-2.jpeg', '25.50', '2025-01-19 18:04:44'),
(5, 29, 5, 'Pantalón Chino', 'Algodón Caqui para Hombre de Maden, Corte Recto Casual de Cintura Alta, Color Sólido, Largo Regular, Cierre de Botón, para Salidas Casuales de Adultos', '679b885a661a2-0a9a6fcb-4450-4a48-8b76-f77ed60ea1db.webp', '679b885a6913d-10339b8b-d653-401d-8510-f0f679b710f5.webp', '28.75', '2025-01-30 09:10:34'),
(6, 30, 21, 'Gorra Deportiva', 'Gorra con logo deportivo', '678d8402bb281-3g.jpg', '678d8402bcf74-4g.jpeg', '12.99', '2025-01-19 18:00:18'),
(7, 30, 21, 'Gorra Casual', 'Gorra de algodón, estilo urbano', '678d83d111c5d-1g.jpg', '678d83d111fb7-2g.jpg', '14.50', '2025-01-19 17:59:29'),
(8, 31, 27, 'Zapatos Deportivos', 'Zapatos deportivos para entrenamiento', '678d84db8f599-zapatillas-de-hombre-seul.jpg', '678d84db8f8b8-IMG_0934.jpg.webp', '45.00', '2025-01-19 18:03:55'),
(9, 31, 27, 'Zapatos Formales', 'Zapatos formales de cuero', '678d84bc7a3bf-19.webp', '678d84bc7a6fd-20.jpg', '60.00', '2025-01-19 18:03:24'),
(10, 34, 28, 'Sudadera con Capucha', 'Sudadera de algodón con capucha', '678d8472ccc61-21.webp', '678d8472ccf59-22.jpg', '35.00', '2025-01-19 18:02:10'),
(11, 34, 28, 'Sudadera Deportiva', 'Sudadera ligera para entrenamiento', '678d8450c3824-1pol.jpg', '678d8450c3acb-2pol.jpeg', '40.00', '2025-01-19 18:01:36'),
(12, 35, 29, 'Chaqueta de Invierno', 'Chaqueta de abrigo para invierno', '679655de13ba7-08434bbf-6488-455b-aee5-89600c49871e.webp', '679655de1476f-24cbcf2d-bf33-4dc9-9d28-9c64c52e8f97.webp', '80.00', '2025-01-26 10:33:50'),
(13, 35, 29, 'Chaqueta de Cuero', 'Chaqueta de cuero para estilo casual', '679655b433fc6-8f0140a36de3f7d8a7846ad23e180b87.webp', '679655b43436a-6740a86340503d403f74df996677797d.webp', '120.00', '2025-01-26 10:33:08'),
(14, 36, 30, 'Sombrero de Sol', 'Sombrero para protegerse del sol', '6796558e3c87c-393fde27-b633-4ff1-8b25-df93124dd82c.webp', '6796558e3fbef-a511a69d-583a-40e9-a285-4fc33fe2fadc.webp', '18.99', '2025-01-26 10:32:30'),
(15, 36, 30, 'Sombrero Estilo Fedora', 'Sombrero Fedora clásico', '679655680d27d-7a9503e8-0fec-4abd-b487-4ba6604d8812.webp', '679655680e190-dbcf0b10-9888-4301-ad3e-bd0f7836967c.webp', '22.50', '2025-01-26 10:31:52'),
(16, 37, 31, 'Bufanda de Lana', 'Bufanda de lana para invierno', '6796554966142-63f48813-c17d-4557-bac8-4f1ebb1a3633.webp', '679655496654c-90650e41-f579-4d53-ac6c-590a03409bae.webp', '14.00', '2025-01-26 10:31:21'),
(17, 37, 31, 'Bufanda de Seda', 'Bufanda de seda para clima templado', '6796551a2f501-7c9e63b309de4d3ca0ab5a632f22cc78-goods.webp', '6796551a30655-410e48a863da4449adf5a2a9be65ba25-goods.webp', '25.00', '2025-01-26 10:30:34'),
(18, 39, 32, 'Calcetines de Algodón', 'Pack de 3 calcetines de algodón', '679654e274a2a-bb49a0ca-8a77-49cb-8a1e-7ece37b728d4.webp', '679654e27556c-fa712fbc-9b3f-454f-bf9f-1977f4fb6852.webp', '9.99', '2025-01-26 10:29:38'),
(19, 39, 32, 'Calcetines de Lana', 'Pack de 2 calcetines de lana', '679654bc27898-a53b821dd583e0595f0e67b16a0de545_1722504425770.webp', '679654bc28503-1722499352987-0fae2d93e17f4898a159138599d4d290-goods.webp', '12.50', '2025-01-26 10:29:00'),
(20, 40, 33, 'Cinturón de Cuero', 'Cinturón de cuero para traje', '6796549603cf6-ecaa5f7d-6c84-45f7-b492-2d0a37dc62af.webp', '67965496040be-7c45c39b43b23b5fccb33fd797da5a68.webp', '20.00', '2025-01-26 10:28:22'),
(21, 40, 33, 'Cinturón Deportivo', 'Cinturón deportivo para jeans', '67965457268b3-91206d5a-b985-4be0-a5ec-537d1486aa56_1800x1800.webp', '6796545729056-63d6ab35f69f375649691a8988e7b573.webp', '15.00', '2025-01-26 10:27:19'),
(22, 45, 34, 'Traje Formal', 'Traje de corte slim para eventos', '679654278cafb-e15715d3-0070-44ce-9570-4fb9a0b1dc18.webp', '679654278cdcf-a35831c2-5df2-43ae-a2db-a58c8407fd56.webp', '120.00', '2025-01-26 10:26:31'),
(23, 45, 34, 'Traje Clásico', 'Traje clásico de dos piezas', '679653e2f30ee-f646711e-7277-4652-a94c-cc346914f564.webp', '679653e2f400b-60d9c8a2-c199-4b78-9460-845b5ab3b0b8.webp', '150.00', '2025-01-26 10:25:23'),
(24, 46, 35, 'Bañador de Hombre', 'Bañador para piscina', '679653b7a5111-c613217b-1f59-4fad-a5ec-babc5276264a.webp', '679653b7aad92-a90f00ed-8439-40ff-860e-a7e43e2fdce6.webp', '25.00', '2025-01-26 10:24:39'),
(26, 47, 36, 'Zapatillas Deportivas', 'Zapatillas para correr', '678d84f654e41-12.jpeg', '678d84f655182-11.webp', '50.00', '2025-01-19 18:04:22'),
(27, 47, 36, 'Zapatillas Casuales', 'Zapatillas para uso diario', '678d8378ceb06-1.jpg', '678d8378cf9cf-2.jpg', '45.00', '2025-01-19 17:58:00'),
(28, 48, 37, 'Abrigo de Invierno', 'Abrigo largo para el frío extremo', '6796536ac7ac9-7e1443e6-bf86-40dc-8eab-c336b35f43cc.webp', '6796536ac7e4f-4b5003d4-564c-40fc-8026-9c7e2ce54457.webp', '150.00', '2025-01-26 10:23:22'),
(29, 48, 37, 'Abrigo Corto', 'Abrigo corto para clima templado', '6796532b07e4b-8724bd9e-d022-4e41-b4ff-289734ffcde9.webp', '6796532b0928e-11d1eae7-969f-4f4e-b6a4-54366c8be034.webp', '100.00', '2025-01-26 10:22:19'),
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
(40, 28, 37, 'Camisa Elegante', 'Esta es una Camiseta basica color plomo', '679980afd9343-6b79e5c3-2bf6-4bf2-8296-7e088f6d5e4f.webp', '679980afd98f2-795602e7-2a4e-47eb-81d0-527deec3138a.webp', '30.00', '2025-01-28 20:14:50'),
(41, 29, 36, 'Pantalon stylo antigua', 'Este es un Pantalon stylo antigua', '678d891a7cfdb-9pa.jpg', '678d891a7cfe2-10pa.jpg', '88.00', '2025-01-19 18:22:02'),
(42, 28, 39, 'Camisa de golf', 'Sólida para hombre, camisa de manga corta casual de alto estiramiento con cuello para actividades al aire libre', '67966c2b78492-88f3c976-66ea-4fb9-b057-56d82ff86847.webp', '67966c2b7849c-352b722c-104f-4b47-9b91-f4076d9f55c1.webp', '40.28', '2025-01-26 12:08:59'),
(43, 28, 37, 'Camiseta de manga', 'Corta de color sólido para hombre - Estilo de trabajo de verano, ajuste regular, tejido de punto con ligero estiramiento, cuello con botones, longitud', '67966c9fbfb81-62bf0868-7b41-41eb-832f-f850c357c26d.webp', '67966c9fbfb87-804c9d14-8898-40d2-ba17-99a2b274d107.webp', '28.60', '2025-01-26 12:10:55'),
(44, 28, 40, 'Camiseta Henley', 'Para hombre, tops deportivos casuales y cómodos para ropa de golf de verano y actividades al aire libre.', '67966ce3df37b-246af589-d267-4b52-8857-b47d46302c6d.webp', '67966ce3df388-80871105-7cf8-460a-9c57-d312c9866a31.webp', '30.33', '2025-01-26 12:12:03'),
(45, 28, 41, 'Camisa de manga corta', 'Cuello volteado con botones de color sólido, ropa de primavera y verano para hombres', '6797974d6f26d-67966d46f10f1-f3336a4d-cee6-4454-9616-ca0275f2e67f.webp', '6797974d6f60d-67966d46f10f8-9304db0f-786d-4ed0-8474-1f6bf5527ab7.webp', '33.00', '2025-01-27 09:25:17'),
(46, 28, 35, 'GENIO LAMODE', 'Ajuste Holgado, Manga Corta Versátil con Cuello Clásico y Detalle de Botones, Mezcla de Poliéster, Lavable a Máquina, Ropa Versátil', '67966d85af471-802c87d2-7f65-4cb6-98c0-a08c8b404833.webp', '67966d85af47d-a13f451b-1da0-49cb-832a-d7d041357240.webp', '35.00', '2025-01-27 09:47:46'),
(47, 28, 30, 'Camisa vintaje', 'Secado rápido para hombre, informal con solapa elástica media con botones, ropa de hombre para verano al aire libre', '67997c77e1bb0-edfe9c53-d448-48f3-80cb-df49f34bcb72.webp', '67997c77e1bbd-bfb4efd1-00a6-4964-b571-0712e6373187.webp', '12.00', '2025-01-28 19:55:19'),
(48, 28, 32, 'Camisa Casual', 'Deportiva Jacquard Nueva 2024', '67997caada571-14192ac2-b69f-407b-8a1f-3a2d1948f00f.webp', '67997caada579-e0c0e727-236a-4712-a7e5-dbb5861abff1.webp', '5.00', '2025-01-28 19:56:10'),
(49, 28, 41, 'Camisa blanca Depor', 'Casual, Cómoda de Poliéster, Manga Corta con Cuello y Placket de Botones, Ideal para Actividades al Aire Libre en Verano, Lavable a Máquina', '67997d10b6ed2-55f5b853-dbb7-4898-bb0d-068a7404b485.webp', '67997d10b6eda-c342a135-f43e-4d0a-a267-85237ac6219b.webp', '18.00', '2025-01-28 19:57:52'),
(50, 29, 33, 'Pantalones holgados', 'Estilo vintage, tejido de poliéster, color liso, sin estiramiento, longitud de 9 pulgadas, para todas las estaciones, corte regular, adulto, tejido, d', '679996d2725f7-65cd769d-d395-4d70-b224-414f35f23054.webp', '679996d272606-708fcdac-22cc-4d09-8cce-d013cb484a82.webp', '70.00', '2025-01-28 21:47:46'),
(51, 29, 29, 'Pantalon de traje', 'Casuales rectos para hombre de verano - 100% poliéster, tejido no elástico, diseño de color liso con bolsillos, estilo de ajuste regular', '6799971156d6b-46e553d1-b46b-4aac-a9ab-69ee16604280.webp', '6799971156d74-5804b0f7-ad12-414f-9bee-20a822aaef2f.webp', '60.00', '2025-01-28 21:48:49'),
(52, 29, 33, 'Vaqueros Elásticos', 'Corte Slim para Hombre - Estilo Casual de Calle, Tiro Medio, Denim Azul Oscuro con Bolsillos, Para Todo Tipo de Clima', '6799973a02a27-3302a718261002fc521986a8558511eb.webp', '6799973a02a2f-af210b6e44098b7b8b8f0c7005ee8862.webp', '80.00', '2025-01-28 21:49:30'),
(53, 29, 35, 'Pantalones Rectos', 'Ajuste Slim y Estilo Casual de Negocios 2024, Caramelo, Tejido Trenzado, Lavables a Máquina', '679997675190f-0788cc64-6a4e-472d-a0f2-c726ad38e8b2.webp', '6799976751918-2ee7a886-652d-4ecf-a85b-22801230516a.webp', '80.00', '2025-01-28 21:50:15'),
(54, 29, 5, 'Pantalones Cargo', 'Algodón para Hombre, Mezcla de 97% Algodón y 3% Spandex, Color Sólido, Corte Regular, con Cierre de Cremallera y Múltiples Bolsillos, para Uso en Toda', '679997959c8bc-c2de146d-ce6e-4fbf-8045-7343e37d6687.webp', '679997959c8c2-76b67882-e3b1-4bfc-addc-e39971063949.webp', '66.00', '2025-01-28 21:51:01'),
(55, 29, 42, 'Vaqueros de Mezclilla', 'Elásticos de Ajuste Slim para Hombre - Pierna Recta Clásica, Azul Lavado con Bigotes, Uso Todo el Año', '679997c7c0bcd-420f987e-9a7e-45a3-8a7d-684c54917c91.webp', '679997c7c0bd5-1902a9e3-153f-4d4b-aad5-145bbf2b5942.webp', '44.00', '2025-01-28 21:51:51'),
(56, 29, 28, 'Pantalon de negocios', 'Para las cuatro estaciones, cómodos pantalones ajustados de mezcla de algodón con microelástico y bolsillos, largo regular, color sólido', '679998089297e-89a9c929-13bf-4cb5-8d22-34c04e08e309.webp', '679998089298a-b58cdd3a-35e6-4a7b-8227-91f256ae4bc5.webp', '70.00', '2025-01-28 21:52:56'),
(57, 29, 5, 'Pantalones Semi-Formales', 'DARALIOS - Antifricción, Tejido Elástico con Insignia Bordada, Lavables a Máquina, Pantalones Negros para Todas las Estaciones para Negocios Casuales', '679b894b739a0-db7592d0-7353-4958-96bb-e3f3ab8df801.webp', '679b894b739aa-cdd4e655-e77a-4ba8-adea-f8fc0c883a45.webp', '100.00', '2025-01-30 09:14:35'),
(58, 29, 28, 'Pantalones de traje', 'Mezcla de algodón sólido para hombre, cintura media, corte regular, fondo de pierna recta, adecuado para otoño/invierno, todas las ocasiones.', '679b897b63224-d4b10f21-7eec-4b5c-b4cd-5ca04aacfeff.webp', '679b897b63232-504b798e-3614-4d21-b661-ac91e7743207.webp', '60.00', '2025-01-30 09:15:23'),
(59, 29, 29, 'Pantalones de vestir', 'Casuales para hombre, corte ajustado, tejido de mezcla de nailon y elastano, color liso con bolsillos, cintura media, ligero estiramiento, para el tra', '679b89e10b519-0f30bb08-d1e6-4922-b217-41053e26b00e.webp', '679b89e10c533-2fcd2bf4-934b-4193-9d87-25fe09361c2c.webp', '55.00', '2025-01-30 09:17:05'),
(60, 29, 31, 'Pantalones ajustados', 'Casuales de verano para hombre, corte regular de cintura media, color liso, nailon 79,10% elastano 20,90%, tejido de punto de alta elasticidad, brague', '679b8a1a8c281-21d05ea6-ec46-44b8-a178-d955398d8123.webp', '679b8a1a8c28a-a8d8e71d-0a97-4bdc-a1c7-eb195a9ffe2d.webp', '45.00', '2025-01-30 09:18:02'),
(61, 29, 5, 'Pantalones Cargo Elásticos', 'Ajuste Slim para Hombre - Impermeables, Resistentes a UV y Manchas, Gris Claro con Múltiples Bolsillos, Cintura Elástica para Uso Casual a Semi-Formal', '679d23290802c-b14770ca-7b74-4dae-a104-158159dcf57c.webp', '679d232908039-ac23d0a4-8d6d-4a57-8943-1f13c667be21.webp', '66.00', '2025-01-31 14:23:21'),
(62, 29, 29, 'Pantalones Sólidos', 'Elegantes Para Hombres Con Bolsillos, Pantalones De Algodón De Ajuste Delgado Y Transpirables Para Primavera Y Otoño', '679d2735852ce-ebfc809688e9f26294a20f2bcfcc4466.webp', '679d2735852dd-b6bfca6b0740c515c831197b544e7d3e.webp', '44.00', '2025-01-31 14:40:37'),
(64, 31, 29, 'zapatos con stylo', 'dddddddddddddddddddddddddddddddddddddddddddddd', '67acf223ee64d-code.png', '67acf223ee70d-Drag and drop your design here.png', '50.00', '2025-02-12 14:10:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenhas`
--

CREATE TABLE `resenhas` (
  `resId` int(11) NOT NULL,
  `venId` varchar(4) DEFAULT NULL,
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

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`stoId`, `proId`, `estId`, `colId`, `talId`, `stoCantidad`, `stoFechaRegis`) VALUES
(41, 1, 1, 26, 1, 30, '2025-01-20 07:17:37'),
(42, 3, 1, 13, 2, 32, '2025-01-20 07:19:00'),
(43, 4, 1, 13, 1, 14, '2025-01-20 07:19:36'),
(44, 5, 1, 34, 11, 36, '2025-01-20 07:20:24'),
(45, 10, 1, 19, 11, 10, '2025-01-20 14:46:53'),
(46, 10, 1, 18, 11, 20, '2025-01-25 10:14:35'),
(47, 10, 1, 13, 1, 20, '2025-01-25 12:12:06'),
(51, 1, 1, 16, 1, 30, '2025-01-26 09:20:48'),
(52, 1, 1, 16, 2, 15, '2025-01-26 09:21:04'),
(53, 2, 1, 25, 13, 20, '2025-01-26 09:21:58'),
(54, 2, 1, 19, 1, 35, '2025-01-26 09:22:10'),
(55, 41, 1, 13, 13, 30, '2025-01-26 10:48:53'),
(56, 3, 1, 26, 12, 33, '2025-01-26 10:49:14'),
(57, 4, 1, 16, 14, 25, '2025-01-26 10:49:38'),
(58, 6, 1, 13, 13, 21, '2025-02-12 14:14:58'),
(59, 6, 1, 26, 11, 44, '2025-01-26 10:51:07'),
(60, 7, 1, 18, 12, 25, '2025-01-26 10:51:29'),
(61, 13, 1, 16, 2, 12, '2025-01-26 12:02:25'),
(62, 10, 1, 21, 13, 8, '2025-01-26 12:02:41'),
(63, 40, 1, 19, 12, 10, '2025-01-26 12:04:25'),
(64, 2, 1, 18, 2, 10, '2025-01-26 12:04:41'),
(65, 12, 1, 13, 13, 36, '2025-01-26 12:05:11'),
(66, 16, 1, 26, 1, 25, '2025-01-26 12:06:31'),
(67, 11, 1, 18, 1, 30, '2025-01-26 12:06:48'),
(68, 20, 1, 13, 13, 10, '2025-01-26 12:07:10'),
(69, 42, 1, 22, 11, 22, '2025-01-26 12:15:49'),
(70, 42, 1, 13, 13, 24, '2025-01-26 12:16:08'),
(71, 46, 1, 26, 14, 10, '2025-01-26 12:16:27'),
(72, 46, 1, 35, 1, 5, '2025-01-26 12:16:43'),
(73, 45, 1, 19, 1, 15, '2025-01-26 12:17:00'),
(74, 43, 1, 26, 13, 14, '2025-01-26 12:17:34'),
(75, 44, 1, 24, 12, 14, '2025-01-26 12:20:24'),
(76, 39, 1, 19, 21, 35, '2025-01-27 10:02:51'),
(77, 9, 1, 13, 15, 15, '2025-01-27 10:03:39'),
(78, 8, 1, 13, 25, 14, '2025-01-27 10:05:13'),
(79, 26, 1, 24, 24, 9, '2025-01-27 10:05:57'),
(80, 27, 1, 16, 16, 5, '2025-01-27 10:06:11'),
(81, 38, 1, 20, 23, 8, '2025-01-27 10:06:28'),
(82, 34, 1, 16, 11, 8, '2025-01-27 10:07:21'),
(83, 35, 1, 26, 12, 12, '2025-01-27 10:07:39'),
(84, 36, 1, 13, 12, 11, '2025-01-27 10:07:55'),
(85, 31, 1, 26, 12, 9, '2025-01-27 10:08:35'),
(86, 30, 1, 13, 11, 7, '2025-01-27 10:09:17'),
(87, 11, 1, 26, 1, 7, '2025-01-27 10:10:40'),
(92, 29, 1, 20, 13, 2, '2025-02-07 13:53:37'),
(93, 47, 1, 34, 1, 15, '2025-01-28 20:04:42'),
(94, 49, 1, 26, 12, 33, '2025-01-28 20:05:06'),
(95, 48, 1, 25, 13, 12, '2025-01-28 20:05:48'),
(96, 56, 1, 34, 11, 4, '2025-01-28 21:53:34'),
(97, 51, 1, 26, 12, 2, '2025-01-28 21:53:50'),
(98, 54, 1, 22, 2, 14, '2025-01-28 21:54:15'),
(99, 50, 1, 26, 1, 5, '2025-01-28 21:54:38'),
(100, 53, 1, 13, 13, 14, '2025-01-28 21:54:57'),
(101, 55, 1, 16, 1, 14, '2025-01-30 09:07:56'),
(102, 52, 1, 13, 11, 12, '2025-01-30 09:08:46'),
(103, 60, 1, 34, 1, 14, '2025-01-30 09:19:18'),
(104, 59, 1, 13, 12, 3, '2025-01-30 09:19:58'),
(105, 58, 1, 16, 12, 3, '2025-01-30 09:21:08'),
(106, 57, 1, 13, 13, 12, '2025-01-30 09:27:28'),
(107, 37, 1, 33, 11, 20, '2025-01-30 09:25:40'),
(108, 17, 1, 16, 1, 12, '2025-01-30 09:28:27'),
(109, 21, 1, 34, 1, 8, '2025-01-30 09:30:02'),
(110, 28, 1, 13, 1, 5, '2025-01-30 09:31:02'),
(111, 61, 1, 16, 1, 12, '2025-02-12 14:14:58'),
(112, 62, 1, 34, 12, 2, '2025-02-12 14:14:58'),
(114, 64, 1, 13, 15, 10, '2025-02-12 14:11:22');

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
(14, 'XS', '2025-01-13 14:22:19'),
(15, '40', '2025-01-26 11:09:40'),
(16, '41', '2025-01-27 09:59:35'),
(17, '42', '2025-01-27 09:59:38'),
(18, '43', '2025-01-27 09:59:40'),
(19, '44', '2025-01-27 09:59:42'),
(20, '45', '2025-01-27 09:59:44'),
(21, '35', '2025-01-27 09:59:47'),
(22, '36', '2025-01-27 09:59:50'),
(23, '37', '2025-01-27 09:59:52'),
(24, '38', '2025-01-27 09:59:53'),
(25, '39', '2025-01-27 09:59:55'),
(28, 'ssssssssss', '2025-02-09 08:37:25');

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
  `venId` varchar(4) NOT NULL,
  `estVenId` int(11) DEFAULT NULL,
  `cliId` int(11) DEFAULT NULL,
  `venFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`venId`, `estVenId`, `cliId`, `venFechaRegis`) VALUES
('A001', 2, 7, '2025-02-11 20:39:41'),
('A002', 1, 7, '2025-02-11 20:12:56'),
('A003', 1, 6, '2025-02-11 20:13:09'),
('A004', 2, 7, '2025-02-11 20:13:09'),
('A005', 1, 6, '2025-02-12 09:06:15'),
('A006', 2, 6, '2025-02-12 11:55:43'),
('A007', 2, 6, '2025-02-12 12:11:44'),
('A008', 2, 6, '2025-02-12 12:12:24'),
('A009', 2, 6, '2025-02-12 12:19:11'),
('A010', 2, 6, '2025-02-12 12:28:53'),
('A011', 2, 6, '2025-02-12 12:31:41'),
('A012', 2, 6, '2025-02-12 12:35:30'),
('A013', 2, 8, '2025-02-12 12:37:05'),
('A014', 2, 8, '2025-02-12 12:38:55'),
('A015', 1, 8, '2025-02-12 13:44:14'),
('A016', 1, 6, '2025-02-12 14:14:58');

--
-- Disparadores `ventas`
--
DELIMITER $$
CREATE TRIGGER `before_insert_ventas` BEFORE INSERT ON `ventas` FOR EACH ROW BEGIN
    DECLARE last_id VARCHAR(4);
    DECLARE letter CHAR(1);
    DECLARE number INT;
    
    -- Obtener el último ID registrado
    SELECT venId INTO last_id FROM ventas ORDER BY venId DESC LIMIT 1;
    
    -- Si la tabla está vacía, iniciar desde 'A001'
    IF last_id IS NULL THEN
        SET NEW.venId = 'A001';
    ELSE
        -- Separar letra y número
        SET letter = LEFT(last_id, 1);
        SET number = CAST(RIGHT(last_id, 3) AS UNSIGNED);
        
        -- Incrementar el número
        IF number < 999 THEN
            SET NEW.venId = CONCAT(letter, LPAD(number + 1, 3, '0'));
        ELSE
            -- Si llega a 999, pasar a la siguiente letra (de A a Z)
            IF letter < 'Z' THEN
                SET NEW.venId = CONCAT(CHAR(ASCII(letter) + 1), '001');
            ELSE
                -- Si ya llegó a 'Z999', evitar desbordamiento
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Límite de ID alcanzado';
            END IF;
        END IF;
    END IF;
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`actId`),
  ADD KEY `usuarioId` (`usuarioId`);

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
  ADD KEY `stoId` (`stoId`),
  ADD KEY `fk_detventa_venta` (`venId`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`estId`);

--
-- Indices de la tabla `estadoventa`
--
ALTER TABLE `estadoventa`
  ADD PRIMARY KEY (`estVenId`);

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
  ADD KEY `fk_rese_venta` (`venId`);

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
  ADD KEY `cliId` (`cliId`),
  ADD KEY `fk_estVenId` (`estVenId`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `actId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `carId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `catId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `cliId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `color`
--
ALTER TABLE `color`
  MODIFY `colId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `contacto`
--
ALTER TABLE `contacto`
  MODIFY `conId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  MODIFY `detVenId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `estId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estadoventa`
--
ALTER TABLE `estadoventa`
  MODIFY `estVenId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `marId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `oferta`
--
ALTER TABLE `oferta`
  MODIFY `ofeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `portada`
--
ALTER TABLE `portada`
  MODIFY `porId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `proId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT de la tabla `resenhas`
--
ALTER TABLE `resenhas`
  MODIFY `resId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `stoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT de la tabla `talla`
--
ALTER TABLE `talla`
  MODIFY `talId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `admId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `actividades_ibfk_1` FOREIGN KEY (`usuarioId`) REFERENCES `usuario` (`admId`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD CONSTRAINT `detalleventa_ibfk_2` FOREIGN KEY (`stoId`) REFERENCES `stock` (`stoId`),
  ADD CONSTRAINT `fk_detventa_venta` FOREIGN KEY (`venId`) REFERENCES `ventas` (`venId`);

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
  ADD CONSTRAINT `fk_rese_venta` FOREIGN KEY (`venId`) REFERENCES `ventas` (`venId`);

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
  ADD CONSTRAINT `fk_estVenId` FOREIGN KEY (`estVenId`) REFERENCES `estadoventa` (`estVenId`),
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliId`) REFERENCES `cliente` (`cliId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
