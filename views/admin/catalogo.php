<?php
$productos = $data['productos'] ?? [];
$servicios = $data['servicios'] ?? [];

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    $desc = $_GET['desc'] ?? '';
    if (strpos($msg, 'creado') !== false) echo '<div class="alert alert-success alert-dismissible fade show">Registro creado.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    if (strpos($msg, 'editado') !== false) echo '<div class="alert alert-success alert-dismissible fade show">Registro editado.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    if (strpos($msg, 'eliminado') !== false) echo '<div class="alert alert-success alert-dismissible fade show">Registro eliminado.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    if ($msg == 'error') echo '<div class="alert alert-danger alert-dismissible fade show">Error: '.htmlspecialchars($desc).'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Catálogo de Productos y Servicios</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary shadow-sm me-2" data-bs-toggle="modal" data-bs-target="#prodModal" onclick="openProdModal('crear')">
            <i class="bi bi-box-seam me-1"></i> Nuevo Producto
        </button>
        <button type="button" class="btn btn-sm btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#servModal" onclick="openServModal('crear')">
            <i class="bi bi-tools me-1"></i> Nuevo Servicio
        </button>
    </div>
</div>

<!-- Nav tabs -->
<ul class="nav nav-tabs mb-4" id="catTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active fw-bold" id="prod-tab" data-bs-toggle="tab" data-bs-target="#prod" type="button" role="tab" aria-controls="prod" aria-selected="true"><i class="bi bi-box-seam"></i> Productos</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link fw-bold" id="serv-tab" data-bs-toggle="tab" data-bs-target="#serv" type="button" role="tab" aria-controls="serv" aria-selected="false"><i class="bi bi-tools"></i> Servicios Adicionales</button>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane fade show active" id="prod" role="tabpanel" aria-labelledby="prod-tab">
      <div class="card shadow-sm border-0">
          <div class="card-body p-0">
              <div class="table-responsive">
                  <table class="table table-hover align-middle mb-0">
                      <thead class="table-light">
                          <tr>
                              <th class="ps-3 border-0 rounded-start">ID</th>
                              <th class="border-0">Imagen</th>
                              <th class="border-0">Producto</th>
                              <th class="border-0">Tipo</th>
                              <th class="border-0">Precio (S/)</th>
                              <th class="border-0">Estado</th>
                              <th class="border-0 text-center rounded-end">Acciones</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($productos as $p): ?>
                          <tr>
                              <td class="ps-3 fw-bold text-muted">#<?= str_pad($p['id'], 3, '0', STR_PAD_LEFT) ?></td>
                              <td>
                                  <?php if($p['imagen']): ?>
                                      <img src="<?= BASE_URL ?>assets/img/catalog/<?= htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                  <?php else: ?>
                                      <div class="bg-light text-muted d-flex align-items-center justify-content-center border rounded" style="width: 50px; height: 50px;"><i class="bi bi-image"></i></div>
                                  <?php endif; ?>
                              </td>
                              <td>
                                  <div class="fw-bold"><?= htmlspecialchars($p['nombre']) ?></div>
                                  <div class="small text-muted text-truncate" style="max-width: 200px;"><?= htmlspecialchars($p['descripcion']) ?></div>
                              </td>
                              <td><span class="badge bg-secondary"><?= ucfirst(str_replace('_', ' ', $p['tipo'])) ?></span></td>
                              <td class="fw-bold">S/ <?= number_format($p['precio'], 2) ?></td>
                              <td>
                                  <?php if($p['estado'] == 'disponible'): ?>
                                      <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 border border-success border-opacity-25 rounded-pill">Disponible</span>
                                  <?php else: ?>
                                      <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 border border-danger border-opacity-25 rounded-pill">Agotado</span>
                                  <?php endif; ?>
                              </td>
                              <td class="text-center">
                                  <button class="btn btn-sm btn-outline-primary" title="Editar" onclick='openProdModal("editar", <?= json_encode($p) ?>)' data-bs-toggle="modal" data-bs-target="#prodModal"><i class="bi bi-pencil"></i></button>
                                  <form method="POST" action="index.php?controller=Admin&action=catalogo" class="d-inline" onsubmit="return confirm('¿Seguro que desea eliminar este producto? Esta acción no se puede deshacer.')">
                                      <input type="hidden" name="action_type" value="eliminar_producto">
                                      <input type="hidden" name="id_producto" value="<?= $p['id'] ?>">
                                      <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                  </form>
                              </td>
                          </tr>
                          <?php endforeach; ?>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>
  
  <div class="tab-pane fade" id="serv" role="tabpanel" aria-labelledby="serv-tab">
      <div class="card shadow-sm border-0">
          <div class="card-body p-0">
              <div class="table-responsive">
                  <table class="table table-hover align-middle mb-0">
                      <thead class="table-light">
                          <tr>
                              <th class="ps-3 border-0 rounded-start">ID</th>
                              <th class="border-0">Servicio</th>
                              <th class="border-0">Descripción</th>
                              <th class="border-0">Precio Base (S/)</th>
                              <th class="border-0">Estado</th>
                              <th class="border-0 text-center rounded-end">Acciones</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($servicios as $s): ?>
                          <tr>
                              <td class="ps-3 fw-bold text-muted">#<?= str_pad($s['id'], 3, '0', STR_PAD_LEFT) ?></td>
                              <td class="fw-bold"><?= htmlspecialchars($s['nombre']) ?></td>
                              <td><div class="small text-muted text-truncate" style="max-width: 300px;"><?= htmlspecialchars($s['descripcion']) ?></div></td>
                              <td class="fw-bold">S/ <?= number_format($s['precio_base'], 2) ?></td>
                              <td>
                                  <?php if($s['estado'] == 'activo'): ?>
                                      <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 border border-success border-opacity-25 rounded-pill">Activo</span>
                                  <?php else: ?>
                                      <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 border border-danger border-opacity-25 rounded-pill">Inactivo</span>
                                  <?php endif; ?>
                              </td>
                              <td class="text-center">
                                  <button class="btn btn-sm btn-outline-primary" title="Editar" onclick='openServModal("editar", <?= json_encode($s) ?>)' data-bs-toggle="modal" data-bs-target="#servModal"><i class="bi bi-pencil"></i></button>
                                  <form method="POST" action="index.php?controller=Admin&action=catalogo" class="d-inline" onsubmit="return confirm('¿Seguro que desea eliminar este servicio? Esta acción puede afectar cotizaciones pasadas.')">
                                      <input type="hidden" name="action_type" value="eliminar_servicio">
                                      <input type="hidden" name="id_servicio" value="<?= $s['id'] ?>">
                                      <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                  </form>
                              </td>
                          </tr>
                          <?php endforeach; ?>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>
