<?php

class Database
{
    private $serverName = 'DESKTOP-TJDPT3K';
    private $database = "GestionBibliothequedemo";

    private $username = 'bibli_user' ;
    private $password = 'BibliPassword123!';

    public function getConnection()
    {
        try {
            $dsn = "sqlsrv:Server={$this->serverName};Database={$this->database};TrustServerCertificate=true";
            
            if ($this->username === null) {
                $conn = new PDO($dsn);
            } else {
                $conn = new PDO($dsn, $this->username, $this->password);
            }

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            return $conn;
            
        } catch (PDOException $e) {
            die("Erreur de connexion SQL Server : " . $e->getMessage());
        }
    }
}