<div class="container my-3">
    <input type="text"
           id="search"
           class="form-control"
           placeholder="Rechercher...">
</div>

<div class="table-responsive">
	
	<table class="table table-striped table-hover" id="tableBody">
	   <tbody
		<tr>
			<th>Barcode</th><th>Lot</th><th>Shop</th><th>Product</th><th>Qty</th><th>Price</th><th>Total net</th><th>Image</th><th>Date</th>
			<th>
				<a href="index.php?pg=product-new">
					<button class="btn btn-primary btn-sm">
						<i class="fa fa-plus"></i> Add new products
					</button>
				</a>
			</th>
		</tr>

		<?php if (!empty($allpro)):?>
			<?php foreach ($allpro as $product):?>
	 		<tr>
				<td><?=esc($product['barcode'])?></td>
				<td style="color:green"><?=esc($product['lot'])?></td>
				<td><?=esc($product['shop'])?></td>
				<td>
					<a href="index.php?pg=products-single&id=<?=$product['id']?>">
						<?=esc($product['description'])?>
					</a>	
				</td>
				<td><?=esc($product['qty'])?></td>
				<td><?=esc($product['amount'])?>$</td>
				<td><?=esc($product['amount']) * esc($product['qty'])?>$</td>
				<td>
					<img src="<?=crop($product['image'])?>" style="width: 100%;max-width:100px;">
				</td>
				<td><?=date("jS M, Y",strtotime($product['date']))?></td>
				<td>
					<?php if ($_SESSION['USER'] !== 'admin'): ?>
					<a href="index.php?pg=product-edit&id=<?=$product['id']?>">
						<button class="btn btn-success btn-sm">Edit</button>
					</a>
					<a href="index.php?pg=product-delete&id=<?=$product['id']?>">
						<button class="btn btn-danger btn-sm">Delete</button>
					</a>
					<a href="index.php?pg=product-delete&id=<?=$product['id']?>">
						<button class="btn btn-info btn-sm">Details</button>
					</a>
					

					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach;?>
		<?php endif;?>
		
		
		</tbody>
	</table>
</div>
<script>
                const search = document.getElementById("search");
                const rows = document.querySelectorAll("#tableBody tr");
                
                search.onkeyup = function () {
                
                    let value = search.value.toLowerCase();
                
                    rows.forEach(function(row) {
                
                        let text = row.innerText.toLowerCase();
                
                        if (text.indexOf(value) > -1) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                
                    });
                };
 </script>

