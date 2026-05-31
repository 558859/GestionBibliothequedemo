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
                    // Build MySQL DSN for Render
                    $host = getenv('DB_SERVER') ?: getenv('DB_HOST') ?: $this->serverName;
                    $db   = getenv('DB_NAME') ?: getenv('DB_DATABASE') ?: $this->database;
                    $charset = 'utf8mb4';
                    $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
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

                    return $conn;
                } catch (PDOException $e) {
                    $lastException = $e;
                    error_log('Database connection attempt failed: ' . $e->getMessage());
                    continue;
                }
            }

            if ($lastException) {
                throw $lastException;
            }

            throw new PDOException('Unable to establish a database connection using any configured credentials.');

        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
}