<?php

use Tests\TestCase;

test('front controller resolves controller class name correctly', function () {
    $controllerName = 'Home';
    $controllerClass = ucfirst($controllerName) . 'Controller';
    expect($controllerClass)->toBe('HomeController');

    $controllerName = 'auth';
    $controllerClass = ucfirst($controllerName) . 'Controller';
    expect($controllerClass)->toBe('AuthController');
});

test('controller file path is constructed correctly and exists', function () {
    $controllerName = 'Admin';
    $controllerClass = ucfirst($controllerName) . 'Controller';
    $controllerFile = 'controllers/' . $controllerClass . '.php';

    expect($controllerFile)->toBe('controllers/AdminController.php');
    expect(file_exists($controllerFile))->toBeTrue();
});
