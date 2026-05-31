<?php require __DIR__ . '/layout/header.php'; ?>

<div class="content-card">
  <div class="card-head">
    <h5><i class="bi bi-exclamation-triangle me-2"></i>Liste des retards</h5>
  </div>
  <div class="card-body-custom p-0">
    <?php if (count($retards) === 0): ?>
      <div class="empty-state">
        <i class="bi bi-check2-circle"></i>
        <p>Aucun retard actuellement.</p>
      </div>
    <?php else: ?>
      <table class="custom-table table-wide">
        <thead>
          <tr>
            <th class="cell-medium">Étudiant</th>
            <th class="cell-medium">Numéro</th>
            <th class="cell-long">Livre</th>
            <th class="cell-medium">Date d'emprunt</th>
            <th class="cell-medium">Retour prévu</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($retards as $retard): ?>
            <tr>
              <td class="cell-medium" data-label="Étudiant"><?= h($retard['prenom'] . ' ' . $retard['nom']) ?></td>
              <td class="cell-medium" data-label="Numéro"><?= h($retard['numero_etudiant']) ?></td>
              <td class="cell-long" data-label="Livre"><?= h($retard['titre']) ?></td>
              <td class="cell-medium" data-label="Date d'emprunt"><?= h($retard['date_emprunt']) ?></td>
              <td class="cell-medium" data-label="Retour prévu"><?= h($retard['date_retour_prevue']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
