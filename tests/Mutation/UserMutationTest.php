<?php

/**
 * Pruebas de Mutación (Mutation Test) - Lógica de Usuarios y Autenticación
 *
 * Este archivo evalúa la solidez del sistema ante entradas mutadas y condiciones al límite,
 * verificando si el modelo User detecta y bloquea estados mutados no válidos.
 */

use Tests\TestCase;

test('Mutation: Intento de desactivar superadministrador id=1 debe ser rechazado por la regla de negocio', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/User.php';
    $userModel = new \User();

    expect(function () use ($userModel) {
        $userModel->toggleEstado(1);
    })->toThrow(\Exception::class);
});

test('Mutation: Mutación de contraseña vacía en autenticación falla la verificación de credenciales', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/User.php';
    $userModel = new \User();

    $result = $userModel->login('admin@ramos.com', '');
    expect($result)->toBeFalse('La autenticación debe fallar al ingresar una contraseña vacía');
});

test('Mutation: Mutación de email con caracteres SQL inyectados no autentica al usuario', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/User.php';
    $userModel = new \User();

    $result = $userModel->login("admin@ramos.com' OR '1'='1", '123456');
    expect($result)->toBeFalse('La consulta preparada debe neutralizar los caracteres de inyección SQL');
});

test('Mutation: Mutación de creación de usuario con email duplicado debe ser rechazada', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/User.php';
    $userModel = new \User();

    $data = [
        'nombre' => 'Duplicate User',
        'email' => 'admin@ramos.com',
        'password' => 'password123',
        'rol_id' => 2,
        'sede_id' => 1
    ];

    expect(function () use ($userModel, $data) {
        $userModel->crearUsuario($data);
    })->toThrow(\Exception::class);
});
