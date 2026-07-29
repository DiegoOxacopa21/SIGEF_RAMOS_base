<?php

/**
 * Pruebas de Regresión - Control de Acceso por Rol (RBAC) y Controladores
 *
 * Este archivo evalúa las reglas de restricción de permisos en BaseController::checkAuth,
 * garantizando que usuarios con roles restringidos (como Operario) no puedan acceder a módulos
 * administrativos no autorizados, y comprobando la correcta carga de contexto en AdminController.
 */

use Tests\TestCase;

test('BaseController checkAuth blocks non-admin user without permission regression', function () {
    $this->setUpSession([
        'user_id' => 5,
        'user_name' => 'OperarioUser',
        'user_role_id' => 5,
        'user_role_name' => 'Operario',
    ]);

    require_once __DIR__ . '/../../controllers/BaseController.php';

    $controller = new class extends \BaseController {
        public function testAccess() {
            $this->checkAuth(['Administrador']);
        }
    };

    // Expecting exit or output when access is denied
    ob_start();
    try {
        $controller->testAccess();
    } catch (\Throwable $e) {
        // Handle exit
    }
    $output = ob_get_clean();

    expect($output)->toContain('Acceso denegado');
    $this->destroySession();
});

test('AdminController dashboard correctly receives role data regression', function () {
    $this->setUpSession([
        'user_id' => 1,
        'user_name' => 'AdminUser',
        'user_role_id' => 1,
        'user_role_name' => 'Administrador',
    ]);

    require_once __DIR__ . '/../../controllers/AdminController.php';

    $admin = new \AdminController();

    ob_start();
    $admin->dashboard();
    $output = ob_get_clean();

    expect($output)->toContain('Administrador');
    $this->destroySession();
});
