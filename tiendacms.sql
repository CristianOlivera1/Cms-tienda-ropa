-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2025 at 03:39 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tienda`
--

-- --------------------------------------------------------

--
-- Table structure for table `cargo`
--

CREATE TABLE `cargo` (
  `carId` int(11) NOT NULL,
  `carNombre` varchar(40) DEFAULT NULL,
  `carFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cargo`
--

INSERT INTO `cargo` (`carId`, `carNombre`, `carFechaRegis`) VALUES
(1, 'Gerente', '2024-12-30 11:21:50'),
(2, 'Administrador', '2024-12-30 11:21:50');

-- --------------------------------------------------------

--
-- Table structure for table `categoria`
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
-- Dumping data for table `categoria`
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
-- Table structure for table `cliente`
--

CREATE TABLE `cliente` (
  `cliId` int(11) NOT NULL,
  `cliDni` char(8) NOT NULL,
  `cliNombre` varchar(100) DEFAULT NULL,
  `cliApellidoPaterno` varchar(100) DEFAULT NULL,
  `cliApellidoMaterno` varchar(100) DEFAULT NULL,
  `cliCorreo` varchar(150) DEFAULT NULL,
  `cliFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cliFechaNacimiento` date DEFAULT NULL,
  `cliDireccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cliente`
--

INSERT INTO `cliente` (`cliId`, `cliDni`, `cliNombre`, `cliApellidoPaterno`, `cliApellidoMaterno`, `cliCorreo`, `cliFechaRegis`, `cliFechaNacimiento`, `cliDireccion`) VALUES
(8, '77663214', 'fer', 'juarez', 'peña', 'fer@gmail.com', '2025-02-09 07:42:30', '2004-04-01', 'callao'),
(9, '89751565', 'pantera', 'rosa', 'del', 'panter@gmail.com', '2025-02-09 07:55:54', '2005-04-01', 'callao'),
(10, '7595850', 'Marco', 'mejia', 'mmmmmm', 'marco_6_67@gmail.com', '2025-02-09 17:24:01', '2015-02-12', 'jr.lima'),
(11, '9595859', 'juan', 'pedraza', 'mmmmmm', 'juan_6_67@gmail.com', '2025-02-09 17:24:29', '2015-02-12', 'jr.lima');

-- --------------------------------------------------------

--
-- Table structure for table `color`
--

CREATE TABLE `color` (
  `colId` int(11) NOT NULL,
  `colNombre` varchar(50) NOT NULL,
  `colCodigoHex` varchar(30) DEFAULT NULL,
  `colFechaRegis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `color`
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
-- Table structure for table `contacto`
--

