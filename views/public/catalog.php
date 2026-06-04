<?php
$ataudes = $data['ataudes'] ?? [];
$otrosProductos = $data['otrosProductos'] ?? [];
$servicios = $data['servicios'] ?? [];
?>

<style>
/* Premium Catalog Styles */
.catalog-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: white;
    padding: 4rem 0;
    margin-bottom: 3rem;
    position: relative;
    overflow: hidden;
}
.catalog-header::after {
    content: '';
    position: absolute;
    bottom: -50px; left: 0; right: 0;
    height: 100px;
    background: #f8f9fa;
    transform: skewY(-2deg);
}
.section-title {
    position: relative;
    padding-bottom: 15px;
    margin-bottom: 30px;
    font-weight: 700;
    color: #2c3e50;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.section-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 60px;
    height: 4px;
    background: #cba153; /* Elegant Gold */
    border-radius: 2px;
}
.premium-card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    background: #fff;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}
.premium-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}
.img-wrapper {
    position: relative;
    overflow: hidden;
    height: 280px;
    background: #f1f2f6;
}
.img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}
.premium-card:hover .img-wrapper img {
    transform: scale(1.08); /* Zoom effect */
}
.card-price-tag {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255, 255, 255, 0.95);
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: bold;
    color: #cba153;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    backdrop-filter: blur(5px);
    z-index: 2;
}
.service-item {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}
.service-item:hover {
    background-color: #f8f9fa;
    border-left: 4px solid #cba153;
    transform: translateX(5px);
}
.btn-gold {
    background: linear-gradient(135deg, #d4af37 0%, #aa7c11 100%);
    color: white;
    border: none;
    transition: all 0.3s ease;
}
.btn-gold:hover {
    background: linear-gradient(135deg, #aa7c11 0%, #876108 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3);
}
</style>

<!-- Hero Section -->
<div class="catalog-header text-center">
    <div class="container position-relative" style="z-index: 2;">
        <h1 class="display-4 fw-bold mb-3">Catálogo y Servicios</h1>
        <p class="lead fw-light text-white-50 mx-auto" style="max-width: 600px;">
            Descubra nuestras opciones diseñadas para brindar el más digno y honorable homenaje a sus seres queridos, con la elegancia y respeto que merecen.
        </p>
    </div>
</div>

<div class="container pb-5">

    <!-- Sección Ataúdes -->
    <div class="mb-5 pb-4">
        <h2 class="section-title">Colección de Ataúdes</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($ataudes as $ataud): ?>
                <div class="col">
                    <div class="card premium-card h-100">
                        <div class="img-wrapper">
                            <img 
                                src="<?= BASE_URL ?>assets/img/catalog/<?= htmlspecialchars($ataud['imagen']) ?>" 
                                alt="<?= htmlspecialchars($ataud['nombre']) ?>" 
                                onerror="this.onerror=null; this.src='<?= BASE_URL ?>assets/img/catalog/no-image.png';"
                            >
                            <div class="card-price-tag">S/ <?= number_format($ataud['precio'], 2) ?></div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="card-title fw-bold mb-3 text-dark"><?= htmlspecialchars($ataud['nombre']) ?></h5>
                            <p class="card-text text-muted small flex-grow-1">
                                <?= htmlspecialchars($ataud['descripcion']) ?>
                            </p>
                            <div class="pt-3 border-top mt-auto">
                                <a href="<?= BASE_URL ?>?controller=Home&action=proforma" class="text-decoration-none fw-bold" style="color: #cba153;">
                                    Solicitar en Proforma <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Sección Arreglos y Urnas -->
    <div class="mb-5 pb-4">
        <h2 class="section-title">Arreglos Florales y Urnas</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach ($otrosProductos as $prod): ?>
                <div class="col">
                    <div class="card premium-card h-100">
                        <div class="img-wrapper" style="height: 200px;">
                            <img 
                                src="<?= BASE_URL ?>assets/img/catalog/<?= htmlspecialchars($prod['imagen']) ?>" 
                                alt="<?= htmlspecialchars($prod['nombre']) ?>" 
                                onerror="this.onerror=null; this.src='<?= BASE_URL ?>assets/img/catalog/no-image.png';"
                            >
                            <div class="card-price-tag fs-6 py-1 px-2">S/ <?= number_format($prod['precio'], 2) ?></div>
                        </div>
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold text-dark mb-1"><?= htmlspecialchars($prod['nombre']) ?></h6>
                            <p class="small text-muted mb-0 text-truncate"><?= htmlspecialchars($prod['descripcion']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Sección Servicios Adicionales -->
    <div class="mb-5 pb-4">
        <h2 class="section-title">Servicios Adicionales</h2>
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
            <ul class="list-group list-group-flush">
                <?php foreach ($servicios as $serv): ?>
                    <li class="list-group-item service-item p-4 d-flex justify-content-between align-items-center">
                        <div class="pe-3">
                            <h5 class="mb-1 fw-bold text-dark"><?= htmlspecialchars($serv['nombre']) ?></h5>
                            <p class="text-muted mb-0 small"><?= htmlspecialchars($serv['descripcion']) ?></p>
                        </div>
                        <div class="text-end" style="min-width: 120px;">
                            <span class="fs-5 fw-bold" style="color: #cba153;">
                                S/ <?= number_format($serv['precio_base'], 2) ?>
                            </span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- CTA -->
    <div class="text-center mt-5 mb-4">
        <div class="p-5" style="background-color: #f8f9fa; border-radius: 15px; border: 1px solid #eee;">
            <h3 class="fw-bold mb-3 text-dark">¿Listo para armar un plan?</h3>
            <p class="text-muted mb-4 mx-auto" style="max-width: 600px;">
                Utilice nuestro simulador de proformas para estimar los costos y planificar los servicios de manera transparente y sin compromisos.
            </p>
            <a href="<?= BASE_URL ?>?controller=Home&action=proforma" class="btn btn-gold btn-lg px-5 rounded-pill shadow">
                <i class="bi bi-calculator me-2"></i> Iniciar Simulador
            </a>
        </div>
    </div>
</div>