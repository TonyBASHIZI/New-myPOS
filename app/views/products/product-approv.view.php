<?php require views_path('partials/header');?>

	<div class="container-fluid border rounded p-4 m-2 col-lg-4 mx-auto">

		<?php if(!empty($row)):?>

		<form method="post" enctype="multipart/form-data">

			<h5 class="text-primary"><i class="fa fa-hamburger"></i> Approvisionner Product</h5>
			
			<div class="mb-3">
			  <label for="productControlInput1" class="form-label">Produit description</label>
			  <input value="<?=set_value('description',$row['description'])?>" name="description" type="text" class="form-control <?=!empty($errors['description']) ? 'border-danger':''?>" id="productControlInput1" placeholder="Product description">
			  <?php if(!empty($errors['description'])):?>
					<small class="text-danger"><?=$errors['description']?></small>
				<?php endif;?>
			</div>
			
			<div class="mb-3">
			  <label for="barcodeControlInput1" class="form-label">Quantite <small class="text-muted"></small></label>
			  <input name="qty_appro" type="text" class="form-control" id="barcodeControlInput1" placeholder="Product qty">
			 
			</div>

			  <input type="hidden" value="<?=set_value('id',$row['id'])?>" name="id_produit" type="text" class="form-control" id="barcodeControlInput1" placeholder="Product qty">
			 
			
            <br>
			<button class="btn btn-success float-end">Save</button>
			<a href="index.php?pg=admin&tab=products">
				<button type="button" class="btn btn-danger">Cancel</button>
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