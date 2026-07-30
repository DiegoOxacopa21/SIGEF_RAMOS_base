<?php

/**
 * Pruebas de Contrato (Contract Test) - Interfaces de Controladores y Rutas
 *
 * Este archivo evalúa los contratos de interfaz entre los controladores y la capa de ruteo,
 * garantizando que los métodos, firmas y estructuras de llamada cumplan las especificaciones esperadas.
 */

use Tests\TestCase;

test('Contract: FrontController resuelve clases de controlador según la convención de nomenclatura', function () {
    $controllerParam = 'Home';
    $controllerName = ucfirst($controllerParam) . 'Controller';

    expect(class_exists($controllerName, false))->toBeFalse('Las clases no están pre-cargadas por falta de autoloader PSR-4');
    
    $controllerFile = __DIR__ . '/../../controllers/' . $controllerName . '.php';
    expect(file_exists($controllerFile))->toBeTrue('El archivo del controlador debe coincidir con la convención de nombres');

    require_once __DIR__ . '/../../controllers/BaseController.php';
    require_once $controllerFile;
    expect(class_exists($controllerName))->toBeTrue('Una vez incluido el archivo, la clase del controlador debe estar definida');
});

test('Contract: BaseController exige la existencia de los métodos protegidos render, checkAuth y redirect', function () {
    require_once __DIR__ . '/../../controllers/BaseController.php';

    $ref = new \ReflectionClass(\BaseController::class);

    expect($ref->hasMethod('render'))->toBeTrue('BaseController debe proveer el contrato del método render');
    expect($ref->hasMethod('checkAuth'))->toBeTrue('BaseController debe proveer el contrato del método checkAuth');
    expect($ref->hasMethod('redirect'))->toBeTrue('BaseController debe proveer el contrato del método redirect');
});

test('Contract: AdminController define las acciones requeridas para el panel de administración', function () {
    require_once __DIR__ . '/../../controllers/BaseController.php';
    require_once __DIR__ . '/../../controllers/AdminController.php';

    $ref = new \ReflectionClass(\AdminController::class);

    $metodosRequeridos = ['dashboard', 'cotizaciones', 'ventas', 'operaciones', 'catalogo', 'clientes', 'usuarios'];
    foreach ($metodosRequeridos as $metodo) {
        expect($ref->hasMethod($metodo))->toBeTrue("AdminController debe cumplir el contrato de implementar el método {$metodo}");
    }
});

test('Contract: AuthController implementa los métodos de entrada y salida del sistema', function () {
    require_once __DIR__ . '/../../controllers/BaseController.php';
    require_once __DIR__ . '/../../controllers/AuthController.php';

    $ref = new \ReflectionClass(\AuthController::class);

    expect($ref->hasMethod('login'))->toBeTrue('AuthController debe implementar la acción login');
    expect($ref->hasMethod('logout'))->toBeTrue('AuthController debe implementar la acción logout');
});
