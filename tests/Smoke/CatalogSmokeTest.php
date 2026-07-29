<?php

/**
 * Pruebas de Humo (Smoke Test) - Catálogo y Recursos Logísticos
 *
 * Este archivo evalúa que los servicios y productos del catálogo funerario, así como los
 * recursos de inventario (salas de velación y vehículos de flota), puedan ser leídos de la
 * base de datos para confirmar que los componentes logísticos y comerciales estén operativos.
 */

use Tests\TestCase;

test('Catalog model retrieves default catalog items smoke test', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Catalog.php';
    $catalogModel = new \Catalog();

    $productos = $catalogModel->getAllProductos();
    $servicios = $catalogModel->getAllServicios();

    expect($productos)->not->toBeEmpty();
    expect($servicios)->not->toBeEmpty();
});

test('Resource model loads fleet and salas de velacion smoke test', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Resource.php';
    $resourceModel = new \Resource();

    $flota = $resourceModel->getAllFlota();
    $salas = $resourceModel->getAllSalas();

    expect($flota)->not->toBeEmpty();
    expect($salas)->not->toBeEmpty();
});
