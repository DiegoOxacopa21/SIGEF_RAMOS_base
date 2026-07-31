<?php

use Tests\TestCase;
use GuzzleHttp\Client;

/*
 * E2E tests require a running PHP dev server: php -S localhost:9999
 * Set TEST_BASE_URL env var if using a different port.
 */

function getBaseUrl(): string
{
    return getenv('TEST_BASE_URL') ?: 'http://localhost:9999';
}

function makeClient(): Client
{
    return new Client([
        'base_uri' => getBaseUrl(),
        'http_errors' => false,
        'timeout' => 10,
    ]);
}

test('la página de inicio retorna 200 y renderiza el layout', function () {
    $client = makeClient();
    $response = $client->get('/');

    expect($response->getStatusCode())->toBe(200);
    $body = (string) $response->getBody();
    expect($body)->toContain('SIGEF-RAMOS');
    expect($body)->toContain('Funeraria Ramos');
});

test('la página de catálogo retorna 500 cuando la base de datos no está disponible', function () {
    $client = makeClient();
    $response = $client->get('/?controller=Home&action=catalogo');
    expect($response->getStatusCode())->toBe(500);
});

test('la página de proforma retorna 500 cuando la base de datos no está disponible', function () {
    $client = makeClient();
    $response = $client->get('/?controller=Home&action=proforma');
    expect($response->getStatusCode())->toBe(500);
});

test('la página de contacto carga sin dependencia de base de datos', function () {
    $client = makeClient();
    $response = $client->get('/?controller=Home&action=contacto');
    expect($response->getStatusCode())->toBe(200);
});

test('el dashboard de administración redirige al login sin sesión', function () {
    $client = makeClient();
    $response = $client->get('/?controller=Admin&action=dashboard', [
        'allow_redirects' => false,
    ]);

    $status = $response->getStatusCode();
    expect(in_array($status, [301, 302, 303, 307, 308]))->toBeTrue();
});
