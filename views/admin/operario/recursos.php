<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Logística y Flota</h1>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white pt-3 pb-2 border-bottom-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-truck me-1"></i> Control de Unidades Móviles</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach($flota as $f): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <div>
                            <div class="fw-bold"><?= htmlspecialchars($f['placa']) ?> <span class="badge bg-secondary ms-2"><?= htmlspecialchars($f['tipo']) ?></span></div>
                            <small class="text-muted"><?= htmlspecialchars($f['marca'] . ' ' . $f['modelo']) ?></small>
                        </div>
                        <?php if($f['estado'] == 'disponible'): ?>
                            <span class="badge bg-success rounded-pill px-3 py-2">Disponible</span>
                        <?php else: ?>
                            <span class="badge bg-warning rounded-pill px-3 py-2 text-dark">En Servicio / Mantenimiento</span>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white pt-3 pb-2 border-bottom-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-door-open me-1"></i> Estado de Salas de Velación</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach($salas as $s): ?>
                    <li class="list-group-item p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold text-primary"><?= htmlspecialchars($s['nombre']) ?></span>
                            <?php if($s['estado'] == 'disponible'): ?>
                                <span class="badge bg-success px-2">Libre</span>
                            <?php else: ?>
                                <span class="badge bg-danger px-2">Ocupada</span>
                            <?php endif; ?>
                        </div>
                        <div class="small text-muted mb-0">Ubicación: <?= htmlspecialchars($s['ubicacion']) ?> | Cap: <?= $s['capacidad'] ?> per.</div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
