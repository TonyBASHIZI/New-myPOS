<?php require views_path('partials/header');?>

	<div class="container-fluid border rounded p-4 m-2 col-lg-4 mx-auto">

		<form method="post" enctype="multipart/form-data">

			<h5 class="text-primary"><i class="fa fa-hamburger"></i> Add Voir Boss</h5>
			
			<div class="mb-3">
			  <label for="productControlInput1" class="form-label">Product Name</label>
			  <input name="productname" type="text" class="form-control <?=!empty($errors['productname']) ? 'border-danger':''?>" id="productControlInput1" value="<?=set_value('productname',$row['productname'])?>">
			   <input name="ref_user" type="hidden" class="form-control <?=!empty($errors['ref_user']) ? 'border-danger':''?>" id="ref_user">
			  <?php if(!empty($errors['productname'])):?>
					<small class="text-danger"><?=$errors['productname']?></small>
				<?php endif;?>
			</div>
			
			<div class="mb-3">
			  <label for="barcodeControlInput1" class="form-label">Amount Due<small class="text-muted"></small></label>
			  <input name="montant" type="text" class="form-control <?=!empty($errors['montant_total_a_payer']) ? 'border-danger':''?>" id="barcodeControlInput1" value="<?=set_value('montant_total_a_payer',$row['montant_total_a_payer'])?>">
			  <?php if(!empty($errors['montant_total_a_payer'])):?>
					<small class="text-danger"><?=$errors['montant_total_a_payer']?></small>
				<?php endif;?>
			</div>

			<div class="mb-3">
			  <label for="barcodeControlInput1" class="form-label">Date<small class="text-muted"></small></label>
			  <input name="date_creation" type="date" class="form-control <?=!empty($errors['date_creation']) ? 'border-danger':''?>" id="barcodeControlInput1" placeholder="">
			</div>

			<br>
			<button class="btn btn-primary float-end">Save</button>
			<a href="index.php?pg=admin&tab=depense">
				<button type="button" class="btn btn-danger">Cancel</button>
			</a>
		</form>
	</div>

<?php require views_path('partials/footer');?>