<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<?php
$ventas = $data['ventas'] ?? [];
$sedes = $data['sedes'] ?? [];
$filtros = $data['filtros'] ?? ['fecha_inicio'=>'', 'fecha_fin'=>'', 'estado'=>'', 'id_sede'=>''];

$total_ingresos = array_reduce($ventas, fn($carry, $v) => $v['estado'] == 'pagada' ? $carry + $v['total'] : $carry, 0);
$total_ventas = count($ventas);
$pendientes = array_reduce($ventas, fn($carry, $v) => $v['estado'] == 'pendiente' ? $carry + 1 : $carry, 0);
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Reportes Consolidados</h1>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-primary text-white h-100">
            <div class="card-body">
                <h6 class="text-uppercase mb-2 opacity-75">Ingresos Totales (Cobrados)</h6>
                <h3 class="mb-0 fw-bold">S/ <?= number_format($total_ingresos, 2) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-success text-white h-100">
            <div class="card-body">
                <h6 class="text-uppercase mb-2 opacity-75">Cantidad de Ventas</h6>
                <h3 class="mb-0 fw-bold"><?= $total_ventas ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-warning text-dark h-100">
            <div class="card-body">
                <h6 class="text-uppercase mb-2 opacity-75">Ventas Pendientes de Cobro</h6>
                <h3 class="mb-0 fw-bold"><?= $pendientes ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="card shadow border-0 mb-4">
    <div class="card-header bg-dark text-white pt-3 pb-2 border-bottom-0">
        <h6 class="fw-bold mb-0"><i class="bi bi-filter-circle me-1"></i> Filtros de Búsqueda</h6>
    </div>
    <div class="card-body bg-light">
        <form class="row g-3" method="GET" action="index.php">
            <input type="hidden" name="controller" value="Admin">
            <input type="hidden" name="action" value="reportes">
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted text-uppercase">Sede</label>
                <select name="id_sede" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    <?php foreach($sedes as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $filtros['id_sede'] == $s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" class="form-control form-control-sm" value="<?= htmlspecialchars($filtros['fecha_inicio']) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted text-uppercase">Fecha Fin</label>
                <input type="date" name="fecha_fin" class="form-control form-control-sm" value="<?= htmlspecialchars($filtros['fecha_fin']) ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted text-uppercase">Estado Venta</label>
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="pagada" <?= $filtros['estado'] == 'pagada' ? 'selected' : '' ?>>Cobrada</option>
                    <option value="pendiente" <?= $filtros['estado'] == 'pendiente' ? 'selected' : '' ?>>Por Cobrar</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-sm btn-dark w-100 shadow-sm"><i class="bi bi-search me-1"></i> Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0" id="reporteTablaContent">
    <div class="card-header bg-white pt-3 pb-2 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-dark mb-0">Detalle Consolidado de Ventas</h6>
        <div class="btn-group" id="exportButtons">
            <a href="index.php?controller=Admin&action=reportes&export=csv&fecha_inicio=<?= urlencode($filtros['fecha_inicio']) ?>&fecha_fin=<?= urlencode($filtros['fecha_fin']) ?>&estado=<?= urlencode($filtros['estado']) ?>&id_sede=<?= urlencode($filtros['id_sede']) ?>" class="btn btn-sm btn-outline-success" title="Excel"><i class="bi bi-file-earmark-spreadsheet"></i> CSV</a>
            <button type="button" class="btn btn-sm btn-outline-danger" title="PDF" onclick="exportPDF()"><i class="bi bi-file-earmark-pdf"></i> PDF</button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-sm text-sm">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 border-0">N° Venta</th>
                        <th class="border-0">Fecha</th>
                        <th class="border-0">Resp. Vendedor</th>
                        <th class="border-0">Sede</th>
                        <th class="border-0">Cliente Relacionado</th>
                        <th class="border-0 text-center">Estado Pago</th>
                        <th class="border-0 text-end pe-3">Total(S/)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ventas)): ?>
                        <tr><td colspan="7" class="text-center py-4">No hay datos en el rango seleccionado.</td></tr>
                    <?php else: ?>
                        <?php foreach ($ventas as $v): ?>
                        <tr>
                            <td class="ps-3 text-primary fw-bold">VN-<?= str_pad($v['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                            <td class="small text-muted"><?= htmlspecialchars($v['vendedor_nom']) ?></td>
                            <td class="small text-muted"><?= htmlspecialchars($v['sede_nom'] ?? '') ?></td>
                            <td><i class="bi bi-person text-secondary me-1"></i> <?= htmlspecialchars($v['cliente_nom'] . ' ' . $v['cliente_ape']) ?></td>
                            <td class="text-center">
                                <?php if($v['estado'] == 'pagada'): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Cobrado</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Pendiente</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-3 fw-bold">S/ <?= number_format($v['total'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function exportPDF() {
    var element = document.getElementById('reporteTablaContent');
    // Hide export buttons for the PDF
    document.getElementById('exportButtons').style.display = 'none';
    
    var opt = {
      margin:       10,
      filename:     'Reporte_Ventas.pdf',
      image:        { type: 'jpeg', quality: 0.98 },
      html2canvas:  { scale: 2 },
      jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
    };

    html2pdf().set(opt).from(element).save().then(function(){
        document.getElementById('exportButtons').style.display = 'block';
    });
}
</script>
