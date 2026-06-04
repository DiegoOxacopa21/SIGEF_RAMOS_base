<?php
require_once 'models/Client.php';
require_once 'models/Difunto.php';
require_once 'models/Catalog.php';
require_once 'models/Sale.php';

$cm = new Client();
$dm = new Difunto();
$cat = new Catalog();
$sm = new Sale();

$clientesList = $cm->getAllClientes();
$difuntosList = $dm->getAllDifuntos();
$ataudesList = $cat->getAtaudes();
$serviciosList = $cat->getServiciosAdicionales();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action_type'])) {
    if ($_POST['action_type'] == 'convertir_cotizacion') {
        $id_cot = $_POST['id_cotizacion'];
        $id_vendedor = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;
        if ($sm->crearVentaDesdeCotizacion($id_cot, $id_vendedor)) {
            // Exito
            header("Location: " . BASE_URL . "?controller=Admin&action=ventas&msg=venta_creada");
            exit;
        } else {
            $error = "No se pudo convertir la cotización. Posible error de BD.";
        }
    } elseif ($_POST['action_type'] == 'nueva_venta') {
        if ($sm->crearVentaDirecta($_POST)) {
            header("Location: " . BASE_URL . "?controller=Admin&action=ventas&msg=venta_creada");
            exit;
        } else {
            $error = "No se pudo registrar la venta directa.";
        }
    }
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Gestión de Ventas</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaVentaDirecta">
            <i class="bi bi-cart-plus me-1"></i> Nueva Venta Directa
        </button>
    </div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'venta_creada'): ?>
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
  <i class="bi bi-check-circle-fill me-2"></i> Venta generada exitosamente. El pago ha sido derivado a <strong>Caja</strong> y la operación a <strong>Logística</strong>.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 border-0">N° Venta</th>
                        <th class="border-0">Fecha</th>
                        <th class="border-0">Cliente / Deudo</th>
                        <th class="border-0">Vendedor</th>
                        <th class="border-0 text-end">Total (S/)</th>
                        <th class="border-0 text-center">Estado Pago</th>
                        <th class="border-0 text-center rounded-end">Caja</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ventas)): ?>
                        <tr><td colspan="7" class="text-center py-4">No hay ventas registradas.</td></tr>
                    <?php else: ?>
                        <?php foreach ($ventas as $v): ?>
                        <tr>
                            <td class="ps-3 fw-bold text-success">VN-<?= str_pad($v['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                            <td><?= htmlspecialchars($v['cliente_nom'] . ' ' . $v['cliente_ape']) ?></td>
                            <td class="small text-muted"><?= htmlspecialchars($v['vendedor_nom']) ?></td>
                            <td class="text-end fw-bold">S/ <?= number_format($v['total'], 2) ?></td>
                            <td class="text-center">
                                <?php if($v['estado'] == 'pendiente'): ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Pendiente en Caja</span>
                                <?php elseif($v['estado'] == 'pagada'): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Pagada</span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Anulada</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($v['estado'] == 'pendiente'): ?>
                                    <a class="btn btn-sm btn-outline-warning" title="Pendiente de cobro en Cajero"><i class="bi bi-cash"></i></a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-secondary" title="Ver Comprobante"><i class="bi bi-receipt"></i></button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Convertir Cotización -->
<div class="modal fade" id="modalConvertirCotizacion" tabindex="-1" aria-labelledby="modalConvertirCotizacionLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow">
      <form method="POST" action="">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="modalConvertirCotizacionLabel"><i class="bi bi-arrow-repeat me-2"></i> Convertir Proforma a Venta</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <input type="hidden" name="action_type" value="convertir_cotizacion">
              <p>Al confirmar, se generará una Venta Real, se derivará a <strong>Caja</strong> para el cobro y se programará una <strong>Operación Logística</strong>.</p>
              
              <div class="mb-3">
                  <label class="form-label fw-bold">Nro de Cotización</label>
                  <input type="text" class="form-control" name="id_cotizacion" id="cotizacion_a_convertir" readonly required>
              </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success fw-bold">Confirmar Venta</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
function abrirConversionModal(idCotizacion) {
    document.getElementById('cotizacion_a_convertir').value = idCotizacion;
    var modal = new bootstrap.Modal(document.getElementById('modalConvertirCotizacion'));
    modal.show();
}
</script>

<!-- Modal Nueva Venta Directa -->
<div class="modal fade" id="modalNuevaVentaDirecta" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content shadow border-0">
      <form method="POST" action="" id="formVenta">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title"><i class="bi bi-cart-plus me-2"></i> Crear Nueva Venta Directa</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body bg-light">
              <input type="hidden" name="action_type" value="nueva_venta">
              
              <div class="row g-4">
                  <!-- Columna Datos Principales -->
                  <div class="col-md-4">
                      <div class="card shadow-sm border-0 h-100">
                          <div class="card-body">
                              <h6 class="fw-bold text-success mb-3">Datos del Cliente</h6>
                              <div class="mb-3">
                                  <label class="form-label small fw-bold text-muted">Cliente / Deudo</label>
                                  <select class="form-select" name="id_cliente" required>
                                      <option value="">-- Seleccione Cliente --</option>
                                      <?php foreach($clientesList as $cl): ?>
                                      <option value="<?= $cl['id'] ?>"><?= htmlspecialchars($cl['nombre'].' '.$cl['apellidos']) ?></option>
                                      <?php endforeach; ?>
                                  </select>
                              </div>
                              <hr>
                              <h6 class="fw-bold text-success mb-3">Resumen de Totales</h6>
                              <div class="d-flex justify-content-between mb-2">
                                  <span>Subtotal:</span>
                                  <span class="fw-bold">S/ <span id="v_res_subtotal">0.00</span></span>
                                  <input type="hidden" name="subtotal" id="v_input_subtotal" value="0">
                              </div>
                              <div class="d-flex justify-content-between mb-2">
                                  <span>IGV (18%):</span>
                                  <span class="fw-bold">S/ <span id="v_res_igv">0.00</span></span>
                                  <input type="hidden" name="igv" id="v_input_igv" value="0">
                              </div>
                              <div class="d-flex justify-content-between mb-2 text-danger fs-5 mt-3 border-top pt-2">
                                  <span class="fw-bold">TOTAL:</span>
                                  <span class="fw-bold">S/ <span id="v_res_total">0.00</span></span>
                                  <input type="hidden" name="total" id="v_input_total" value="0">
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Columna Detalle -->
                  <div class="col-md-8">
                      <div class="card shadow-sm border-0 h-100">
                          <div class="card-body">
                              <h6 class="fw-bold text-success mb-3">Detalle de Servicios y Productos</h6>
                              
                              <div class="row g-2 mb-3 align-items-end">
                                  <div class="col-md-5">
                                      <label class="form-label small text-muted">Agregar Ataúd</label>
                                      <select class="form-select" id="v_sel_ataud">
                                          <option value="">-- Seleccionar --</option>
                                          <?php foreach($ataudesList as $a): ?>
                                          <option value="<?= $a['id'] ?>" data-nombre="<?= htmlspecialchars($a['nombre']) ?>" data-precio="<?= $a['precio'] ?>"><?= htmlspecialchars($a['nombre']) ?> - S/<?= $a['precio'] ?></option>
                                          <?php endforeach; ?>
                                      </select>
                                  </div>
                                  <div class="col-md-2">
                                      <button type="button" class="btn btn-outline-success w-100" onclick="agregarItemV('ataud', 'v_sel_ataud')"><i class="bi bi-plus"></i> Add</button>
                                  </div>
                                  
                                  <div class="col-md-5">
                                      <label class="form-label small text-muted">Agregar Servicio Adicional</label>
                                      <select class="form-select" id="v_sel_servicio">
                                          <option value="">-- Seleccionar --</option>
                                          <?php foreach($serviciosList as $s): ?>
                                          <option value="<?= $s['id'] ?>" data-nombre="<?= htmlspecialchars($s['nombre']) ?>" data-precio="<?= $s['precio_base'] ?>"><?= htmlspecialchars($s['nombre']) ?> - S/<?= $s['precio_base'] ?></option>
                                          <?php endforeach; ?>
                                      </select>
                                  </div>
                                  <div class="col-md-2 mt-2">
                                      <button type="button" class="btn btn-outline-secondary w-100" onclick="agregarItemV('servicio', 'v_sel_servicio')"><i class="bi bi-plus"></i> Add</button>
                                  </div>
                              </div>

                              <div class="table-responsive">
                                  <table class="table table-bordered table-sm mt-3" id="v_tabla_detalles">
                                      <thead class="table-light">
                                          <tr>
                                              <th>Descripción</th>
                                              <th style="width: 80px">Cant.</th>
                                              <th style="width: 100px">P.Unit</th>
                                              <th style="width: 100px">Subtotal</th>
                                              <th style="width: 50px"></th>
                                          </tr>
                                      </thead>
                                      <tbody id="v_tbody_detalles">
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
            <button type="submit" class="btn btn-success fw-bold px-4" id="btnGuardarV" disabled>Registrar Venta</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
let itemsV = [];
let idxV = 0;

function agregarItemV(tipo, selectId) {
    const sel = document.getElementById(selectId);
    if(sel.value === "") return;
    
    const option = sel.options[sel.selectedIndex];
    const id = option.value;
    const nombre = option.getAttribute('data-nombre');
    const precio = parseFloat(option.getAttribute('data-precio'));
    
    const isProd = tipo === 'ataud';
    
    itemsV.push({
        idx: idxV,
        id_producto: isProd ? id : '',
        id_servicio: isProd ? '' : id,
        descripcion: nombre,
        cantidad: 1,
        precio: precio
    });
    idxV++;
    
    sel.value = "";
    renderTablaV();
}

function eliminarItemV(idx) {
    itemsV = itemsV.filter(i => i.idx !== idx);
    renderTablaV();
}

function actualizarCantV(idx, cant) {
    const obj = itemsV.find(i => i.idx === idx);
    if(obj) {
        obj.cantidad = parseInt(cant) || 1;
        renderTablaV();
    }
}

function renderTablaV() {
    const tbody = document.getElementById('v_tbody_detalles');
    tbody.innerHTML = '';
    
    let subT = 0;
    
    itemsV.forEach((item, i) => {
        const itemSub = item.cantidad * item.precio;
        subT += itemSub;
        
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
                <td><input type="number" class="form-control form-control-sm" name="items[${i}][cantidad]" value="${item.cantidad}" min="1" onchange="actualizarCantV(${item.idx}, this.value)"></td>
                <td>S/ ${item.precio.toFixed(2)}</td>
                <td class="fw-bold">S/ ${itemSub.toFixed(2)}</td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-danger py-0 px-1" onclick="eliminarItemV(${item.idx})"><i class="bi bi-trash"></i></button></td>
            </tr>
        `;
    });
    
    const sub = subT / 1.18;
    const igv = subT - sub;
    const total = subT;
    
    document.getElementById('v_res_subtotal').innerText = sub.toFixed(2);
    document.getElementById('v_res_igv').innerText = igv.toFixed(2);
    document.getElementById('v_res_total').innerText = total.toFixed(2);
    
    document.getElementById('v_input_subtotal').value = sub.toFixed(2);
    document.getElementById('v_input_igv').value = igv.toFixed(2);
    document.getElementById('v_input_total').value = total.toFixed(2);
    
    document.getElementById('btnGuardarV').disabled = itemsV.length === 0;
}
</script>
