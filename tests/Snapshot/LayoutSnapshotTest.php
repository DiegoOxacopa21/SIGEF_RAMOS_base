<?php

/**
 * Pruebas de Instantánea (Snapshot Test) - Layouts y Vistas del Sistema
 *
 * Este archivo evalúa la consistencia de las estructuras de salida HTML y vistas renderizadas,
 * verificando que los componentes visuales principales mantengan su contrato de layout intacto.
 */

use Tests\TestCase;

test('Snapshot: Estructura HTML de la vista de login contiene campos obligatorios del formulario', function () {
    $viewPath = __DIR__ . '/../../views/admin/login.php';
    expect(file_exists($viewPath))->toBeTrue('La vista views/admin/login.php debe existir en el sistema');

    $content = file_get_contents($viewPath);
    expect(str_contains($content, 'name="email"'))->toBeTrue('El formulario de login debe contener el campo input email');
    expect(str_contains($content, 'name="password"'))->toBeTrue('El formulario de login debe contener el campo input password');
    expect(str_contains($content, 'type="submit"'))->toBeTrue('El formulario de login debe contener un botón de envío submit');
});

test('Snapshot: Vista pública del catálogo mantiene la estructura de contenedores HTML', function () {
    $viewPath = __DIR__ . '/../../views/public/catalog.php';
    expect(file_exists($viewPath))->toBeTrue('La vista views/public/catalog.php debe existir');

    $content = file_get_contents($viewPath);
    expect(str_contains($content, 'Catálogo de Servicios'))->toBeTrue('El encabezado principal del catálogo debe coincidir con el snapshot visual');
});

test('Snapshot: Layout principal de administración incluye navegación y contenedor de contenido', function () {
    $viewPath = __DIR__ . '/../../views/layouts/admin.php';
    expect(file_exists($viewPath))->toBeTrue('El layout views/layouts/admin.php debe existir');

    $content = file_get_contents($viewPath);
    expect(str_contains($content, '<nav'))->toBeTrue('El layout de administración debe contener la estructura nav');
    expect(str_contains($content, 'SIGEF-RAMOS'))->toBeTrue('El layout de administración debe mantener la marca institucional SIGEF-RAMOS');
});

test('Snapshot: Vista pública de cotizador proforma mantiene controles interactivos de selección', function () {
    $viewPath = __DIR__ . '/../../views/public/proforma.php';
    expect(file_exists($viewPath))->toBeTrue('La vista views/public/proforma.php debe existir');

    $content = file_get_contents($viewPath);
    expect(str_contains($content, 'Simulador de Cotización'))->toBeTrue('La página de proforma debe contener el encabezado del simulador');
});
