<?php

/**
 * Pruebas de Regresión - Autenticación de Usuarios y Compatibilidad PDO
 *
 * Este archivo evalúa los hallazgos técnicos conocidos en la autenticación, específicamente la
 * dependencia de rowCount() tras consultas SELECT en MySQL (que falla en motores como SQLite) y el
 * comportamiento seguro ante intentos de inicio de sesión con correos inactivos o inexistentes.
 */

use Tests\TestCase;

test('User login fails on SQLite due to rowCount MySQL dependency regression', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/User.php';
    $userModel = new \User();

    // rowCount() on SELECT query returns 0 in PDO SQLite, causing login() to fail false
    $result = $userModel->login('admin@ramos.com', '123456');
    expect($result)->toBeFalse();
});

test('User login with invalid email handles password_verify deprecation gracefully', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/User.php';
    $userModel = new \User();

    $result = $userModel->login('invalido@inexistente.com', 'password123');
    expect($result)->toBeFalse();
});
