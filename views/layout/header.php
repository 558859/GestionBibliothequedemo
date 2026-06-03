<?php
$pageTitle = $pageTitle ?? 'Gestion Bibliothèque';
$pageHeading = $pageHeading ?? $pageTitle;
$activePage = $activePage ?? '';
$baseUrl = $baseUrl ?? '/gbibliotheque';
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= h($pageTitle) ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= h($baseUrl) ?>/assets/img/favicon.svg?v=1" />
    <script>
      (function () {
        var theme = localStorage.getItem('gb-theme') || 'light';
        document.documentElement.dataset.theme = theme;
      })();
    </script>
    <link href="<?= h($baseUrl) ?>/src/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= h($baseUrl) ?>/src/bootstrap-icons-1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="<?= h($baseUrl) ?>/assets/css/app.css" rel="stylesheet" />
  </head>
  <body>
    <div class="topbar">
      <button class="topbar-menu-btn d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarNav" aria-controls="sidebarNav" aria-expanded="false" aria-label="Ouvrir le menu">
        <i class="bi bi-list"></i>
      </button>
      <a href="<?= h($baseUrl) ?>/index.php" class="brand">
        <img src="<?= h($baseUrl) ?>/assets/img/favicon.svg" alt="" class="brand-icon" aria-hidden="true">
        <span class="brand-text">Gestion <span>Bibliothèque</span></span>
      </a>
      <div class="topbar-welcome">Bienvenue dans votre espace bibliothèque</div>
      <div class="topbar-right">
        <button class="theme-toggle" type="button" aria-label="Changer le thème" title="Changer le thème">
          <i class="bi bi-moon-stars"></i>
        </button>
        <span class="user-name">Admin</span>
        <div class="avatar">BU</div>
      </div>
    </div>

    <?php require __DIR__ . '/sidebar.php'; ?>

    <div class="main-content">
      <div class="page-header">
        <div>
          <h1><?= h($pageHeading) ?></h1>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?= h($baseUrl) ?>/index.php">Accueil</a></li>
              <li class="breadcrumb-item active"><?= h($pageHeading) ?></li>
            </ol>
          </nav>
        </div>
      </div>
      <?php showMessage(); ?>
