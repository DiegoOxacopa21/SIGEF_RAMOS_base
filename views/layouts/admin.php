<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'SIGEF RAMOS - Panel' ?></title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/style.css" rel="stylesheet">
    <style>
        body { font-size: 0.9rem; }
        .sidebar-menu { min-height: calc(100vh - 56px); }
        .bg-custom-dark { background-color: #2c3e50; }
        .nav-link.text-white:hover { background-color: rgba(255,255,255,0.1); }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-custom-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>?controller=Admin&action=dashboard">
                <i class="bi bi-flower1"></i> SIGEF-RAMOS <span class="badge bg-secondary ms-1 fs-6"><?= $_SESSION['user_role_name'] ?? '' ?></span>
            </a>
            <div class="d-flex ms-auto align-items-center">
                <div class="dropdown text-end">
                    <a href="#" class="d-block link-light text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-5 me-1"></i> <?= $_SESSION['user_name'] ?? 'Usuario' ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person-gear"></i> Mi Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>?controller=Auth&action=logout"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Menú Lateral Moderno -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse sidebar-menu">
                <div class="position-sticky pt-3">
                    
                    <ul class="nav flex-column mb-auto">
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>?controller=Admin&action=dashboard" class="nav-link text-white <?= (!isset($_GET['action']) || $_GET['action'] == 'dashboard') ? 'active bg-primary' : '' ?>">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        
                        <?php if(in_array($_SESSION['user_role_name'] ?? '', ['Administrador', 'Vendedor', 'Gerente'])): ?>
                        <div class="sidebar-heading mt-3 text-muted px-3 text-uppercase fs-7">Ventas</div>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>?controller=Admin&action=cotizaciones" class="nav-link text-white <?= (isset($_GET['action']) && $_GET['action'] == 'cotizaciones') ? 'active bg-primary' : '' ?>">
                                <i class="bi bi-file-earmark-text me-2"></i> Cotizaciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>?controller=Admin&action=ventas" class="nav-link text-white <?= (isset($_GET['action']) && $_GET['action'] == 'ventas') ? 'active bg-primary' : '' ?>">
                                <i class="bi bi-cart-check me-2"></i> Ventas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>?controller=Admin&action=clientes" class="nav-link text-white <?= (isset($_GET['action']) && $_GET['action'] == 'clientes') ? 'active bg-primary' : '' ?>">
                                <i class="bi bi-people me-2"></i> Clientes / Deudos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>?controller=Admin&action=difuntos" class="nav-link text-white <?= (isset($_GET['action']) && $_GET['action'] == 'difuntos') ? 'active bg-primary' : '' ?>">
                                <i class="bi bi-person-badge me-2"></i> Registro Difuntos
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if(in_array($_SESSION['user_role_name'] ?? '', ['Administrador', 'Operario', 'Gerente'])): ?>
                        <div class="sidebar-heading mt-3 text-muted px-3 text-uppercase fs-7">Operaciones</div>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>?controller=Admin&action=operaciones" class="nav-link text-white <?= (isset($_GET['action']) && $_GET['action'] == 'operaciones') ? 'active bg-primary' : '' ?>">
                                <i class="bi bi-gear me-2"></i> Órdenes Logísticas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>?controller=Admin&action=recursos" class="nav-link text-white <?= (isset($_GET['action']) && $_GET['action'] == 'recursos') ? 'active bg-primary' : '' ?>">
                                <i class="bi bi-truck me-2"></i> Salas y Flota
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if(in_array($_SESSION['user_role_name'] ?? '', ['Administrador', 'Cajero', 'Gerente'])): ?>
                        <div class="sidebar-heading mt-3 text-muted px-3 text-uppercase fs-7">Caja y Facturación</div>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>?controller=Admin&action=pagos" class="nav-link text-white <?= (isset($_GET['action']) && $_GET['action'] == 'pagos') ? 'active bg-primary' : '' ?>">
                                <i class="bi bi-cash-coin me-2"></i> Registro de Cobros
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>?controller=Admin&action=comprobantes" class="nav-link text-white <?= (isset($_GET['action']) && $_GET['action'] == 'comprobantes') ? 'active bg-primary' : '' ?>">
                                <i class="bi bi-receipt me-2"></i> Comprobantes
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if(in_array($_SESSION['user_role_name'] ?? '', ['Administrador', 'Gerente'])): ?>
                        <div class="sidebar-heading mt-3 text-muted px-3 text-uppercase fs-7">Reportes</div>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>?controller=Admin&action=reportes" class="nav-link text-white <?= (isset($_GET['action']) && $_GET['action'] == 'reportes') ? 'active bg-primary' : '' ?>">
                                <i class="bi bi-bar-chart-line me-2"></i> Reportes Consolid.
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if(in_array($_SESSION['user_role_name'] ?? '', ['Administrador'])): ?>
                        <div class="sidebar-heading mt-3 text-muted px-3 text-uppercase fs-7">Sistema</div>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>?controller=Admin&action=usuarios" class="nav-link text-white <?= (isset($_GET['action']) && $_GET['action'] == 'usuarios') ? 'active bg-primary' : '' ?>">
                                <i class="bi bi-person-lines-fill me-2"></i> Gestión de Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>?controller=Admin&action=catalogo" class="nav-link text-white <?= (isset($_GET['action']) && $_GET['action'] == 'catalogo') ? 'active bg-primary' : '' ?>">
                                <i class="bi bi-bag-plus me-2"></i> Catálogo de Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>?controller=Admin&action=modulo&m=Configuracion" class="nav-link text-white">
                                <i class="bi bi-gear me-2"></i> Configuración
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <li class="nav-item mt-5 pt-3 border-top border-secondary">
                            <a href="<?= BASE_URL ?>?controller=Home&action=index" target="_blank" class="nav-link text-white">
                                <i class="bi bi-box-arrow-up-right me-2"></i> Ver Sitio Público
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Contenido Principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 bg-light" style="min-height: calc(100vh - 56px);">
                <?= $content ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
</body>
</html>
