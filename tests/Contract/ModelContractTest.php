<?php

/**
 * Pruebas de Contrato (Contract Test) - Contratos de Datos y Modelos
 *
 * Este archivo evalúa los contratos de tipos de datos, llaves obligatorias e integridad
 * referencial retornados por los modelos del sistema hacia los controladores.
 */

use Tests\TestCase;

test('Contract: Modelo Payment retorna tipos de datos estructurados para los métodos de pago', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Payment.php';
    $paymentModel = new \Payment();
    $metodos = $paymentModel->getMetodosPago();

    expect($metodos)->toBeArray();
    expect($metodos)->not->toBeEmpty();

    foreach ($metodos as $metodo) {
        expect($metodo)->toHaveKeys(['id', 'nombre', 'activo']);
        expect(is_numeric($metodo['id']))->toBeTrue('El ID del método de pago debe ser numérico');
    }
});

test('Contract: Modelo Client retorna un ID entero único tras crear un nuevo cliente', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Client.php';
    $clientModel = new \Client();

    $data = [
        'nombre' => 'Juan Contrato',
        'dni' => '99887766',
        'telefono' => '987654321',
        'direccion' => 'Av. Contrato 123',
        'email' => 'juan.contrato@ejemplo.com'
    ];

    $clienteId = $clientModel->addCliente($data);
    expect($clienteId)->not->toBeFalse();
    expect(is_numeric($clienteId))->toBeTrue('El contrato de addCliente exige devolver el ID numérico insertado');
});

test('Contract: Modelo Catalog garantiza precios numéricos en la lista de productos', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Catalog.php';
    $catalogModel = new \Catalog();
    $productos = $catalogModel->getAllProductos();

    expect($productos)->not->toBeEmpty();
    foreach ($productos as $producto) {
        expect(is_numeric($producto['precio']))->toBeTrue('El precio de todo producto en el catálogo debe ser de tipo numérico');
    }
});

test('Contract: Modelo Resource entrega contratos de llaves primarias válidas para salas y flota', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Resource.php';
    $resourceModel = new \Resource();

    $salas = $resourceModel->getAllSalas();
    $flota = $resourceModel->getAllFlota();

    expect($salas)->not->toBeEmpty();
    expect($flota)->not->toBeEmpty();

    expect($salas[0])->toHaveKeys(['id', 'nombre', 'estado']);
    expect($flota[0])->toHaveKeys(['id', 'nombre', 'estado']);
});
