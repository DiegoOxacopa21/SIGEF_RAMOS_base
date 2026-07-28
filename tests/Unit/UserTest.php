<?php

use Tests\TestCase;

test('toggleEstado rejects deactivating main admin (id=1)', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/User.php';
    $userModel = new \User();

    $userModel->toggleEstado(1, 'inactivo');
})->throws(Exception::class, 'No se puede desactivar al Administrador principal.');

test('toggleEstado allows deactivating other users', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/User.php';
    $userModel = new \User();

    $result = $userModel->toggleEstado(3, 'inactivo');
    expect($result)->toBeTrue();

    $stmt = $pdo->query("SELECT estado FROM usuarios WHERE id = 3");
    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
    expect($row['estado'])->toBe('inactivo');
});

test('crearUsuario throws on duplicate email', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/User.php';
    $userModel = new \User();

    $userModel->crearUsuario([
        'email' => 'admin@ramos.com',
        'password' => 'test123',
        'nombre' => 'Duplicate',
        'id_rol' => 3,
        'id_sede' => 1,
    ]);
})->throws(Exception::class, 'El email ya');

test('crearUsuario succeeds with valid unique data', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/User.php';
    $userModel = new \User();

    $result = $userModel->crearUsuario([
        'email' => 'nuevo@test.com',
        'password' => 'password123',
        'nombre' => 'Nuevo Usuario',
        'id_rol' => 3,
        'id_sede' => 1,
    ]);

    expect($result)->toBeTrue();

    $stmt = $pdo->query("SELECT * FROM usuarios WHERE email = 'nuevo@test.com'");
    $user = $stmt->fetch(\PDO::FETCH_ASSOC);
    expect($user)->not->toBeFalse();
    expect($user['nombre'])->toBe('Nuevo Usuario');
    expect(password_verify('password123', $user['password']))->toBeTrue();
});

test('getAllUsers returns all users with roles and sedes', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/User.php';
    $userModel = new \User();

    $users = $userModel->getAllUsers();

    expect($users)->toHaveCount(5);
    expect($users[0])->toHaveKey('rol_nombre');
    expect($users[0])->toHaveKey('sede_nombre');
});
