<?php
require_once 'models/Client.php';
$cm = new Client();
$clientesList = $cm->getAllClientes();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action_type']) && $_POST['action_type'] == 'add_difunto') {
    require_once 'models/Difunto.php';
    $dm = new Difunto();
    $dm->addDifunto($_POST);
    header("Location: " . BASE_URL . "?controller=Admin&action=difuntos");
    exit;
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Registro de Difuntos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoDifunto">
            <i class="bi bi-person-badge me-1"></i> Registrar Difunto
        </button>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 border-0">Documento</th>
                        <th class="border-0">Nombre Completo del Difunto</th>
                        <th class="border-0">Fecha Defunción</th>
                        <th class="border-0">Cliente Relacionado (Deudo)</th>
                        <th class="border-0 text-center rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($difuntos)): ?>
                        <tr><td colspan="5" class="text-center py-4">No hay registros funerarios.</td></tr>
                    <?php else: ?>
                        <?php foreach ($difuntos as $d): ?>
                        <tr>
                            <td class="ps-3"><?= htmlspecialchars($d['num_documento'] ?? 'N/A') ?></td>
                            <td class="fw-bold fs-6"><i class="bi bi-person text-secondary me-1"></i> <?= htmlspecialchars($d['nombre'] . ' ' . $d['apellidos']) ?></td>
                            <td><?= $d['fecha_defuncion'] ? date('d/m/Y', strtotime($d['fecha_defuncion'])) : '-' ?></td>
                            <td><span class="badge bg-light text-dark border"><i class="bi bi-person-check text-primary"></i> <?= htmlspecialchars($d['cliente_nom'] . ' ' . $d['cliente_ape']) ?></span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" title="Editar Expediente"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-outline-info" title="Ver Certificado"><i class="bi bi-file-medical"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nuevo Difunto -->
<div class="modal fade" id="modalNuevoDifunto" tabindex="-1" aria-labelledby="modalNuevoDifuntoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title" id="modalNuevoDifuntoLabel">Registrar Difunto</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <input type="hidden" name="action_type" value="add_difunto">
              
              <div class="row g-3">
                  <div class="col-12 mb-3 border-bottom pb-2">
                      <label class="form-label fw-bold text-primary">Cliente Responsable (Deudo)</label>
                      <select class="form-select" name="id_cliente" required>
                          <option value="">-- Seleccionar Cliente Existente --</option>
                          <?php foreach($clientesList as $cl): ?>
                              <option value="<?= $cl['id'] ?>"><?= htmlspecialchars($cl['nombre'] . ' ' . $cl['apellidos']) ?> (<?= $cl['num_documento'] ?>)</option>
                          <?php endforeach; ?>
                      </select>
                      <small class="text-muted">Si no existe, debe registrarlo primero en el módulo de Clientes.</small>
                  </div>
                  
                  <div class="col-md-4">
                      <label class="form-label">DNI / Documento</label>
                      <input type="text" class="form-control" name="num_documento">
                  </div>
                  <div class="col-md-4">
                      <label class="form-label">Nombres</label>
                      <input type="text" class="form-control" name="nombre" required>
                  </div>
                  <div class="col-md-4">
                      <label class="form-label">Apellidos</label>
                      <input type="text" class="form-control" name="apellidos" required>
                  </div>

                  <div class="col-md-6">
                      <label class="form-label">Fecha de Nacimiento</label>
                      <input type="date" class="form-control" name="fecha_nacimiento">
                  </div>
                  <div class="col-md-6">
                      <label class="form-label">Fecha de Defunción</label>
                      <input type="date" class="form-control" name="fecha_defuncion" required>
                  </div>

                  <div class="col-12">
                      <label class="form-label">Causa de Fallecimiento</label>
                      <input type="text" class="form-control" name="causa">
                  </div>
                  <div class="col-12">
                      <label class="form-label">Lugar de Fallecimiento (Hospital, Domicilio, etc)</label>
                      <input type="text" class="form-control" name="lugar">
                  </div>
              </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-dark">Guardar Expediente</button>
          </div>
      </form>
    </div>
  </div>
</div>
