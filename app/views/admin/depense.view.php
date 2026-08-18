<div class="container my-3">
    <input id="searchInput" 
           type="text" 
           class="form-control" 
           placeholder="Rechercher...">
</div>

<form method="GET" class="row mb-3">

    <input type="hidden" name="pg" value="admin">
    <input type="hidden" name="tab" value="depenses">

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
        <a href="index.php?pg=admin&tab=depenses"
           class="btn btn-secondary w-100">
            Reset
        </a>
    </div>

</form>


<h3>
    Total Expense :
    <span class="text-danger">
        $<?=number_format($total_depense,2)?>
    </span>
</h3>

<div class="table-responsive">
	
	<table class="table table-striped table-hover" id="myTable">
		<tr>
			<th>Details</th>
			<th>Amount</th>
			<th>Own user</th>
			<th>Date</th>
			
			<th>
				
					<button class="btn btn-primary btn-sm" onclick="show_depense_modal()">
    					<i class="fa fa-plus"></i> Add Expense
					</button>
                    <button onclick="exportTableToExcel()" class="btn btn-success">
                    Export Excel File
                </button>
				
			</th>
		</tr>

		<!-- MODAL AJOUT DEPENSE -->
<!-- MODAL DEPENSE -->
<div class="js-depense-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 9999; display: none; align-items: center; justify-content: center;">
    <div style="width:450px; background-color:white; padding:25px; border-radius:12px; border-top: 10px solid #007bff;">
        <div class="text-center mb-3">
            <i class="fa fa-wallet text-primary" style="font-size: 50px;"></i>
            <h4 class="fw-bold text-dark mt-2" id="depense-title">New Expense</h4>
        </div>

        <input type="hidden" id="js-depense-id">
        
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
				<td><?=esc($depense['motif_depense'])?></td>
				<td><?=esc($depense['montant'])?>$</td>
				<td><?=esc($depense['username'])?></td>
				<td><?=esc($depense['date_depense'])?></td>			
				
					<td>

					<button class="btn btn-success btn-sm" 
                         onclick='edit_depense_modal(<?=json_encode($depense)?>)'>
                         Edit
                    </button>
					
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
// 1. Ouvrir pour un AJOUT
function show_depense_modal() {
    document.getElementById("depense-title").innerText = "Expense Form";
    document.getElementById("js-depense-id").value = ""; 
    document.getElementById("js-depense-motif").value = "";
    document.getElementById("js-depense-montant").value = "";
    document.querySelector(".js-depense-modal").style.display = "flex";
}

// 2. Ouvrir pour une EDITION
function edit_depense_modal(data) {
    document.getElementById("depense-title").innerText = "Edit Expense";
    document.getElementById("js-depense-id").value = data.id_depense;
    document.getElementById("js-depense-motif").value = data.motif_depense;
    document.getElementById("js-depense-montant").value = data.montant;
    document.querySelector(".js-depense-modal").style.display = "flex";
}


// 3. Sauvegarder (Ajout ou Edit)
function save_depense() {
    var id = document.getElementById("js-depense-id").value;
    var montant = document.getElementById("js-depense-montant").value;
    var motif = document.getElementById("js-depense-motif").value;

    if(montant === "" || motif === "") {
        Swal.fire("Attention", "Veuillez remplir tous les champs", "warning");
        return;
    }

    var type = (id === "") ? "add_depense" : "edit_depense";

    var data = {
        data_type: type,
        id_depense: id,
        montant: montant,
        motif: motif
    };

    var ajax = new XMLHttpRequest();
    ajax.open('post', 'index.php?pg=ajax', true);
    ajax.onload = function() {
        if (ajax.status === 200) {
            document.querySelector(".js-depense-modal").style.display = "none";
            try {
                var res = JSON.parse(ajax.responseText);
                Swal.fire("Succès", res.data, "success").then(() => { location.reload(); });
            } catch(e) {
                console.error("Erreur retour JSON", ajax.responseText);
            }
        }
    };
    ajax.send(JSON.stringify(data));
}



</script>
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