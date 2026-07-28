<?php

use Tests\TestCase;

test('crearCotizacion inserts cotizacion and details in database', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Quotation.php';
    $quoteModel = new \Quotation();

    $result = $quoteModel->crearCotizacion([
        'id_cliente' => 1,
        'items' => [
            ['id_producto' => 1, 'id_servicio' => null, 'descripcion' => 'Ataud Clasico', 'cantidad' => 1, 'precio' => 1500, 'subtotal' => 1500],
        ],
        'subtotal' => 1500,
        'igv' => 270,
        'total' => 1770,
    ]);

    expect($result)->toBeTrue();

    $stmt = $pdo->query("SELECT * FROM cotizaciones ORDER BY id DESC LIMIT 1");
    $cotizacion = $stmt->fetch(\PDO::FETCH_ASSOC);
    expect($cotizacion)->not->toBeFalse();
    expect((int) $cotizacion['id_cliente'])->toBe(1);
    expect((float) $cotizacion['total'])->toEqual(1770.0);
    expect($cotizacion['estado'])->toBe('pendiente');

    $stmt = $pdo->prepare("SELECT * FROM detalle_cotizacion WHERE id_cotizacion = ?");
    $stmt->execute([$cotizacion['id']]);
    $detalles = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    expect($detalles)->toHaveCount(1);
    expect($detalles[0]['descripcion'])->toBe('Ataud Clasico');
});
