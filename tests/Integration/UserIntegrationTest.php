<?php

use Tests\TestCase;

test('login with valid credentials should succeed (FAILS: rowCount post-SELECT no portable)', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];

    require_once __DIR__ . '/../../models/User.php';
    $userModel = new \User();

    $stmt = $pdo->query("SELECT password FROM usuarios WHERE email = 'admin@ramos.com'");
    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
    expect(password_verify('123456', $row['password']))->toBeTrue();

    $result = $userModel->login('admin@ramos.com', '123456');

    expect($result)->toBeTrue('User::login() falla porque rowCount() post-SELECT retorna 0 en SQLite');
    expect($_SESSION['user_id'])->toBe(1);
    expect($_SESSION['user_role_name'])->toBe('Administrador');

    $this->destroySession();
});

test('login with wrong password returns false', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];

    require_once __DIR__ . '/../../models/User.php';
    $userModel = new \User();

    $result = $userModel->login('admin@ramos.com', 'wrongpassword');
    expect($result)->toBeFalse();
    expect($_SESSION)->not->toHaveKey('user_id');

    $this->destroySession();
});
