<?php

/**
 * Pruebas de Regresión - Registro de Pagos y Emisión de Comprobantes
 *
 * Este archivo evalúa que el proceso de cobro en el modelo Payment efectúe correctamente el cambio
 * de estado de las ventas a 'pagada', genere la numeración correlativa con formato (ej. B001-000001)
 * y filtre únicamente los métodos de pago habilitados.
 */

use Tests\TestCase;

test('Payment registrarPago updates sale status to pagada and creates comprobante regression', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Payment.php';
    $paymentModel = new \Payment();

    $result = $paymentModel->registrarPago([
        'id_venta' => 1,
        'id_metodo_pago' => 1,
        'id_cajero' => 4,
        'monto' => 1770.00,
        'referencia' => 'REF-001',
        'tipo_comprobante' => 'boleta',
    ]);

    expect($result)->toBeTrue();

    $stmtV = $pdo->query("SELECT estado FROM ventas WHERE id = 1");
    expect($stmtV->fetchColumn())->toBe('pagada');

    $stmtC = $pdo->query("SELECT * FROM comprobantes WHERE id_venta = 1");
    $comp = $stmtC->fetch(\PDO::FETCH_ASSOC);
    expect($comp['serie'])->toBe('B001');
    expect($comp['numero'])->toBe('000001');
});

test('Payment getMetodosPago filters active methods only regression', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Payment.php';
    $paymentModel = new \Payment();

    $metodos = $paymentModel->getMetodosPago();
    expect($metodos)->not->toBeEmpty();
    foreach ($metodos as $m) {
        expect($m['estado'])->toBe('activo');
    }
});
