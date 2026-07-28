<?php

use Tests\TestCase;

test('getVentasFiltradas returns correct results with filters', function () {
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
