-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 05-11-2024 a las 15:46:45
-- Versión del servidor: 10.11.9-MariaDB
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u290946630_AtVzW`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_paradas_imagenes`
--

CREATE TABLE `wp_paradas_imagenes` (
  `imagen_id` bigint(20) UNSIGNED NOT NULL,
  `parada_id` bigint(20) UNSIGNED NOT NULL,
  `nombre_imagen` varchar(255) DEFAULT NULL,
  `archivo_imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `wp_paradas_imagenes`
--
ALTER TABLE `wp_paradas_imagenes`
  ADD PRIMARY KEY (`imagen_id`),
  ADD KEY `parada_id` (`parada_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `wp_paradas_imagenes`
--
ALTER TABLE `wp_paradas_imagenes`
  MODIFY `imagen_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `wp_paradas_imagenes`
--
ALTER TABLE `wp_paradas_imagenes`
  ADD CONSTRAINT `wp_paradas_imagenes_ibfk_1` FOREIGN KEY (`parada_id`) REFERENCES `wp_paradas` (`parada_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
