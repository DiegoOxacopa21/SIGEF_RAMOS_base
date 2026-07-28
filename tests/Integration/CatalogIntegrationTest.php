<?php

use Tests\TestCase;

test('addProducto and getAllProductos includes new product', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Catalog.php';
    $catalogModel = new \Catalog();

    $initialCount = count($catalogModel->getAllProductos());

    $catalogModel->addProducto([
        'tipo' => 'ataud',
        'nombre' => 'Ataud Test',
        'descripcion' => 'Descripcion test',
        'precio' => 999.99,
        'imagen' => 'test.png',
        'estado' => 'disponible',
    ]);

    $productos = $catalogModel->getAllProductos();
    expect($productos)->toHaveCount($initialCount + 1);
    $nombres = array_column($productos, 'nombre');
    expect($nombres)->toContain('Ataud Test');
});

test('deleteProducto removes product from database', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Catalog.php';
    $catalogModel = new \Catalog();

    $pdo->exec("INSERT INTO productos_catalogo (tipo, nombre, descripcion, precio, estado) VALUES ('otro', 'Delete Me', 'test', 100, 'disponible')");
    $id = $pdo->lastInsertId();

    $result = $catalogModel->deleteProducto($id);
    expect($result)->toBeTrue();

    $stmt = $pdo->prepare("SELECT id FROM productos_catalogo WHERE id = ?");
    $stmt->execute([$id]);
    expect($stmt->fetch())->toBeFalse();
});
