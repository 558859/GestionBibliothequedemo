<?php

class Statistique
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    private function count($sql)
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // Permet de savoir instantanément si on utilise SQLite (en ligne) ou SQL Server (en local)
    private function isSQLite()
    {
        return $this->conn->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite';
    }

    public function totalLivres()
    {
        return $this->count('SELECT COUNT(*) FROM livres');
    }

    public function totalEtudiants()
    {
        return $this->count('SELECT COUNT(*) FROM etudiants');
    }

    public function totalEmprunts()
    {
        return $this->count('SELECT COUNT(*) FROM emprunts');
    }

    public function empruntsEnCours()
    {
        return $this->count('SELECT COUNT(*) FROM emprunts WHERE est_retourne = 0');
    }

    public function empruntsRetournes()
    {
        return $this->count('SELECT COUNT(*) FROM emprunts WHERE est_retourne = 1');
    }

    public function nombreRetards()
    {
        $dateExpr = currentDateSql();
        $sql = 'SELECT COUNT(*) FROM emprunts WHERE est_retourne = 0 AND date_retour_prevue < ' . $dateExpr;
        return $this->count($sql);
    }

    public function derniersEmprunts()
    {
        if ($this->isSQLite()) {
            $sql = "SELECT e.id, l.titre, et.nom, et.prenom, e.date_emprunt, e.date_retour_prevue, e.est_retourne
                    FROM emprunts e
                    INNER JOIN livres l ON l.id = e.livre_id
                    INNER JOIN etudiants et ON et.id = e.etudiant_id
                    ORDER BY e.id DESC
                    LIMIT 5";
        } else {
            $sql = "SELECT TOP 5 e.id, l.titre, et.nom, et.prenom, e.date_emprunt, e.date_retour_prevue, e.est_retourne
                    FROM emprunts e
                    INNER JOIN livres l ON l.id = e.livre_id
                    INNER JOIN etudiants et ON et.id = e.etudiant_id
                    ORDER BY e.id DESC";
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function retards()
    {
        $dateExpr = currentDateSql();
        $sql = "SELECT e.date_emprunt, e.date_retour_prevue, l.titre, et.nom, et.prenom, et.numero_etudiant
                FROM emprunts e
                INNER JOIN livres l ON l.id = e.livre_id
                INNER JOIN etudiants et ON et.id = e.etudiant_id
                WHERE e.est_retourne = 0 AND e.date_retour_prevue < " . $dateExpr . "
                ORDER BY e.id DESC";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function livresParCategorie()
    {
        $stmt = $this->conn->prepare("
            SELECT c.id, c.nom, COUNT(l.id) AS total
            FROM categories c
            LEFT JOIN livres l ON l.categorie_id = c.id
            GROUP BY c.id, c.nom
            ORDER BY c.id DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function livresPlusEmpruntes()
    {
        if ($this->isSQLite()) {
            $sql = "SELECT l.id, l.titre, COUNT(e.id) AS total
                    FROM emprunts e
                    INNER JOIN livres l ON l.id = e.livre_id
                    GROUP BY l.id, l.titre
                    ORDER BY total DESC, l.id DESC
                    LIMIT 10";
        } else {
            $sql = "SELECT TOP 10 l.id, l.titre, COUNT(e.id) AS total
                    FROM emprunts e
                    INNER JOIN livres l ON l.id = e.livre_id
                    GROUP BY l.id, l.titre
                    ORDER BY total DESC, l.id DESC";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function etudiantsPlusActifs()
    {
        if ($this->isSQLite()) {
            $sql = "SELECT et.nom, et.prenom, et.numero_etudiant, COUNT(e.id) AS total
                    FROM emprunts e
                    INNER JOIN etudiants et ON et.id = e.etudiant_id
                    GROUP BY et.nom, et.prenom, et.numero_etudiant
                    ORDER BY total DESC
                    LIMIT 10";
        } else {
            $sql = "SELECT TOP 10 et.nom, et.prenom, et.numero_etudiant, COUNT(e.id) AS total
                    FROM emprunts e
                    INNER JOIN etudiants et ON et.id = e.etudiant_id
                    GROUP BY et.nom, et.prenom, et.numero_etudiant
                    ORDER BY total DESC";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>