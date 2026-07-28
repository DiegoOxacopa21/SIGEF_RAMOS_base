-- SQLite-compatible schema for SIGEF-RAMOS tests
-- Adapted from MySQL schema: ENUM→TEXT, removed MySQL-specific syntax

CREATE TABLE roles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    descripcion TEXT
);

CREATE TABLE sedes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    direccion TEXT,
    telefono TEXT,
    estado TEXT DEFAULT 'activo' CHECK (estado IN ('activo', 'inactivo'))
);

CREATE TABLE usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_rol INTEGER NOT NULL,
    id_sede INTEGER NOT NULL,
    nombre TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    estado TEXT DEFAULT 'activo' CHECK (estado IN ('activo', 'inactivo')),
    FOREIGN KEY (id_rol) REFERENCES roles(id),
    FOREIGN KEY (id_sede) REFERENCES sedes(id)
);

CREATE TABLE clientes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tipo_documento TEXT DEFAULT 'DNI' CHECK (tipo_documento IN ('DNI', 'CE', 'PASAPORTE', 'RUC')),
    num_documento TEXT NOT NULL UNIQUE,
    nombre TEXT NOT NULL,
    apellidos TEXT,
    telefono TEXT,
    email TEXT,
    direccion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE difuntos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_cliente INTEGER NOT NULL,
    num_documento TEXT,
    nombre TEXT NOT NULL,
    apellidos TEXT NOT NULL,
    fecha_nacimiento DATE,
    fecha_defuncion DATE,
    causa_fallecimiento TEXT,
    lugar_fallecimiento TEXT,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id) ON DELETE CASCADE
);

CREATE TABLE servicios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    descripcion TEXT,
    precio_base REAL NOT NULL,
    estado TEXT DEFAULT 'activo' CHECK (estado IN ('activo', 'inactivo'))
);

CREATE TABLE productos_catalogo (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tipo TEXT NOT NULL CHECK (tipo IN ('ataud', 'arreglo_floral', 'urna', 'otro')),
    nombre TEXT NOT NULL,
    descripcion TEXT,
    precio REAL NOT NULL,
    imagen TEXT,
    estado TEXT DEFAULT 'disponible' CHECK (estado IN ('disponible', 'agotado'))
);

CREATE TABLE cotizaciones (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_cliente INTEGER,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    subtotal REAL NOT NULL,
    igv REAL NOT NULL,
    total REAL NOT NULL,
    estado TEXT DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'aprobada', 'rechazada'))
);

CREATE TABLE detalle_cotizacion (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_cotizacion INTEGER NOT NULL,
    id_producto INTEGER NULL,
    id_servicio INTEGER NULL,
    descripcion TEXT NOT NULL,
    cantidad INTEGER NOT NULL DEFAULT 1,
    precio_unitario REAL NOT NULL,
    subtotal REAL NOT NULL,
    FOREIGN KEY (id_cotizacion) REFERENCES cotizaciones(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos_catalogo(id) ON DELETE SET NULL,
    FOREIGN KEY (id_servicio) REFERENCES servicios(id) ON DELETE SET NULL
);

CREATE TABLE ventas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_cotizacion INTEGER NULL,
    id_cliente INTEGER NOT NULL,
    id_vendedor INTEGER NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    subtotal REAL NOT NULL,
    igv REAL NOT NULL,
    total REAL NOT NULL,
    estado TEXT DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'pagada', 'anulada')),
    FOREIGN KEY (id_cotizacion) REFERENCES cotizaciones(id) ON DELETE SET NULL,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id),
    FOREIGN KEY (id_vendedor) REFERENCES usuarios(id)
);

CREATE TABLE detalle_venta (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_venta INTEGER NOT NULL,
    id_producto INTEGER NULL,
    id_servicio INTEGER NULL,
    descripcion TEXT NOT NULL,
    cantidad INTEGER NOT NULL DEFAULT 1,
    precio_unitario REAL NOT NULL,
    subtotal REAL NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES ventas(id) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos_catalogo(id) ON DELETE SET NULL,
    FOREIGN KEY (id_servicio) REFERENCES servicios(id) ON DELETE SET NULL
);

CREATE TABLE metodos_pago (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    estado TEXT DEFAULT 'activo' CHECK (estado IN ('activo', 'inactivo'))
);

CREATE TABLE pagos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_venta INTEGER NOT NULL,
    id_metodo_pago INTEGER NOT NULL,
    id_cajero INTEGER NOT NULL,
    monto REAL NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    referencia TEXT,
    FOREIGN KEY (id_venta) REFERENCES ventas(id) ON DELETE CASCADE,
    FOREIGN KEY (id_metodo_pago) REFERENCES metodos_pago(id),
    FOREIGN KEY (id_cajero) REFERENCES usuarios(id)
);

CREATE TABLE comprobantes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_venta INTEGER NOT NULL,
    tipo TEXT NOT NULL CHECK (tipo IN ('boleta', 'factura')),
    serie TEXT NOT NULL,
    numero TEXT NOT NULL,
    fecha_emision DATETIME DEFAULT CURRENT_TIMESTAMP,
    total REAL NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES ventas(id) ON DELETE CASCADE
);

CREATE TABLE salas_velacion (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    capacidad INTEGER NOT NULL,
    ubicacion TEXT,
    estado TEXT DEFAULT 'disponible' CHECK (estado IN ('disponible', 'ocupada', 'mantenimiento'))
);

CREATE TABLE flota_movil (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    placa TEXT NOT NULL UNIQUE,
    marca TEXT,
    modelo TEXT,
    tipo TEXT NOT NULL CHECK (tipo IN ('carroza', 'traslado')),
    estado TEXT DEFAULT 'disponible' CHECK (estado IN ('disponible', 'en_servicio', 'mantenimiento'))
);

CREATE TABLE recursos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    tipo TEXT,
    cantidad INTEGER NOT NULL DEFAULT 0,
    estado TEXT DEFAULT 'disponible' CHECK (estado IN ('disponible', 'agotado'))
);

CREATE TABLE reportes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo TEXT NOT NULL,
    fecha_generacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    generado_por INTEGER NOT NULL,
    parametros TEXT,
    FOREIGN KEY (generado_por) REFERENCES usuarios(id)
);

CREATE TABLE operaciones (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_venta INTEGER NOT NULL,
    id_sala INTEGER NULL,
    id_flota INTEGER NULL,
    fecha_programada DATETIME NOT NULL,
    estado TEXT DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'en_proceso', 'finalizado', 'cancelado')),
    observaciones TEXT,
    FOREIGN KEY (id_venta) REFERENCES ventas(id) ON DELETE CASCADE,
    FOREIGN KEY (id_sala) REFERENCES salas_velacion(id) ON DELETE SET NULL,
    FOREIGN KEY (id_flota) REFERENCES flota_movil(id) ON DELETE SET NULL
);
