<?php
// Manejo sencillo de guardado para demostración de funcionalidad
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action_type']) && $_POST['action_type'] == 'add_cliente') {
    require_once 'models/Client.php';
    $cm = new Client();
    $cm->addCliente($_POST);
    // Recargar para evitar reenvio y mostrar nuevo dato
    header("Location: " . BASE_URL . "?controller=Admin&action=clientes");
    exit;
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Clientes / Deudos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente">
            <i class="bi bi-person-plus me-1"></i> Registrar Cliente
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
                        <th class="border-0">Nombre Completo</th>
                        <th class="border-0">Teléfono</th>
                        <th class="border-0">Email</th>
                        <th class="border-0">Fecha Registro</th>
                        <th class="border-0 text-center rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clientes)): ?>
                        <tr><td colspan="6" class="text-center py-4">No hay clientes registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $c): ?>
                        <tr>
                            <td class="ps-3"><span class="badge bg-secondary"><?= $c['tipo_documento'] ?></span> <?= htmlspecialchars($c['num_documento']) ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellidos']) ?></td>
                            <td><?= htmlspecialchars($c['telefono']) ?></td>
                            <td><?= htmlspecialchars($c['email']) ?></td>
                            <td><?= date('d/m/Y', strtotime($c['fecha_registro'])) ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></button>
                                <a href="<?= BASE_URL ?>?controller=Admin&action=ventas&client=<?= $c['id'] ?>" class="btn btn-sm btn-outline-success" title="Nueva Venta"><i class="bi bi-cart"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nuevo Cliente -->
<div class="modal fade" id="modalNuevoCliente" tabindex="-1" aria-labelledby="modalNuevoClienteLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="modalNuevoClienteLabel">Registrar Nuevo Cliente</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <input type="hidden" name="action_type" value="add_cliente">
              
              <div class="row g-3">
                  <div class="col-md-4">
                      <label class="form-label">Tipo Documento</label>
                      <select class="form-select" name="tipo_documento" required>
                          <option value="DNI">DNI</option>
                          <option value="CE">CE</option>
                          <option value="PASAPORTE">Pasaporte</option>
                          <option value="RUC">RUC</option>
                      </select>
                  </div>
                  <div class="col-md-8">
                      <label class="form-label">Número de Documento</label>
                      <input type="text" class="form-control" name="num_documento" required>
                  </div>
                  
                  <div class="col-md-6">
                      <label class="form-label">Nombres</label>
                      <input type="text" class="form-control" name="nombre" required>
                  </div>
                  <div class="col-md-6">
                      <label class="form-label">Apellidos</label>
                      <input type="text" class="form-control" name="apellidos" required>
                  </div>

                  <div class="col-md-6">
                      <label class="form-label">Teléfono</label>
                      <input type="tel" class="form-control" name="telefono" required>
                  </div>
                  <div class="col-md-6">
                      <label class="form-label">Correo Electrónico</label>
                      <input type="email" class="form-control" name="email">
                  </div>

                  <div class="col-12">
                      <label class="form-label">Dirección Completa</label>
                      <input type="text" class="form-control" name="direccion">
                  </div>
              </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar Cliente</button>
          </div>
      </form>
    </div>
  </div>
</div>
