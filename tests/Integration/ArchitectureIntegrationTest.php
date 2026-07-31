<?php

use Tests\TestCase;

test('los modelos dependen de global $conn evitando la inyección de dependencias (FALLA: acoplamiento global)', function () {
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
        'Constructor de User usa global $conn en vez de inyección de dependencias'
    );
});
