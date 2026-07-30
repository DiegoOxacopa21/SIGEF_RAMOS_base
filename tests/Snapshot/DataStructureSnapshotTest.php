<?php

/**
 * Pruebas de Instantánea (Snapshot Test) - Estructuras de Datos de Modelos
 *
 * Este archivo evalúa que los arreglos y objetos retornados por los modelos del sistema
 * mantengan la estructura de claves y metadatos esperada (snapshots de datos).
 */

use Tests\TestCase;

test('Snapshot: Estructura de llaves de un producto en el catálogo coincide con el snapshot esperado', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Catalog.php';
    $catalogModel = new \Catalog();
    $productos = $catalogModel->getAllProductos();

    expect($productos)->not->toBeEmpty();
    $productoSnapshot = $productos[0];

    expect(array_keys($productoSnapshot))->toContain('id', 'nombre', 'descripcion', 'precio', 'categoria_id');
});

test('Snapshot: Estructura del registro de usuario devuelto por la base de datos', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/User.php';
    $userModel = new \User();
    $usuarios = $userModel->getAllUsers();

    expect($usuarios)->not->toBeEmpty();
    $userSnapshot = $usuarios[0];

    expect($userSnapshot)->toHaveKeys(['id', 'nombre', 'email', 'rol_nombre', 'sede_nombre']);
});

test('Snapshot: Estructura de datos del registro de cliente coincide con el esquema base', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Client.php';
    $clientModel = new \Client();
    $clientes = $clientModel->getAllClientes();

    expect($clientes)->not->toBeEmpty();
    $clienteSnapshot = $clientes[0];

    expect($clienteSnapshot)->toHaveKeys(['id', 'nombre', 'dni', 'telefono', 'direccion']);
});

test('Snapshot: Estructura del resumen de cotización registrada en base de datos', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Quotation.php';
    $quotationModel = new \Quotation();
    $cotizaciones = $quotationModel->getAllCotizaciones();

    expect($cotizaciones)->not->toBeEmpty();
    $cotizacionSnapshot = $cotizaciones[0];

    expect($cotizacionSnapshot)->toHaveKeys(['id', 'codigo', 'cliente_id', 'total', 'estado']);
});
