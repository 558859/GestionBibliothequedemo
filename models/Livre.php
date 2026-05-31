<?php

class Livre
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll($search = '')
    {
        $params = [];
        $sql = "
            SELECT l.id, l.titre, l.auteur, l.categorie_id, l.annee, l.quantite_disponible, c.nom AS categorie
            FROM livres l
            INNER JOIN categories c ON c.id = l.categorie_id
        ";

        if ($search !== '') {
            $sql .= ' WHERE l.titre LIKE ? OR l.auteur LIKE ? OR c.nom LIKE ?';
            $like = '%' . $search . '%';
            $params = [$like, $like, $like];
        }

        $sql .= ' ORDER BY l.id DESC';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getAvailableForSelect()
    {
        $stmt = $this->conn->prepare('SELECT id, titre, quantite_disponible FROM livres ORDER BY id DESC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare('SELECT * FROM livres WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function countEmprunts($id)
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) FROM emprunts WHERE livre_id = ?');
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn();
    }

    public function create($titre, $auteur, $categorieId, $annee, $quantite)
    {
        $stmt = $this->conn->prepare('
            INSERT INTO livres (titre, auteur, categorie_id, annee, quantite_disponible)
            VALUES (?, ?, ?, ?, ?)
        ');
        $stmt->execute([$titre, $auteur, $categorieId, $annee, $quantite]);
    }

    public function update($id, $titre, $auteur, $categorieId, $annee, $quantite)
    {
        $stmt = $this->conn->prepare('
            UPDATE livres
            SET titre = ?, auteur = ?, categorie_id = ?, annee = ?, quantite_disponible = ?
            WHERE id = ?
        ');
        $stmt->execute([$titre, $auteur, $categorieId, $annee, $quantite, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare('DELETE FROM livres WHERE id = ?');
        $stmt->execute([$id]);
    }
}
