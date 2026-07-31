<?php

use Tests\TestCase;

test('registrarPago marca la venta como pagada y crea comprobante', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    $pdo->exec("INSERT INTO ventas (id_cliente, id_vendedor, subtotal, igv, total, estado) VALUES (1, 3, 1500, 270, 1770, 'pendiente')");
    $id_venta = $pdo->lastInsertId();

    require_once __DIR__ . '/../../models/Payment.php';
    $paymentModel = new \Payment();

    $result = $paymentModel->registrarPago([
        'id_venta' => $id_venta,
        'id_metodo_pago' => 1,
        'id_cajero' => 4,
        'monto' => 1770,
        'referencia' => 'EFECTIVO-001',
        'tipo_comprobante' => 'boleta',
    ]);

    expect($result)->toBeTrue();

    $stmt = $pdo->prepare("SELECT estado FROM ventas WHERE id = ?");
    $stmt->execute([$id_venta]);
    expect($stmt->fetch()['estado'])->toBe('pagada');

    $stmt = $pdo->prepare("SELECT * FROM pagos WHERE id_venta = ?");
    $stmt->execute([$id_venta]);
    $pago = $stmt->fetch(\PDO::FETCH_ASSOC);
    expect($pago)->not->toBeFalse();
    expect((float) $pago['monto'])->toEqual(1770.0);

    $stmt = $pdo->prepare("SELECT * FROM comprobantes WHERE id_venta = ?");
    $stmt->execute([$id_venta]);
    $comp = $stmt->fetch(\PDO::FETCH_ASSOC);
    expect($comp)->not->toBeFalse();
    expect($comp['tipo'])->toBe('boleta');
    expect($comp['serie'])->toBe('B001');
});
