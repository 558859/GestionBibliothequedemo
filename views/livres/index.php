<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="row">
  <div class="col-xl-4 col-lg-12">
    <div class="content-card">
      <div class="card-head">
        <h5><i class="bi bi-book me-2"></i><?= $editLivre ? 'Modifier un livre' : 'Ajouter un livre' ?></h5>
      </div>
      <div class="card-body-custom">
        <div class="form-section-title">Informations du livre</div>
        <form method="POST" action="<?= h($baseUrl) ?>/index.php?page=livres&action=<?= $editLivre ? 'edit&id=' . h($editLivre['id']) : 'create' ?>">
          <div class="mb-3">
            <label class="form-label">Titre <span class="text-danger">*</span></label>
            <input type="text" name="titre" class="form-control" value="<?= h(old('titre', $editLivre['titre'] ?? '')) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Auteur <span class="text-danger">*</span></label>
            <input type="text" name="auteur" class="form-control" value="<?= h(old('auteur', $editLivre['auteur'] ?? '')) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Catégorie <span class="text-danger">*</span></label>
            <select name="categorie_id" class="form-select" required>
              <option value="">-- Choisir une catégorie --</option>
              <?php foreach ($categories as $categorie): ?>
                <option value="<?= h($categorie['id']) ?>" <?= (int) old('categorie_id', $editLivre['categorie_id'] ?? 0) === (int)$categorie['id'] ? 'selected' : '' ?>>
                  <?= h($categorie['nom']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Année</label>
              <input type="number" name="annee" class="form-control" value="<?= h(old('annee', $editLivre['annee'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Quantité disponible <span class="text-danger">*</span></label>
              <input type="number" name="quantite_disponible" min="0" class="form-control" value="<?= h(old('quantite_disponible', $editLivre['quantite_disponible'] ?? '0')) ?>" required>
            </div>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn-primary-custom"><i class="bi bi-floppy"></i> Enregistrer</button>
            <?php if ($editLivre): ?>
              <a href="<?= h($baseUrl) ?>/index.php?page=livres" class="btn-secondary-custom"><i class="bi bi-x-lg"></i> Annuler</a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-xl-8 col-lg-12">
    <div class="content-card">
      <div class="card-head">
        <h5><i class="bi bi-list-ul me-2"></i>Liste des livres</h5>
      </div>
      <div class="card-body-custom">
        <form method="GET" action="<?= h($baseUrl) ?>/index.php" class="d-flex gap-2 mb-3">
          <input type="hidden" name="page" value="livres">
          <input type="text" name="q" class="form-control" placeholder="Rechercher par titre, auteur ou catégorie" value="<?= h($search) ?>">
          <button class="btn-primary-custom" type="submit"><i class="bi bi-search"></i> Rechercher</button>
        </form>
      </div>
      <div class="card-body-custom p-0">
        <?php if (count($livres) === 0): ?>
          <div class="empty-state">
            <i class="bi bi-book"></i>
            <p><?= $search !== '' ? 'Aucun livre ne correspond à cette recherche.' : 'Aucun livre enregistré pour le moment.' ?></p>
          </div>
        <?php else: ?>
        <table class="custom-table table-wide">
          <thead>
            <tr>
              <th class="cell-long">Titre</th>
              <th class="cell-medium">Auteur</th>
              <th class="cell-medium">Catégorie</th>
              <th class="cell-small">Année</th>
              <th class="cell-small">Qté</th>
              <th class="table-actions">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($livres as $livre): ?>
              <tr>
                <td class="cell-long" data-label="Titre"><?= h($livre['titre']) ?></td>
                <td class="cell-medium" data-label="Auteur"><?= h($livre['auteur']) ?></td>
                <td class="cell-medium" data-label="Catégorie"><span class="badge-filiere"><?= h($livre['categorie']) ?></span></td>
                <td class="cell-small" data-label="Année"><?= h($livre['annee']) ?></td>
                <td class="cell-small" data-label="Qté"><?= h($livre['quantite_disponible']) ?></td>
                <td class="table-actions" data-label="Actions">
                  <span class="action-group">
                    <a class="btn-icon edit" href="<?= h($baseUrl) ?>/index.php?page=livres&action=edit&id=<?= h($livre['id']) ?>"><i class="bi bi-pencil-square"></i></a>
                    <button type="button" class="btn-icon delete" data-delete-url="<?= h($baseUrl) ?>/index.php?page=livres&action=delete&id=<?= h($livre['id']) ?>" data-delete-title="Supprimer un livre" data-delete-message="Voulez-vous vraiment supprimer ce livre ?" data-delete-note="Un livre lié à un emprunt ne peut pas être supprimé."><i class="bi bi-trash"></i></button>
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
