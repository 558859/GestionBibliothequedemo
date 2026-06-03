<?php require __DIR__ . '/layout/header.php'; ?>

<?php
$membres = [
    ['matricule' => '554042862358', 'nom' => 'ATTOUMANI', 'prenoms' => 'IBRAHIM'],
    ['matricule' => '554032242367', 'nom' => 'BARRY', 'prenoms' => 'MAMADOU'],
    ['matricule' => '554039412371', 'nom' => 'DADA', 'prenoms' => 'ZEKRI'],
    ['matricule' => '554018152348', 'nom' => 'DIALLO', 'prenoms' => 'BOUBACAR'],
    ['matricule' => '554015892356', 'nom' => 'MAOMY', 'prenoms' => 'CE PAYARD'],
    ['matricule' => '554018822317', 'nom' => 'TOURE', 'prenoms' => 'AMADOU SADIO'],
];
?>

<div class="content-card about-hero">
  <div class="card-body-custom">
    <div class="about-intro">
      <div class="about-icon"><i class="bi bi-book-half"></i></div>
      <div>
        <span class="about-badge">Groupe 1</span>
        <h2>Gestion Bibliothèque Universitaire</h2>
        <p>
          Ce projet de gestion de bibliothèque a été réalisé dans le cadre d'un travail de groupe.
          Il permet de gérer les livres, les catégories, les étudiants, les emprunts, les retards
          et les statistiques de la bibliothèque.
        </p>
        <p class="about-note">Projet réalisé par les membres du Groupe 1.</p>
      </div>
    </div>
  </div>
</div>

<div class="content-card">
  <div class="card-head">
    <h5><i class="bi bi-people me-2"></i>Membres du Groupe 1</h5>
    <span class="about-count"><?= count($membres) ?> membres</span>
  </div>
  <div class="card-body-custom p-0">
    <div class="about-table-wrap">
      <table class="custom-table table-wide about-table">
        <thead>
          <tr>
            <th class="about-rank">#</th>
            <th>Matricule</th>
            <th>Nom</th>
            <th>Prénoms</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($membres as $index => $membre): ?>
            <tr>
              <td class="about-rank" data-label="#"><?= h($index + 1) ?></td>
              <td class="about-matricule" data-label="Matricule"><?= h($membre['matricule']) ?></td>
              <td data-label="Nom"><?= h($membre['nom']) ?></td>
              <td data-label="Prénoms"><?= h($membre['prenoms']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
