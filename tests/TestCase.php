<?php

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    protected static ?\PDO $testDb = null;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Create a fresh in-memory SQLite database with schema + seed data.
     */
    protected function createTestDatabase(): \PDO
    {
        $pdo = new \PDO('sqlite::memory:');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $schema = file_get_contents(__DIR__ . '/database/schema.sqlite.sql');
        $pdo->exec($schema);

        $seed = file_get_contents(__DIR__ . '/database/seed.sqlite.sql');
        $pdo->exec($seed);

        self::$testDb = $pdo;
        return $pdo;
    }

    /**
     * Get the shared test database (creates if needed).
     */
    protected function getTestDatabase(): \PDO
    {
        if (self::$testDb === null) {
            return $this->createTestDatabase();
        }
        return self::$testDb;
    }

    /**
     * Inject a PDO connection into the global scope (simulates config/db.php behavior).
     */
    protected function injectGlobalConnection(\PDO $pdo): void
    {
        global $conn;
        $conn = $pdo;
    }

    /**
     * Set up a session with given values (simulates logged-in user).
     */
    protected function setUpSession(array $data = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = array_merge([
            'user_id' => 1,
            'user_name' => 'TestUser',
            'user_role_id' => 1,
            'user_role_name' => 'Administrador',
        ], $data);
    }

    /**
     * Clean up session.
     */
    protected function destroySession(): void
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}
