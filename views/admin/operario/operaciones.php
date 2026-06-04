<?php
require_once 'models/Operation.php';
require_once 'models/Resource.php';

$op = new Operation();
$rm = new Resource();
$salas = $rm->getAllSalas();
$flotas = $rm->getAllFlota();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action_type']) && $_POST['action_type'] == 'asignar_logistica') {
    if ($op->updateOperacion($_POST['id_operacion'], $_POST)) {
        header("Location: " . BASE_URL . "?controller=Admin&action=operaciones&msg=ok");
        exit;
    }
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Servicios y Operaciones</h1>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'ok'): ?>
<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
  <i class="bi bi-check-circle-fill me-2"></i> Asignación logística y estado de operación actualizados corerectamente.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-primary text-white pt-3 pb-2 border-bottom-0">
        <h6 class="fw-bold mb-0"><i class="bi bi-gear me-1"></i> Panel de Control Logístico / Órdenes de Servicio</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 border-0">ID Venta</th>
                        <th class="border-0">Difunto / Deudo</th>
                        <th class="border-0 text-center">Fecha Prog.</th>
                        <th class="border-0 text-center">Estado Op.</th>
                        <th class="border-0">Recursos Asignados</th>
                        <th class="border-0 text-center rounded-end">Logística</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($operaciones)): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">No existen operaciones pendientes en este momento.</td></tr>
                    <?php else: ?>
                        <?php foreach ($operaciones as $o): ?>
                        <tr>
                            <td class="ps-3 fw-bold text-success">VN-<?= str_pad($o['id_venta'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td>
                                <div><i class="bi bi-person-badge text-dark me-1"></i> <span class="fw-bold"><?= $o['difunto_nom'] ? htmlspecialchars($o['difunto_nom'] . ' ' . $o['difunto_ape']) : 'N/A' ?></span></div>
                                <div class="small text-muted"><i class="bi bi-person me-1"></i> Resp: <?= htmlspecialchars($o['cliente_nom'] . ' ' . $o['cliente_ape']) ?></div>
                            </td>
                            <td class="text-center"><?= date('d/m/Y H:i', strtotime($o['fecha_programada'])) ?></td>
                            <td class="text-center">
                                <?php if($o['estado'] == 'pendiente'): ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i> Pendiente</span>
                                <?php elseif($o['estado'] == 'en_proceso'): ?>
                                    <span class="badge bg-primary"><i class="bi bi-arrow-repeat me-1"></i> En Proceso</span>
                                <?php elseif($o['estado'] == 'finalizado'): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-all me-1"></i> Finalizado</span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><i class="bi bi-x me-1"></i> Cancelado</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($o['sala_nom']): ?> <span class="badge bg-info text-dark border me-1"><i class="bi bi-door-open"></i> <?= htmlspecialchars($o['sala_nom']) ?></span> <?php endif; ?>
                                <?php if($o['flota_placa']): ?> <span class="badge bg-secondary border me-1"><i class="bi bi-truck"></i> <?= htmlspecialchars($o['flota_placa']) ?></span> <?php endif; ?>
                                <?php if(!$o['sala_nom'] && !$o['flota_placa']): ?> <span class="text-muted small">Sin asignaciones</span> <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-dark shadow-sm rounded-pill px-3" onclick="abrirModalOperacion(<?= $o['id'] ?>, '<?= $o['id_sala'] ?>', '<?= $o['id_flota'] ?>', '<?= $o['estado'] ?>', '<?= addslashes(htmlspecialchars($o['observaciones'])) ?>')">
                                    <i class="bi bi-tools me-1"></i> Asignar
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

<!-- Modal Asignacion -->
<div class="modal fade" id="modalOperacion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow border-0">
      <form method="POST" action="">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title"><i class="bi bi-gear-fill me-2"></i> Asignación y Estado Logístico</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body bg-light">
              <input type="hidden" name="action_type" value="asignar_logistica">
              <input type="hidden" name="id_operacion" id="id_op_hidden" required>
              
              <div class="mb-3">
                  <label class="form-label fw-bold text-uppercase small text-muted">Estado del Servicio</label>
                  <select class="form-select" name="estado" id="estado_op" required>
                      <option value="pendiente">Pendiente (En espera)</option>
                      <option value="en_proceso">En Proceso (Ejecución)</option>
                      <option value="finalizado">Finalizado</option>
                      <option value="cancelado">Cancelado</option>
                  </select>
              </div>

              <div class="mb-3">
                  <label class="form-label fw-bold text-uppercase small text-muted">Asignar Sala de Velación</label>
                  <select class="form-select" name="id_sala" id="sala_op">
                      <option value="">-- Sin Sala / A Domicilio --</option>
                      <?php foreach($salas as $s): ?>
                          <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nombre']) ?> (Cap: <?= $s['capacidad'] ?>)</option>
                      <?php endforeach; ?>
                  </select>
              </div>

              <div class="mb-3">
                  <label class="form-label fw-bold text-uppercase small text-muted">Asignar Carroza / Flota</label>
                  <select class="form-select" name="id_flota" id="flota_op">
                      <option value="">-- Sin Transporte --</option>
                      <?php foreach($flotas as $f): ?>
                          <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['placa'] . ' - ' . $f['modelo']) ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>

              <div class="mb-0">
                  <label class="form-label fw-bold text-uppercase small text-muted">Observaciones / Detalles Técnicos</label>
                  <textarea class="form-control" name="observaciones" id="obs_op" rows="3" placeholder="Anotaciones extra sobre arreglos, tiempos, etc."></textarea>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary fw-bold">Guardar Cambios</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
function abrirModalOperacion(id, idSala, idFlota, estado, obs) {
    document.getElementById('id_op_hidden').value = id;
    document.getElementById('sala_op').value = idSala || "";
    document.getElementById('flota_op').value = idFlota || "";
    document.getElementById('estado_op').value = estado;
    document.getElementById('obs_op').value = obs;
    var modal = new bootstrap.Modal(document.getElementById('modalOperacion'));
    modal.show();
}
</script>
