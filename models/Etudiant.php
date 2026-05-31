<?php

class Etudiant
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $stmt = $this->conn->prepare('SELECT * FROM etudiants ORDER BY id DESC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare('SELECT * FROM etudiants WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function numeroExists($numero, $excludeId = 0)
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM etudiants WHERE numero_etudiant = ? AND id <> ?');
        $stmt->execute([$numero, $excludeId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function countEmprunts($id)
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM emprunts WHERE etudiant_id = ?');
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn();
    }

    public function create($nom, $prenom, $numero, $filiere, $contact)
    {
        $stmt = $this->conn->prepare('
            INSERT INTO etudiants (nom, prenom, numero_etudiant, filiere, contact)
            VALUES (?, ?, ?, ?, ?)
        ');
        $stmt->execute([$nom, $prenom, $numero, $filiere, $contact]);
    }

    public function update($id, $nom, $prenom, $numero, $filiere, $contact)
    {
        $stmt = $this->conn->prepare('
            UPDATE etudiants
            SET nom = ?, prenom = ?, numero_etudiant = ?, filiere = ?, contact = ?
            WHERE id = ?
        ');
        $stmt->execute([$nom, $prenom, $numero, $filiere, $contact, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare('DELETE FROM etudiants WHERE id = ?');
        $stmt->execute([$id]);
    }
}
