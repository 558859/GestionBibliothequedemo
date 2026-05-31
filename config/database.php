<?php

class Database
{
    // On lit les variables d'environnement (Render), sinon on prend la configuration locale
    private $serverName;
    private $database;
    private $username;
    private $password;

    public function __construct() {
        $this->serverName = getenv('DB_SERVER') ?: 'DESKTOP-TJDPT3K';
        $this->database   = getenv('DB_NAME') ?: 'GestionBibliothequedemo';
        $this->username   = getenv('DB_USER') ?: 'bibli_user';
        $this->password   = getenv('DB_PASS') ?: 'BibliPassword123!';
    }

    public function getConnection()
    {
        try {
            $dsn = "sqlsrv:Server={$this->serverName};Database={$this->database};TrustServerCertificate=true";
            
            if ($this->username === null || $this->username === '') {
                $conn = new PDO($dsn);
            } else {
                $conn = new PDO($dsn, $this->username, $this->password);
            }

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $conn->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
            
            return $conn;
            
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
}