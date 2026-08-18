<div class="container my-3">
    <div class="mb-3">
                <button onclick="exportTableToExcel()" class="btn btn-success">
                    Exporter en Excel
                </button>
            </div>
</div>
<div class="container my-3">
    <input id="searchInput" 
           type="text" 
           class="form-control" 
           placeholder="Rechercher...">
</div>
<div class="table-responsive">
	
	<table class="table table-striped table-hover" id="myTable">
		<tr>
			<th>Product</th><th>From shop</th><th>To Shop</th><th>Qty</th><th>Date</th>
			<th>
				<!-- <a href="index.php?pg=product-new">
					<button class="btn btn-primary btn-sm">
						<i class="fa fa-plus"></i> Add new products
					</button>
				</a> -->
			</th>
		</tr>

		<?php if (!empty($products)):?>
			<?php foreach ($products as $product):?>
	 		<tr>
				<td>
					
						<?=esc($product['description'])?>
		
				</td>
				<td>
        <?php 
            if($product['from_location'] == 1) echo "MAURICE";
            elseif($product['from_location'] == 2) echo "ADELAR";
            else echo "Shop " . esc($product['from_location']);
        ?>
    </td>
				    <td>
        <?php 
            if($product['to_location'] == 1) echo "MAURICE";
            elseif($product['to_location'] == 2) echo "ADELAR";
            else echo "Shop " . esc($product['to_location']);
        ?>
    </td>
				<td><?= esc($product['transfer_qty']) ?></td> 
				<td><?=date("jS M, Y",strtotime($product['date_transfert']))?></td>
				<td>
					<?php if ($_SESSION['USER'] !== 'admin'): ?>
				


					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach;?>
		<?php endif;?>
		
	</table>
</div>

<!-- Modal Transfert -->
<div id="transferModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:10000;">
    <div style="background:white; width:450px; margin:50px auto; padding:25px; border-radius:12px;">
        <h4 class="text-primary"><i class="fa fa-exchange-alt"></i> Transfert de Stock</h4>
        <hr>
        
        <input type="hidden" id="transferId"> <!-- ID technique de la ligne source -->

        <div class="mb-3">
            <label class="form-label font-weight-bold">Product :</label>
            <input type="text" id="transferProductName" class="form-control" readonly>
        </div>

        <div class="row">
            <div class="col-6 mb-3">
                <label class="form-label text-danger">From Shop :</label>
                <select id="transferFromShop" class="form-select" disabled>
                    <option value="1">Shop (1)</option>
                    <option value="2" selected>Shop 2</option> 
                    
                </select>
            </div>
            <div class="col-6 mb-3">
                <label class="form-label text-success">To Shop :</label>
                <select id="transferToShop" class="form-select"> 
                    <option value="">-- Choisir --</option>
                    <option value="1">Shop 1</option>
                    <option value="2">Shop 2</option>
                    <!-- <option value="3">Dépôt (3)</option> -->
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Quantity to transfert (Max: <b id="maxQty"></b>) :</label>
            <input type="number" id="transferQty" class="form-control" min="1">
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button onclick="closeModal()" class="btn btn-secondary">Cancel</button>
            <button onclick="sendTransfer()" class="btn btn-warning">Execute Transfert</button>
        </div>
    </div>
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

	<script>

	function openTransferModal(product) {
    document.getElementById('transferId').value = product.id;
    document.getElementById('transferProductName').value = product.description;
    document.getElementById('maxQty').innerText = product.qty;
    
    // On sélectionne automatiquement le shop d'origine
    document.getElementById('transferFromShop').value = "2";
    
    // On réinitialise la destination et la quantité
    document.getElementById('transferToShop').value = "";
    document.getElementById('transferQty').value = "";
    
    document.getElementById('transferModal').style.display = 'block';
}
function closeModal() {
    document.getElementById('transferModal').style.display = 'none';
}


function sendTransfer() {
    let id_source = document.getElementById('transferId').value;
    let from_shop = document.getElementById('transferFromShop').value;
    let to_shop = document.getElementById('transferToShop').value;
    let qty = parseInt(document.getElementById('transferQty').value);
    let max = parseInt(document.getElementById('maxQty').innerText);

    if (to_shop == "" || from_shop == to_shop) {
        alert("Veuillez choisir un shop de destination différent du shop source.");
        return;
    }
    if (qty <= 0 || qty > max) {
        alert("Quantité invalide (doit être entre 1 et " + max + ")");
        return;
    }

    let data = {
        data_type: "transfer_stock",
        id_source: id_source, // L'ID de la ligne dans la table products
        qty: qty,
        to_shop: to_shop      // L'ID du shop qui reçoit
    };

  fetch('index.php?pg=ajax', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
})

    .then(res => res.json())
    .then(res => {
        console.log(res); // Pour voir la réponse du serveur dans la console
    alert(res.data);
    location.reload();
})
.catch(err => {
    console.error("Erreur de connexion:", err);
    alert("Erreur de serveur");
    });
}


function sendTransfer() {
    let to_shop = document.getElementById('transferToShop').value;

    if (to_shop == "2") {
        alert("Destination invalide : Vous ne pouvez pas transférer du Shop 2 vers le Shop 2 !");
        return;
    }
    
    // ... reste du code fetch ...
}

	</script>


