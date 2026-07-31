<?php

use Tests\TestCase;

test('eliminarCotizacion rechaza cotizaciones que no están pendientes', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    $pdo->exec("INSERT INTO cotizaciones (id_cliente, subtotal, igv, total, estado) VALUES (1, 1000, 180, 1180, 'aprobada')");
    $id = $pdo->lastInsertId();

    require_once __DIR__ . '/../../models/Quotation.php';
    $quoteModel = new \Quotation();

    $result = $quoteModel->eliminarCotizacion($id);
    expect($result)->toBeFalse();
});

test('eliminarCotizacion tiene éxito en cotizaciones pendientes y elimina en cascada', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    $pdo->exec("INSERT INTO cotizaciones (id_cliente, subtotal, igv, total, estado) VALUES (1, 1000, 180, 1180, 'pendiente')");
    $id = $pdo->lastInsertId();
    $pdo->prepare("INSERT INTO detalle_cotizacion (id_cotizacion, id_producto, descripcion, cantidad, precio_unitario, subtotal) VALUES (?, 1, 'Ataud', 1, 1000, 1000)")
        ->execute([$id]);

    require_once __DIR__ . '/../../models/Quotation.php';
    $quoteModel = new \Quotation();

    $result = $quoteModel->eliminarCotizacion($id);
    expect($result)->toBeTrue();

    $stmt = $pdo->prepare("SELECT id FROM cotizaciones WHERE id = ?");
    $stmt->execute([$id]);
    expect($stmt->fetch())->toBeFalse();

    $stmt = $pdo->prepare("SELECT id FROM detalle_cotizacion WHERE id_cotizacion = ?");
    $stmt->execute([$id]);
    expect($stmt->fetchAll())->toHaveLength(0);
});

test('crearCotizacion rechaza items vacíos', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Quotation.php';
    $quoteModel = new \Quotation();

    $result = $quoteModel->crearCotizacion([
        'id_cliente' => 1,
        'items' => [],
        'subtotal' => 0,
        'igv' => 0,
        'total' => 0,
    ]);

    expect($result)->toBeFalse();
});
