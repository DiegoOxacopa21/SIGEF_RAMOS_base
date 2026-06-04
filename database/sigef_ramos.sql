-- Base de datos para SIGEF-RAMOS
DROP DATABASE IF EXISTS sigef_ramos;
CREATE DATABASE IF NOT EXISTS sigef_ramos DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE sigef_ramos;
USE sigef_ramos;

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT
);

CREATE TABLE sedes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion TEXT,
    telefono VARCHAR(20),
    estado ENUM('activo', 'inactivo') DEFAULT 'activo'
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_rol INT NOT NULL,
    id_sede INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    FOREIGN KEY (id_rol) REFERENCES roles(id) ON DELETE RESTRICT,
    FOREIGN KEY (id_sede) REFERENCES sedes(id) ON DELETE RESTRICT
);

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_documento ENUM('DNI', 'CE', 'PASAPORTE', 'RUC') DEFAULT 'DNI',
    num_documento VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100),
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE difuntos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    num_documento VARCHAR(20),
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    fecha_defuncion DATE,
    causa_fallecimiento TEXT,
    lugar_fallecimiento TEXT,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id) ON DELETE CASCADE
);

CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio_base DECIMAL(10,2) NOT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo'
);

CREATE TABLE productos_catalogo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('ataud', 'arreglo_floral', 'urna', 'otro') NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(255),
    estado ENUM('disponible', 'agotado') DEFAULT 'disponible'
);

CREATE TABLE cotizaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(10,2) NOT NULL,
    igv DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'aprobada', 'rechazada') DEFAULT 'pendiente'
);

CREATE TABLE detalle_cotizacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cotizacion INT NOT NULL,
    id_producto INT NULL,
    id_servicio INT NULL,
    descripcion VARCHAR(255) NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_cotizacion) REFERENCES cotizaciones(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos_catalogo(id) ON DELETE SET NULL,
    FOREIGN KEY (id_servicio) REFERENCES servicios(id) ON DELETE SET NULL
);

CREATE TABLE ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_cotizacion INT NULL,
    id_cliente INT NOT NULL,
    id_vendedor INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(10,2) NOT NULL,
    igv DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'pagada', 'anulada') DEFAULT 'pendiente',
    FOREIGN KEY (id_cotizacion) REFERENCES cotizaciones(id) ON DELETE SET NULL,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id) ON DELETE RESTRICT,
    FOREIGN KEY (id_vendedor) REFERENCES usuarios(id) ON DELETE RESTRICT
);

CREATE TABLE detalle_venta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT NOT NULL,
    id_producto INT NULL,
    id_servicio INT NULL,
    descripcion VARCHAR(255) NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES ventas(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos_catalogo(id) ON DELETE SET NULL,
    FOREIGN KEY (id_servicio) REFERENCES servicios(id) ON DELETE SET NULL
);

CREATE TABLE metodos_pago (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo'
);

CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT NOT NULL,
    id_metodo_pago INT NOT NULL,
    id_cajero INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    referencia VARCHAR(100),
    FOREIGN KEY (id_venta) REFERENCES ventas(id) ON DELETE CASCADE,
    FOREIGN KEY (id_metodo_pago) REFERENCES metodos_pago(id) ON DELETE RESTRICT,
    FOREIGN KEY (id_cajero) REFERENCES usuarios(id) ON DELETE RESTRICT
);

CREATE TABLE comprobantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT NOT NULL,
    tipo ENUM('boleta', 'factura') NOT NULL,
    serie VARCHAR(10) NOT NULL,
    numero VARCHAR(20) NOT NULL,
    fecha_emision DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES ventas(id) ON DELETE CASCADE
);

CREATE TABLE salas_velacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    capacidad INT NOT NULL,
    ubicacion TEXT,
    estado ENUM('disponible', 'ocupada', 'mantenimiento') DEFAULT 'disponible'
);

