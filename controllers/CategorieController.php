<?php

require_once __DIR__ . '/../models/Categorie.php';

class CategorieController
{
    private $categorieModel;
    private $baseUrl;

    public function __construct($db, $baseUrl)
    {
        $this->categorieModel = new Categorie($db);
        $this->baseUrl = $baseUrl;
    }

    public function index($editCategorie = null)
    {
        $categories = $this->categorieModel->getAllWithCounts();
        $pageTitle = 'Catégories';
        $pageHeading = 'Gestion des catégories';
        $activePage = 'categories';
        $baseUrl = $this->baseUrl;
        require __DIR__ . '/../views/categories/index.php';
    }

    public function create()
    {
        $nom = trim($_POST['nom'] ?? '');

        if ($nom === '') {
            redirectWithMessage($this->baseUrl . '/index.php?page=categories', 'error', 'Le nom de la catégorie est obligatoire.', ['nom' => $_POST['nom'] ?? '']);
        }

        $this->categorieModel->create($nom);
        redirectWithMessage($this->baseUrl . '/index.php?page=categories', 'success', 'Catégorie ajoutée avec succès.');
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');

            if ($nom === '') {
                redirectWithMessage($this->baseUrl . '/index.php?page=categories&action=edit&id=' . (int) $id, 'error', 'Le nom de la catégorie est obligatoire.', ['nom' => $_POST['nom'] ?? '']);
            }

            $this->categorieModel->update($id, $nom);
            redirectWithMessage($this->baseUrl . '/index.php?page=categories', 'success', 'Catégorie modifiée avec succès.');
        }

        $editCategorie = $this->categorieModel->getById($id);
        $this->index($editCategorie);
    }

    public function delete($id)
    {
        if ($this->categorieModel->countLivres($id) > 0) {
            redirectWithMessage($this->baseUrl . '/index.php?page=categories', 'error', 'Impossible de supprimer cette catégorie car elle contient des livres.');
        }

        $this->categorieModel->delete($id);
        redirectWithMessage($this->baseUrl . '/index.php?page=categories', 'success', 'Catégorie supprimée avec succès.');
    }
}