</div>

<!-- Modal Producto -->
<div class="modal fade" id="prodModal" tabindex="-1" aria-labelledby="prodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="prodModalLabel">Nuevo Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?controller=Admin&action=catalogo" id="prodForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action_type" id="prod_action_type" value="crear_producto">
                    <input type="hidden" name="id_producto" id="prod_id" value="">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre del Producto</label>
                        <input type="text" name="nombre" id="prod_nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipo</label>
                        <select name="tipo" id="prod_tipo" class="form-select" required>
                            <option value="ataud">Ataúd</option>
                            <option value="arreglo_floral">Arreglo Floral</option>
                            <option value="urna">Urna</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea name="descripcion" id="prod_descripcion" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Precio (S/)</label>
                            <input type="number" step="0.01" name="precio" id="prod_precio" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Estado</label>
                            <select name="estado" id="prod_estado" class="form-select" required>
                                <option value="disponible">Disponible</option>
                                <option value="agotado">Agotado</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Imagen</label>
                        <input type="file" name="imagen" id="prod_imagen" class="form-control" accept="image/*">
                        <small class="text-muted" id="prod_imagen_help" style="display:none;">Seleccione una nueva imagen para reemplazar la actual, o deje en blanco para mantenerla.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Servicio -->
<div class="modal fade" id="servModal" tabindex="-1" aria-labelledby="servModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="servModalLabel">Nuevo Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?controller=Admin&action=catalogo" id="servForm">
                <div class="modal-body">
                    <input type="hidden" name="action_type" id="serv_action_type" value="crear_servicio">
                    <input type="hidden" name="id_servicio" id="serv_id" value="">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre del Servicio</label>
                        <input type="text" name="nombre" id="serv_nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea name="descripcion" id="serv_descripcion" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Precio Base (S/)</label>
                            <input type="number" step="0.01" name="precio_base" id="serv_precio" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Estado</label>
                            <select name="estado" id="serv_estado" class="form-select" required>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openProdModal(action, data = null) {
    document.getElementById('prodForm').reset();
    if(action === 'crear') {
        document.getElementById('prodModalLabel').innerText = 'Nuevo Producto';
        document.getElementById('prod_action_type').value = 'crear_producto';
        document.getElementById('prod_id').value = '';
        document.getElementById('prod_imagen').required = true;
        document.getElementById('prod_imagen_help').style.display = 'none';
    } else {
        document.getElementById('prodModalLabel').innerText = 'Editar Producto';
        document.getElementById('prod_action_type').value = 'editar_producto';
        document.getElementById('prod_id').value = data.id;
        document.getElementById('prod_nombre').value = data.nombre;
        document.getElementById('prod_tipo').value = data.tipo;
        document.getElementById('prod_descripcion').value = data.descripcion;
        document.getElementById('prod_precio').value = data.precio;
        document.getElementById('prod_estado').value = data.estado;
        document.getElementById('prod_imagen').required = false;
        document.getElementById('prod_imagen_help').style.display = 'block';
    }
}

function openServModal(action, data = null) {
    document.getElementById('servForm').reset();
    if(action === 'crear') {
        document.getElementById('servModalLabel').innerText = 'Nuevo Servicio';
        document.getElementById('serv_action_type').value = 'crear_servicio';
        document.getElementById('serv_id').value = '';
    } else {
        document.getElementById('servModalLabel').innerText = 'Editar Servicio';
        document.getElementById('serv_action_type').value = 'editar_servicio';
        document.getElementById('serv_id').value = data.id;
        document.getElementById('serv_nombre').value = data.nombre;
        document.getElementById('serv_descripcion').value = data.descripcion;
        document.getElementById('serv_precio').value = data.precio_base;
        document.getElementById('serv_estado').value = data.estado;
    }
}
</script>
