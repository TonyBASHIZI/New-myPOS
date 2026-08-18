<div class="container my-3">
    <input id="searchInput" 
           type="text" 
           class="form-control" 
           placeholder="Rechercher...">
</div>
<div class="table-responsive">
	
	<table class="table table-striped table-hover" id="myTable">
		<tr>
			<th>Details</th><th>Amount</th><th>Own</th><th>Date</th>
			<th>
					<button class="btn btn-primary btn-sm" onclick="show_depense_modal()">
    					<i class="fa fa-plus"></i> New Expenses
					</button>
				
			</th>
		</tr>
<!-- MODAL AJOUT DEPENSE -->
<div class="js-depense-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 9999; display: none; align-items: center; justify-content: center;">
    <div style="width:450px; background-color:white; padding:25px; border-radius:12px; border-top: 10px solid #007bff;">
        <div class="text-center mb-3">
            <i class="fa fa-wallet text-primary" style="font-size: 50px;"></i>
            <h4 class="fw-bold text-dark mt-2">New Expense</h4>
        </div>
        
        <div class="mb-3 text-start">
            <label class="form-label small fw-bold">Description expense</label>
            <input type="text" id="js-depense-motif" class="form-control" placeholder="Ex: Achat carburant">
        </div>

        <div class="mb-3 text-start">
            <label class="form-label small fw-bold">Amount ($)</label>
            <input type="number" id="js-depense-montant" class="form-control" placeholder="Ex: 50">
        </div>

        <button onclick="save_depense()" class="btn btn-primary btn-lg w-100 py-3 mb-2 shadow text-white"><b>Save expense</b></button>
        <button onclick="document.querySelector('.js-depense-modal').style.display='none'" class="btn btn-link w-100 text-secondary text-decoration-none">Cancel</button>
    </div>
</div>

		<?php if (!empty($depense)):?>
			<?php foreach ($depense as $depense):?>
	 		<tr>
				<td><?=esc($depense['barcode'])?></td>
				<td>
					<a href="index.php?pg=depense-single&id=<?=$depense['id']?>">
						<?=esc($depense['description'])?>
					</a>	
				</td>
				<td><?=esc($depense['qty'])?></td>
				<td><?=esc($depense['amount'])?>$</td>
				<td><?=esc($depense['amount']) * esc($depense['qty'])?>$</td>
				<td>
					<img src="<?=crop($depense['image'])?>" style="width: 100%;max-width:100px;" >
				</td>
				<td><?=date("jS M, Y",strtotime($depense['date']))?></td>
				<td>
					<a href="index.php?pg=depense-edit&id=<?=$depense['id']?>">
						<button class="btn btn-success btn-sm">Edit</button>
					</a>
					<a href="index.php?pg=depense-delete&id=<?=$depense['id']?>">
						<button class="btn btn-danger btn-sm">Delete</button>
					</a>
					<a href="index.php?pg=depense-delete&id=<?=$depense['id']?>">
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

<script>
function show_depense_modal() {
    document.getElementById("js-depense-motif").value = "";
    document.getElementById("js-depense-montant").value = "";
    document.querySelector(".js-depense-modal").style.display = "flex";
}

function save_depense() {
    const motif = document.getElementById("js-depense-motif").value;
    const montant = document.getElementById("js-depense-montant").value;

    if(motif === "" || montant === "") {
        Swal.fire("Erreur", "Tous les champs sont obligatoires", "error");
        return;
    }

    const obj = {
        data_type: "add_depense",
        motif: motif,
        montant: montant
    };

    const ajax = new XMLHttpRequest();
    ajax.open('post', 'index.php?pg=ajax', true);

    ajax.onload = function() {
        if (ajax.status === 200) {
            const res = JSON.parse(ajax.responseText);
            if(res.data_type === "add_depense") {
                Swal.fire("Succès", res.data, "success").then(() => {
                    location.reload();
                });
            }
        }
    };
    ajax.send(JSON.stringify(obj));
}
</script>
