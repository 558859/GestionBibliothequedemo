<?php
$items = [
    'dashboard' => ['Tableau de bord', 'bi-grid-1x2', 'dashboard'],
    'livres' => ['Livres', 'bi-book', 'livres'],
    'categories' => ['Catégories', 'bi-tags', 'categories'],
    'etudiants' => ['Étudiants', 'bi-mortarboard', 'etudiants'],
    'emprunts' => ['Emprunts', 'bi-arrow-left-right', 'emprunts'],
    'retards' => ['Retards', 'bi-exclamation-triangle', 'retards'],
    'statistiques' => ['Statistiques', 'bi-bar-chart', 'statistiques'],
];
?>
<div class="sidebar collapse d-md-block" id="sidebarNav">
  <div class="sidebar-section-title">Navigation</div>
  <?php foreach ($items as $key => $item): ?>
    <a href="<?= h($baseUrl) ?>/index.php?page=<?= h($item[2]) ?>" class="<?= $activePage === $key ? 'active' : '' ?>">
      <i class="bi <?= h($item[1]) ?>"></i> <?= h($item[0]) ?>
    </a>
  <?php endforeach; ?>
</div>
