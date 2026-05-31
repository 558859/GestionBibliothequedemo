<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="row">
  <div class="col-xl-4 col-lg-12">
    <div class="content-card">
      <div class="card-head">
        <h5><i class="bi bi-tags me-2"></i><?= $editCategorie ? 'Modifier une catégorie' : 'Ajouter une catégorie' ?></h5>
      </div>
      <div class="card-body-custom">
        <div class="form-section-title">Informations</div>
        <form method="POST" action="<?= h($baseUrl) ?>/index.php?page=categories&action=<?= $editCategorie ? 'edit&id=' . h($editCategorie['id']) : 'create' ?>">
          <div class="mb-3">
            <label class="form-label">Nom <span class="text-danger">*</span></label>
            <input type="text" name="nom" class="form-control" value="<?= h(old('nom', $editCategorie['nom'] ?? '')) ?>" required>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn-primary-custom"><i class="bi bi-floppy"></i> Enregistrer</button>
            <?php if ($editCategorie): ?>
              <a href="<?= h($baseUrl) ?>/index.php?page=categories" class="btn-secondary-custom"><i class="bi bi-x-lg"></i> Annuler</a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-xl-8 col-lg-12">
    <div class="content-card">
      <div class="card-head">
        <h5><i class="bi bi-list-ul me-2"></i>Liste des catégories</h5>
      </div>
      <div class="card-body-custom p-0">
        <?php if (count($categories) === 0): ?>
          <div class="empty-state">
            <i class="bi bi-tags"></i>
            <p>Aucune catégorie disponible.</p>
          </div>
        <?php else: ?>
        <table class="custom-table table-wide">
          <thead>
            <tr>
              <th class="cell-small">#</th>
              <th class="cell-long">Nom</th>
              <th class="cell-small">Livres</th>
              <th class="table-actions">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($categories as $categorie): ?>
              <tr>
                <td class="cell-small" data-label="#"><?= h($categorie['id']) ?></td>
                <td class="cell-long" data-label="Nom"><?= h($categorie['nom']) ?></td>
                <td class="cell-small" data-label="Livres"><?= h($categorie['nombre_livres']) ?></td>
                <td class="table-actions" data-label="Actions">
                  <span class="action-group">
                    <a class="btn-icon edit" href="<?= h($baseUrl) ?>/index.php?page=categories&action=edit&id=<?= h($categorie['id']) ?>"><i class="bi bi-pencil-square"></i></a>
                    <button type="button" class="btn-icon delete" data-delete-url="<?= h($baseUrl) ?>/index.php?page=categories&action=delete&id=<?= h($categorie['id']) ?>" data-delete-title="Supprimer une catégorie" data-delete-message="Voulez-vous vraiment supprimer cette catégorie ?" data-delete-note="Une catégorie liée à des livres ne peut pas être supprimée."><i class="bi bi-trash"></i></button>
                  </span>
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
