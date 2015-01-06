-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2015 at 06:36 PM
-- Server version: 5.5.32
-- PHP Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "=04:30";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cemento`
--
CREATE DATABASE IF NOT EXISTS `cemento` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `cemento`;

-- --------------------------------------------------------

--
-- Table structure for table `dias`
--

CREATE TABLE IF NOT EXISTS `dias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  `dia` varchar(20) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `dias`
--

INSERT INTO `dias` (`id`, `fecha`, `descripcion`, `dia`, `hora_inicio`, `hora_fin`) VALUES
(10, '0000-00-00', '', 'Mon', '08:00:00', '23:31:00'),
(11, '0000-00-00', '', 'Tue', '08:13:00', '23:32:00'),
(12, '0000-00-00', '', 'Wed', '08:32:00', '23:32:00'),
(13, '0000-00-00', '', 'Thu', '08:33:00', '23:33:00'),
(14, '0000-00-00', '', 'Fri', '08:33:00', '23:33:00'),
(15, '0000-00-00', '', 'Sun', '08:33:00', '23:33:00');

-- --------------------------------------------------------

--
-- Table structure for table `entregas`
--

CREATE TABLE IF NOT EXISTS `entregas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `num_bolsas` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fecha_entrega` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `periodo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `parametros`
--

CREATE TABLE IF NOT EXISTS `parametros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dias` int(11) NOT NULL,
  `max_bolsas` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `parametros`
--

INSERT INTO `parametros` (`id`, `dias`, `max_bolsas`) VALUES
(1, 30, 10);

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `num_bolsas` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `abierto` tinyint(4) NOT NULL DEFAULT '1',
  `cantidad` int(11) NOT NULL,
  `fecha_entrega` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `periodos`
--

CREATE TABLE IF NOT EXISTS `periodos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `periodo` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `bolsas` int(11) NOT NULL,
  `abierto` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `rol` varchar(20) DEFAULT NULL,
  `correo` varchar(50) NOT NULL,
  `cedula` varchar(50) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `status` varchar(15) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nombre`, `apellido`, `rol`, `correo`, `cedula`, `telefono`, `status`, `created`, `modified`, `nombre_completo`, `tipo`) VALUES
(8, 'admin', '$2a$10$6yrSlP6eqNUPV9kMqBims.w6kmwaQfWJRmCLNjWRghPdTkAM/7nKW', 'Admin', 'Admin', 'administrador', 'admin@admin.com', '17584986', '7822814', 'activo', '2014-12-04 20:20:11', '2014-12-12 11:23:16', 'Admin Porras', 'externo');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cedula` varchar(20) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `telefono_opcional` varchar(20) NOT NULL,
  `correo` varchar(30) NOT NULL,
  `pais` varchar(20) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `ciudad` varchar(20) NOT NULL,
  `direccion` text NOT NULL,
  `bolsas_actual` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
