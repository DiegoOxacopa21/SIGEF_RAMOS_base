# SIGEF-RAMOS

## Que es este proyecto

SIGEF-RAMOS es un sistema de gestion integral para funerarias, construido en PHP 8.3 sin framework, siguiendo el patron MVC. Administra el ciclo completo de un servicio funerario: desde la cotizacion y venta, pasando por el registro de difuntos, hasta la logistica de operaciones (salas, flota, traslados) y la facturacion.

**Objetivo de este repositorio**: Evaluar la calidad tecnica de un sistema legacy real. El codigo fuente es intocable — los tests existen para medir, no para corregir.

## Arquitectura

- **Front controller**: `index.php` enruta via query string `?controller=X&action=Y`
- **4 controladores**: BaseController (render/auth), HomeController (publico), AuthController (login/logout), AdminController (monolitico, 330+ lineas, todas las acciones admin)
- **11 modelos**: User, Client, Catalog, Quotation, Sale, Payment, Operation, Resource, Role, Sede, Difunto — todos dependen de `global $conn` para acceso PDO
- **17 tablas MySQL**: schema en `database/sigef_ramos.sql` con datos demo
- **5 roles**: Administrador, Gerente, Vendedor, Cajero, Operario
- **Sin**: autoloader PSR-4, namespaces, CSRF, sanitizacion de inputs, inyeccion de dependencias

## Que se hizo (testing)

Se construyo una suite de **25 tests** con Pest v2 (PHPUnit 10.6) para evaluar la calidad del proyecto:

| Capa | Cantidad | Que mide |
|------|----------|----------|
| Unit | 10 | Logica de negocio: reglas de negocio en modelos, routing del front controller |
| Integration | 10 | Modelo + base de datos: CRUD, login, transacciones, filtros |
| E2E | 5 | HTTP completo: paginas publicas, redirects, manejo de errores sin DB |

### Stack de testing
- **SQLite in-memory** para unit e integration (schema adaptado en `tests/database/`)
- **Guzzle** para E2E contra `php -S localhost:9999`
- **`tests/TestCase.php`** provee helpers: `createTestDatabase()`, `injectGlobalConnection()`, `setUpSession()`, `destroySession()`

### Hallazgos de calidad documentados por los tests

1. `rowCount()` tras SELECT es MySQL-specific — `User::login()` falla en SQLite (`tests/Integration/UserIntegrationTest.php`)
2. `DATE_ADD(NOW(), INTERVAL 1 DAY)` en Sale model falla fuera de MySQL (`tests/Integration/SaleIntegrationTest.php`)
3. Errores de conexion se vierten directo al HTML — E2E tests lo confirman
4. `password_verify()` recibe null en usuarios inactivos (deprecation PHP 8.4)
5. Todos los modelos dependen de `global $conn` — imposible inyectar dependencias
6. `config.php` ejecuta `session_start()` en cada request incluyendo bootstrap de tests
7. Sin proteccion CSRF, sin sanitizacion de POST, sin autoloader

## Comandos

```bash
# Instalar dependencias
composer install

# Correr todos los tests
vendor/bin/pest --no-coverage

# Correr por capa
vendor/bin/pest tests/Unit --no-coverage
vendor/bin/pest tests/Integration --no-coverage
vendor/bin/pest tests/E2E --no-coverage

# Correr un archivo
vendor/bin/pest tests/Unit/UserTest.php --no-coverage

# E2E: primero levantar servidor
php -S localhost:9999
vendor/bin/pest tests/E2E --no-coverage
```

## Archivos clave

```
index.php                          # Front controller
config/config.php                  # Session + BASE_URL + require db
config/db.php                      # Conexion PDO MySQL
controllers/BaseController.php     # Render + checkAuth + redirect
controllers/AdminController.php    # Todas las acciones admin (monolitico)
models/*.php                       # 11 modelos, todos con global $conn
database/sigef_ramos.sql           # Schema MySQL + seed data
tests/TestCase.php                 # Base class para tests
tests/database/schema.sqlite.sql   # Schema adaptado a SQLite
tests/database/seed.sqlite.sql     # Datos demo para tests
```
