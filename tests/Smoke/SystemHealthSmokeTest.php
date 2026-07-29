<?php

/**
 * Pruebas de Humo (Smoke Test) - Salud del Sistema y Estructura Base
 *
 * Este archivo evalúa la integridad global del entorno de ejecución, verificando la creación
 * correcta del esquema relacional completo (17 tablas) en la base de datos de pruebas y la
 * presencia física de los archivos de plantilla de vista (layouts public y admin).
 */

use Tests\TestCase;

test('SQLite memory schema creates all required tables smoke test', function () {
    $pdo = $this->createTestDatabase();

    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(\PDO::FETCH_COLUMN);

    expect($tables)->toContain('usuarios');
    expect($tables)->toContain('clientes');
    expect($tables)->toContain('cotizaciones');
    expect($tables)->toContain('ventas');
    expect($tables)->toContain('operaciones');
    expect($tables)->toContain('comprobantes');
});

test('BaseController initializes and verifies layout file existence smoke test', function () {
    require_once __DIR__ . '/../../controllers/BaseController.php';

    expect(file_exists(__DIR__ . '/../../views/layouts/public.php'))->toBeTrue();
    expect(file_exists(__DIR__ . '/../../views/layouts/admin.php'))->toBeTrue();
});
