<?php

class Database
{
    // On lit les variables d'environnement (Render) ou utiliser DB_DSN en production
    private $serverName;
    private $database;
    private $username;
    private $password;
    private $dsn;

    public function __construct() {
        $this->serverName = getenv('DB_SERVER') ?: 'localhost';
        $this->database   = getenv('DB_NAME') ?: 'GestionBibliothequedemo';
        $this->username   = getenv('DB_USER') ?: 'bibli_user';
        $this->password   = getenv('DB_PASS') ?: '';
        $this->dsn        = getenv('DB_DSN') ?: null; // If provided, use full DSN from env
    }

    public function getConnection()
    {
        try {
            // Detect Render environment: prefer RENDER_EXTERNAL_URL or HTTP_HOST containing onrender.com
            $isRender = getenv('RENDER_EXTERNAL_URL') || (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'onrender.com') !== false);

            // If a full DSN is provided in env, use it and try to infer driver
            if ($this->dsn) {
                $dsn = $this->dsn;
                $driver = strpos($dsn, 'mysql:') === 0 ? 'mysql' : (strpos($dsn, 'sqlsrv:') === 0 ? 'sqlsrv' : null);
            } else {
                // Choose driver based on environment: Render => MySQL, otherwise local => sqlsrv
                $driver = $isRender ? 'mysql' : 'sqlsrv';
                if ($driver === 'sqlsrv') {
                    $dsn = "sqlsrv:Server={$this->serverName};Database={$this->database};TrustServerCertificate=true";
                } else {
                    // Build MySQL DSN for Render using standard env vars
                    $host = getenv('DB_HOST') ?: getenv('DB_SERVER') ?: 'localhost';
                    $port = getenv('DB_PORT') ?: '3306';
                    $db   = getenv('DB_DATABASE') ?: getenv('DB_NAME') ?: null;
                    $user = getenv('DB_USERNAME') ?: getenv('DB_USER') ?: null;
                    $pass = getenv('DB_PASSWORD') ?: getenv('DB_PASS') ?: null;
                    $charset = 'utf8mb4';

                    // Build base DSN and include db if provided
                    $dsn = "mysql:host={$host};port={$port}";
                    if ($db) {
                        $dsn .= ";dbname={$db}";
                    }
                    $dsn .= ";charset={$charset}";
                }
            }

            // Clean DSN for sqlsrv: remove CharacterSet/charset entries
            if (isset($driver) && $driver === 'sqlsrv') {
                $dsn = preg_replace('/;?\s*CharacterSet=[^;]*/i', '', $dsn);
                $dsn = preg_replace('/;?\s*charset=[^;]*/i', '', $dsn);
            }

            // Build credential attempts per driver
            $attempts = [];
            if ($driver === 'sqlsrv') {
                if (!empty($this->username) || $this->username === '0') {
                    $attempts[] = ['user' => $this->username, 'pass' => $this->password];
                }
                // Try integrated auth (no creds)
                $attempts[] = ['user' => null, 'pass' => null];
                $fallbackUser = getenv('DB_FALLBACK_USER');
                if ($fallbackUser) {
                    $attempts[] = ['user' => $fallbackUser, 'pass' => getenv('DB_FALLBACK_PASS') ?: ''];
                }
            } else {
                // mysql
                $user = getenv('DB_USER') ?: getenv('DB_USERNAME') ?: $this->username;
                $pass = getenv('DB_PASS') ?: getenv('DB_PASSWORD') ?: $this->password;
                if (!empty($user) || $user === '0') {
                    $attempts[] = ['user' => $user, 'pass' => $pass];
                }
                $fallbackUser = getenv('DB_FALLBACK_USER');
                if ($fallbackUser) {
                    $attempts[] = ['user' => $fallbackUser, 'pass' => getenv('DB_FALLBACK_PASS') ?: ''];
                }
            }

            $lastException = null;
            foreach ($attempts as $cred) {
                try {
                    if ($cred['user'] === null) {
                        $conn = new PDO($dsn);
                    } else {
                        $conn = new PDO($dsn, $cred['user'], $cred['pass']);
                    }

                    // Common attributes
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                    if ($driver === 'sqlsrv') {
                        if (defined('PDO::SQLSRV_ATTR_ENCODING') && defined('PDO::SQLSRV_ENCODING_UTF8')) {
                            try {
                                $conn->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
                            } catch (Exception $e) {
                                // non-fatal
                            }
                        }
                    } else {
                        // MySQL specific tuning
                        if (defined('PDO::ATTR_EMULATE_PREPARES')) {
                            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                        }
                    }

                    $lastException = null;
                    foreach ($attempts as $cred) {
                        try {
                            if ($cred['user'] === null) {
                                $conn = new PDO($dsn);
                            } else {
                                $conn = new PDO($dsn, $cred['user'], $cred['pass']);
                            }

                            // Common attributes
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                            if ($driver === 'sqlsrv') {
                                if (defined('PDO::SQLSRV_ATTR_ENCODING') && defined('PDO::SQLSRV_ENCODING_UTF8')) {
                                    try {
                                        $conn->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
                                    } catch (Exception $e) {
                                        // non-fatal
                                    }
                                }
                            } else {
                                // MySQL specific tuning
                                if (defined('PDO::ATTR_EMULATE_PREPARES')) {
                                    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                                }
                            }

                            return $conn;
                        } catch (PDOException $e) {
                            $lastException = $e;
                            error_log('Database connection attempt failed: ' . $e->getMessage());
                            continue;
                        }
                    }

                    // If connection attempts failed, log and return null (non-fatal)
                    if ($lastException) {
                        error_log('All database connection attempts failed: ' . $lastException->getMessage());
                    } else {
                        error_log('No database connection attempts were made (missing credentials or configuration).');
                    }

                    return null;

                } catch (Exception $e) {
                    // Any unexpected error: log and return null rather than killing the app
                    error_log('Unexpected error in Database::getConnection: ' . $e->getMessage());
                    return null;
                }