<?php require views_path('partials/header');?>
<div class="table-responsive">
	
	<table class="table table-striped table-hover">
		<tr>
			<th>Id</th><th>Categorie name</th><th>Description</th><th>Date</th>
			<th>
				<a href="#">
					<button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#formModal"><i class="fa fa-plus"></i> Add new</button>
				</a>
			</th>
		 </tr>

		<?php if(!empty($categories)):?>

			<?php foreach($categories as $categorie):?>
			<tr>
				<td><?=esc($categorie['id'])?></td>
				<td><a href="#"><?=esc($categorie['designation'])?></a></td>
				<td><?=esc($categorie['description'])?></td>
				<td><?=esc($categorie['created_at'])?></td>
				<td>
					<a href="#">
						<button class="btn btn-primary btn-sm" title="Edit">
							<i class="fas fa-edit"></i>
						</button>

					</a>
					<a href="#">
						<button class="btn btn-info btn-sm" title="View">
							<i class="fas fa-eye"></i>
						</button>
						
					</a>
					<a href="#">
						<button class="btn btn-danger btn-sm" title="Delete">
							<i class="fas fa-trash"></i>
						</button>
						
					</a>
				</td>
			</tr>
			<?php endforeach ?>

		<?php endif ?>
	</table>
</div>
<div id="notif" class="alert alert-success text-center d-none" role="alert">
  Données enregistrées avec succès !
</div>
<!-- ✅ Popup (Modal) -->
  <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <!-- En-tête -->
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="formModalLabel">
            <i class="fas fa-user-plus"></i> New category
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>

        <!-- Corps du formulaire -->
        <div class="modal-body">
          <form id="popupForm" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="categorieName" class="form-label">Category name</label>
              <input type="text" class="form-control" name="categorieName" id="categorieName" placeholder="Enter category">
              <?php if(!empty($errors['categorieName'])):?>
					<small class="text-danger"><?=$errors['categorieName']?></small>
				<?php endif;?>
            </div>

            <div class="mb-3">
              <label for="descritionCategorie" class="form-label">Description</label>
              <textarea class="form-control" name="descritionCategorie" id="descritionCategorie" rows="5" maxlength="100" placeholder="Description text (max 100 caractères)..."></textarea>
               <div class="form-text text-end"><span id="charCount">0</span>/100 caractères</div>
                <?php if(!empty($errors['descritionCategorie'])):?>
					<small class="text-danger"><?=$errors['descritionCategorie']?></small>
				<?php endif;?>
            </div>

            
         
        </div>

        <!-- Pied de la modale -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times"></i> Cancel
          </button>
          <button type="submit" form="popupForm" class="btn btn-success">
            <i class="fas fa-plus"></i> Save
          </button>
        </div>
         </form>

      </div>
    </div>
  </div>
  <script>
  const message = document.getElementById('descritionCategorie');
  const counter = document.getElementById('charCount');

  message.addEventListener('input', () => {
    counter.textContent = message.value.length;
  });
</script>




<?php require views_path('partials/footer');?>