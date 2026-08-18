
<div class="container my-3">
    <input id="searchInput" 
           type="text" 
           class="form-control" 
           placeholder="Rechercher...">
</div>
<div class="table-responsive">
	
	<table class="table table-striped table-hover" id="myTable">
		<tr>
			<th>Barcode</th><th>Product</th><th>Qty</th><th>Price</th><th>Total net</th><th>Image</th><th>Date</th>
			<th>
				<a href="index.php?pg=product-new">
					<button class="btn btn-primary btn-sm">
						<i class="fa fa-plus"></i> Add new products
					</button>
				</a>
			</th>
		</tr>

		<?php if (!empty($products)):?>
			<?php foreach ($products as $product):?>
	 		<tr>
				<td><?=esc($product['barcode'])?></td>
				<td>
					<a href="index.php?pg=product-single&id=<?=$product['id']?>">
						<?=esc($product['description'])?>
					</a>	
				</td>
				<td><?=esc($product['qty'])?></td>
				<td><?=esc($product['amount'])?>$</td>
				<td><?=esc($product['amount']) * esc($product['qty'])?>$</td>
				<td>
					<img src="<?=crop($product['image'])?>" style="width: 100%;max-width:100px;" >
				</td>
				<td><?=date("jS M, Y",strtotime($product['date']))?></td>
				<td>
					<a href="index.php?pg=product-edit&id=<?=$product['id']?>">
						<button class="btn btn-success btn-sm">Edit</button>
					</a>
					<a href="index.php?pg=product-delete&id=<?=$product['id']?>">
						<button class="btn btn-danger btn-sm">Delete</button>
					</a>
					<a href="index.php?pg=product-delete&id=<?=$product['id']?>">
						<button class="btn btn-info btn-sm">Details</button>
					</a>
				</td>
			</tr>
			<?php endforeach;?>
		<?php endif;?>
		
	</table>
</div>
<script>
document.getElementById('searchInput').addEventListener('input', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#myTable tbody tr");

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>