<?php

use Tests\TestCase;

test('addCliente y getClienteById retornan los datos correctos', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Client.php';
    $clientModel = new \Client();

    $result = $clientModel->addCliente([
        'tipo_documento' => 'DNI',
        'num_documento' => '99999999',
        'nombre' => 'Pedro',
        'apellidos' => 'Garcia',
        'telefono' => '999888777',
        'email' => 'pedro@test.com',
        'direccion' => 'Av. Test 123',
    ]);

    expect($result)->toBeTrue();

    $stmt = $pdo->query("SELECT id FROM clientes WHERE num_documento = '99999999'");
    $id = $stmt->fetch()['id'];

    $cliente = $clientModel->getClienteById($id);
    expect($cliente)->not->toBeFalse();
    expect($cliente['nombre'])->toBe('Pedro');
    expect($cliente['apellidos'])->toBe('Garcia');
});

test('getAllClientes retorna todos los clientes ordenados por fecha', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Client.php';
    $clientModel = new \Client();

    $clientes = $clientModel->getAllClientes();
    expect($clientes)->toHaveCount(2);
    expect($clientes[0])->toHaveKey('nombre');
    expect($clientes[0])->toHaveKey('num_documento');
});
