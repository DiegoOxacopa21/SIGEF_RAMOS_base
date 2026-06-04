<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold text-dark">Módulo: <?= htmlspecialchars($title) ?></h1>
</div>

<div class="alert alert-secondary mt-4 shadow-sm py-5 text-center" role="alert" style="background-color: #fcfcfc;">
    <i class="bi bi-tools fs-1 text-muted mb-3 d-block"></i>
    <h4 class="alert-heading text-dark fw-bold">En Construcción</h4>
    <p class="text-muted max-w-50 mx-auto">Este módulo se encuentra en fase de desarrollo. Pronto estarán disponibles las funcionalidades de gestión y reportes para esta área.</p>
    <hr>
    <a href="<?= BASE_URL ?>?controller=Admin&action=dashboard" class="btn btn-outline-secondary mt-2">
        <i class="bi bi-arrow-left me-1"></i> Volver al Inicio
    </a>
</div>
