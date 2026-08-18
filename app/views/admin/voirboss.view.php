<div class="container my-3">
    <input id="searchInput" 
           type="text" 
           class="form-control" 
           placeholder="Rechercher...">
</div>
  <!-- MODAL DE CONFIRMATION DE RETOUR -->
<!-- MODAL RAPPORT BOSS -->
<div class="js-boss-modal hide" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 9999; display: none; align-items: center; justify-content: center;">
    <div style="width:450px; background-color:white; padding:25px; border-radius:12px; border-top: 10px solid #17a2b8;">
        <div class="text-center mb-3">
            <i class="fa fa-user-tie text-info" style="font-size: 50px;"></i>
            <h4 class="fw-bold text-dark mt-2">Edit Voir boss</h4>
        </div>
        
        <!-- CHAMP 1 : TOTAL REEL (Lecture seule) -->
        <div class="mb-3">
            <label class="form-label small fw-bold">Amount due ($)</label>
            <input type="text" id="js-facture-reel" class="form-control bg-light" readonly>
        </div>

        <!-- CHAMP 2 : MONTANT VOIR BOSS (Saisie) -->
        <div class="mb-3">
            <label class="form-label small fw-bold text-primary">Amount paid ($)</label>
            <input type="number" id="js-montant-boss" class="form-control border-primary" placeholder="Ex: 50">
             <input type="hidden" id="js-id-boss" class="form-control border-primary">
        </div>

        <!-- CHAMP 3 : DETAILS / NOTES -->
        <div class="mb-4">
            <label class="form-label small fw-bold">Note / Justification</label>
            <textarea id="js-note-boss" class="form-control" rows="2" placeholder="Ex: Remise accordée ou versement partiel"></textarea>
        </div>

        <button onclick="save_boss_report()" class="btn btn-info btn-lg w-100 py-3 mb-2 shadow text-white"><b>Save modification</b></button>
        <button onclick="document.querySelector('.js-boss-modal').style.display='none'" class="btn btn-link w-100 text-secondary text-decoration-none">Cancel</button>
    </div>
</div>


<div class="table-responsive">
	
	<table class="table table-striped table-hover" id="myTable">
		<tr>
			<th>REF.SALES</th><th>Product name</th><th>Amount Due</th><th>Reduced amount</th><th>Details</th><th>User</th><th>Date</th>
			<th>
				 <button onclick="exportTableToExcel()" class="btn btn-success">
                    Export Excel File
                </button>
			</th>
		</tr>

		<?php if (!empty($allVoirboss)):?>
			<?php foreach ($allVoirboss as $voirboss):?>
	 		<tr>
	 		    
	 		    <td><?=esc($voirboss['ref_sales'])?></td>
				<td><?=esc($voirboss['productname'])?></td>
				<td><?=esc($voirboss['montant_total_a_payer'])?>$</td>
				<td><?=esc($voirboss['montant_reduction'])?>$</td>
				<td><?=esc($voirboss['detail'])?></td>	
				<td><?=esc($voirboss['username'])?></td>
				<td><?=esc($voirboss['date_creation'])?></td>
				
				<td>

					<!--<a href="#">-->
					<!--	<button class="btn btn-danger btn-sm">Paid</button>-->
					<!--</a>-->
					
					<button class="btn btn-info btn-sm text-white" onclick="show_boss_modal({
                    id: '<?=esc($voirboss['id_voir'])?>',
                    detail: '<?=esc($voirboss['detail'])?>',
                    totalNet: '<?=esc($voirboss['montant_total_a_payer'])?>',
                    total: '<?=esc($voirboss['montant_reduction'])?>'
                   })">
    <i class="fa fa-user-tie"></i>Edit Voir Boss
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
        // 1. Fonction pour ouvrir le modal
function show_boss_modal(data) {
    boss_sale_id = data.id;
    boss_product_name = data.description;
    
    // Remplit les champs
    document.getElementById("js-facture-reel").value = data.totalNet;
    document.getElementById("js-montant-boss").value = data.total; // Pré-rempli par défaut
    document.getElementById("js-note-boss").value = data.detail;
    document.getElementById("js-id-boss").value = boss_sale_id;
    
    // Affiche le modal
    document.querySelector(".js-boss-modal").style.display = "flex";
}

    // send data to ajax for editing voirboss (XMLHttpRequest)
function save_boss_report() {
    var m_boss = document.getElementById("js-montant-boss").value;
    var m_reel = document.getElementById("js-facture-reel").value;
    var note   = document.getElementById("js-note-boss").value;
    var id   = document.getElementById("js-id-boss").value;

    if(m_boss === "" || m_boss < 0) {
        Swal.fire("Erreur", "Amount invalid", "error");
        return;
    }

    var obj = { 
        data_type: "save_boss_edit", 
        sale_id: boss_sale_id,
        productname: boss_product_name,
        montant_reel: m_reel,
        montant_boss: m_boss,
        note: note,
        id: id
    };
 
    var ajax = new XMLHttpRequest();
    ajax.open('post', 'index.php?pg=ajax', true);

    ajax.onload = function() {
        if (ajax.status === 200) {
            try {
                var res = JSON.parse(ajax.responseText);
                if(res.data_type === "save_boss_redit") {
                    document.querySelector(".js-boss-modal").style.display = "none";
                    Swal.fire("Succès", res.data, "success");
                    setTimeout(() => { window.location.reload(); }, 2000);
                }
            } catch(e) {
                console.error("Réponse serveur non JSON:", ajax.responseText);
                Swal.fire("Erreur", "Le serveur n'a pas renvoyé un format valide", "error");
            }
        }
    };

    ajax.send(JSON.stringify(obj));
}
        
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    
    