CREATE TABLE flota_movil (
    id INT AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(15) NOT NULL UNIQUE,
    marca VARCHAR(50),
    modelo VARCHAR(50),
    tipo ENUM('carroza', 'traslado') NOT NULL,
    estado ENUM('disponible', 'en_servicio', 'mantenimiento') DEFAULT 'disponible'
);

CREATE TABLE recursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo VARCHAR(50),
    cantidad INT NOT NULL DEFAULT 0,
    estado ENUM('disponible', 'agotado') DEFAULT 'disponible'
);

CREATE TABLE reportes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    fecha_generacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    generado_por INT NOT NULL,
    parametros TEXT,
    FOREIGN KEY (generado_por) REFERENCES usuarios(id) ON DELETE RESTRICT
);

CREATE TABLE operaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT NOT NULL,
    id_sala INT NULL,
    id_flota INT NULL,
    fecha_programada DATETIME NOT NULL,
    estado ENUM('pendiente', 'en_proceso', 'finalizado', 'cancelado') DEFAULT 'pendiente',
    observaciones TEXT,
    FOREIGN KEY (id_venta) REFERENCES ventas(id) ON DELETE CASCADE,
    FOREIGN KEY (id_sala) REFERENCES salas_velacion(id) ON DELETE SET NULL,
    FOREIGN KEY (id_flota) REFERENCES flota_movil(id) ON DELETE SET NULL
);

-- INSERCIÓN DE DATOS DEMO

-- Roles
INSERT INTO roles (nombre, descripcion) VALUES 
('Administrador', 'Control total del sistema'),
('Gerente', 'Supervisión y reportes'),
('Vendedor', 'Ventas, cotizaciones y clientes'),
('Cajero', 'Pagos, caja y comprobantes'),
('Operario', 'Logística, salas y flota');

-- Sedes
INSERT INTO sedes (nombre, direccion, telefono) VALUES
('Av. Simón Bolívar 509', 'Distrito: Ilo, Provincia: Ilo, Región: Moquegua', '01-1234567'),
('Avenida Simón Bolívar Nro. 82', 'Sector Urb. El Huayco / Urb. El Gallito, Ciudad: Moquegua, Provincia: Mariscal Nieto, Departamento: Moquegua', '01-7654321');

-- Usuarios
INSERT INTO usuarios (id_rol, id_sede, nombre, email, password) VALUES 
(1, 1, 'Admin', 'admin@ramos.com', '$2y$10$OoKW8M0XrDPqm/lqYayF0.GrqAMdwOxvddCFsULGHsgbEE5UAdVvi'), -- 123456
(2, 1, 'Gerente General', 'gerente@ramos.com', '$2y$10$OoKW8M0XrDPqm/lqYayF0.GrqAMdwOxvddCFsULGHsgbEE5UAdVvi'),
(3, 1, 'Vendedor Principal', 'vendedor@ramos.com', '$2y$10$OoKW8M0XrDPqm/lqYayF0.GrqAMdwOxvddCFsULGHsgbEE5UAdVvi'),
(4, 1, 'Cajero Central', 'cajero@ramos.com', '$2y$10$OoKW8M0XrDPqm/lqYayF0.GrqAMdwOxvddCFsULGHsgbEE5UAdVvi'),
(5, 1, 'Operario Logístico', 'operario@ramos.com', '$2y$10$OoKW8M0XrDPqm/lqYayF0.GrqAMdwOxvddCFsULGHsgbEE5UAdVvi'),
(3, 2, 'Vendedor Norte', 'vendedor.norte@ramos.com', '$2y$10$OoKW8M0XrDPqm/lqYayF0.GrqAMdwOxvddCFsULGHsgbEE5UAdVvi');

-- Clientes Demo
INSERT INTO clientes (tipo_documento, num_documento, nombre, apellidos, telefono, email, direccion) VALUES 
('DNI', '12345678', 'Juan', 'Perez', '987654321', 'juan.perez@example.com', 'Av. Siempre Viva 123'),
('DNI', '87654321', 'Maria', 'Gomez', '912345678', 'maria.gomez@example.com', 'Calle Falsa 456');

