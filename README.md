# SIGEF-RAMOS — Sistema de Gestión Funeraria

Sistema web para la administración y gestión integral de una funeraria. Desarrollado en **PHP nativo + MySQL** con **Bootstrap 5**.

## Funcionalidades

- **Catálogo público** — Vista de productos (ataúdes, arreglos florales, urnas) y servicios adicionales
- **Proforma online** — Simulador de presupuesto para clientes
- **Panel administrador** — Gestión completa con roles:
  - **Administrador** — Usuarios, catálogo, y acceso a todos los módulos
  - **Gerente** — Reportes de ventas, dashboard general
  - **Vendedor** — Clientes, difuntos, cotizaciones, ventas
  - **Cajero** — Pagos, comprobantes
  - **Operario** — Operaciones logísticas, recursos, flota, salas de velación

## Requisitos

- [XAMPP](https://www.apachefriends.org/) (Apache + PHP 7.4+ + MySQL)
- Navegador web moderno

## Instalación

### 1. Clonar el repositorio

```bash
cd C:\xampp\htdocs
git clone https://github.com/DiegoOxacopa21/SIGEF_RAMOS_base.git sigef-ramosT
```

### 2. Importar la base de datos

Opción A — Desde phpMyAdmin:
1. Abrir `http://localhost/phpmyadmin`
2. Crear base de datos `sigef_ramos` (utf8_general_ci)
3. Importar `database/sigef_ramos.sql`

Opción B — Desde línea de comandos:
```bash
mysql -u root < C:\xampp\htdocs\sigef-ramosT\database\sigef_ramos.sql
```

### 3. Configurar

La configuración por defecto ya apunta a `http://localhost/sigef-ramosT/`. Si cambias la carpeta, editar:

```
config/config.php  →  define('BASE_URL', 'http://localhost/sigef-ramosT/');
config/db.php      →  credenciales MySQL (usuario/contraseña)
```

### 4. Abrir en el navegador

```
http://localhost/sigef-ramosT/
```

## Usuarios de prueba

Todos los usuarios tienen contraseña **`123456`**.

| Correo | Rol |
|---|---|
| admin@admin.com | Administrador |
| gerente@funeraria.com | Gerente |
| vendedor@funeraria.com | Vendedor |
| cajero@funeraria.com | Cajero |
| operario@funeraria.com | Operario |

## Estructura del proyecto

```
├── assets/
│   ├── css/          →  Estilos personalizados
│   ├── img/catalog/  →  Imágenes de productos
│   └── js/           →  Scripts (proforma)
├── config/
│   ├── config.php    →  Configuración general (BASE_URL, sesión)
│   └── db.php        →  Conexión MySQL con PDO
├── controllers/      →  Controladores MVC
├── database/         →  Script SQL (schema + datos)
├── models/           →  Modelos MVC
├── views/
│   ├── admin/        →  Vistas del panel (por rol)
│   ├── layouts/      →  Layouts (admin.php, public.php)
│   └── public/       →  Vistas públicas
├── index.php         →  Front controller
├── AGENTS.md         →  Guía para OpenCode/AI
└── README.md         →  Este archivo
```

## Tecnologías

- **PHP 7.4+** — Sin framework, MVC casero
- **MySQL** — Base de datos relacional
- **Bootstrap 5** — Interfaz responsive
- **Bootstrap Icons** — Iconografía
- **PDO** — Conexión segura a base de datos
