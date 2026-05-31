<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/CategorieController.php';
require_once __DIR__ . '/controllers/LivreController.php';
require_once __DIR__ . '/controllers/EtudiantController.php';
require_once __DIR__ . '/controllers/EmpruntController.php';
require_once __DIR__ . '/controllers/StatistiqueController.php';

$baseUrl = '/GestionBibliothequedemo';

$database = new Database();
$conn = $database->getConnection();

$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$dashboardController = new DashboardController($conn, $baseUrl);
$categorieController = new CategorieController($conn, $baseUrl);
$livreController = new LivreController($conn, $baseUrl);
$etudiantController = new EtudiantController($conn, $baseUrl);
$empruntController = new EmpruntController($conn, $baseUrl);
$statistiqueController = new StatistiqueController($conn, $baseUrl);

switch ($page) {
    case 'categories':
        if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $categorieController->create();
        } elseif ($action === 'edit' && $id > 0) {
            $categorieController->edit($id);
        } elseif ($action === 'delete' && $id > 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $categorieController->delete($id);
        } elseif ($action === 'delete') {
            redirectWithMessage($baseUrl . '/index.php?page=categories', 'error', 'Action non autorisée.');
        } else {
            $categorieController->index();
        }
        break;

    case 'livres':
        if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $livreController->create();
        } elseif ($action === 'edit' && $id > 0) {
            $livreController->edit($id);
        } elseif ($action === 'delete' && $id > 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $livreController->delete($id);
        } elseif ($action === 'delete') {
            redirectWithMessage($baseUrl . '/index.php?page=livres', 'error', 'Action non autorisée.');
        } else {
            $livreController->index();
        }
        break;

    case 'etudiants':
        if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $etudiantController->create();
        } elseif ($action === 'edit' && $id > 0) {
            $etudiantController->edit($id);
        } elseif ($action === 'delete' && $id > 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $etudiantController->delete($id);
        } elseif ($action === 'delete') {
            redirectWithMessage($baseUrl . '/index.php?page=etudiants', 'error', 'Action non autorisée.');
        } else {
            $etudiantController->index();
        }
        break;

    case 'emprunts':
        if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $empruntController->create();
        } elseif ($action === 'retour' && $id > 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $empruntController->retour($id);
        } elseif ($action === 'retour') {
            redirectWithMessage($baseUrl . '/index.php?page=emprunts', 'error', 'Action non autorisée.');
        } else {
            $empruntController->index();
        }
        break;

    case 'retards':
        $statistiqueController->retards();
        break;

    case 'statistiques':
        $statistiqueController->statistiques();
        break;

    case 'dashboard':
    default:
        $dashboardController->index();
        break;
}
