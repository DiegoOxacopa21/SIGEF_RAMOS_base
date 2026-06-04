<?php
require_once 'models/Payment.php';
$pm = new Payment();
$metodos = $pm->getMetodosPago();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action_type']) && $_POST['action_type'] == 'cobrar_venta') {
    $_POST['id_cajero'] = $_SESSION['user_id'];
    if ($pm->registrarPago($_POST)) {
        header("Location: " . BASE_URL . "?controller=Admin&action=pagos&msg=pago_exitoso");
        exit;
    }
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Registro de Pagos</h1>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'pago_exitoso'): ?>
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
  <i class="bi bi-check-circle-fill me-2"></i> Operación registrada. Se ha procesado el pago y emitido el comprobante correctamente.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-warning text-dark pt-3 pb-2 border-bottom-0">
        <h6 class="fw-bold mb-0"><i class="bi bi-hourglass-split me-1"></i> Ventas Pendientes de Cobro Enviadas por Vendedores</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 border-0">N° Venta</th>
                        <th class="border-0">Fecha</th>
                        <th class="border-0">Cliente / Deudo</th>
                        <th class="border-0">Doc. Identidad</th>
                        <th class="border-0 text-end">Total a Cobrar</th>
                        <th class="border-0 text-center rounded-end">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ventas)): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">No existen facturas/ventas pendientes de cobro en este momento.</td></tr>
                    <?php else: ?>
                        <?php foreach ($ventas as $v): ?>
                        <tr>
                            <td class="ps-3 fw-bold">VN-<?= str_pad($v['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                            <td><i class="bi bi-person text-secondary me-1"></i> <?= htmlspecialchars($v['cliente_nom'] . ' ' . $v['cliente_ape']) ?></td>
                            <td class="small text-muted"><?= htmlspecialchars($v['num_documento']) ?></td>
                            <td class="text-end fw-bold text-danger fs-5">S/ <?= number_format($v['total'], 2) ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-success shadow-sm rounded-pill px-3" onclick="abrirModalCobro(<?= $v['id'] ?>, '<?= htmlspecialchars($v['cliente_nom'] . ' ' . $v['cliente_ape']) ?>', <?= $v['total'] ?>)">
                                    <i class="bi bi-cash-coin me-1"></i> Cobrar
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

<!-- Modal Cobro -->
<div class="modal fade" id="modalCobro" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content shadow border-0">
      <form method="POST" action="">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title"><i class="bi bi-cash me-2"></i> Procesar Pago en Caja</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body bg-light">
              <input type="hidden" name="action_type" value="cobrar_venta">
              <input type="hidden" name="id_venta" id="id_venta_cobro" required>
              <input type="hidden" name="monto" id="monto_cobro_hidden" required>
              
              <div class="row g-4">
                  <div class="col-md-6">
                      <div class="card h-100 border text-center shadow-sm">
                          <div class="card-body">
                              <h6 class="text-muted text-uppercase small">Cliente</h6>
                              <h5 id="cliente_cobro" class="fw-bold text-primary mb-3">Cliente X</h5>
                              <h6 class="text-muted text-uppercase small">Monto Total a Cobrar</h6>
                              <h2 id="monto_cobro_text" class="text-danger fw-bold mb-0">S/ 0.00</h2>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="card h-100 border shadow-sm">
                          <div class="card-body">
                              <div class="mb-3">
                                  <label class="form-label fw-bold small text-muted text-uppercase">Método de Pago</label>
                                  <select class="form-select" name="id_metodo_pago" required>
                                      <?php foreach($metodos as $m): ?>
                                      <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nombre']) ?></option>
                                      <?php endforeach; ?>
                                  </select>
                              </div>
                              <div class="mb-3">
                                  <label class="form-label fw-bold small text-muted text-uppercase">Nro Operación / Referencia (Opcional)</label>
                                  <input type="text" class="form-control" name="referencia" placeholder="Ej: OP-09823 si es Transferencia">
                              </div>
                              <div class="mb-0">
                                  <label class="form-label fw-bold small text-muted text-uppercase">Tipo de Comprobante</label>
                                  <select class="form-select" name="tipo_comprobante" required>
                                      <option value="boleta">Boleta Electrónica</option>
                                      <option value="factura">Factura Electrónica</option>
                                  </select>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success fw-bold px-5">Confirmar Cobro y Emitir Comprobante</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
function abrirModalCobro(idVenta, cliente, monto) {
    document.getElementById('id_venta_cobro').value = idVenta;
    document.getElementById('monto_cobro_hidden').value = monto;
    document.getElementById('cliente_cobro').innerText = cliente;
    document.getElementById('monto_cobro_text').innerText = 'S/ ' + parseFloat(monto).toFixed(2);
    var modal = new bootstrap.Modal(document.getElementById('modalCobro'));
    modal.show();
}
</script>
