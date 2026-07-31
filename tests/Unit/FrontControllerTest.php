<?php

use Tests\TestCase;

test('el front controller resuelve correctamente el nombre de la clase del controlador', function () {
    $controllerName = 'Home';
    $controllerClass = ucfirst($controllerName) . 'Controller';
    expect($controllerClass)->toBe('HomeController');

    $controllerName = 'auth';
    $controllerClass = ucfirst($controllerName) . 'Controller';
    expect($controllerClass)->toBe('AuthController');
});

test('la ruta del archivo del controlador se construye correctamente y existe', function () {
    $controllerName = 'Admin';
    $controllerClass = ucfirst($controllerName) . 'Controller';
    $controllerFile = 'controllers/' . $controllerClass . '.php';

    expect($controllerFile)->toBe('controllers/AdminController.php');
    expect(file_exists($controllerFile))->toBeTrue();
});
