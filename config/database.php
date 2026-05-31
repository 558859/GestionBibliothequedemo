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
                $dsn = "sqlsrv:Server={$this->serverName};Database={$this->database};TrustServerCertificate=true;CharacterSet=UTF-8";
            }

            if (empty($this->username) && $this->username !== '0') {
                $conn = new PDO($dsn);
            } else {
                $conn = new PDO($dsn, $this->username, $this->password);
            }

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $conn;
            
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
}