<div class="container my-3">
    <input id="searchInput" 
           type="text" 
           class="form-control" 
           placeholder="Rechercher...">
</div>
<div class="table-responsive">
	
	<table class="table table-striped table-hover" id="myTable">
		<tr>
			<th>Barcode</th><th>Product</th><th>Qty</th><th>Price</th>
			<th>
				<a href="#">Total
					 <button onclick="exportTableToExcel()" class="btn btn-success">
                    Exporter en Excel
                </button>
				</a>
			</th>
		</tr>

		<?php if (!empty($stock)):?>
			<?php foreach ($stock as $product):?>
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
				
					<!-- <td> <a href="index.php?pg=product-edit&id=<?=$product['id']?>">
						<button class="btn btn-success btn-sm">Edit</button>
					</a>
					<a href="index.php?pg=product-delete&id=<?=$product['id']?>">
						<button class="btn btn-danger btn-sm">Delete</button>
					</a>
					
				</td> -->
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
 <script>
    function exportTableToExcel() {
        var table = document.getElementById("myTable");
        var workbook = XLSX.utils.table_to_book(table, {sheet:"Feuille1"});
        XLSX.writeFile(workbook, "export_table.xlsx");
    }
</script>
