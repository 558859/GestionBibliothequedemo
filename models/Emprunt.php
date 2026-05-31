<?php

class Emprunt
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll($onlyCurrent = false)
    {
        $sql = "
            SELECT e.id, e.date_emprunt, e.date_retour_prevue, e.est_retourne,
                   l.titre, et.nom, et.prenom, et.numero_etudiant
            FROM emprunts e
            INNER JOIN livres l ON l.id = e.livre_id
            INNER JOIN etudiants et ON et.id = e.etudiant_id
        ";

        if ($onlyCurrent) {
            $sql .= ' WHERE e.est_retourne = 0';
        }

        $sql .= ' ORDER BY e.id DESC';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($livreId, $etudiantId, $dateEmprunt, $dateRetourPrevue)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare('SELECT id, quantite_disponible FROM livres WHERE id = ?');
            $stmt->execute([$livreId]);
            $livre = $stmt->fetch();

            if (!$livre || (int) $livre['quantite_disponible'] <= 0) {
                $this->conn->rollBack();
                return 'indisponible';
            }

            $stmt = $this->conn->prepare('UPDATE livres SET quantite_disponible = quantite_disponible - 1 WHERE id = ? AND quantite_disponible > 0');
            $stmt->execute([$livreId]);

            if ($stmt->rowCount() === 0) {
                $this->conn->rollBack();
                return 'indisponible';
            }

            $stmt = $this->conn->prepare('
                INSERT INTO emprunts (livre_id, etudiant_id, date_emprunt, date_retour_prevue, est_retourne)
                VALUES (?, ?, ?, ?, 0)
            ');
            $stmt->execute([$livreId, $etudiantId, $dateEmprunt, $dateRetourPrevue]);

            $this->conn->commit();
            return 'ok';
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return 'erreur';
        }
    }

    public function retour($id)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare('SELECT id, livre_id, est_retourne FROM emprunts WHERE id = ?');
            $stmt->execute([$id]);
            $emprunt = $stmt->fetch();

            if (!$emprunt) {
                $this->conn->rollBack();
                return 'introuvable';
            }

            if ((int) $emprunt['est_retourne'] === 1) {
                $this->conn->rollBack();
                return 'deja_retourne';
            }

            $stmt = $this->conn->prepare('UPDATE emprunts SET est_retourne = 1 WHERE id = ?');
            $stmt->execute([$id]);

            $stmt = $this->conn->prepare('UPDATE livres SET quantite_disponible = quantite_disponible + 1 WHERE id = ?');
            $stmt->execute([$emprunt['livre_id']]);

            $this->conn->commit();
            return 'ok';
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return 'erreur';
        }
    }
}
