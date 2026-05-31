<?php

class Categorie
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $stmt = $this->conn->prepare('SELECT id, nom FROM categories ORDER BY id DESC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllWithCounts()
    {
        $stmt = $this->conn->prepare("
            SELECT c.id, c.nom, COUNT(l.id) AS nombre_livres
            FROM categories c
            LEFT JOIN livres l ON l.categorie_id = c.id
            GROUP BY c.id, c.nom
            ORDER BY c.id DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare('SELECT id, nom FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function countLivres($id)
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM livres WHERE categorie_id = ?');
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn();
    }

    public function create($nom)
    {
        $stmt = $this->conn->prepare('INSERT INTO categories (nom) VALUES (?)');
        $stmt->execute([$nom]);
    }

    public function update($id, $nom)
    {
        $stmt = $this->conn->prepare('UPDATE categories SET nom = ? WHERE id = ?');
        $stmt->execute([$nom, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute([$id]);
    }
}
