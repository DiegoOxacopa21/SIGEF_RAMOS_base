<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary shadow-sm"><i class="bi bi-calendar me-1"></i> Hoy</button>
            <button type="button" class="btn btn-sm btn-outline-secondary shadow-sm"><i class="bi bi-calendar-week me-1"></i> Semana</button>
            <button type="button" class="btn btn-sm btn-outline-secondary shadow-sm"><i class="bi bi-calendar-month me-1"></i> Mes</button>
        </div>
    </div>
</div>

<div class="alert alert-info shadow-sm" role="alert">
    <i class="bi bi-info-circle-fill me-2"></i> Bienvenido al Panel de Control de <strong>SIGEF-RAMOS</strong>. Has iniciado sesión como <strong><?= htmlspecialchars($role) ?></strong>.
</div>

<div class="row g-4 mb-4">
    <!-- Tarjeta Resumen 1 -->
    <div class="col-12 col-sm-6 col-xxl-3">
        <div class="card shadow-sm border-0 border-start border-primary border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="text-muted fw-normal mb-1">Servicios Activos</h6>
                        <h3 class="fw-bold mb-0 text-dark">12</h3>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="bi bi-activity fs-3 text-primary"></i>
                    </div>
                </div>
                <div class="small">
                    <span class="text-success fw-bold"><i class="bi bi-arrow-up-short"></i> +2%</span> <span class="text-muted">desde ayer</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tarjeta Resumen 2 -->
    <div class="col-12 col-sm-6 col-xxl-3">
        <div class="card shadow-sm border-0 border-start border-success border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="text-muted fw-normal mb-1">Ventas del Mes</h6>
                        <h3 class="fw-bold mb-0 text-dark">S/ 45,200</h3>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="bi bi-currency-dollar fs-3 text-success"></i>
                    </div>
                </div>
                <div class="small">
                    <span class="text-success fw-bold"><i class="bi bi-arrow-up-short"></i> +15%</span> <span class="text-muted">vs mes pasado</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjeta Resumen 3 -->
    <div class="col-12 col-sm-6 col-xxl-3">
        <div class="card shadow-sm border-0 border-start border-warning border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="text-muted fw-normal mb-1">Cotizaciones Pendientes</h6>
                        <h3 class="fw-bold mb-0 text-dark">8</h3>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="bi bi-file-earmark-text fs-3 text-warning"></i>
                    </div>
                </div>
                <div class="small">
                    <span class="text-danger fw-bold"><i class="bi bi-exclamation-circle"></i> Requieren atención</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjeta Resumen 4 -->
    <div class="col-12 col-sm-6 col-xxl-3">
        <div class="card shadow-sm border-0 border-start border-danger border-4 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                         <h6 class="text-muted fw-normal mb-1">Salas Ocupadas</h6>
                         <h3 class="fw-bold mb-0 text-dark">2 / 3</h3>
                    </div>
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                        <i class="bi bi-door-closed fs-3 text-danger"></i>
                    </div>
                </div>
                <div class="small">
                    <span class="text-muted">1 sala disponible (VIP)</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección Gráficos & Tablas demo -->
<div class="row g-4 mt-2">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white pt-3 pb-2 border-bottom-0">
                <h6 class="fw-bold text-dark mb-0">Últimas Operaciones</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>Cliente / Deudo</th>
                                <th>Servicio Principal</th>
                                <th>Estado</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-3">#VN-1025</td>
                                <td>Familia Perez Ramos</td>
                                <td>Servicio Premium</td>
                                <td><span class="badge bg-success">Pagado</span></td>
                                <td>S/ 4,500.00</td>
                            </tr>
                            <tr>
                                <td class="ps-3">#VN-1024</td>
                                <td>Sonia Gutierrez</td>
                                <td>Traslado y Velación</td>
                                <td><span class="badge bg-warning text-dark">Pendiente</span></td>
                                <td>S/ 2,100.00</td>
                            </tr>
                            <tr>
                                <td class="ps-3">#CT-0899</td>
                                <td>Marcos Silva</td>
                                <td>Cotización Básica</td>
                                <td><span class="badge bg-secondary">Borrador</span></td>
                                <td>S/ 1,500.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-center py-2">
                <a href="<?= BASE_URL ?>?controller=Admin&action=ventas" class="text-decoration-none small text-muted">Ver todas las operaciones</a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white pt-3 pb-2 border-bottom-0">
                <h6 class="fw-bold text-dark mb-0">Disponibilidad Flota</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <i class="bi bi-truck me-2 text-muted"></i> CAR-001 <small class="text-muted d-block ms-4">Carroza Mercedes</small>
                        </div>
                        <span class="badge bg-success rounded-pill">Disponible</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <i class="bi bi-truck me-2 text-muted"></i> CAR-002 <small class="text-muted d-block ms-4">Carroza Lincoln</small>
                        </div>
                        <span class="badge bg-danger rounded-pill">En Servicio</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <i class="bi bi-truck-flatbed me-2 text-muted"></i> TRA-001 <small class="text-muted d-block ms-4">Van Translado H1</small>
                        </div>
                        <span class="badge bg-success rounded-pill">Disponible</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
