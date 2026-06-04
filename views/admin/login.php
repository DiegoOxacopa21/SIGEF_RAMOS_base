<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white text-center py-4 text-primary">
                <i class="bi bi-person-circle fs-1"></i>
                <h4 class="mb-0 mt-2 text-white">Acceso al Sistema</h4>
            </div>
            <div class="card-body p-4 bg-white">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <form action="<?= BASE_URL ?>?controller=Auth&action=login" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="bi bi-envelope"></i> Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="correo@ramos.com" value="admin@ramos.com">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label"><i class="bi bi-lock"></i> Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="******" value="123456">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="background-color: #5d4037; border:none;">Ingresar</button>
                </form>
            </div>
            <div class="card-footer text-center bg-light">
                <small class="text-muted">Área restringida para personal de la Funeraria.</small><br>
                <small>¿Necesitas ayuda? Contacta al administrador.</small>
            </div>
        </div>
    </div>
</div>
