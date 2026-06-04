<?php
// Obtener algunas métricas rápidas (en un caso real vendrían del controlador)
require_once 'models/Client.php';
require_once 'models/Sale.php';
require_once 'models/Quotation.php';

$clientModel = new Client();
$saleModel = new Sale();
$quoteModel = new Quotation();

$clientes = count($clientModel->getAllClientes());
$ventas = count($saleModel->getAllVentas());
$cotizaciones = count($quoteModel->getAllCotizaciones());
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Panel de Ventas</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= BASE_URL ?>?controller=Admin&action=cotizaciones" class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-file-earmark-plus"></i> Nueva Proforma</a>
        <a href="<?= BASE_URL ?>?controller=Admin&action=ventas" class="btn btn-sm btn-primary shadow-sm"><i class="bi bi-cart-plus me-1"></i> Nueva Venta</a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-md-4">
        <div class="card shadow-sm border-0 border-start border-primary border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-normal mb-1">Clientes Registrados</h6>
                        <h3 class="fw-bold mb-0 text-dark"><?= $clientes ?></h3>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="bi bi-people fs-3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-4">
        <div class="card shadow-sm border-0 border-start border-success border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-normal mb-1">Ventas Totales</h6>
                        <h3 class="fw-bold mb-0 text-dark"><?= $ventas ?></h3>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="bi bi-bag-check fs-3 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card shadow-sm border-0 border-start border-warning border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-normal mb-1">Cotizaciones</h6>
                        <h3 class="fw-bold mb-0 text-dark"><?= $cotizaciones ?></h3>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="bi bi-file-text fs-3 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-white pt-3 pb-2">
        <h6 class="fw-bold text-dark mb-0">Accesos Rápidos del Flujo Transaccional</h6>
    </div>
    <div class="card-body p-4">
        <div class="row text-center g-4">
            <div class="col-md-3">
                <a href="<?= BASE_URL ?>?controller=Admin&action=clientes" class="text-decoration-none">
                    <div class="p-4 border rounded bg-light hover-shadow transition-all">
                        <i class="bi bi-person-plus fs-1 text-primary mb-2"></i>
                        <h6 class="fw-bold text-dark">1. Registrar Cliente</h6>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="<?= BASE_URL ?>?controller=Admin&action=difuntos" class="text-decoration-none">
                    <div class="p-4 border rounded bg-light hover-shadow transition-all">
                        <i class="bi bi-person-badge fs-1 text-secondary mb-2"></i>
                        <h6 class="fw-bold text-dark">2. Registrar Difunto</h6>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="<?= BASE_URL ?>?controller=Admin&action=cotizaciones" class="text-decoration-none">
                    <div class="p-4 border rounded bg-light hover-shadow transition-all">
                        <i class="bi bi-calculator fs-1 text-warning mb-2"></i>
                        <h6 class="fw-bold text-dark">3. Crear Cotización</h6>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="<?= BASE_URL ?>?controller=Admin&action=ventas" class="text-decoration-none">
                    <div class="p-4 border rounded bg-light hover-shadow transition-all">
                        <i class="bi bi-currency-dollar fs-1 text-success mb-2"></i>
                        <h6 class="fw-bold text-dark">4. Generar Venta</h6>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
