<?php
$sidebarCounts = $sidebarCounts ?? ($GLOBALS['sidebarCounts'] ?? []);
$items = [
    'dashboard' => ['Tableau de bord', 'bi-grid-1x2', 'dashboard', false],
    'livres' => ['Livres', 'bi-book', 'livres', true],
    'categories' => ['Catégories', 'bi-tags', 'categories', true],
    'etudiants' => ['Étudiants', 'bi-mortarboard', 'etudiants', true],
    'emprunts' => ['Emprunts', 'bi-arrow-left-right', 'emprunts', true],
    'retards' => ['Retards', 'bi-exclamation-triangle', 'retards', true],
    'statistiques' => ['Statistiques', 'bi-bar-chart', 'statistiques', false],
    'apropos' => ['À propos', 'bi-info-circle', 'apropos', false],
];
?>
<div class="sidebar collapse d-md-block" id="sidebarNav">
  <div class="sidebar-section-title">Navigation</div>
  <?php foreach ($items as $key => $item): ?>
    <a href="<?= h($baseUrl) ?>/index.php?page=<?= h($item[2]) ?>" class="<?= $activePage === $key ? 'active' : '' ?>">
      <span class="sidebar-link-main">
        <i class="bi <?= h($item[1]) ?>"></i>
        <span><?= h($item[0]) ?></span>
      </span>
      <?php if ($item[3] && array_key_exists($key, $sidebarCounts)): ?>
        <span class="sidebar-count"><?= h($sidebarCounts[$key]) ?></span>
      <?php endif; ?>
    </a>
  <?php endforeach; ?>
</div>