-- Productos Catálogo
INSERT INTO productos_catalogo (tipo, nombre, descripcion, precio, imagen) VALUES 
('ataud', 'Ataúd Clásico Madera', 'Ataúd de madera cedro con acabados clásicos.', 1500.00, 'ataud_clasico.png'),
('ataud', 'Ataúd Premium Metálico', 'Ataúd metálico reforzado con acabados de primera.', 2500.00, 'ataud_premium.png'),
('ataud', 'Ataúd Ecológico', 'Ataúd fabricado con materiales biodegradables.', 1200.00, 'ataud_ecologico.png'),
('arreglo_floral', 'Corona de Rosas y Lirios', 'Corona fúnebre grande con rosas blancas y lirios.', 300.00, 'corona_rosas.png'),
('arreglo_floral', 'Lágrima Floral', 'Arreglo en forma de lágrima para velatorio.', 150.00, 'lagrima_floral.png'),
('urna', 'Urna de Mármol', 'Urna cineraria de mármol blanco.', 450.00, 'urna_marmol.png'),
('otro', 'Libro de Condolencias', 'Libro empastado para firmas.', 50.00, 'libro_condolencias.png');

-- Servicios Adicionales
INSERT INTO servicios (nombre, descripcion, precio_base) VALUES 
('Sala de Velación Básica', 'Uso de sala de velación por 24 horas.', 800.00),
('Sala de Velación VIP', 'Uso de sala VIP con cafetería por 24 horas.', 1200.00),
('Carroza Fúnebre', 'Traslado de la sala de velación al cementerio.', 400.00),
('Servicio de Traslado interprovincial', 'Traslado en van equipada fuera de la ciudad.', 1500.00),
('Trámites Legales', 'Gestión de certificado de defunción y permisos.', 250.00);

-- Metodos de Pago
INSERT INTO metodos_pago (nombre) VALUES 
('Efectivo'), ('Tarjeta de Crédito'), ('Tarjeta de Débito'), ('Transferencia Bancaria');

-- Salas de Velación
INSERT INTO salas_velacion (nombre, capacidad, ubicacion) VALUES 
('Sala Paz', 50, 'Sede Central - Piso 1'),
('Sala Esperanza', 100, 'Sede Central - Piso 1'),
('Sala VIP Paraíso', 80, 'Sede Central - Piso 2');

-- Flota Movil
INSERT INTO flota_movil (placa, marca, modelo, tipo) VALUES 
('CAR-001', 'Mercedes-Benz', 'Clase E', 'carroza'),
('CAR-002', 'Lincoln', 'Town Car', 'carroza'),
('TRA-001', 'Hyundai', 'H1', 'traslado');

-- Recursos
INSERT INTO recursos (nombre, tipo, cantidad) VALUES 
('Sillas plegables extras', 'Mobiliario', 100),
('Toldos 3x3', 'Mobiliario', 5),
('Equipos de sonido portátiles', 'Audiovisual', 3);

-- Demo Cotizaciones y Ventas para que el Dashboard no este vacio
INSERT INTO cotizaciones (id_cliente, subtotal, igv, total, estado) VALUES
(1, 1500.00, 270.00, 1770.00, 'aprobada'),
(2, 2500.00, 450.00, 2950.00, 'pendiente');

INSERT INTO ventas (id_cotizacion, id_cliente, id_vendedor, subtotal, igv, total, estado) VALUES
(1, 1, 3, 1500.00, 270.00, 1770.00, 'pendiente');

INSERT INTO detalle_venta (id_venta, id_producto, id_servicio, descripcion, precio_unitario, subtotal) VALUES
(1, 1, NULL, 'Ataúd Clásico Madera', 1500.00, 1500.00);

-- Operaciones
INSERT INTO operaciones (id_venta, id_sala, id_flota, fecha_programada, estado) VALUES
(1, 1, 1, DATE_ADD(NOW(), INTERVAL 1 DAY), 'pendiente');
