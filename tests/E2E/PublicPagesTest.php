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

test('home page returns 200 and renders layout', function () {
    $client = makeClient();
    $response = $client->get('/');

    expect($response->getStatusCode())->toBe(200);
    $body = (string) $response->getBody();
    expect($body)->toContain('SIGEF-RAMOS');
    expect($body)->toContain('Funeraria Ramos');
});

test('catalog page returns 500 when database is unavailable', function () {
    $client = makeClient();
    $response = $client->get('/?controller=Home&action=catalogo');
    expect($response->getStatusCode())->toBe(500);
});

test('proforma page returns 500 when database is unavailable', function () {
    $client = makeClient();
    $response = $client->get('/?controller=Home&action=proforma');
    expect($response->getStatusCode())->toBe(500);
});

test('contact page loads without database dependency', function () {
    $client = makeClient();
    $response = $client->get('/?controller=Home&action=contacto');
    expect($response->getStatusCode())->toBe(200);
});

test('admin dashboard redirects to login without session', function () {
    $client = makeClient();
    $response = $client->get('/?controller=Admin&action=dashboard', [
        'allow_redirects' => false,
    ]);

    $status = $response->getStatusCode();
    expect(in_array($status, [301, 302, 303, 307, 308]))->toBeTrue();
});
