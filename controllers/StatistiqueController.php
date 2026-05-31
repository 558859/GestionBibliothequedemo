<?php

require_once __DIR__ . '/../models/Statistique.php';

class StatistiqueController
{
    private $statistiqueModel;
    private $baseUrl;

    public function __construct($db, $baseUrl)
    {
        $this->statistiqueModel = new Statistique($db);
        $this->baseUrl = $baseUrl;
    }

    public function retards()
    {
        $retards = $this->statistiqueModel->retards();
        $pageTitle = 'Retards';
        $pageHeading = 'Étudiants en retard';
        $activePage = 'retards';
        $baseUrl = $this->baseUrl;
        require __DIR__ . '/../views/retards.php';
    }

    public function statistiques()
    {
        $livresParCategorie = $this->statistiqueModel->livresParCategorie();
        $totalEmprunts = $this->statistiqueModel->totalEmprunts();
        $livresPlusEmpruntes = $this->statistiqueModel->livresPlusEmpruntes();
        $etudiantsPlusActifs = $this->statistiqueModel->etudiantsPlusActifs();
        $pageTitle = 'Statistiques';
        $pageHeading = 'Statistiques simples';
        $activePage = 'statistiques';
        $baseUrl = $this->baseUrl;
        require __DIR__ . '/../views/statistiques.php';
    }
}
