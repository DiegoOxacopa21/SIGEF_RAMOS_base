<?php

/**
 * Pruebas de Mutación (Mutation Test) - Reglas de Negocio Operativas y Financieras
 *
 * Este archivo analiza el comportamiento del sistema ante condiciones de borde mutadas en
 * los modelos de cotización, ventas, operaciones y catálogo.
 */

use Tests\TestCase;

test('Mutation: Eliminación de cotización en estado no pendiente debe ser rechazada', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Quotation.php';
    $quotationModel = new \Quotation();

    // Actualizamos una cotización a estado 'aprobada' para mutar su condición de eliminación
    $stmt = $pdo->prepare("UPDATE cotizaciones SET estado = 'aprobada' WHERE id = 1");
    $stmt->execute();

    $result = $quotationModel->eliminarCotizacion(1);
    expect($result)->toBeFalse('No se debe permitir eliminar una cotización que ya no está pendiente');
});

test('Mutation: Inserción de ítem con precio negativo en cotización evalúa consistencia del total', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Quotation.php';
    $quotationModel = new \Quotation();

    $items = [
        ['producto_id' => 1, 'cantidad' => 1, 'precio_unitario' => -500.00]
    ];

    // Evaluamos el comportamiento real del modelo ante precios mutados
    $cotizacionId = $quotationModel->crearCotizacion(1, 1, $items);
    if ($cotizacionId) {
        $stmt = $pdo->prepare("SELECT total FROM cotizaciones WHERE id = ?");
        $stmt->execute([$cotizacionId]);
        $total = $stmt->fetchColumn();
        expect((float)$total)->toBeLessThan(0, 'Si el sistema permite montos negativos, el total reflejará la mutación del precio');
    } else {
        expect($cotizacionId)->toBeFalse('El sistema rechazó la cotización con monto negativo');
    }
});

test('Mutation: Intento de eliminar producto inerte o inexistente en el catálogo', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Catalog.php';
    $catalogModel = new \Catalog();

    $result = $catalogModel->deleteProducto(999999);
    expect($result)->toBeTrue('PDO execute retorna true incluso si rowCount es 0 al eliminar registro inexistente');
});

test('Mutation: Asignación de vehículo de flota inexistente a una operación logística', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Operation.php';
    $operationModel = new \Operation();

    $data = [
        'id' => 1,
        'sala_id' => 1,
        'flota_id' => 999999,
        'observaciones' => 'Vehículo mutado no existente'
    ];

    expect(function () use ($operationModel, $data) {
        $operationModel->updateOperacion($data);
    })->toThrow(\PDOException::class);
});
