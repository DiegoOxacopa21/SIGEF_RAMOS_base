<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="fw-bold">Simulador de Cotización</h2>
            <p class="text-muted">Seleccione los servicios requeridos para calcular un costo estimado referencial.</p>
        </div>
    </div>
    
    <div class="row">
        <!-- Formulario de Selección -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold text-dark">
                    <i class="bi bi-box-seam me-2"></i>1. Selección de Ataúd
                </div>
                <div class="card-body">
                    <select class="form-select mb-3 proforma-input" id="sim_ataud">
                        <option value="0" data-price="0">-- No incluir ataúd --</option>
                        <?php foreach($ataudes as $item): ?>
                            <option value="<?= $item['id'] ?>" data-price="<?= $item['precio'] ?>" data-name="<?= $item['nombre'] ?>">
                                <?= htmlspecialchars($item['nombre']) ?> - S/ <?= number_format($item['precio'], 2) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold text-dark">
                    <i class="bi bi-flower2 me-2"></i>2. Urnas y Arreglos Florales
                </div>
                <div class="card-body">
                    <?php foreach($otrosProductos as $item): ?>
                    <div class="form-check mb-2">
                        <input class="form-check-input proforma-checkbox" type="checkbox" id="prod_<?= $item['id'] ?>" value="<?= $item['id'] ?>" data-price="<?= $item['precio'] ?>" data-name="<?= $item['nombre'] ?>">
                        <label class="form-check-label d-flex justify-content-between" for="prod_<?= $item['id'] ?>">
                            <span><?= htmlspecialchars($item['nombre']) ?></span>
                            <span class="text-muted">S/ <?= number_format($item['precio'], 2) ?></span>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold text-dark">
                    <i class="bi bi-card-checklist me-2"></i>3. Servicios Adicionales
                </div>
                <div class="card-body">
                    <?php foreach($servicios as $item): ?>
                    <div class="form-check mb-2">
                        <input class="form-check-input proforma-checkbox" type="checkbox" id="serv_<?= $item['id'] ?>" value="<?= $item['id'] ?>" data-price="<?= $item['precio_base'] ?>" data-name="<?= $item['nombre'] ?>">
                        <label class="form-check-label d-flex justify-content-between" for="serv_<?= $item['id'] ?>">
                            <span>
                                <?= htmlspecialchars($item['nombre']) ?>
                                <small class="text-muted d-block"><?= htmlspecialchars($item['descripcion']) ?></small>
                            </span>
                            <span class="text-muted">S/ <?= number_format($item['precio_base'], 2) ?></span>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Resumen de Proforma -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-primary sticky-top" style="top: 2rem; z-index: 10;">
                <div class="card-header bg-primary text-white text-center fw-bold fs-5">
                    Resumen Estimado
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3" id="sim_resumen_lista">
                        <li class="list-group-item text-muted text-center small" id="sim_empty">No hay servicios seleccionados.</li>
                    </ul>
                    <hr>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Subtotal:</span>
                        <span id="sim_subtotal" class="fw-bold">S/ 0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>IGV (18%):</span>
                        <span id="sim_igv" class="fw-bold">S/ 0.00</span>
                    </div>
                    <div class="d-flex justify-content-between fs-5 text-success mb-3 fw-bold">
                        <span>Total:</span>
                        <span id="sim_total">S/ 0.00</span>
                    </div>
                    <div class="alert alert-warning small px-2 py-2 mb-0 mt-3 text-center">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Esta proforma es una <strong>simulación referencial</strong>. La formalización y cotización final la realiza el vendedor en nuestras oficinas.
                    </div>
                </div>
                <div class="card-footer bg-light text-center">
                    <button class="btn btn-outline-primary w-100" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>Imprimir Simulación
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
