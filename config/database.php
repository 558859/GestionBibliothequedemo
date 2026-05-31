<?php
class Database {
    private $host = "localhost";
    private $db_name = "GestionBibliothequedemo";
    private $username = "bibli_user";
    private $password = "BibliPassword123!";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        // Détection automatique : Si on est sur Render, on utilise SQLite pour éviter les erreurs de driver
        if (getenv('RENDER') || isset($_ENV['RENDER'])) {
            try {
                // Crée ou utilise un fichier de base de données local sur Render
                $sqlite_path = __DIR__ . '/../database.sqlite';
                $this->conn = new PDO("sqlite:" . $sqlite_path);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Création automatique des tables si le fichier est neuf
                $this->initSQLiteTables();
            } catch(PDOException $exception) {
                echo "Erreur de connexion SQLite : " . $exception->getMessage();
            }
        } else {
            // En LOCAL (sur ton PC avec WampServer / SQL Server)
            try {
                $this->conn = new PDO("sqlsrv:Server=" . $this->host . ";Database=" . $this->db_name . ";TrustServerCertificate=true", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $exception) {
                echo "Erreur de connexion SQL Server : " . $exception->getMessage();
            }
        }

        return $this->conn;
    }

    private function initSQLiteTables() {
        // Crée les tables adaptées pour Render si elles n'existent pas
        $this->conn->exec("CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nom TEXT NOT NULL UNIQUE
        );");

        $this->conn->exec("CREATE TABLE IF NOT EXISTS livres (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            titre TEXT NOT NULL,
            auteur TEXT NOT NULL,
            categorie_id INTEGER NOT NULL,
            annee INTEGER NULL,
            quantite_disponible INTEGER NOT NULL,
            FOREIGN KEY (categorie_id) REFERENCES categories(id)
        );");

        $this->conn->exec("CREATE TABLE IF NOT EXISTS etudiants (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nom TEXT NOT NULL,
            prenom TEXT NOT NULL,
            numero_etudiant TEXT NOT NULL UNIQUE,
            filiere TEXT NOT NULL,
            contact TEXT NOT NULL
        );");

        $this->conn->exec("CREATE TABLE IF NOT EXISTS emprunts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            livre_id INTEGER NOT NULL,
            etudiant_id INTEGER NOT NULL,
            date_emprunt TEXT NOT NULL,
            date_retour_prevue TEXT NOT NULL,
            est_retourne INTEGER NOT NULL DEFAULT 0,
            FOREIGN KEY (livre_id) REFERENCES livres(id),
            FOREIGN KEY (etudiant_id) REFERENCES etudiants(id)
        );");
    }
}
?>