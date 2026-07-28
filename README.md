# SIGEF-RAMOS

Sistema Integral de Gestion de Funerarias — Ramos

Sistema web para la administracion de servicios funerarios. Gestiona cotizaciones, ventas, clientes, difuntos, operaciones logisticas (salas de velacion, flota, traslados), pagos y comprobantes.

## Requisitos

- PHP 8.3 o superior
- MySQL 5.7+ o MariaDB 10.3+
- Composer
- XAMPP, WAMP o similar (para Apache + MySQL)

## Instalacion

### 1. Clonar o copiar el proyecto

Copiar la carpeta `sigef-ramosT` dentro de `htdocs` (XAMPP) o `www` (WAMP).

### 2. Crear la base de datos

Abrir phpMyAdmin y ejecutar el script SQL:

```
database/sigef_ramos.sql
```

Esto crea la base de datos `sigef_ramos` con 17 tablas y datos demo.

### 3. Configurar conexion a BD

Editar `config/db.php` si tus credenciales de MySQL son distintas:

```php
$host = "localhost";
$db_name = "sigef_ramos";
$username = "root";
$password = "";
```

### 4. Configurar URL base

Editar `config/config.php` — cambiar `BASE_URL` segun tu ruta de instalacion:

```php
define('BASE_URL', 'http://localhost/sigef-ramosT/');
```

### 5. Iniciar el servidor

Iniciar Apache y MySQL desde XAMPP. Luego acceder a:

```
http://localhost/sigef-ramosT/
```

## Usuarios demo

| Email | Password | Rol |
|-------|----------|-----|
| admin@ramos.com | 123456 | Administrador |
| gerente@ramos.com | 123456 | Gerente |
| vendedor@ramos.com | 123456 | Vendedor |
| cajero@ramos.com | 123456 | Cajero |
| operario@ramos.com | 123456 | Operario |

Acceso al panel: `?controller=Auth&action=login`

## Instalar tests (opcional)

Los tests evaluan la calidad tecnica del proyecto. No son necesarios para usar el sistema.

```bash
# Instalar dependencias de testing
composer install

# Correr los 25 tests
vendor/bin/pest --no-coverage
```

Para tests E2E, levantar el servidor PHP integrado en otra terminal:

```bash
php -S localhost:9999
vendor/bin/pest tests/E2E --no-coverage
```

## Estructura del proyecto

```
index.php              Front controller — enruta ?controller=X&action=Y
config/
  config.php           Sesion + BASE_URL
  db.php               Conexion PDO MySQL
controllers/
  BaseController.php   Render de vistas + autenticacion
  HomeController.php   Paginas publicas (inicio, catalogo, proforma, contacto)
  AuthController.php   Login y logout
  AdminController.php  Todas las acciones del panel admin
models/
  User.php             Usuarios, login, CRUD
  Client.php           Clientes / deudos
  Difunto.php          Registro de difuntos
  Catalog.php          Productos y servicios
  Quotation.php        Cotizaciones
  Sale.php             Ventas (directa y desde cotizacion)
  Payment.php          Pagos y comprobantes
  Operation.php        Operaciones logisticas
  Resource.php         Salas, flota, recursos
  Role.php             Roles del sistema
  Sede.php             Sedes
views/
  layouts/             Plantillas (admin.php, public.php)
  public/              Paginas publicas
  admin/               Paneles del admin por rol
database/
  sigef_ramos.sql      Schema completo + datos demo
tests/                 Suite de testing (Pest v2)
```
