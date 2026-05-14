CREATE DATABASE HotelVillaMarina;
USE HotelVillaMarina;

-- --------------------------------------------------------
-- 1. Tabla: estados
-- --------------------------------------------------------
CREATE TABLE `estados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) CHARSET=utf8mb4;

INSERT INTO `estados` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Disponible', 'Elemento disponible para uso o reserva'),
(2, 'Ocupado', 'Actualmente en uso o reservado'),
(3, 'Pendiente', 'Proceso pendiente de confirmación'),
(4, 'Cancelado', 'Registro cancelado'),
(5, 'Activo', 'Usuario activo'),
(6, 'Inactivo', 'Usuario inactivo'),
(7, 'Mantenimiento', 'Habitación en mantenimiento'),
(8, 'Finalizado', 'Reserva completada');

-- --------------------------------------------------------
-- 2. Tabla: roles
-- --------------------------------------------------------
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) CHARSET=utf8mb4;

INSERT INTO `roles` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Administrador', 'Acceso total al sistema'),
(2, 'Cliente', 'Acceso para realizar reservas');

-- --------------------------------------------------------
-- 3. Tabla: tipos_documento
-- --------------------------------------------------------
CREATE TABLE `tipos_documento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) CHARSET=utf8mb4;

INSERT INTO `tipos_documento` (`id`, `tipo`) VALUES
(1, 'Cédula de Ciudadanía'),
(2, 'Cédula de Extranjería'),
(3, 'Pasaporte');

-- --------------------------------------------------------
-- 4. Tabla: categorias
-- --------------------------------------------------------
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) CHARSET=utf8mb4;

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Estándar', 'Habitación cómoda y funcional'),
(2, 'Superior', 'Mayor espacio y comodidades'),
(3, 'Deluxe', 'Lujo y acabados premium'),
(4, 'Familiar', 'Ideal para grupos o familias');

-- --------------------------------------------------------
-- 5. Tabla: metodos_pago
-- --------------------------------------------------------
CREATE TABLE `metodos_pago` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) CHARSET=utf8mb4;

INSERT INTO `metodos_pago` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Nequi', 'Billetera digital Nequi'),
(2, 'Daviplata', 'Billetera digital Daviplata'),
(3, 'Bancolombia', 'Transferencia Bancolombia');

-- --------------------------------------------------------
-- 6. Tabla: usuarios
-- --------------------------------------------------------
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `tipo_documento_id` int(11) DEFAULT NULL,
  `documento` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_rol` int(11) DEFAULT 2,
  `estado` int(11) DEFAULT 5,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `documento` (`documento`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`tipo_documento_id`) REFERENCES `tipos_documento` (`id`),
  CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`),
  CONSTRAINT `usuarios_ibfk_3` FOREIGN KEY (`estado`) REFERENCES `estados` (`id`)
) CHARSET=utf8mb4;

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `telefono`, `tipo_documento_id`, `documento`, `password`, `id_rol`, `estado`) VALUES
(1, 'Kevin', 'Donoso', 'kalejodv@gmail.com', '3184050242', 1, '1105679576', '$2y$10$OqV7YwPc5JTudCtx4hOKEe5dXyHOEzZ3nM69hUuQc9c4nARD6W9Lq', 1, 5);

-- --------------------------------------------------------
-- 7. Tabla: habitaciones
-- --------------------------------------------------------
CREATE TABLE `habitaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num_habitacion` varchar(20) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `num_camas` int(11) NOT NULL,
  `max_personas` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `num_habitacion` (`num_habitacion`),
  CONSTRAINT `habitaciones_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`),
  CONSTRAINT `habitaciones_ibfk_2` FOREIGN KEY (`estado`) REFERENCES `estados` (`id`)
) CHARSET=utf8mb4;

INSERT INTO `habitaciones` (`id`, `num_habitacion`, `id_categoria`, `num_camas`, `max_personas`, `descripcion`, `precio`, `estado`) VALUES
(1, '101', 1, 1, 2, 'Habitación cómoda con vista al jardín', 180000.00, 1),
(2, '201', 2, 1, 2, 'Cama king con balcón y vista al mar', 320000.00, 1),
(3, '301', 3, 1, 4, 'Suite de lujo con jacuzzi y terraza', 650000.00, 1),
(4, '401', 4, 2, 6, 'Habitación familiar amplia', 420000.00, 1);

-- --------------------------------------------------------
-- 8. Tabla: reservas
-- --------------------------------------------------------
CREATE TABLE `reservas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_habitacion` int(11) DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_final` date NOT NULL,
  `num_personas` int(11) NOT NULL,
  `estado` int(11) DEFAULT 3,
  `precio` decimal(10,2) DEFAULT NULL,
  `id_metodo_pago` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_habitacion`) REFERENCES `habitaciones` (`id`),
  CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`estado`) REFERENCES `estados` (`id`),
  CONSTRAINT `reservas_ibfk_4` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_pago` (`id`)
) CHARSET=utf8mb4;

