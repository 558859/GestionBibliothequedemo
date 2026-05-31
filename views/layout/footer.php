    </div>

    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal">
          <div class="modal-header border-0 pb-0">
            <div class="delete-modal-icon"><i class="bi bi-trash"></i></div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body pt-3">
            <h5 class="modal-title mb-2" id="deleteModalTitle">Confirmer la suppression</h5>
            <p class="mb-2" id="deleteModalMessage">Voulez-vous vraiment supprimer cet élément ?</p>
            <p class="delete-modal-note mb-0" id="deleteModalNote">La suppression peut être bloquée si cet élément est lié à des données.</p>
          </div>
          <div class="modal-footer border-0 pt-0">
            <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Annuler</button>
            <form method="POST" id="deleteModalForm" class="m-0">
              <button type="submit" class="btn-danger-custom"><i class="bi bi-trash"></i> Supprimer</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="<?= h($baseUrl) ?>/src/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= h($baseUrl) ?>/assets/vendor/chartjs/chart.umd.min.js"></script>
    <script src="<?= h($baseUrl) ?>/assets/js/app.js"></script>
    <?php clearFlashData(); ?>
  </body>
</html>
