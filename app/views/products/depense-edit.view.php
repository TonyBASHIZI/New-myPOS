<?php require views_path('partials/header');?>

	<div class="container-fluid border rounded p-4 m-2 col-lg-4 mx-auto">

		<?php if(!empty($row)):?>

		<form method="post" enctype="multipart/form-data">

			<h5 class="text-primary"><i class="fa fa-hamburger"></i> Edit depense</h5>
			
			<div class="mb-3">
			  <label for="productControlInput1" class="form-label">Product montant</label>
			  <input value="<?=set_value('motif_depense',$row['motif_depense'])?>" name="motif_depense" type="text" class="form-control <?=!empty($errors['motif_depense']) ? 'border-danger':''?>" id="productControlInput1" placeholder="Product montant">
			  <?php if(!empty($errors['motif_depense'])):?>
					<small class="text-danger"><?=$errors['motif_depense']?></small>
				<?php endif;?>
			</div>
			
            <div class="mb-3">
			  <label for="productControlInput1" class="form-label">Product montant</label>
			  <input value="<?=set_value('montant',$row['montant'])?>" name="montant" type="text" class="form-control <?=!empty($errors['montant']) ? 'border-danger':''?>" id="productControlInput1" placeholder="Product montant">
			  <?php if(!empty($errors['montant'])):?>
					<small class="text-danger"><?=$errors['montant']?></small>
				<?php endif;?>
			</div>
			
				<br>
			
			
			
			<button class="btn btn-danger float-end">Save</button>
			<a href="index.php?pg=admin&tab=products">
				<button type="button" class="btn btn-primary">Cancel</button>
			</a>
		</form>
		<?php else:?>
			That product was not found
			<br><br>
			<a href="index.php?pg=admin&tab=products">
				<button type="button" class="btn btn-primary">Back to products</button>
			</a>

		<?php endif;?>

	</div>

<?php require views_path('partials/footer');?>