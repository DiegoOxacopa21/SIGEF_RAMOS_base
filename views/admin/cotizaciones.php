<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Cotizaciones</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <?php if(in_array($_SESSION['user_role_name'] ?? '', ['Administrador', 'Vendedor'])): ?>
        <button type="button" class="btn btn-sm btn-primary shadow-sm me-2" data-bs-toggle="modal" data-bs-target="#modalNuevaCotizacion">
            <i class="bi bi-plus-lg me-1"></i> Nueva Cotización
        </button>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($_GET['msg'])): ?>
    <?php if ($_GET['msg'] == 'cotizacion_creada'): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i> Cotización generada exitosamente.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php elseif ($_GET['msg'] == 'cotizacion_eliminada'): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
    <i class="bi bi-trash-fill me-2"></i> Cotización eliminada correctamente.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php elseif ($_GET['msg'] == 'error_eliminar'): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
    <i class="bi bi-x-circle-fill me-2"></i> No se pudo eliminar la cotización. Posiblemente ya fue procesada.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 border-0 rounded-start">N° Cotización</th>
                        <th class="border-0">Fecha</th>
                        <th class="border-0">Cliente / Interesado</th>
                        <th class="border-0">Estado</th>
                        <th class="border-0 text-end">Total Estimado</th>
                        <th class="border-0 text-center rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cotizaciones)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No hay cotizaciones registradas actualmente.</td>
                    </tr>
                    <?php endif; ?>
                    
                    <?php foreach ($cotizaciones as $c): ?>
                    <tr>
                        <td class="ps-3 fw-bold text-primary">COT-<?= str_pad($c['id'], 5, '0', STR_PAD_LEFT) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($c['fecha'])) ?></td>
                        <td><?= $c['nombre'] ? htmlspecialchars($c['nombre'] . ' ' . $c['apellidos']) : '<i class="text-muted">Simulación sin nombre</i>' ?></td>
                        <td>
                            <?php if($c['estado'] == 'pendiente'): ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Pendiente</span>
                            <?php elseif($c['estado'] == 'aprobada'): ?>
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aprobada / Vendida</span>
                            <?php else: ?>
                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Rechazada</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold text-end">S/ <?= number_format($c['total'], 2) ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-info" title="Ver Detalles"><i class="bi bi-eye"></i></button>
                            <?php if($c['estado'] == 'pendiente' && in_array($_SESSION['user_role_name'] ?? '', ['Administrador', 'Vendedor'])): ?>
                                <!-- Activar Venta Form -->
                                <form method="POST" action="<?= BASE_URL ?>?controller=Admin&action=ventas" class="d-inline">
                                    <input type="hidden" name="action_type" value="convertir_cotizacion">
                                    <input type="hidden" name="id_cotizacion" value="<?= $c['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-success text-white" title="Convertir a Venta" onclick="return confirm('¿Confirmar conversión de la cotización a Venta? Se enviará a Caja y Logística.')"><i class="bi bi-cart-check"></i> Activar Venta</button>
                                </form>
                                <!-- Eliminar Form -->
                                <form method="POST" action="" class="d-inline">
                                    <input type="hidden" name="action_type" value="eliminar_cotizacion">
                                    <input type="hidden" name="id_cotizacion" value="<?= $c['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger text-white" title="Eliminar Cotización" onclick="return confirm('¿Está seguro de eliminar esta cotización de forma permanente?')"><i class="bi bi-trash"></i></button>
                                </form>
                            <?php endif; ?>
                            <button class="btn btn-sm btn-outline-secondary" title="Imprimir PDF"><i class="bi bi-file-pdf"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nueva Cotización -->
