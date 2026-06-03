<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="row">
  <div class="col-xl-4 col-lg-12">
    <div class="content-card">
      <div class="card-head">
        <h5><i class="bi bi-person-plus me-2"></i><?= $editEtudiant ? 'Modifier un étudiant' : 'Ajouter un étudiant' ?></h5>
      </div>
      <div class="card-body-custom">
        <div class="form-section-title">Informations personnelles</div>
        <form method="POST" action="<?= h($baseUrl) ?>/index.php?page=etudiants&action=<?= $editEtudiant ? 'edit&id=' . h($editEtudiant['id']) : 'create' ?>">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Nom <span class="text-danger">*</span></label>
              <input type="text" name="nom" class="form-control" value="<?= h(old('nom', $editEtudiant['nom'] ?? '')) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Prénom <span class="text-danger">*</span></label>
              <input type="text" name="prenom" class="form-control" value="<?= h(old('prenom', $editEtudiant['prenom'] ?? '')) ?>" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Numéro étudiant <span class="text-danger">*</span></label>
            <input type="text" name="numero_etudiant" class="form-control" value="<?= h(old('numero_etudiant', $editEtudiant['numero_etudiant'] ?? '')) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Filière <span class="text-danger">*</span></label>
            <input type="text" name="filiere" class="form-control" value="<?= h(old('filiere', $editEtudiant['filiere'] ?? '')) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contact <span class="text-danger">*</span></label>
            <input type="text" name="contact" class="form-control" value="<?= h(old('contact', $editEtudiant['contact'] ?? '')) ?>" required>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn-primary-custom"><i class="bi bi-floppy"></i> Enregistrer</button>
            <?php if ($editEtudiant): ?>
              <a href="<?= h($baseUrl) ?>/index.php?page=etudiants" class="btn-secondary-custom"><i class="bi bi-x-lg"></i> Annuler</a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-xl-8 col-lg-12">
    <div class="content-card">
      <div class="card-head">
        <h5><i class="bi bi-people me-2"></i>Liste des étudiants</h5>
      </div>
      <div class="card-body-custom p-0">
        <?php if (count($etudiants) === 0): ?>
          <div class="empty-state">
            <i class="bi bi-mortarboard"></i>
            <p>Aucun étudiant enregistré pour le moment.</p>
          </div>
        <?php else: ?>
        <div class="etudiants-table-wrap">
        <table class="custom-table table-wide etudiants-table">
          <colgroup>
            <col class="col-nom">
            <col class="col-prenom">
            <col class="col-numero">
            <col class="col-filiere">
            <col class="col-contact">
            <col class="col-actions">
          </colgroup>
          <thead>
            <tr>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Numéro</th>
              <th>Filière</th>
              <th>Contact</th>
              <th class="table-actions">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($etudiants as $etudiant): ?>
              <tr>
                <td data-label="Nom"><?= h($etudiant['nom']) ?></td>
                <td data-label="Prénom"><?= h($etudiant['prenom']) ?></td>
                <td data-label="Numéro"><?= h($etudiant['numero_etudiant']) ?></td>
                <td data-label="Filière"><span class="badge-filiere" title="<?= h($etudiant['filiere']) ?>"><?= h($etudiant['filiere']) ?></span></td>
                <td data-label="Contact"><?= h($etudiant['contact']) ?></td>
                <td class="table-actions" data-label="Actions">
                  <span class="action-group">
                    <a class="btn-icon edit" href="<?= h($baseUrl) ?>/index.php?page=etudiants&action=edit&id=<?= h($etudiant['id']) ?>"><i class="bi bi-pencil-square"></i></a>
                    <button type="button" class="btn-icon delete" data-delete-url="<?= h($baseUrl) ?>/index.php?page=etudiants&action=delete&id=<?= h($etudiant['id']) ?>" data-delete-title="Supprimer un étudiant" data-delete-message="Voulez-vous vraiment supprimer cet étudiant ?" data-delete-note="Un étudiant lié à un emprunt ne peut pas être supprimé."><i class="bi bi-trash"></i></button>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
