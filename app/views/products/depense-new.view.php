<?php require views_path('partials/header');?>

	<div class="container-fluid border rounded p-4 m-2 col-lg-4 mx-auto">

		<form method="post" enctype="multipart/form-data">

			<h5 class="text-primary"><i class="fa fa-hamburger"></i> Add depense</h5>
			
			<div class="mb-3">
			  <label for="productControlInput1" class="form-label">Motif depense</label>
			  <input name="motif_depense" type="text" class="form-control <?=!empty($errors['motif_depense']) ? 'border-danger':''?>" id="productControlInput1" placeholder="Motif depense">
			  <?php if(!empty($errors['motif_depense'])):?>
					<small class="text-danger"><?=$errors['description']?></small>
				<?php endif;?>
			</div>
			
			<div class="mb-3">
			  <label for="barcodeControlInput1" class="form-label">Montant <small class="text-muted"></small></label>
			  <input name="montant" type="text" class="form-control <?=!empty($errors['montant']) ? 'border-danger':''?>" id="barcodeControlInput1" placeholder="Montant depense">
			  <?php if(!empty($errors['montant'])):?>
					<small class="text-danger"><?=$errors['montant']?></small>
				<?php endif;?>
			</div>

			<div class="mb-3">
			  <label for="barcodeControlInput1" class="form-label">Date depense <small class="text-muted"></small></label>
			  <input name="date_depense" type="date" class="form-control <?=!empty($errors['date_depense']) ? 'border-danger':''?>" id="barcodeControlInput1" placeholder="">
			</div>

			
				

			<br>
			<button class="btn btn-primary float-end">Save</button>
			<a href="index.php?pg=admin&tab=depense">
				<button type="button" class="btn btn-danger">Cancel</button>
			</a>
		</form>
	</div>

<?php require views_path('partials/footer');?>

