<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Emisión y Registro de Comprobantes</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-outline-secondary shadow-sm">
            <i class="bi bi-calendar me-1"></i> Filtrar Hoy
        </button>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 border-0">N° Comprobante</th>
                        <th class="border-0">Tipo</th>
                        <th class="border-0">Fecha Emisión</th>
                        <th class="border-0">Venta Relacionada</th>
                        <th class="border-0">Cliente</th>
                        <th class="border-0 text-end">Total Facturado</th>
                        <th class="border-0 text-center rounded-end">Ver PDF</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($comprobantes)): ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">Aún no se han emitido comprobantes. Realice un cobro primero en el módulo de Pagos.</td></tr>
                    <?php else: ?>
                        <?php foreach ($comprobantes as $c): ?>
                        <tr>
                            <td class="ps-3 fw-bold text-primary"><?= htmlspecialchars($c['serie'] . '-' . $c['numero']) ?></td>
                            <td>
                                <?php if($c['tipo'] == 'factura'): ?>
                                    <span class="badge bg-danger">Factura</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Boleta</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($c['fecha_emision'])) ?></td>
                            <td class="small text-muted">VN-<?= str_pad($c['id_venta'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><i class="bi bi-person text-secondary me-1"></i> <?= htmlspecialchars($c['nombre'] . ' ' . $c['apellidos']) ?></td>
                            <td class="text-end fw-bold">S/ <?= number_format($c['total'], 2) ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-danger" title="Generar PDF" onclick="alert('Generación de PDF en progreso...')">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
