<?php
$categorieLabels = array_map(function ($row) {
    return $row['nom'];
}, $livresParCategorie);
$categorieValues = array_map(function ($row) {
    return (int) $row['total'];
}, $livresParCategorie);
$topLivreLabels = array_map(function ($row) {
    return $row['titre'];
}, $livresPlusEmpruntes);
$topLivreValues = array_map(function ($row) {
    return (int) $row['total'];
}, $livresPlusEmpruntes);
$topEtudiantLabels = array_map(function ($row) {
    return $row['prenom'] . ' ' . $row['nom'];
}, $etudiantsPlusActifs);
$topEtudiantValues = array_map(function ($row) {
    return (int) $row['total'];
}, $etudiantsPlusActifs);
?>
<?php require __DIR__ . '/layout/header.php'; ?>

<div class="row g-3 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="stat-card green">
      <div class="stat-icon green"><i class="bi bi-arrow-left-right"></i></div>
      <div>
        <div class="stat-label">Nombre total d'emprunts</div>
        <div class="stat-value"><?= h($totalEmprunts) ?></div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-lg-6">
    <div class="content-card h-100">
      <div class="card-head">
        <h5><i class="bi bi-pie-chart me-2"></i>Répartition par catégorie</h5>
      </div>
      <div class="card-body-custom">
        <?php if (array_sum($categorieValues) === 0): ?>
          <div class="empty-state">
            <i class="bi bi-pie-chart"></i>
            <p>Aucune donnée de catégorie pour le moment.</p>
          </div>
        <?php else: ?>
          <div class="chart-box">
            <canvas class="js-chart" data-type="doughnut" data-label="Livres" data-labels="<?= h(json_encode($categorieLabels, JSON_UNESCAPED_UNICODE)) ?>" data-values="<?= h(json_encode($categorieValues)) ?>"></canvas>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="content-card h-100">
      <div class="card-head">
        <h5><i class="bi bi-bar-chart me-2"></i>Livres les plus empruntés</h5>
      </div>
      <div class="card-body-custom">
        <?php if (array_sum($topLivreValues) === 0): ?>
          <div class="empty-state">
            <i class="bi bi-bar-chart"></i>
            <p>Aucun emprunt de livre pour le moment.</p>
          </div>
        <?php else: ?>
          <div class="chart-box">
            <canvas class="js-chart" data-type="bar" data-label="Emprunts" data-labels="<?= h(json_encode($topLivreLabels, JSON_UNESCAPED_UNICODE)) ?>" data-values="<?= h(json_encode($topLivreValues)) ?>"></canvas>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="content-card">
  <div class="card-head">
    <h5><i class="bi bi-mortarboard me-2"></i>Étudiants les plus actifs</h5>
  </div>
  <div class="card-body-custom">
    <?php if (array_sum($topEtudiantValues) === 0): ?>
      <div class="empty-state">
        <i class="bi bi-mortarboard"></i>
        <p>Aucun emprunt étudiant pour le moment.</p>
      </div>
    <?php else: ?>
      <div class="chart-box chart-box-wide">
        <canvas class="js-chart" data-type="bar" data-label="Emprunts" data-labels="<?= h(json_encode($topEtudiantLabels, JSON_UNESCAPED_UNICODE)) ?>" data-values="<?= h(json_encode($topEtudiantValues)) ?>"></canvas>
      </div>
    <?php endif; ?>
  </div>
</div>

<div class="row">
  <div class="col-lg-6">
    <div class="content-card">
      <div class="card-head">
        <h5><i class="bi bi-tags me-2"></i>Livres par catégorie</h5>
      </div>
      <div class="card-body-custom p-0">
        <?php if (count($livresParCategorie) === 0): ?>
          <div class="empty-state">
            <i class="bi bi-tags"></i>
            <p>Aucune donnée statistique disponible pour le moment.</p>
          </div>
        <?php else: ?>
        <table class="custom-table table-wide">
          <thead>
            <tr>
              <th class="cell-long">Catégorie</th>
              <th class="cell-medium">Nombre de livres</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($livresParCategorie as $row): ?>
              <tr>
                <td class="cell-long" data-label="Catégorie"><?= h($row['nom']) ?></td>
                <td class="cell-medium" data-label="Nombre de livres"><?= h($row['total']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="content-card">
      <div class="card-head">
        <h5><i class="bi bi-book me-2"></i>Livres les plus empruntés</h5>
      </div>
      <div class="card-body-custom p-0">
        <?php if (count($livresPlusEmpruntes) === 0): ?>
          <div class="empty-state">
            <i class="bi bi-book"></i>
            <p>Aucun emprunt de livre pour le moment.</p>
          </div>
        <?php else: ?>
        <table class="custom-table table-wide">
          <thead>
            <tr>
              <th class="cell-long">Livre</th>
              <th class="cell-medium">Emprunts</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($livresPlusEmpruntes as $row): ?>
              <tr>
                <td class="cell-long" data-label="Livre"><?= h($row['titre']) ?></td>
                <td class="cell-medium" data-label="Emprunts"><?= h($row['total']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="content-card">
  <div class="card-head">
    <h5><i class="bi bi-mortarboard me-2"></i>Étudiants qui empruntent le plus</h5>
  </div>
  <div class="card-body-custom p-0">
    <?php if (count($etudiantsPlusActifs) === 0): ?>
      <div class="empty-state">
        <i class="bi bi-mortarboard"></i>
        <p>Aucun emprunt étudiant pour le moment.</p>
      </div>
    <?php else: ?>
    <table class="custom-table table-wide">
      <thead>
        <tr>
          <th class="cell-medium">Étudiant</th>
          <th class="cell-medium">Numéro</th>
          <th class="cell-medium">Emprunts</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($etudiantsPlusActifs as $row): ?>
          <tr>
            <td class="cell-medium" data-label="Étudiant"><?= h($row['prenom'] . ' ' . $row['nom']) ?></td>
            <td class="cell-medium" data-label="Numéro"><?= h($row['numero_etudiant']) ?></td>
            <td class="cell-medium" data-label="Emprunts"><?= h($row['total']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
