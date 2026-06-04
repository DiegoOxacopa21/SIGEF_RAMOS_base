<?php
require_once 'models/Sale.php';
require_once 'models/Payment.php';

$sm = new Sale();
$pm = new Payment();

$ventas = $sm->getAllVentas();
$comprobantes = $pm->getAllComprobantes();

$totalIngresos = 0;
foreach($comprobantes as $c) {
    if($c['tipo'] != 'anulada') {
        $totalIngresos += $c['total'];
    }
}

$ventasTotalCount = count($ventas);
$pendientesCount = 0;
foreach($ventas as $v) {
    if($v['estado'] == 'pendiente') $pendientesCount++;
}
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Panel Gerencial</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Exportar PDF</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Exportar Excel</button>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <i class="bi bi-calendar3 me-1"></i> Este Mes
        </button>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-md-3">
        <div class="card bg-primary text-white shadow-sm border-0 h-100">
            <div class="card-body">
                <h6 class="text-uppercase fw-normal mb-2 text-white-50">Ingresos Totales (Pagados)</h6>
                <h2 class="fw-bold mb-0">S/ <?= number_format($totalIngresos, 2) ?></h2>
                <div class="mt-3 small text-white-50"><i class="bi bi-graph-up-arrow me-1"></i> Respecto a mes anterior</div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-3">
        <div class="card bg-success text-white shadow-sm border-0 h-100">
            <div class="card-body">
                <h6 class="text-uppercase fw-normal mb-2 text-white-50">Ventas Finalizadas</h6>
                <h2 class="fw-bold mb-0"><?= $ventasTotalCount - $pendientesCount ?> <span class="fs-6 fw-normal">servicios</span></h2>
                <div class="mt-3 small text-white-50"><a href="<?= BASE_URL ?>?controller=Admin&action=reportes" class="text-white text-decoration-none">Ver Reporte <i class="bi bi-arrow-right"></i></a></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-3">
        <div class="card bg-warning text-dark shadow-sm border-0 h-100">
            <div class="card-body">
                <h6 class="text-uppercase fw-normal mb-2 opacity-75">Ventas por Cobrar</h6>
                <h2 class="fw-bold mb-0"><?= $pendientesCount ?> <span class="fs-6 fw-normal">pendientes</span></h2>
                <div class="mt-3 small opacity-75"><i class="bi bi-exclamation-circle me-1"></i> Pendientes en caja</div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-3">
        <div class="card bg-info text-white shadow-sm border-0 h-100">
            <div class="card-body">
                <h6 class="text-uppercase fw-normal mb-2 text-white-50">Operatividad Logística</h6>
                <h2 class="fw-bold mb-0">85%</h2>
                <div class="mt-3 small text-white-50"><i class="bi bi-truck me-1"></i> Flota y Salas en uso</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white pt-3 pb-2">
                <h6 class="fw-bold mb-0">Ingresos Recientes</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 border-0">Comprobante</th>
                                <th class="border-0">Fecha</th>
                                <th class="border-0">Cliente</th>
                                <th class="border-0 text-end pe-3">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $recentComprobantes = array_slice($comprobantes, 0, 5);
                            foreach ($recentComprobantes as $c): ?>
                            <tr>
                                <td class="ps-3 fw-bold text-success"><?= htmlspecialchars($c['serie'].'-'.$c['numero']) ?></td>
                                <td><?= date('d/m/Y', strtotime($c['fecha_emision'])) ?></td>
                                <td class="small"><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellidos']) ?></td>
                                <td class="text-end pe-3 fw-bold">S/ <?= number_format($c['total'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <!-- Placeholder for a Chart -->
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white pt-3 pb-2">
                <h6 class="fw-bold mb-0">Distribución de Ingresos</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center bg-light">
                <div class="text-center text-muted">
                    <i class="bi bi-pie-chart fs-1 mb-2 d-block"></i>
                    <small>Integración de gráfico requeriría librería como Chart.js</small>
                </div>
            </div>
        </div>
    </div>
</div>
