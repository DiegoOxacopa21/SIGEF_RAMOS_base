<?php

/**
 * Pruebas de Humo (Smoke Test) - Cotizaciones, Clientes y Difuntos
 *
 * Este archivo evalúa la funcionalidad básica del flujo comercial inicial y gestión de deudos,
 * asegurando que el modelo de cotizaciones y los registros de clientes y personas fallecidas
 * respondan correctamente a las consultas del sistema.
 */

use Tests\TestCase;

test('Quotation model retrieves initial quotations smoke test', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Quotation.php';
    $quoteModel = new \Quotation();

    $cotizaciones = $quoteModel->getAllCotizaciones();
    expect($cotizaciones)->not->toBeEmpty();
});

test('Client and Difunto models retrieve seed records smoke test', function () {
    $pdo = $this->createTestDatabase();
    $this->injectGlobalConnection($pdo);

    require_once __DIR__ . '/../../models/Client.php';
    require_once __DIR__ . '/../../models/Difunto.php';

    $clientModel = new \Client();
    $difuntoModel = new \Difunto();

    $clientes = $clientModel->getAllClientes();
    $difuntos = $difuntoModel->getAllDifuntos();

    expect($clientes)->not->toBeEmpty();
    expect($difuntos)->toBeArray();
});
