<?php

require_once __DIR__ . '/../models/Etudiant.php';

class EtudiantController
{
    private $etudiantModel;
    private $baseUrl;

    public function __construct($db, $baseUrl)
    {
        $this->etudiantModel = new Etudiant($db);
        $this->baseUrl = $baseUrl;
    }

    public function index($editEtudiant = null)
    {
        $etudiants = $this->etudiantModel->getAll();
        $pageTitle = 'Étudiants';
        $pageHeading = 'Gestion des étudiants';
        $activePage = 'etudiants';
        $baseUrl = $this->baseUrl;
        require __DIR__ . '/../views/etudiants/index.php';
    }

    public function create()
    {
        $data = $this->readPost();
        $this->validate($data, 0, $this->baseUrl . '/index.php?page=etudiants');
        $this->etudiantModel->create($data['nom'], $data['prenom'], $data['numero_etudiant'], $data['filiere'], $data['contact']);
        redirectWithMessage($this->baseUrl . '/index.php?page=etudiants', 'success', 'Étudiant ajouté avec succès.');
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->readPost();
            $this->validate($data, $id, $this->baseUrl . '/index.php?page=etudiants&action=edit&id=' . (int) $id);
            $this->etudiantModel->update($id, $data['nom'], $data['prenom'], $data['numero_etudiant'], $data['filiere'], $data['contact']);
            redirectWithMessage($this->baseUrl . '/index.php?page=etudiants', 'success', 'Étudiant modifié avec succès.');
        }

        $editEtudiant = $this->etudiantModel->getById($id);
        $this->index($editEtudiant);
    }

    public function delete($id)
    {
        if ($this->etudiantModel->countEmprunts($id) > 0) {
            redirectWithMessage($this->baseUrl . '/index.php?page=etudiants', 'error', 'Impossible de supprimer cet étudiant car il est lié à un emprunt.');
        }

        $this->etudiantModel->delete($id);
        redirectWithMessage($this->baseUrl . '/index.php?page=etudiants', 'success', 'Étudiant supprimé avec succès.');
    }

    private function readPost()
    {
        return [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'numero_etudiant' => trim($_POST['numero_etudiant'] ?? ''),
            'filiere' => trim($_POST['filiere'] ?? ''),
            'contact' => trim($_POST['contact'] ?? ''),
        ];
    }

    private function validate($data, $id, $redirectUrl)
    {
        if ($data['nom'] === '' || $data['prenom'] === '' || $data['numero_etudiant'] === '' || $data['filiere'] === '') {
            redirectWithMessage($redirectUrl, 'error', 'Veuillez remplir tous les champs obligatoires.', $data);
        }

        if ($data['contact'] === '') {
            redirectWithMessage($redirectUrl, 'error', "Le contact de l'étudiant est obligatoire.", $data);
        }

        if ($this->etudiantModel->numeroExists($data['numero_etudiant'], $id)) {
            redirectWithMessage($redirectUrl, 'error', 'Ce numéro étudiant existe déjà.', $data);
        }
    }
}
