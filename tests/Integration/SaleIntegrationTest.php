<?php

use Tests\TestCase;

test('getVentasFiltradas retorna resultados correctos con filtros', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    $pdo->exec("INSERT INTO ventas (id_cliente, id_vendedor, subtotal, igv, total, estado, fecha) VALUES (1, 3, 1000, 180, 1180, 'pagada', '2025-03-15 10:00:00')");
    $pdo->exec("INSERT INTO ventas (id_cliente, id_vendedor, subtotal, igv, total, estado, fecha) VALUES (1, 3, 2000, 360, 2360, 'pendiente', '2025-06-20 10:00:00')");
    $pdo->exec("INSERT INTO ventas (id_cliente, id_vendedor, subtotal, igv, total, estado, fecha) VALUES (2, 3, 500, 90, 590, 'pagada', '2025-09-01 10:00:00')");

    require_once __DIR__ . '/../../models/Sale.php';
    $saleModel = new \Sale();

    // Total: seed(1 pending) + 3 new = 4 ventas
    $ventas = $saleModel->getVentasFiltradas('2025-01-01', '2025-06-30', 'pagada', '');
    expect($ventas)->toHaveCount(1);
    expect($ventas[0]['estado'])->toBe('pagada');

    $ventas = $saleModel->getVentasFiltradas('', '', 'pendiente', '');
    expect($ventas)->toHaveCount(2);

    $ventas = $saleModel->getVentasFiltradas('', '', '', '');
    expect($ventas)->toHaveCount(4);
});

test('crearVentaDesdeCotizacion debe crear venta con operación (FALLA: DATE_ADD específico de MySQL)', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    $pdo->exec("INSERT INTO cotizaciones (id_cliente, subtotal, igv, total, estado) VALUES (1, 1000, 180, 1180, 'pendiente')");
    $id_cot = $pdo->lastInsertId();
    $pdo->prepare("INSERT INTO detalle_cotizacion (id_cotizacion, id_producto, descripcion, cantidad, precio_unitario, subtotal) VALUES (?, 1, 'Test', 1, 1000, 1000)")->execute([$id_cot]);

    require_once __DIR__ . '/../../models/Sale.php';
    $saleModel = new \Sale();

    $result = $saleModel->crearVentaDesdeCotizacion($id_cot, 3);

    expect($result)->not->toBeFalse('crearVentaDesdeCotizacion falla: DATE_ADD(NOW(),INTERVAL 1 DAY) no existe en SQLite');
    expect($result)->toBeNumeric();
});
