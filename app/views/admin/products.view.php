<div class="container my-3">
    <div class="mb-3">
                <button onclick="exportTableToExcel()" class="btn btn-success">
                    Exporter en Excel
                </button>
            </div>
</div>
<div class="container my-3">
    <input type="text"
           id="search"
           class="form-control"
           placeholder="Search...">
</div>
<form method="GET" class="row mb-3">

    <input type="hidden" name="pg" value="admin">
    <input type="hidden" name="tab" value="products">

    <div class="col-md-3">
        <label>From</label>
        <input type="date"
               name="date_from"
               class="form-control"
               value="<?= $_GET['date_from'] ?? '' ?>">
    </div>

    <div class="col-md-3">
        <label>To</label>
        <input type="date"
               name="date_to"
               class="form-control"
               value="<?= $_GET['date_to'] ?? '' ?>">
    </div>

    <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-primary w-100">
            Go
        </button>
    </div>

    <div class="col-md-2 d-flex align-items-end">
        <a href="index.php?pg=admin&tab=products"
           class="btn btn-secondary w-100">
            Reset
        </a>
    </div>

</form>

<h3 class="mb-3">
    Total Stock Value :
    <span class="text-success">
        $<?= number_format($total_stock,2) ?>
    </span>
</h3>

<div class="table-responsive">
	
	<table class="table table-striped table-hover" id="tableBody">
	   <tbody>
		<tr>
			<th>Barcode</th><th>Lot</th><th>Shop</th><th>Product</th><th>Old_Qty</th><th>Qty</th><th>Price</th><th>Total net</th><th>Image</th><th>Date</th>
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
				<td style="color:green"><?=esc($product['lot'])?></td>
				<td><?=esc($product['shop'])?></td>
				<td style="font-weight: bold;">
					<a href="index.php?pg=products-single&id=<?=$product['id']?>">
						<?=esc($product['description'])?>
					</a>	
				</td>
				<td><?=esc($product['qty_old'])?></td>
				<td style="font-weight: bold;"><?=esc($product['qty'])?></td>
				<td style="font-weight: bold;"><?=esc($product['amount'])?>$</td>
				<td style="font-weight: bold;"><?=esc($product['amount']) * esc($product['qty'])?>$</td>
				<td>
					<img src="<?=crop($product['image'])?>" style="width: 100%;max-width:100px;" >
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
					<!--<a href="index.php?pg=product-delete&id=<?=$product['id']?>">-->
					<!--	<button class="btn btn-info btn-sm">Details</button>-->
					<!--</a>-->
					<!-- <a href="index.php?pg=product-facture&id=<?=$product['id']?>" target="_blank">
						<button class="btn btn-primary btn-sm">Facture</button>
					</a> -->
					<!-- Ajoutez ce bouton dans votre boucle foreach, près des boutons Edit/Delete -->
                    
                    <button onclick="openTransferModal(<?=htmlspecialchars(json_encode($product))?>)" class="btn btn-warning btn-sm">
                        <i class="fa fa-exchange-alt"></i> Transfer
                    </button>
                    
                    
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach;?>
		<?php endif;?>
		
		
		</tbody>
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
    <label class="form-label">Quantity to transfert (Max: <b id="transfer_max_stock"></b>) :</label>
    <!-- On change l'id en transfer_qty_input -->
    <input type="number" id="transfer_qty_input" class="form-control" min="1" autocomplete="off">
</div>


        <div class="d-flex justify-content-between mt-4">
            <button onclick="closeModal()" class="btn btn-secondary">Cancel</button>
            <button onclick="sendTransfer()" class="btn btn-warning">Execute Transfert</button>
        </div>
    </div>
</div>

<script>
// Export Excel
function exportTableToExcel() {
    var table = document.getElementById("tableBody");
    var workbook = XLSX.utils.table_to_book(table, {sheet:"Feuille1"});
    XLSX.writeFile(workbook, "export_table.xlsx");
}

// --- LOGIQUE DE TRANSFERT ---
</script>

<script>
function openTransferModal(product) {
    document.getElementById('transferId').value = product.id;
    document.getElementById('transferProductName').value = product.description;
    
    // On remplit le nouveau span du max stock
    document.getElementById('transfer_max_stock').innerText = product.qty;
    
    document.getElementById('transferFromShop').value = "2";
    document.getElementById('transferToShop').value = "";
    
    // On vide l'input de saisie
    document.getElementById('transfer_qty_input').value = "";
    
    document.getElementById('transferModal').style.display = 'block';
}


function closeModal() {
    document.getElementById('transferModal').style.display = 'none';
}

function sendTransfer() {
    // On cible spécifiquement le nouvel ID unique
    let inputField = document.getElementById('transfer_qty_input');
    let qty_saisie = inputField.value; 
    
    let id_source = document.getElementById('transferId').value;
    let to_shop = document.getElementById('transferToShop').value;
    let stock_max = parseInt(document.getElementById('transfer_max_stock').innerText);

    // --- TEST DE DEBUG ---
    // alert("Valeur récupérée : " + qty_saisie); 

    if (qty_saisie === "" || parseInt(qty_saisie) <= 0) {
        alert("Veuillez saisir une quantité.");
        return;
    }

    if (parseInt(qty_saisie) > stock_max) {
        alert("Erreur : " + qty_saisie + " dépasse le stock de " + stock_max);
        return;
    }

    let data = {
        data_type: "transfer_stock",
        id_source: id_source,
        qty: parseInt(qty_saisie), // ICI on envoie bien la saisie
        to_shop: to_shop
    };

    fetch('index.php?pg=ajax', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        alert(res.data);
        location.reload();
    })
    .catch(err => alert("Erreur de communication"));
}
</script>
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











