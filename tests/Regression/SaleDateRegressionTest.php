<?php

/**
 * Pruebas de Regresión - Procesamiento de Ventas y Sintaxis SQL
 *
 * Este archivo evalúa las regresiones asociadas al modelo Sale, verificando el comportamiento
 * ante la falta de cotización (control de rollback en transacciones) y la presencia de funciones de
 * fecha MySQL-específicas como DATE_ADD(NOW(), INTERVAL 1 DAY) que causan fallos en otros motores.
 */

use Tests\TestCase;

test('Sale creation from non-existent cotizacion fails and rolls back transaction regression', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Sale.php';
    $saleModel = new \Sale();

    $result = $saleModel->crearVentaDesdeCotizacion(9999, 1);
    expect($result)->toBeFalse();

    $stmt = $pdo->query("SELECT COUNT(*) FROM ventas");
    expect((int)$stmt->fetchColumn())->toBe(1); // Only the initial seed sale exists
});

test('Sale creation from cotizacion triggers DATE_ADD MySQL syntax incompatibility regression', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    // Cotizacion 1 exists in seed data ('aprobada' / ready)
    require_once __DIR__ . '/../../models/Sale.php';
    $saleModel = new \Sale();

    // DATE_ADD(NOW(), INTERVAL 1 DAY) fails in SQLite PDO driver
    $result = $saleModel->crearVentaDesdeCotizacion(1, 1);
    expect($result)->toBeFalse();
});
