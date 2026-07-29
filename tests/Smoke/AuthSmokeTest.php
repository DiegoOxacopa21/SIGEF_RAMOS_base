<?php

/**
 * Pruebas de Humo (Smoke Test) - Autenticación y Roles
 *
 * Este archivo evalúa la integridad y disponibilidad básica del subsistema de usuarios,
 * roles y sedes, verificando que los modelos principales se instancien correctamente
 * y puedan consultar los registros fundamentales sin errores de arranque.
 */

use Tests\TestCase;

test('User model instantiates and retrieves admin user smoke test', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/User.php';
    $userModel = new \User();

    $users = $userModel->getAllUsers();
    expect($users)->not->toBeEmpty();
    expect($users[0]['email'])->toBe('admin@ramos.com');
});

test('Role and Sede models load seed data smoke test', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Role.php';
    require_once __DIR__ . '/../../models/Sede.php';

    $roleModel = new \Role();
    $sedeModel = new \Sede();

    $roles = $roleModel->getAllRoles();
    $sedes = $sedeModel->getAllSedes();

    expect($roles)->toHaveCount(5);
    expect($sedes)->toHaveCount(2);
});
