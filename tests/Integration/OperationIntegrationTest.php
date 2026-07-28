<?php

use Tests\TestCase;

test('updateOperacion assigns sala and flota to operation', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    $pdo->exec("INSERT INTO ventas (id_cliente, id_vendedor, subtotal, igv, total, estado) VALUES (1, 3, 1000, 180, 1180, 'pendiente')");
    $id_venta = $pdo->lastInsertId();
    $pdo->prepare("INSERT INTO operaciones (id_venta, fecha_programada, estado) VALUES (?, datetime('now', '+1 day'), 'pendiente')")->execute([$id_venta]);
    $id_op = $pdo->lastInsertId();

    require_once __DIR__ . '/../../models/Operation.php';
    $opModel = new \Operation();

    $result = $opModel->updateOperacion($id_op, [
        'id_sala' => 1,
        'id_flota' => 1,
        'estado' => 'en_proceso',
        'observaciones' => 'Asignado sala paz y carroza',
    ]);

    expect($result)->toBeTrue();

    $stmt = $pdo->prepare("SELECT * FROM operaciones WHERE id = ?");
    $stmt->execute([$id_op]);
    $op = $stmt->fetch(\PDO::FETCH_ASSOC);
    expect($op['id_sala'])->toBe(1);
    expect($op['id_flota'])->toBe(1);
    expect($op['estado'])->toBe('en_proceso');
});
