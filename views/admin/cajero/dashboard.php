<?php
require_once 'models/Payment.php';
require_once 'models/Sale.php';

$payModel = new Payment();
$saleModel = new Sale();

$pendientes = count($saleModel->getVentasPendientes());
$comprobantes = count($payModel->getAllComprobantes());
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Panel de Caja</h1>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-md-6 border-end">
        <div class="card shadow-sm border-0 border-start border-warning border-4 h-100 bg-light">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-normal mb-1">Cuentas por Cobrar (Ventas Pendientes)</h6>
                        <h3 class="fw-bold mb-0 text-dark"><?= $pendientes ?></h3>
                        <a href="<?= BASE_URL ?>?controller=Admin&action=pagos" class="btn btn-warning mt-3 shadow-sm rounded-pill px-4"><i class="bi bi-cash-coin me-2"></i> Ir a Cobrar</a>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="bi bi-hourglass-split fs-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-6 border-end">
        <div class="card shadow-sm border-0 border-start border-success border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-normal mb-1">Comprobantes Emitidos</h6>
                        <h3 class="fw-bold mb-0 text-dark"><?= $comprobantes ?></h3>
                        <a href="<?= BASE_URL ?>?controller=Admin&action=comprobantes" class="btn btn-success mt-3 shadow-sm rounded-pill px-4"><i class="bi bi-receipt me-2"></i> Ver Historial</a>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="bi bi-file-earmark-check fs-1 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
