<?php
$usuarios = $data['usuarios'] ?? [];
$sedes = $data['sedes'] ?? [];
$roles = $data['roles'] ?? [];

// Mostrar mensajes
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    $desc = $_GET['desc'] ?? '';
    if ($msg == 'creado') echo '<div class="alert alert-success alert-dismissible fade show">Usuario creado correctamente.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    if ($msg == 'editado') echo '<div class="alert alert-success alert-dismissible fade show">Usuario editado correctamente.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    if ($msg == 'estado_actualizado') echo '<div class="alert alert-success alert-dismissible fade show">Estado del usuario actualizado.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    if ($msg == 'error') echo '<div class="alert alert-danger alert-dismissible fade show">Error: '.htmlspecialchars($desc).'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Gestión de Usuarios</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#userModal" onclick="openCreateModal()">
            <i class="bi bi-person-plus me-1"></i> Nuevo Usuario
        </button>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 border-0 rounded-start">ID</th>
                        <th class="border-0">Nombre</th>
                        <th class="border-0">Email</th>
                        <th class="border-0">Rol</th>
                        <th class="border-0">Sede</th>
                        <th class="border-0">Estado</th>
                        <th class="border-0 text-center rounded-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td class="ps-3 fw-bold text-muted">#<?= str_pad($u['id'], 3, '0', STR_PAD_LEFT) ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    <?= strtoupper(substr($u['nombre'], 0, 1)) ?>
                                </div>
                                <?= htmlspecialchars($u['nombre']) ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($u['rol_nombre']) ?></span></td>
                        <td><span class="small text-muted"><?= htmlspecialchars($u['sede_nombre'] ?? 'N/A') ?></span></td>
                        <td>
                            <?php if($u['estado'] == 'activo'): ?>
                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 border border-success border-opacity-25 rounded-pill"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 border border-danger border-opacity-25 rounded-pill"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" title="Editar" onclick='openEditModal(<?= json_encode($u) ?>)' data-bs-toggle="modal" data-bs-target="#userModal"><i class="bi bi-pencil"></i></button>
                            <form method="POST" action="index.php?controller=Admin&action=usuarios" class="d-inline">
                                <input type="hidden" name="action_type" value="toggle_estado">
                                <input type="hidden" name="id_usuario" value="<?= $u['id'] ?>">
                                <input type="hidden" name="nuevo_estado" value="<?= $u['estado'] == 'activo' ? 'inactivo' : 'activo' ?>">
                                <?php if($u['estado'] == 'activo'): ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Desactivar" onclick="return confirm('¿Seguro que desea desactivar este usuario?')"><i class="bi bi-person-x"></i></button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Activar" onclick="return confirm('¿Seguro que desea activar este usuario?')"><i class="bi bi-person-check"></i></button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar Usuario -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="userModalLabel">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?controller=Admin&action=usuarios" id="userForm">
                <div class="modal-body">
                    <input type="hidden" name="action_type" id="action_type" value="crear_usuario">
                    <input type="hidden" name="id_usuario" id="id_usuario" value="">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre Completo</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Correo Electrónico</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control">
                        <small class="text-muted" id="passwordHelp" style="display:none;">Dejar en blanco para no cambiarla.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Rol</label>
                        <select name="id_rol" id="id_rol" class="form-select" required>
                            <?php foreach($roles as $r): ?>
                                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sede</label>
                        <select name="id_sede" id="id_sede" class="form-select" required>
                            <?php foreach($sedes as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Estado</label>
                        <select name="estado" id="estado" class="form-select" required>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
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

<script>
function openCreateModal() {
    document.getElementById('userModalLabel').innerText = 'Nuevo Usuario';
    document.getElementById('action_type').value = 'crear_usuario';
    document.getElementById('id_usuario').value = '';
    document.getElementById('userForm').reset();
    document.getElementById('password').required = true;
    document.getElementById('passwordHelp').style.display = 'none';
}

function openEditModal(user) {
    document.getElementById('userModalLabel').innerText = 'Editar Usuario';
    document.getElementById('action_type').value = 'editar_usuario';
    document.getElementById('id_usuario').value = user.id;
    document.getElementById('nombre').value = user.nombre;
    document.getElementById('email').value = user.email;
    document.getElementById('id_rol').value = user.id_rol;
    document.getElementById('id_sede').value = user.id_sede;
    document.getElementById('estado').value = user.estado;
    document.getElementById('password').required = false;
    document.getElementById('password').value = '';
    document.getElementById('passwordHelp').style.display = 'block';
}
</script>