<div class="modal fade" id="modalNuevaCotizacion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content shadow border-0">
      <form method="POST" action="" id="formCotizacion">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title"><i class="bi bi-calculator me-2"></i> Crear Nueva Cotización</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body bg-light">
              <input type="hidden" name="action_type" value="nueva_cotizacion">
              
              <div class="row g-4">
                  <!-- Columna Datos Principales -->
                  <div class="col-md-4">
                      <div class="card shadow-sm border-0 h-100">
                          <div class="card-body">
                              <h6 class="fw-bold text-primary mb-3">Datos del Cliente</h6>
                              <div class="mb-3">
                                  <label class="form-label small fw-bold text-muted">Cliente / Interesado</label>
                                  <select class="form-select" name="id_cliente" required>
                                      <option value="">-- Seleccione Cliente --</option>
                                      <?php foreach($clientes as $cl): ?>
                                      <option value="<?= $cl['id'] ?>"><?= htmlspecialchars($cl['nombre'].' '.$cl['apellidos']) ?></option>
                                      <?php endforeach; ?>
                                  </select>
                                  <div class="form-text">Debe registrar al cliente antes.</div>
                              </div>
                              <hr>
                              <h6 class="fw-bold text-primary mb-3">Resumen de Totales</h6>
                              <div class="d-flex justify-content-between mb-2">
                                  <span>Subtotal:</span>
                                  <span class="fw-bold">S/ <span id="res_subtotal">0.00</span></span>
                                  <input type="hidden" name="subtotal" id="input_subtotal" value="0">
                              </div>
                              <div class="d-flex justify-content-between mb-2">
                                  <span>IGV (18%):</span>
                                  <span class="fw-bold">S/ <span id="res_igv">0.00</span></span>
                                  <input type="hidden" name="igv" id="input_igv" value="0">
                              </div>
                              <div class="d-flex justify-content-between mb-2 text-danger fs-5 mt-3 border-top pt-2">
                                  <span class="fw-bold">TOTAL:</span>
                                  <span class="fw-bold">S/ <span id="res_total">0.00</span></span>
                                  <input type="hidden" name="total" id="input_total" value="0">
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Columna Detalle -->
                  <div class="col-md-8">
                      <div class="card shadow-sm border-0 h-100">
                          <div class="card-body">
                              <h6 class="fw-bold text-primary mb-3">Detalle de Servicios y Productos</h6>
                              
                              <div class="row g-2 mb-3 align-items-end">
                                  <div class="col-md-5">
                                      <label class="form-label small text-muted">Agregar Ataúd</label>
                                      <select class="form-select" id="sel_ataud">
                                          <option value="">-- Seleccionar --</option>
                                          <?php foreach($ataudes as $a): ?>
                                          <option value="<?= $a['id'] ?>" data-nombre="<?= htmlspecialchars($a['nombre']) ?>" data-precio="<?= $a['precio'] ?>"><?= htmlspecialchars($a['nombre']) ?> - S/<?= $a['precio'] ?></option>
                                          <?php endforeach; ?>
                                      </select>
                                  </div>
                                  <div class="col-md-2">
                                      <button type="button" class="btn btn-outline-primary w-100" onclick="agregarItem('ataud', 'sel_ataud')"><i class="bi bi-plus"></i> Add</button>
                                  </div>
                                  
                                  <div class="col-md-5">
                                      <label class="form-label small text-muted">Agregar Servicio Adicional</label>
                                      <select class="form-select" id="sel_servicio">
                                          <option value="">-- Seleccionar --</option>
                                          <?php foreach($servicios as $s): ?>
                                          <option value="<?= $s['id'] ?>" data-nombre="<?= htmlspecialchars($s['nombre']) ?>" data-precio="<?= $s['precio_base'] ?>"><?= htmlspecialchars($s['nombre']) ?> - S/<?= $s['precio_base'] ?></option>
                                          <?php endforeach; ?>
                                      </select>
                                  </div>
                                  <div class="col-md-2 mt-2">
                                      <button type="button" class="btn btn-outline-secondary w-100" onclick="agregarItem('servicio', 'sel_servicio')"><i class="bi bi-plus"></i> Add</button>
                                  </div>
                              </div>

                              <div class="table-responsive">
                                  <table class="table table-bordered table-sm mt-3" id="tabla_detalles">
                                      <thead class="table-light">
                                          <tr>
                                              <th>Descripción</th>
                                              <th style="width: 80px">Cant.</th>
                                              <th style="width: 100px">P.Unit</th>
                                              <th style="width: 100px">Subtotal</th>
                                              <th style="width: 50px"></th>
                                          </tr>
                                      </thead>
                                      <tbody id="tbody_detalles">
                                          <!-- Js content -->
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary fw-bold px-4" id="btnGuardar" disabled>Guardar Cotización</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
let itemsCoti = [];
let itemIndex = 0;

function agregarItem(tipo, selectId) {
    const sel = document.getElementById(selectId);
    if(sel.value === "") return;
    
    const option = sel.options[sel.selectedIndex];
    const id = option.value;
    const nombre = option.getAttribute('data-nombre');
    const precio = parseFloat(option.getAttribute('data-precio'));
    
    const isProd = tipo === 'ataud';
    
    itemsCoti.push({
        idx: itemIndex,
        id_producto: isProd ? id : '',
        id_servicio: isProd ? '' : id,
        descripcion: nombre,
        cantidad: 1,
        precio: precio
    });
    itemIndex++;
    
    sel.value = "";
    renderTabla();
}

function eliminarItem(idx) {
    itemsCoti = itemsCoti.filter(i => i.idx !== idx);
    renderTabla();
}

function actualizarCant(idx, cant) {
    const obj = itemsCoti.find(i => i.idx === idx);
    if(obj) {
        obj.cantidad = parseInt(cant) || 1;
        renderTabla();
    }
}

function renderTabla() {
    const tbody = document.getElementById('tbody_detalles');
    tbody.innerHTML = '';
    
    let subtotalTotal = 0;
    
    itemsCoti.forEach((item, i) => {
        const itemSub = item.cantidad * item.precio;
        subtotalTotal += itemSub;
        
        tbody.innerHTML += `
            <tr>
                <td>
                    ${item.descripcion}
                    <input type="hidden" name="items[${i}][id_producto]" value="${item.id_producto}">
                    <input type="hidden" name="items[${i}][id_servicio]" value="${item.id_servicio}">
                    <input type="hidden" name="items[${i}][descripcion]" value="${item.descripcion}">
                    <input type="hidden" name="items[${i}][precio]" value="${item.precio}">
                    <input type="hidden" name="items[${i}][subtotal]" value="${itemSub}">
                </td>
                <td><input type="number" class="form-control form-control-sm" name="items[${i}][cantidad]" value="${item.cantidad}" min="1" onchange="actualizarCant(${item.idx}, this.value)"></td>
                <td>S/ ${item.precio.toFixed(2)}</td>
                <td class="fw-bold">S/ ${itemSub.toFixed(2)}</td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-danger py-0 px-1" onclick="eliminarItem(${item.idx})"><i class="bi bi-trash"></i></button></td>
            </tr>
        `;
    });
    
    const subtotal = subtotalTotal / 1.18;
    const igv = subtotalTotal - subtotal;
    const total = subtotalTotal;
    
    document.getElementById('res_subtotal').innerText = subtotal.toFixed(2);
    document.getElementById('res_igv').innerText = igv.toFixed(2);
    document.getElementById('res_total').innerText = total.toFixed(2);
    
    document.getElementById('input_subtotal').value = subtotal.toFixed(2);
    document.getElementById('input_igv').value = igv.toFixed(2);
    document.getElementById('input_total').value = total.toFixed(2);
    
    document.getElementById('btnGuardar').disabled = itemsCoti.length === 0;
}
</script>
