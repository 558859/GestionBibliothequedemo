<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="row">
  <div class="col-xl-4 col-lg-12">
    <div class="content-card">
      <div class="card-head">
        <h5><i class="bi bi-plus-lg me-2"></i>Enregistrer un emprunt</h5>
      </div>
      <div class="card-body-custom">
        <div class="form-section-title">Informations de l'emprunt</div>
        <form method="POST" action="<?= h($baseUrl) ?>/index.php?page=emprunts&action=create">
          <div class="mb-3">
            <label class="form-label">Livre <span class="text-danger">*</span></label>
            <select name="livre_id" class="form-select" required>
              <option value="">-- Choisir un livre --</option>
              <?php foreach ($livres as $livre): ?>
                <option value="<?= h($livre['id']) ?>" <?= (int) old('livre_id', 0) === (int) $livre['id'] ? 'selected' : '' ?>>
                  <?= h($livre['titre']) ?> (disponible: <?= h($livre['quantite_disponible']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Étudiant <span class="text-danger">*</span></label>
            <select name="etudiant_id" class="form-select" required>
              <option value="">-- Choisir un étudiant --</option>
              <?php foreach ($etudiants as $etudiant): ?>
                <option value="<?= h($etudiant['id']) ?>" <?= (int) old('etudiant_id', 0) === (int) $etudiant['id'] ? 'selected' : '' ?>>
                  <?= h($etudiant['prenom'] . ' ' . $etudiant['nom'] . ' - ' . $etudiant['numero_etudiant']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Date d'emprunt <span class="text-danger">*</span></label>
              <input type="date" name="date_emprunt" class="form-control" value="<?= h(old('date_emprunt', date('Y-m-d'))) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Retour prévu <span class="text-danger">*</span></label>
              <input type="date" name="date_retour_prevue" class="form-control" value="<?= h(old('date_retour_prevue')) ?>" required>
            </div>
          </div>
          <button type="submit" class="btn-primary-custom"><i class="bi bi-floppy"></i> Enregistrer</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-xl-8 col-lg-12">
    <div class="content-card">
      <div class="card-head">
        <h5><i class="bi bi-arrow-left-right me-2"></i>Liste des emprunts</h5>
        <div class="d-flex gap-2">
          <a href="<?= h($baseUrl) ?>/index.php?page=emprunts" class="btn-secondary-custom">Tous</a>
          <a href="<?= h($baseUrl) ?>/index.php?page=emprunts&filtre=en_cours" class="btn-primary-custom">En cours</a>
        </div>
      </div>
      <div class="card-body-custom p-0">
        <?php if (count($emprunts) === 0): ?>
          <div class="empty-state">
            <i class="bi bi-arrow-left-right"></i>
            <p><?= $filtre === 'en_cours' ? 'Aucun emprunt en cours pour le moment.' : 'Aucun emprunt enregistré pour le moment.' ?></p>
          </div>
        <?php else: ?>
        <table class="custom-table table-wide">
          <thead>
            <tr>
              <th class="cell-long">Livre</th>
              <th class="cell-medium">Étudiant</th>
              <th class="cell-medium">Emprunt</th>
              <th class="cell-medium">Retour prévu</th>
              <th class="cell-medium">Statut</th>
              <th class="table-actions">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($emprunts as $emprunt): ?>
              <tr>
                <td class="cell-long" data-label="Livre"><?= h($emprunt['titre']) ?></td>
                <td class="cell-medium" data-label="Étudiant"><?= h($emprunt['prenom'] . ' ' . $emprunt['nom']) ?></td>
                <td class="cell-medium" data-label="Emprunt"><?= h($emprunt['date_emprunt']) ?></td>
                <td class="cell-medium" data-label="Retour prévu"><?= h($emprunt['date_retour_prevue']) ?></td>
                <td class="cell-medium" data-label="Statut"><span class="badge-filiere"><?= $emprunt['est_retourne'] ? 'Retourné' : 'En cours' ?></span></td>
                <td class="table-actions" data-label="Action">
                  <?php if (!$emprunt['est_retourne']): ?>
                    <span class="action-group">
                      <form method="POST" action="<?= h($baseUrl) ?>/index.php?page=emprunts&action=retour&id=<?= h($emprunt['id']) ?>" class="m-0">
                        <button type="submit" class="btn-icon return" title="Marquer comme retourné"><i class="bi bi-check2-circle"></i></button>
                      </form>
                    </span>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
