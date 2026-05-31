<?php

require_once __DIR__ . '/../models/Livre.php';
require_once __DIR__ . '/../models/Categorie.php';

class LivreController
{
    private $livreModel;
    private $categorieModel;
    private $baseUrl;

    public function __construct($db, $baseUrl)
    {
        $this->livreModel = new Livre($db);
        $this->categorieModel = new Categorie($db);
        $this->baseUrl = $baseUrl;
    }

    public function index($editLivre = null)
    {
        $search = trim($_GET['q'] ?? '');
        $livres = $this->livreModel->getAll($search);
        $categories = $this->categorieModel->getAll();
        $pageTitle = 'Livres';
        $pageHeading = 'Gestion des livres';
        $activePage = 'livres';
        $baseUrl = $this->baseUrl;
        require __DIR__ . '/../views/livres/index.php';
    }

    public function create()
    {
        $data = $this->readPost();
        $this->validate($data, $this->baseUrl . '/index.php?page=livres');
        $this->livreModel->create($data['titre'], $data['auteur'], $data['categorie_id'], $data['annee'] === '' ? null : (int) $data['annee'], (int) $data['quantite_disponible']);
        redirectWithMessage($this->baseUrl . '/index.php?page=livres', 'success', 'Livre ajouté avec succès.');
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->readPost();
            $this->validate($data, $this->baseUrl . '/index.php?page=livres&action=edit&id=' . (int) $id);
            $this->livreModel->update($id, $data['titre'], $data['auteur'], $data['categorie_id'], $data['annee'] === '' ? null : (int) $data['annee'], (int) $data['quantite_disponible']);
            redirectWithMessage($this->baseUrl . '/index.php?page=livres', 'success', 'Livre modifié avec succès.');
        }

        $editLivre = $this->livreModel->getById($id);
        $this->index($editLivre);
    }

    public function delete($id)
    {
        if ($this->livreModel->countEmprunts($id) > 0) {
            redirectWithMessage($this->baseUrl . '/index.php?page=livres', 'error', 'Impossible de supprimer ce livre car il est lié à un emprunt.');
        }

        $this->livreModel->delete($id);
        redirectWithMessage($this->baseUrl . '/index.php?page=livres', 'success', 'Livre supprimé avec succès.');
    }

    private function readPost()
    {
        return [
            'titre' => trim($_POST['titre'] ?? ''),
            'auteur' => trim($_POST['auteur'] ?? ''),
            'categorie_id' => (int) ($_POST['categorie_id'] ?? 0),
            'annee' => trim($_POST['annee'] ?? ''),
            'quantite_disponible' => trim($_POST['quantite_disponible'] ?? ''),
        ];
    }

    private function validate($data, $redirectUrl)
    {
        if ($data['titre'] === '' || $data['auteur'] === '' || $data['categorie_id'] <= 0 || $data['quantite_disponible'] === '') {
            redirectWithMessage($redirectUrl, 'error', 'Veuillez remplir tous les champs obligatoires.', $data);
        }

        if ($data['annee'] !== '') {
            $anneeMax = (int) date('Y') + 1;

            if (!ctype_digit($data['annee']) || (int) $data['annee'] < 0 || (int) $data['annee'] > $anneeMax) {
                redirectWithMessage($redirectUrl, 'error', 'L’année du livre n’est pas valide.', $data);
            }
        }

        if (!ctype_digit($data['quantite_disponible']) || (int) $data['quantite_disponible'] < 0) {
            redirectWithMessage($redirectUrl, 'error', 'La quantité disponible doit être un nombre supérieur ou égal à 0.', $data);
        }
    }
}
