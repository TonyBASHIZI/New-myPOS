<style>
  @media print {
    .no-print {
      display: none;
    }
  }
</style>


  <!-- MODAL DE CONFIRMATION DE RETOUR -->
<!-- MODAL RAPPORT BOSS -->
<div class="js-boss-modal hide" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 9999; display: none; align-items: center; justify-content: center;">
    <div style="width:450px; background-color:white; padding:25px; border-radius:12px; border-top: 10px solid #17a2b8;">
        <div class="text-center mb-3">
            <i class="fa fa-user-tie text-info" style="font-size: 50px;"></i>
            <h4 class="fw-bold text-dark mt-2">Form Voir boss</h4>
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
        </div>

        <!-- CHAMP 3 : DETAILS / NOTES -->
        <div class="mb-4">
            <label class="form-label small fw-bold">Note / Justification</label>
            <textarea id="js-note-boss" class="form-control" rows="2" placeholder="Ex: Remise accordée ou versement partiel"></textarea>
        </div>

        <button onclick="save_boss_report()" class="btn btn-info btn-lg w-100 py-3 mb-2 shadow text-white"><b>Save</b></button>
        <button onclick="document.querySelector('.js-boss-modal').style.display='none'" class="btn btn-link w-100 text-secondary text-decoration-none">Cancel</button>
    </div>
</div>



<div class="table-responsive">

	<table class="table table-striped table-hover">
        <!-- ton tableau existant avec ses données -->
    </table>
</div> <br>
<ul class="nav nav-tabs">
  
  <li class="nav-item">
    <a class="nav-link <?=($section =='table') ? 'active':''?>" aria-current="page" href="index.php?pg=admin&tab=saleshistorique">
	    Table View
	</a>
	
  </li>
  
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;flex-wrap:wrap;gap:10px;">

    <!-- Champ de recherche -->
    <input type="text" id="searchInput" placeholder=" Rechercher..."
    style="padding:8px 10px;border:1px solid #ccc;border-radius:6px;width:250px;">

    <!-- Bouton export -->
   
                <div class="mb-3">
                    <button onclick="exportTableToExcel()" class="btn btn-success">
                        <i class="fa fa-file-excel"></i> Exporter en Excel
                    </button>

                    <button onclick="functionTablePDF()" class="btn btn-danger ms-2">
                        <i class="fa fa-file-pdf"></i> Exporter en PDF
                    </button>
                </div>

</div>

  
  <li class="nav-item">
    <!-- <a class="nav-link <?=($section =='graph') ? 'active':''?>" href="index.php?pg=admin&tab=sales&s=graph">
	    Graph View
	</a> -->
  </li>
  
</ul>
<br>

<form method="GET" class="row mb-3">

    <input type="hidden" name="pg" value="admin">
    <input type="hidden" name="tab" value="saleshistorique">

    <div class="col-md-3">
        <label>From</label>
        <input type="date" name="date_from" class="form-control"
               value="<?= $_GET['date_from'] ?? '' ?>">
    </div>

    <div class="col-md-3">
        <label>To</label>
        <input type="date" name="date_to" class="form-control"
               value="<?= $_GET['date_to'] ?? '' ?>">
    </div>

    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary">Go</button>
    </div>

    <div class="col-md-2 d-flex align-items-end">
        <a href="index.php?pg=admin&tab=saleshistorique" class="btn btn-secondary">
            Reset
        </a>
    </div>

</form>

<div class="table-responsive" id="tableData">

	<table class="table table-striped table-hover">
		<tr>
			<th>Barcode</th><th>Facture No</th><th>Details</th><th>Qty</th><th>U Price</th><th>Total to paid</th><th>Total paid</th><th>Balance</th><th>Caissier</th><th>Date sales</th>
			

			<th>
	
			</th>
		</tr>
        <tbody>
		<?php if (!empty($allsales)):?>
			<?php foreach ($allsales as $sale):?>
	 		<tr>
				<td><?=esc($sale['barcode'])?></td>
				<td><?=esc($sale['receipt_no'])?></td>
				<td>
 					<?=esc($sale['description'])?>
 				</td>
				<td><?=esc($sale['qty'])?></td>
				<td><?=esc($sale['amount'])?>$</td>
				<td><?=esc($sale['total'])?>$</td>
                 <td class="text-success fw-bold">
            $<?= number_format($sale['total'] - $sale['balance'], 2) ?>
        </td>
				 <td style="font-weight: bold; color: <?= ($sale['balance'] > 0) ? '#e74c3c' : '#2ecc71' ?>;">
            $<?= number_format($sale['balance'], 2) ?>
            <?php if($sale['balance'] > 0): ?>
                <br><small class="badge bg-danger"> A justifier</small>
            <?php endif; ?>
        </td>
				<?php 
					$cashier = get_user_by_id($sale['user_id']);
					if(empty($cashier)){
						$name = "Unknown";
						$namelink = "#";
					}else{
						$name = $cashier['username'];
						
						$namelink = "index.php?pg=profile&id=".$cashier['id'];
					}
				?>
				<td>
					<a href="<?=$namelink?>">
						<?=esc($name)?>
					</a>
				</td>
		
				<td><?=date("jS M, Y",strtotime($sale['date']))?></td>
				
			</tr>
			<?php endforeach;?>
		<?php endif;?>
		<tbody>
	</table>


</div>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
 
 <script>
     function hide_modal(e,modal)
{
    if(e == true || e.target.getAttribute("role") == "close-button")
    {
        if(modal == "amount-paid"){
            document.querySelector(".js-amount-paid-modal").classList.add("hide");
        }else
        if(modal == "change"){
            document.querySelector(".js-change-modal").classList.add("hide");
        }else
        if(modal == "confirm"){
            document.querySelector(".js-confirm-modal").classList.add("hide");
            CONFIRM_CALLBACK = null; // cancel clears the pending action
        }
    }
}
 </script>

<script>

// recherche
document.getElementById("searchInput").addEventListener("keyup", function() {

    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#tableData tbody tr");

    rows.forEach(function(row) {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });

});


// export excel
function exportTableToExcel() {
    var table = document.getElementById("tableData");
    var workbook = XLSX.utils.table_to_book(table, {sheet:"Feuille1"});
    XLSX.writeFile(workbook, "ventePBCARS.xlsx");
}function functionTablePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'pt', 'a4'); // landscape, since you have many columns

    // Title
    doc.setFontSize(14);
    doc.text("Sales Report", 40, 30);
    doc.setFontSize(9);
    doc.text("Generated: " + new Date().toLocaleString(), 40, 45);

    doc.autoTable({
        html: '#tableData table',   // point directly at the table inside your div
        startY: 55,
        theme: 'striped',
        headStyles: {
            fillColor: [40, 40, 40],
            textColor: 255,
            fontSize: 8
        },
        bodyStyles: {
            fontSize: 8
        },
        // Drop the trailing empty <th></th> column if it has no real data
        columnStyles: {
            10: { cellWidth: 0 } // adjust/remove if that last column is actually used
        },
        margin: { left: 20, right: 20 }
    });

    doc.save('sales_export_' + Date.now() + '.pdf');
}


</script>




