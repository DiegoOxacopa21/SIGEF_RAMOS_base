-- Minimal seed data for tests

-- Roles
INSERT INTO roles (id, nombre, descripcion) VALUES
(1, 'Administrador', 'Control total del sistema'),
(2, 'Gerente', 'Supervision y reportes'),
(3, 'Vendedor', 'Ventas, cotizaciones y clientes'),
(4, 'Cajero', 'Pagos, caja y comprobantes'),
(5, 'Operario', 'Logistica, salas y flota');

-- Sedes
INSERT INTO sedes (id, nombre, direccion, telefono, estado) VALUES
(1, 'Sede Central', 'Av. Simon Bolivar 509', '01-1234567', 'activo'),
(2, 'Sede Norte', 'Av. Simon Bolivar 82', '01-7654321', 'activo');

-- Usuarios (password for all: 123456)
INSERT INTO usuarios (id, id_rol, id_sede, nombre, email, password, estado) VALUES
(1, 1, 1, 'Admin', 'admin@ramos.com', '$2y$10$OoKW8M0XrDPqm/lqYayF0.GrqAMdwOxvddCFsULGHsgbEE5UAdVvi', 'activo'),
(2, 2, 1, 'Gerente General', 'gerente@ramos.com', '$2y$10$OoKW8M0XrDPqm/lqYayF0.GrqAMdwOxvddCFsULGHsgbEE5UAdVvi', 'activo'),
(3, 3, 1, 'Vendedor Principal', 'vendedor@ramos.com', '$2y$10$OoKW8M0XrDPqm/lqYayF0.GrqAMdwOxvddCFsULGHsgbEE5UAdVvi', 'activo'),
(4, 4, 1, 'Cajero Central', 'cajero@ramos.com', '$2y$10$OoKW8M0XrDPqm/lqYayF0.GrqAMdwOxvddCFsULGHsgbEE5UAdVvi', 'activo'),
(5, 5, 1, 'Operario', 'operario@ramos.com', '$2y$10$OoKW8M0XrDPqm/lqYayF0.GrqAMdwOxvddCFsULGHsgbEE5UAdVvi', 'activo');

-- Clientes
INSERT INTO clientes (id, tipo_documento, num_documento, nombre, apellidos, telefono, email, direccion) VALUES
(1, 'DNI', '12345678', 'Juan', 'Perez', '987654321', 'juan.perez@example.com', 'Av. Siempre Viva 123'),
(2, 'DNI', '87654321', 'Maria', 'Gomez', '912345678', 'maria.gomez@example.com', 'Calle Falsa 456');

-- Productos Catalogo
INSERT INTO productos_catalogo (id, tipo, nombre, descripcion, precio, imagen, estado) VALUES
(1, 'ataud', 'Ataud Clasico Madera', 'Ataud de madera cedro.', 1500.00, 'ataud_clasico.png', 'disponible'),
(2, 'ataud', 'Ataud Premium Metalico', 'Ataud metalico reforzado.', 2500.00, 'ataud_premium.png', 'disponible'),
(3, 'arreglo_floral', 'Corona de Rosas', 'Corona funebre grande.', 300.00, 'corona_rosas.png', 'disponible'),
(4, 'urna', 'Urna de Marmol', 'Urna cineraria.', 450.00, 'urna_marmol.png', 'disponible');

-- Servicios
INSERT INTO servicios (id, nombre, descripcion, precio_base, estado) VALUES
(1, 'Sala de Velacion Basica', 'Uso de sala por 24h.', 800.00, 'activo'),
(2, 'Sala de Velacion VIP', 'Sala VIP con cafeteria.', 1200.00, 'activo'),
(3, 'Carroza Funebre', 'Traslado al cementerio.', 400.00, 'activo');

-- Metodos de pago
INSERT INTO metodos_pago (id, nombre, estado) VALUES
(1, 'Efectivo', 'activo'),
(2, 'Tarjeta de Credito', 'activo'),
(3, 'Transferencia Bancaria', 'activo');

-- Salas de velacion
INSERT INTO salas_velacion (id, nombre, capacidad, ubicacion, estado) VALUES
(1, 'Sala Paz', 50, 'Sede Central - Piso 1', 'disponible'),
(2, 'Sala Esperanza', 100, 'Sede Central - Piso 1', 'disponible');

-- Flota
INSERT INTO flota_movil (id, placa, marca, modelo, tipo, estado) VALUES
(1, 'CAR-001', 'Mercedes-Benz', 'Clase E', 'carroza', 'disponible'),
(2, 'TRA-001', 'Hyundai', 'H1', 'traslado', 'disponible');

-- Recursos
INSERT INTO recursos (id, nombre, tipo, cantidad, estado) VALUES
(1, 'Sillas plegables', 'Mobiliario', 100, 'disponible'),
(2, 'Equipos de sonido', 'Audiovisual', 3, 'disponible');

-- Cotizaciones demo
INSERT INTO cotizaciones (id_cliente, subtotal, igv, total, estado) VALUES
(1, 1500.00, 270.00, 1770.00, 'aprobada'),
(2, 2500.00, 450.00, 2950.00, 'pendiente');

-- Detalle cotizacion
INSERT INTO detalle_cotizacion (id_cotizacion, id_producto, descripcion, cantidad, precio_unitario, subtotal) VALUES
(1, 1, 'Ataud Clasico Madera', 1, 1500.00, 1500.00);

-- Ventas demo
INSERT INTO ventas (id_cotizacion, id_cliente, id_vendedor, subtotal, igv, total, estado) VALUES
(1, 1, 3, 1500.00, 270.00, 1770.00, 'pendiente');

-- Detalle venta
INSERT INTO detalle_venta (id_venta, id_producto, descripcion, cantidad, precio_unitario, subtotal) VALUES
(1, 1, 'Ataud Clasico Madera', 1, 1500.00, 1500.00);

-- Operaciones demo
INSERT INTO operaciones (id_venta, fecha_programada, estado) VALUES
(1, datetime('now', '+1 day'), 'pendiente');
