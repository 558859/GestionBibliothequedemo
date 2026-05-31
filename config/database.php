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
            // Allow providing a full DSN via env (e.g., for Render), otherwise build one for sqlsrv
            if ($this->dsn) {
                $dsn = $this->dsn;
            } else {
                // Do NOT include CharacterSet in the DSN for pdo_sqlsrv; we'll set encoding via PDO attribute
                $dsn = "sqlsrv:Server={$this->serverName};Database={$this->database};TrustServerCertificate=true";
            }

            // Remove any CharacterSet or charset parameters if present in the DSN (case-insensitive)
            $dsn = preg_replace('/;?\s*CharacterSet=[^;]*/i', '', $dsn);
            $dsn = preg_replace('/;?\s*charset=[^;]*/i', '', $dsn);

            // Prepare a list of credential attempts in order of preference
            $attempts = [];

            // Primary: explicit username/password if provided
            if (!empty($this->username) || $this->username === '0') {
                $attempts[] = ['user' => $this->username, 'pass' => $this->password];
            }

            // Secondary: try without credentials (e.g. Windows Authentication / integrated auth)
            $attempts[] = ['user' => null, 'pass' => null];

            // Tertiary: optional fallback credentials provided via env (DB_FALLBACK_USER / DB_FALLBACK_PASS)
            $fallbackUser = getenv('DB_FALLBACK_USER');
            if ($fallbackUser !== false && $fallbackUser !== null && $fallbackUser !== '') {
                $attempts[] = ['user' => $fallbackUser, 'pass' => getenv('DB_FALLBACK_PASS') ?: ''];
            }

            $lastException = null;
            $conn = null;

            foreach ($attempts as $cred) {
                try {
                    if ($cred['user'] === null) {
                        $conn = new PDO($dsn);
                    } else {
                        $conn = new PDO($dsn, $cred['user'], $cred['pass']);
                    }

                    // Success: set common attributes
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                    // For pdo_sqlsrv, set the encoding via the SQLSRV PDO attribute (preferred over DSN charset)
                    if (defined('PDO::SQLSRV_ATTR_ENCODING') && defined('PDO::SQLSRV_ENCODING_UTF8')) {
                        try {
                            $conn->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
                        } catch (Exception $e) {
                            // non-fatal: continue with connection
                        }
                    }

                    return $conn;

                } catch (PDOException $e) {
                    // store and continue to next attempt
                    $lastException = $e;
                    error_log('Database connection attempt failed: ' . $e->getMessage());
                    // If it's a credentials error and there are more attempts, try next
                    continue;
                }
            }

            // If we reach here, all attempts failed
            if ($lastException) {
                throw $lastException;
            }

            // Fallback: should not reach here, but in case, throw generic exception
            throw new PDOException('Unable to establish a database connection using any configured credentials.');
            
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
}