<?php

use Tests\TestCase;

test('models depend on global $conn preventing dependency injection (FAILS: acoplamiento global)', function () {
    global $conn;
    $savedConn = $conn;
    $conn = null;

    require_once __DIR__ . '/../../models/User.php';

    $user = new \User();
    $ref = (new \ReflectionClass($user))->getProperty('conn');
    $ref->setAccessible(true);
    $connValue = $ref->getValue($user);

    $conn = $savedConn;

    expect($connValue)->toBeInstanceOf(
        \PDO::class,
        'Constructor de User usa global $conn en vez de inyeccion de dependencias'
    );
});
