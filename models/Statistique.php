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
        $stmt = $this->conn->prepare("
            SELECT TOP 5 e.id, l.titre, et.nom, et.prenom, e.date_emprunt, e.date_retour_prevue, e.est_retourne
            FROM emprunts e
            INNER JOIN livres l ON l.id = e.livre_id
            INNER JOIN etudiants et ON et.id = e.etudiant_id
            ORDER BY e.id DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function retards()
    {
        $dateExpr = currentDateSql();
        $sql = "\n            SELECT e.date_emprunt, e.date_retour_prevue, l.titre, et.nom, et.prenom, et.numero_etudiant\n            FROM emprunts e\n            INNER JOIN livres l ON l.id = e.livre_id\n            INNER JOIN etudiants et ON et.id = e.etudiant_id\n            WHERE e.est_retourne = 0 AND e.date_retour_prevue < " . $dateExpr . "\n            ORDER BY e.id DESC\n        ";
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
        $stmt = $this->conn->prepare("
            SELECT TOP 10 l.id, l.titre, COUNT(e.id) AS total
            FROM emprunts e
            INNER JOIN livres l ON l.id = e.livre_id
            GROUP BY l.id, l.titre
            ORDER BY total DESC, l.id DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function etudiantsPlusActifs()
    {
        $stmt = $this->conn->prepare("
            SELECT TOP 10 et.nom, et.prenom, et.numero_etudiant, COUNT(e.id) AS total
            FROM emprunts e
            INNER JOIN etudiants et ON et.id = e.etudiant_id
            GROUP BY et.nom, et.prenom, et.numero_etudiant
            ORDER BY total DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
