# SIGEF-RAMOS — Guía para OpenCode

Idioma: **español**. Sistema de gestión funeraria. PHP + MySQL + Bootstrap 5.

## Stack y herramientas
- PHP 7.4+ nativo (sin framework ni composer)
- MySQL vía PDO (`config/db.php`)
- Sin npm, webpack, vite, gulp, docker, ni CI
- Servidor: XAMPP (Apache + MySQL)

## Setup local (XAMPP)
```bash
# 1. Clonar dentro de htdocs
cd C:\xampp\htdocs
git clone <repo-url> sigef-ramosT

# 2. Importar BD desde phpMyAdmin o CLI
mysql -u root < database/sigef_ramos.sql

# 3. Ajustar BASE_URL en config/config.php si cambia la ruta
#    define('BASE_URL', 'http://localhost/sigef-ramosT/');

# 4. Abrir en navegador
http://localhost/sigef-ramosT/
```

## Credenciales por defecto
Todos los usuarios seed tienen contraseña `123456`. Roles: Administrador, Gerente, Vendedor, Cajero, Operario.

## Arquitectura — MVC casero

### Front Controller (`index.php`)
Ruteo por query string: `?controller=X&action=Y` → `controllers/XController.php::Y()`.
Default: `controller=Home&action=index`.

### Controladores
- `HomeController` — rutas públicas (catalogo, proforma, contacto)
- `AuthController` — login/logout
- `AdminController` — todo el panel admin (30+ actions), delega por rol

### Métodos base (`BaseController`)
```php
$this->render('ruta/vista', $data, 'admin');     // renderiza con layout
$this->checkAuth(['Administrador', 'Gerente']);   // redirige a login o 403
$this->redirect('?controller=Admin&action=...');  // redirect absoluto
```

### Modelos
Patrón fijo: `require_once 'config/config.php'` en la parte superior, `global $conn` en constructor.

### Vistas + layouts
- `render($view, $data, $layout)` busca `views/$view.php` y lo envuelve en `views/layouts/$layout.php`
- Layout público: `public.php`. Layout admin con sidebar: `admin.php`
- Vistas admin organizadas por rol: `views/admin/vendedor/`, `cajero/`, `operario/`, `gerente/`

### Convenciones importantes
- Los modelos se cargan con `require_once` manual dentro de cada action (no hay autoload)
- `$_POST['action_type']` discrimina submisiones (ej: `crear_usuario`, `editar_producto`)
- Roles en BD: Administrador (id=1), Gerente, Vendedor, Cajero, Operario
- `checkAuth()` sin argumentos solo exige login. Con array, exige uno de esos roles

## Lo que NO existe
- README.md, .gitignore, composer.json, package.json
- tests, CI, Docker, build steps
- ORM, autoloader, dependency injection
- Migraciones (el SQL es el schema definitivo)

## Archivos de configuración clave
| Archivo | Qué contiene |
|---|---|
| `config/db.php` | credenciales MySQL (`root`/``, `sigef_ramos`) |
| `config/config.php` | `session_start()`, `BASE_URL`, require de db.php |
| `database/sigef_ramos.sql` | schema + datos semilla |
| `assets/img/catalog/` | imágenes subidas desde el admin |

## Convenciones de estilo
- PHP sin namespaces, todo en global
- Nombres de tabla en plural español: `usuarios`, `clientes`, `difuntos`, `ventas`
- IDs auto-incrementales, FK con prefijo `id_`: `id_rol`, `id_sede`, `id_cliente`
- CSS oscuro/marrón temático funerario en `assets/css/style.css`
