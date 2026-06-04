<?php
require_once 'models/Operation.php';
require_once 'models/Resource.php';

$opModel = new Operation();
$resModel = new Resource();

$operaciones = count($opModel->getAllOperaciones());
$flota = count($resModel->getAllFlota());
$salas = count($resModel->getAllSalas());
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Panel de Control General (Logística)</h1>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-md-4 border-end">
        <div class="card shadow-sm border-0 border-start border-primary border-4 h-100 bg-light">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-normal mb-1">Operaciones Asignadas</h6>
                        <h3 class="fw-bold mb-0 text-dark"><?= $operaciones ?></h3>
                        <a href="<?= BASE_URL ?>?controller=Admin&action=operaciones" class="btn btn-primary mt-3 shadow-sm rounded-pill px-4"><i class="bi bi-gear-fill me-2"></i> Ver Órdenes</a>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="bi bi-clock-history fs-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-4 border-end">
        <div class="card shadow-sm border-0 border-start border-success border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-normal mb-1">Flota Móvil Total</h6>
                        <h3 class="fw-bold mb-0 text-dark"><?= $flota ?></h3>
                        <a href="<?= BASE_URL ?>?controller=Admin&action=recursos" class="btn btn-success mt-3 shadow-sm rounded-pill px-4"><i class="bi bi-truck me-2"></i> Ver Flota</a>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="bi bi-car-front-fill fs-1 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4 border-end">
        <div class="card shadow-sm border-0 border-start border-warning border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-normal mb-1">Salas Disponibles</h6>
                        <h3 class="fw-bold mb-0 text-dark"><?= $salas ?></h3>
                        <a href="<?= BASE_URL ?>?controller=Admin&action=recursos" class="btn btn-warning mt-3 shadow-sm rounded-pill px-4"><i class="bi bi-door-open me-2"></i> Ver Salas</a>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="bi bi-house-door-fill fs-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