CREATE TABLE `contacto` (
  `conId` int(11) NOT NULL,
  `conTelefono` char(9) DEFAULT NULL,
  `conEmail` varchar(100) DEFAULT NULL,
  `conFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacto`
--

INSERT INTO `contacto` (`conId`, `conTelefono`, `conEmail`, `conFechaRegis`) VALUES
(1, '987654320', '2211@ddddd.com', '2025-01-04 16:34:28');

-- --------------------------------------------------------

--
-- Table structure for table `detalleventa`
--

CREATE TABLE `detalleventa` (
  `detVenId` int(11) NOT NULL,
  `venId` varchar(4) NOT NULL,
  `stoId` int(11) DEFAULT NULL,
  `detVenCantidad` int(11) DEFAULT NULL,
  `detVenPrecio` decimal(8,2) DEFAULT NULL,
  `detVenFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detalleventa`
--

INSERT INTO `detalleventa` (`detVenId`, `venId`, `stoId`, `detVenCantidad`, `detVenPrecio`, `detVenFechaRegis`) VALUES
(7, 'A001', 42, 1, 10.00, '2025-02-09 11:26:54'),
(8, 'A002', 53, 5, 20.00, '2025-02-09 17:20:35'),
(9, 'A003', 60, 5, 4.00, '2025-02-09 17:25:46'),
(10, 'A004', 42, 2, 50.00, '2025-02-09 17:26:08'),
(11, 'A005', 46, 5, 20.00, '2025-02-28 19:45:56'),
(12, 'A006', 56, 2, 5.00, '2025-02-25 19:46:19'),
(13, 'A004', 60, 2, 20.00, '2025-02-20 19:46:46');

-- --------------------------------------------------------

--
-- Table structure for table `estado`
--

CREATE TABLE `estado` (
  `estId` int(11) NOT NULL,
  `estDisponible` varchar(20) DEFAULT NULL,
  `estFechaRegis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estado`
--

INSERT INTO `estado` (`estId`, `estDisponible`, `estFechaRegis`) VALUES
(1, 'Disponible', '2025-01-08 09:30:25'),
(2, 'No disponible', '2025-01-08 09:30:25'),
(3, 'En oferta', '2025-01-28 11:32:53');

-- --------------------------------------------------------

--
-- Table structure for table `estadoventa`
--

CREATE TABLE `estadoventa` (
  `estVenId` int(11) NOT NULL,
  `estVenNombre` varchar(50) NOT NULL,
  `estVenDescripcion` varchar(255) DEFAULT NULL,
  `estVenFechaRegis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estadoventa`
--

INSERT INTO `estadoventa` (`estVenId`, `estVenNombre`, `estVenDescripcion`, `estVenFechaRegis`) VALUES
(1, 'Pendiente', 'La venta ha sido creada pero no se ha procesado aún.', '2025-02-09 06:23:02'),
(2, 'Completada', 'La venta ha sido procesada exitosamente y los productos han sido enviados.', '2025-02-09 06:23:02'),
(3, 'Cancelada', 'La venta ha sido cancelada por el cliente o el sistema.', '2025-02-09 06:23:02');

-- --------------------------------------------------------

--
-- Table structure for table `marca`
--

CREATE TABLE `marca` (
  `marId` int(11) NOT NULL,
  `marNombre` varchar(50) DEFAULT NULL,
  `marImg` varchar(255) NOT NULL,
  `marFechaRegis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marca`
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
-- Table structure for table `oferta`
--

CREATE TABLE `oferta` (
  `ofeId` int(11) NOT NULL,
  `stoId` int(11) DEFAULT NULL,
  `ofePorcentaje` int(11) DEFAULT NULL,
  `ofeTiempo` datetime DEFAULT NULL,
  `ofeFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `oferta`
--

INSERT INTO `oferta` (`ofeId`, `stoId`, `ofePorcentaje`, `ofeTiempo`, `ofeFechaRegis`) VALUES
(29, 41, 20, '2025-02-10 18:33:00', '2025-02-09 17:33:24'),
(30, 54, 30, '2025-02-10 17:34:00', '2025-02-09 17:34:04'),
(31, 83, 50, '2025-02-09 19:34:00', '2025-02-09 17:34:11'),
(32, 72, 20, '2025-02-10 17:34:00', '2025-02-09 17:34:29'),
(33, 77, 40, '2025-02-10 17:34:00', '2025-02-09 17:34:41'),
(34, 100, 70, '2025-02-10 17:34:00', '2025-02-09 17:34:51'),
(35, 85, 90, '2025-02-09 19:35:00', '2025-02-09 17:35:04'),
(36, 84, 99, '2025-02-09 18:35:00', '2025-02-09 17:35:23');

-- --------------------------------------------------------

--
-- Table structure for table `portada`
--

CREATE TABLE `portada` (
  `porId` int(11) NOT NULL,
  `porTitulo` varchar(70) DEFAULT NULL,
  `porDescripcion` varchar(200) DEFAULT NULL,
  `porFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `portada`
--

INSERT INTO `portada` (`porId`, `porTitulo`, `porDescripcion`, `porFechaRegis`) VALUES
(1, 'Cms tienda de ropas para todos', 'Este es un sistio wew similar a un ecommerce diseñaado para una tienda de prendas de vestir.', '2024-12-30 20:08:09');

-- --------------------------------------------------------

--
-- Table structure for table `producto`
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
-- Dumping data for table `producto`
--

INSERT INTO `producto` (`proId`, `catId`, `marId`, `proNombre`, `proDescripcion`, `proImg`, `proImg2`, `proPrecio`, `proFechaRegistro`) VALUES
(1, 28, 4, 'Camiseta Deportiva', 'Camiseta de fútbol Nike', '678e3df0327b6-d2bd8030-73fc-4f9b-bb51-89bb0c1e8c19.webp', '678e3df033f83-2edc10b5-a98f-4fcc-a4ed-73621f952ff4.webp', 25.99, '2025-01-20 07:13:36'),
(2, 28, 4, 'Camiseta Básica', 'Camiseta de algodón de varios colores', '679652b78d569-ce0600a4-1827-4d76-8995-bbc0b4e20de6.webp', '679652b78ee0f-88894263-053f-445f-9e26-4f5e2a89bc09.webp', 15.99, '2025-01-26 10:20:23'),
(3, 28, 4, 'Camiseta Estampada', 'Camiseta con estampado gráfico', '678d85a83661b-5.jpg', '678d85a836a3e-6.jpg', 19.99, '2025-01-19 18:07:20'),
(4, 29, 5, 'Pantalón Jeans', 'Pantalón de mezclilla azul', '678d850ca29d4-1pa.webp', '678d850ca2c5d-2.jpeg', 25.50, '2025-01-19 18:04:44'),
(5, 29, 5, 'Pantalón Chino', 'Pantalón chino para ocasiones casuales', '678d84280200e-3pa.jpg', '678d842802ec6-4pa.jpg', 28.75, '2025-01-19 18:00:56'),
(6, 30, 21, 'Gorra Deportiva', 'Gorra con logo deportivo', '678d8402bb281-3g.jpg', '678d8402bcf74-4g.jpeg', 12.99, '2025-01-19 18:00:18'),
(7, 30, 21, 'Gorra Casual', 'Gorra de algodón, estilo urbano', '678d83d111c5d-1g.jpg', '678d83d111fb7-2g.jpg', 14.50, '2025-01-19 17:59:29'),
(8, 31, 27, 'Zapatos Deportivos', 'Zapatos deportivos para entrenamiento', '678d84db8f599-zapatillas-de-hombre-seul.jpg', '678d84db8f8b8-IMG_0934.jpg.webp', 45.00, '2025-01-19 18:03:55'),
(9, 31, 27, 'Zapatos Formales', 'Zapatos formales de cuero', '678d84bc7a3bf-19.webp', '678d84bc7a6fd-20.jpg', 60.00, '2025-01-19 18:03:24'),
(10, 34, 28, 'Sudadera con Capucha', 'Sudadera de algodón con capucha', '678d8472ccc61-21.webp', '678d8472ccf59-22.jpg', 35.00, '2025-01-19 18:02:10'),
(11, 34, 28, 'Sudadera Deportiva', 'Sudadera ligera para entrenamiento', '678d8450c3824-1pol.jpg', '678d8450c3acb-2pol.jpeg', 40.00, '2025-01-19 18:01:36'),
(12, 35, 29, 'Chaqueta de Invierno', 'Chaqueta de abrigo para invierno', '679655de13ba7-08434bbf-6488-455b-aee5-89600c49871e.webp', '679655de1476f-24cbcf2d-bf33-4dc9-9d28-9c64c52e8f97.webp', 80.00, '2025-01-26 10:33:50'),
(13, 35, 29, 'Chaqueta de Cuero', 'Chaqueta de cuero para estilo casual', '679655b433fc6-8f0140a36de3f7d8a7846ad23e180b87.webp', '679655b43436a-6740a86340503d403f74df996677797d.webp', 120.00, '2025-01-26 10:33:08'),
(14, 36, 30, 'Sombrero de Sol', 'Sombrero para protegerse del sol', '6796558e3c87c-393fde27-b633-4ff1-8b25-df93124dd82c.webp', '6796558e3fbef-a511a69d-583a-40e9-a285-4fc33fe2fadc.webp', 18.99, '2025-01-26 10:32:30'),
(15, 36, 30, 'Sombrero Estilo Fedora', 'Sombrero Fedora clásico', '679655680d27d-7a9503e8-0fec-4abd-b487-4ba6604d8812.webp', '679655680e190-dbcf0b10-9888-4301-ad3e-bd0f7836967c.webp', 22.50, '2025-01-26 10:31:52'),
(16, 37, 31, 'Bufanda de Lana', 'Bufanda de lana para invierno', '6796554966142-63f48813-c17d-4557-bac8-4f1ebb1a3633.webp', '679655496654c-90650e41-f579-4d53-ac6c-590a03409bae.webp', 14.00, '2025-01-26 10:31:21'),
(17, 37, 31, 'Bufanda de Seda', 'Bufanda de seda para clima templado', '6796551a2f501-7c9e63b309de4d3ca0ab5a632f22cc78-goods.webp', '6796551a30655-410e48a863da4449adf5a2a9be65ba25-goods.webp', 25.00, '2025-01-26 10:30:34'),
(18, 39, 32, 'Calcetines de Algodón', 'Pack de 3 calcetines de algodón', '679654e274a2a-bb49a0ca-8a77-49cb-8a1e-7ece37b728d4.webp', '679654e27556c-fa712fbc-9b3f-454f-bf9f-1977f4fb6852.webp', 9.99, '2025-01-26 10:29:38'),
(19, 39, 32, 'Calcetines de Lana', 'Pack de 2 calcetines de lana', '679654bc27898-a53b821dd583e0595f0e67b16a0de545_1722504425770.webp', '679654bc28503-1722499352987-0fae2d93e17f4898a159138599d4d290-goods.webp', 12.50, '2025-01-26 10:29:00'),
(20, 40, 33, 'Cinturón de Cuero', 'Cinturón de cuero para traje', '6796549603cf6-ecaa5f7d-6c84-45f7-b492-2d0a37dc62af.webp', '67965496040be-7c45c39b43b23b5fccb33fd797da5a68.webp', 20.00, '2025-01-26 10:28:22'),
(21, 40, 33, 'Cinturón Deportivo', 'Cinturón deportivo para jeans', '67965457268b3-91206d5a-b985-4be0-a5ec-537d1486aa56_1800x1800.webp', '6796545729056-63d6ab35f69f375649691a8988e7b573.webp', 15.00, '2025-01-26 10:27:19'),
(22, 45, 34, 'Traje Formal', 'Traje de corte slim para eventos', '679654278cafb-e15715d3-0070-44ce-9570-4fb9a0b1dc18.webp', '679654278cdcf-a35831c2-5df2-43ae-a2db-a58c8407fd56.webp', 120.00, '2025-01-26 10:26:31'),
(23, 45, 34, 'Traje Clásico', 'Traje clásico de dos piezas', '679653e2f30ee-f646711e-7277-4652-a94c-cc346914f564.webp', '679653e2f400b-60d9c8a2-c199-4b78-9460-845b5ab3b0b8.webp', 150.00, '2025-01-26 10:25:23'),
(24, 46, 35, 'Bañador de Hombre', 'Bañador para piscina', '679653b7a5111-c613217b-1f59-4fad-a5ec-babc5276264a.webp', '679653b7aad92-a90f00ed-8439-40ff-860e-a7e43e2fdce6.webp', 25.00, '2025-01-26 10:24:39'),
(26, 47, 36, 'Zapatillas Deportivas', 'Zapatillas para correr', '678d84f654e41-12.jpeg', '678d84f655182-11.webp', 50.00, '2025-01-19 18:04:22'),
(27, 47, 36, 'Zapatillas Casuales', 'Zapatillas para uso diario', '678d8378ceb06-1.jpg', '678d8378cf9cf-2.jpg', 45.00, '2025-01-19 17:58:00'),
(28, 48, 37, 'Abrigo de Invierno', 'Abrigo largo para el frío extremo', '6796536ac7ac9-7e1443e6-bf86-40dc-8eab-c336b35f43cc.webp', '6796536ac7e4f-4b5003d4-564c-40fc-8026-9c7e2ce54457.webp', 150.00, '2025-01-26 10:23:22'),
(29, 48, 37, 'Abrigo Corto', 'Abrigo corto para clima templado', '6796532b07e4b-8724bd9e-d022-4e41-b4ff-289734ffcde9.webp', '6796532b0928e-11d1eae7-969f-4f4e-b6a4-54366c8be034.webp', 100.00, '2025-01-26 10:22:19'),
(30, 34, 5, 'Sudadera negra con diseño', 'Este es una Sudadera negra con diseño', '678d8630197ed-11pol.jpg', '678d8630197f5-12pol.jpeg', 66.00, '2025-01-19 18:09:36'),
(31, 34, 4, 'Sudadera blanca con deportiva', 'Sudadera blanca con deportiva de la seleccion peruana', '678d86a3701ed-23.webp', '678d86a3701f7-24.webp', 70.00, '2025-01-19 18:11:31'),
(32, 30, 27, 'Gorra blanca urbana', 'Esta es una Gorra blanca urbana', '678d8715ababb-7g.jpg', '678d8715abac4-8g.jpg', 50.00, '2025-01-19 18:13:25'),
(33, 30, 4, 'Gorra urbana plana', 'Esta es una gorra urbana plana', '678d874a21a7f-5g.webp', '678d874a21a88-6g.jpg', 40.00, '2025-01-19 18:14:18'),
(34, 34, 21, 'Sudadera azul con estampado', 'Esta es una Sudadera azul con estampado', '678d8789b5ef3-7pol.jpeg', '678d8789b5efc-8pol.jpg', 70.00, '2025-01-19 18:15:21'),
(35, 35, 30, 'Chaqueta blanca', 'Esta es una Chaqueta blanca', '678d87db1e501-30.webp', '678d87db1e50b-29.webp', 43.00, '2025-01-19 18:16:43'),
(36, 34, 30, 'Sudadera negra con capucha urbana', 'Esta es un Sudadera negra con capucha urbana', '678d88114b51e-28.jpeg', '678d88114b529-27.jpg', 55.00, '2025-01-19 18:17:37'),
(37, 34, 33, 'Sudadera con capucha urbana color piel', 'Esta es una Sudadera con capucha urbana color piel', '678d88424e82b-4pol.jpg', '678d88424e834-5pol.jpg', 66.00, '2025-01-19 18:18:26'),
(38, 47, 4, 'Zapatillas deportivas color amarillo', 'Esta es una Zapatillas deportivas color amarillo/negro', '678d887a5d621-4.jpg.webp', '678d887a5d629-3.jpg.webp', 150.00, '2025-01-19 18:19:22'),
(39, 47, 34, 'Zapatilla urbana color gris', 'Esta es una Zapatilla urbana color gris', '678d88a516b2a-9.webp', '678d88a516b32-10.webp', 200.00, '2025-01-19 18:20:05'),
(40, 28, 37, 'Camisa Elegante', 'Esta es una Camiseta basica color plomo', '679980afd9343-6b79e5c3-2bf6-4bf2-8296-7e088f6d5e4f.webp', '679980afd98f2-795602e7-2a4e-47eb-81d0-527deec3138a.webp', 30.00, '2025-01-28 20:14:50'),
(41, 29, 36, 'Pantalon stylo antigua', 'Este es un Pantalon stylo antigua', '678d891a7cfdb-9pa.jpg', '678d891a7cfe2-10pa.jpg', 88.00, '2025-01-19 18:22:02'),
(42, 28, 39, 'Camisa de golf', 'Sólida para hombre, camisa de manga corta casual de alto estiramiento con cuello para actividades al aire libre', '67966c2b78492-88f3c976-66ea-4fb9-b057-56d82ff86847.webp', '67966c2b7849c-352b722c-104f-4b47-9b91-f4076d9f55c1.webp', 40.28, '2025-01-26 12:08:59'),
(43, 28, 37, 'Camiseta de manga', 'Corta de color sólido para hombre - Estilo de trabajo de verano, ajuste regular, tejido de punto con ligero estiramiento, cuello con botones, longitud', '67966c9fbfb81-62bf0868-7b41-41eb-832f-f850c357c26d.webp', '67966c9fbfb87-804c9d14-8898-40d2-ba17-99a2b274d107.webp', 28.60, '2025-01-26 12:10:55'),
(44, 28, 40, 'Camiseta Henley', 'Para hombre, tops deportivos casuales y cómodos para ropa de golf de verano y actividades al aire libre.', '67966ce3df37b-246af589-d267-4b52-8857-b47d46302c6d.webp', '67966ce3df388-80871105-7cf8-460a-9c57-d312c9866a31.webp', 30.33, '2025-01-26 12:12:03'),
(45, 28, 41, 'Camisa de manga corta', 'Cuello volteado con botones de color sólido, ropa de primavera y verano para hombres', '6797974d6f26d-67966d46f10f1-f3336a4d-cee6-4454-9616-ca0275f2e67f.webp', '6797974d6f60d-67966d46f10f8-9304db0f-786d-4ed0-8474-1f6bf5527ab7.webp', 33.00, '2025-01-27 09:25:17'),
(46, 28, 35, 'GENIO LAMODE', 'Ajuste Holgado, Manga Corta Versátil con Cuello Clásico y Detalle de Botones, Mezcla de Poliéster, Lavable a Máquina, Ropa Versátil', '67966d85af471-802c87d2-7f65-4cb6-98c0-a08c8b404833.webp', '67966d85af47d-a13f451b-1da0-49cb-832a-d7d041357240.webp', 35.00, '2025-01-27 09:47:46'),
(47, 28, 30, 'Camisa vintaje', 'Secado rápido para hombre, informal con solapa elástica media con botones, ropa de hombre para verano al aire libre', '67997c77e1bb0-edfe9c53-d448-48f3-80cb-df49f34bcb72.webp', '67997c77e1bbd-bfb4efd1-00a6-4964-b571-0712e6373187.webp', 12.00, '2025-01-28 19:55:19'),
(48, 28, 32, 'Camisa Casual', 'Deportiva Jacquard Nueva 2024', '67997caada571-14192ac2-b69f-407b-8a1f-3a2d1948f00f.webp', '67997caada579-e0c0e727-236a-4712-a7e5-dbb5861abff1.webp', 5.00, '2025-01-28 19:56:10'),
(49, 28, 41, 'Camisa blanca Depor', 'Casual, Cómoda de Poliéster, Manga Corta con Cuello y Placket de Botones, Ideal para Actividades al Aire Libre en Verano, Lavable a Máquina', '67997d10b6ed2-55f5b853-dbb7-4898-bb0d-068a7404b485.webp', '67997d10b6eda-c342a135-f43e-4d0a-a267-85237ac6219b.webp', 18.00, '2025-01-28 19:57:52'),
(50, 29, 33, 'Pantalones holgados', 'Estilo vintage, tejido de poliéster, color liso, sin estiramiento, longitud de 9 pulgadas, para todas las estaciones, corte regular, adulto, tejido, d', '679996d2725f7-65cd769d-d395-4d70-b224-414f35f23054.webp', '679996d272606-708fcdac-22cc-4d09-8cce-d013cb484a82.webp', 70.00, '2025-01-28 21:47:46'),
(51, 29, 29, 'Pantalon de traje', 'Casuales rectos para hombre de verano - 100% poliéster, tejido no elástico, diseño de color liso con bolsillos, estilo de ajuste regular', '6799971156d6b-46e553d1-b46b-4aac-a9ab-69ee16604280.webp', '6799971156d74-5804b0f7-ad12-414f-9bee-20a822aaef2f.webp', 60.00, '2025-01-28 21:48:49'),
(52, 29, 33, 'Vaqueros Elásticos', 'Corte Slim para Hombre - Estilo Casual de Calle, Tiro Medio, Denim Azul Oscuro con Bolsillos, Para Todo Tipo de Clima', '6799973a02a27-3302a718261002fc521986a8558511eb.webp', '6799973a02a2f-af210b6e44098b7b8b8f0c7005ee8862.webp', 80.00, '2025-01-28 21:49:30'),
(53, 29, 35, 'Pantalones Rectos', 'Ajuste Slim y Estilo Casual de Negocios 2024, Caramelo, Tejido Trenzado, Lavables a Máquina', '679997675190f-0788cc64-6a4e-472d-a0f2-c726ad38e8b2.webp', '6799976751918-2ee7a886-652d-4ecf-a85b-22801230516a.webp', 80.00, '2025-01-28 21:50:15'),
(54, 29, 5, 'Pantalones Cargo', 'Algodón para Hombre, Mezcla de 97% Algodón y 3% Spandex, Color Sólido, Corte Regular, con Cierre de Cremallera y Múltiples Bolsillos, para Uso en Toda', '679997959c8bc-c2de146d-ce6e-4fbf-8045-7343e37d6687.webp', '679997959c8c2-76b67882-e3b1-4bfc-addc-e39971063949.webp', 66.00, '2025-01-28 21:51:01'),
(55, 29, 42, 'Vaqueros de Mezclilla', 'Elásticos de Ajuste Slim para Hombre - Pierna Recta Clásica, Azul Lavado con Bigotes, Uso Todo el Año', '679997c7c0bcd-420f987e-9a7e-45a3-8a7d-684c54917c91.webp', '679997c7c0bd5-1902a9e3-153f-4d4b-aad5-145bbf2b5942.webp', 44.00, '2025-01-28 21:51:51'),
(56, 29, 28, 'Pantalon de negocios', 'Para las cuatro estaciones, cómodos pantalones ajustados de mezcla de algodón con microelástico y bolsillos, largo regular, color sólido', '679998089297e-89a9c929-13bf-4cb5-8d22-34c04e08e309.webp', '679998089298a-b58cdd3a-35e6-4a7b-8227-91f256ae4bc5.webp', 70.00, '2025-01-28 21:52:56');

-- --------------------------------------------------------

--
-- Table structure for table `resenhas`
--

CREATE TABLE `resenhas` (
  `resId` int(11) NOT NULL,
  `venId` varchar(4) NOT NULL,
  `resMensaje` varchar(255) DEFAULT NULL,
  `resFechaRegis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resenhas`
--

INSERT INTO `resenhas` (`resId`, `venId`, `resMensaje`, `resFechaRegis`) VALUES
(1, 'A001', 'Esta es una prueba de reseñas para el producto.', '2025-02-09 17:15:59'),
(3, 'A002', 'Excelente Producto,sugiero pedir tallas mas grandes.', '2025-02-09 17:21:24'),
(4, 'A003', 'Producto promedio,pero me gusto los colores.', '2025-02-09 17:26:48'),
(5, 'A004', 'Me agrado el tamaño,pero no me gusto los colores.', '2025-02-09 17:27:15');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
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
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stoId`, `proId`, `estId`, `colId`, `talId`, `stoCantidad`, `stoFechaRegis`) VALUES
(41, 1, 3, 26, 1, 30, '2025-02-09 17:33:24'),
(42, 3, 1, 13, 2, 32, '2025-01-20 07:19:00'),
(43, 4, 1, 13, 1, 14, '2025-01-20 07:19:36'),
(44, 5, 1, 34, 11, 36, '2025-01-20 07:20:24'),
(45, 10, 1, 19, 11, 10, '2025-01-20 14:46:53'),
(46, 10, 1, 18, 11, 20, '2025-01-25 10:14:35'),
(47, 10, 1, 13, 1, 20, '2025-01-25 12:12:06'),
(51, 1, 1, 16, 1, 30, '2025-01-26 09:20:48'),
(52, 1, 1, 16, 2, 15, '2025-01-26 09:21:04'),
(53, 2, 1, 25, 13, 20, '2025-01-26 09:21:58'),
(54, 2, 3, 19, 1, 35, '2025-02-09 17:34:04'),
(55, 41, 1, 13, 13, 30, '2025-01-26 10:48:53'),
(56, 3, 1, 26, 12, 33, '2025-01-26 10:49:14'),
(57, 4, 1, 16, 14, 25, '2025-01-26 10:49:38'),
(58, 6, 1, 13, 13, 22, '2025-01-26 10:50:48'),
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
(72, 46, 3, 35, 1, 5, '2025-02-09 17:34:29'),
(73, 45, 1, 19, 1, 15, '2025-01-26 12:17:00'),
(74, 43, 1, 26, 13, 14, '2025-01-26 12:17:34'),
(75, 44, 1, 24, 12, 14, '2025-01-26 12:20:24'),
(76, 39, 1, 19, 21, 35, '2025-01-27 10:02:51'),
(77, 9, 3, 13, 15, 15, '2025-02-09 17:34:41'),
(78, 8, 1, 13, 25, 14, '2025-01-27 10:05:13'),
(79, 26, 1, 24, 24, 9, '2025-01-27 10:05:57'),
(80, 27, 1, 16, 16, 5, '2025-01-27 10:06:11'),
(81, 38, 1, 20, 23, 8, '2025-01-27 10:06:28'),
(82, 34, 1, 16, 11, 8, '2025-01-27 10:07:21'),
(83, 35, 3, 26, 12, 12, '2025-02-09 17:34:11'),
(84, 36, 3, 13, 12, 11, '2025-02-09 17:35:23'),
(85, 31, 3, 26, 12, 9, '2025-02-09 17:35:04'),
(86, 30, 1, 13, 11, 7, '2025-01-27 10:09:17'),
(87, 11, 1, 26, 1, 7, '2025-01-27 10:10:40'),
(92, 29, 3, 20, 13, 2, '2025-01-28 11:37:55'),
(93, 47, 1, 34, 1, 15, '2025-01-28 20:04:42'),
(94, 49, 1, 26, 12, 33, '2025-01-28 20:05:06'),
(95, 48, 1, 25, 13, 12, '2025-01-28 20:05:48'),
(96, 56, 1, 34, 11, 4, '2025-01-28 21:53:34'),
(97, 51, 1, 26, 12, 2, '2025-01-28 21:53:50'),
(98, 54, 1, 22, 2, 14, '2025-01-28 21:54:15'),
(99, 50, 1, 26, 1, 5, '2025-01-28 21:54:38'),
(100, 53, 3, 13, 13, 14, '2025-02-09 17:34:51');

-- --------------------------------------------------------

--
-- Table structure for table `talla`
--

CREATE TABLE `talla` (
  `talId` int(11) NOT NULL,
  `talNombre` varchar(30) DEFAULT NULL,
  `talFechaRegis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talla`
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
(25, '39', '2025-01-27 09:59:55');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `admId` int(11) NOT NULL,
  `admUser` varchar(100) DEFAULT NULL,
  `admPassword` varchar(60) DEFAULT NULL,
  `admFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `carId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`admId`, `admUser`, `admPassword`, `admFechaRegis`, `carId`) VALUES
(3, 'admin', '$2y$10$XC4T3j7LdBJtZdrbMRqjq.BcYi6K1011PtNIXiY1NtoCjfpmJ3r6i', '2025-01-04 16:34:54', 2),
(4, 'pruebagerente', '$2y$10$eWM5d7YzzQIY.k9DDsG/kez9DUneGuqqHx19TixxrRdBpk6rcaVEm', '2024-12-30 19:51:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

CREATE TABLE `ventas` (
  `venId` varchar(4) NOT NULL,
  `cliId` int(11) DEFAULT NULL,
  `venFechaRegis` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estVenId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ventas`
--

INSERT INTO `ventas` (`venId`, `cliId`, `venFechaRegis`, `estVenId`) VALUES
('A001', 8, '2025-02-09 17:03:33', 2),
('A002', 9, '2025-02-09 17:22:25', 1),
('A003', 11, '2025-02-09 17:24:51', 1),
('A004', 10, '2025-02-09 19:47:30', 1),
('A005', 10, '2025-02-28 19:30:54', 1),
('A006', 8, '2025-02-25 19:31:20', 1);

--
-- Triggers `ventas`
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
-- Indexes for dumped tables
--

--
-- Indexes for table `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`carId`);

--
-- Indexes for table `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`catId`);

--
-- Indexes for table `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cliId`);

--
-- Indexes for table `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`colId`);

--
-- Indexes for table `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`conId`);

--
-- Indexes for table `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD PRIMARY KEY (`detVenId`),
  ADD KEY `venId` (`venId`),
  ADD KEY `stoId` (`stoId`);

--
-- Indexes for table `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`estId`);

--
-- Indexes for table `estadoventa`
--
ALTER TABLE `estadoventa`
  ADD PRIMARY KEY (`estVenId`);

--
-- Indexes for table `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`marId`);

--
-- Indexes for table `oferta`
--
ALTER TABLE `oferta`
  ADD PRIMARY KEY (`ofeId`),
  ADD KEY `stoId` (`stoId`);

--
-- Indexes for table `portada`
--
ALTER TABLE `portada`
  ADD PRIMARY KEY (`porId`);

--
-- Indexes for table `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`proId`),
  ADD KEY `catId` (`catId`),
  ADD KEY `marId` (`marId`);

--
-- Indexes for table `resenhas`
--
ALTER TABLE `resenhas`
  ADD PRIMARY KEY (`resId`),
  ADD KEY `venId` (`venId`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stoId`),
  ADD KEY `proId` (`proId`),
  ADD KEY `estId` (`estId`),
  ADD KEY `colId` (`colId`),
  ADD KEY `talId` (`talId`);

--
-- Indexes for table `talla`
--
ALTER TABLE `talla`
  ADD PRIMARY KEY (`talId`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`admId`),
  ADD KEY `fk_admi_cargo` (`carId`);

--
-- Indexes for table `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`venId`),
  ADD KEY `cliId` (`cliId`),
  ADD KEY `estVenId` (`estVenId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cargo`
--
ALTER TABLE `cargo`
  MODIFY `carId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categoria`
--
ALTER TABLE `categoria`
  MODIFY `catId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `cliente`
--
ALTER TABLE `cliente`
  MODIFY `cliId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `color`
--
ALTER TABLE `color`
  MODIFY `colId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `contacto`
--
ALTER TABLE `contacto`
  MODIFY `conId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `detalleventa`
--
ALTER TABLE `detalleventa`
  MODIFY `detVenId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `estado`
--
ALTER TABLE `estado`
  MODIFY `estId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `marca`
--
ALTER TABLE `marca`
  MODIFY `marId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `oferta`
--
ALTER TABLE `oferta`
  MODIFY `ofeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `portada`
--
ALTER TABLE `portada`
  MODIFY `porId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `producto`
--
ALTER TABLE `producto`
  MODIFY `proId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `resenhas`
--
ALTER TABLE `resenhas`
  MODIFY `resId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `talla`
--
ALTER TABLE `talla`
  MODIFY `talId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `admId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD CONSTRAINT `detalleventa_ibfk_2` FOREIGN KEY (`stoId`) REFERENCES `stock` (`stoId`),
  ADD CONSTRAINT `fk_detalleventa_venta` FOREIGN KEY (`venId`) REFERENCES `ventas` (`venId`) ON DELETE CASCADE;

--
-- Constraints for table `oferta`
--
ALTER TABLE `oferta`
  ADD CONSTRAINT `oferta_ibfk_1` FOREIGN KEY (`stoId`) REFERENCES `stock` (`stoId`) ON DELETE CASCADE;

--
-- Constraints for table `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`catId`) REFERENCES `categoria` (`catId`),
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`marId`) REFERENCES `marca` (`marId`);

--
-- Constraints for table `resenhas`
--
ALTER TABLE `resenhas`
  ADD CONSTRAINT `fk_resenhas_venta` FOREIGN KEY (`venId`) REFERENCES `ventas` (`venId`) ON DELETE CASCADE;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`proId`) REFERENCES `producto` (`proId`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`estId`) REFERENCES `estado` (`estId`),
  ADD CONSTRAINT `stock_ibfk_3` FOREIGN KEY (`colId`) REFERENCES `color` (`colId`),
  ADD CONSTRAINT `stock_ibfk_4` FOREIGN KEY (`talId`) REFERENCES `talla` (`talId`);

--
-- Constraints for table `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_admi_cargo` FOREIGN KEY (`carId`) REFERENCES `cargo` (`carId`);

--
-- Constraints for table `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliId`) REFERENCES `cliente` (`cliId`) ON DELETE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`estVenId`) REFERENCES `estadoventa` (`estVenId`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
