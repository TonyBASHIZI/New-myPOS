<?php 
   //include ('Mail/mailer.php');
   //include ('Mail/sms.php');
   $mailAdmin = "tonnybash5@gmail.com";
   $totalCollect = number_format($sales_total,2);
  //var_dump($totalCollect);die();
   
       if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] == 'sms') {
           

            $message = "PBCARSoil Rapport ventes:\n";
            
            foreach($result as $row)
            {
                $message .= $row['username']." : USD".number_format($row['total_sales'],2)."\n";
            }
            
             $param1 ="243977756737";
        //   $param2 = "VOTRE COMMANDE EST RECUE NOUS ALLONS VOUS CONTACTER LE TOTAL EST DE: " .$total. "USD";
            // $param2 = "PBCARSoil Total collected this week " .$totalCollect. "USD , ShopGoma";
          
          // Initialize cURL session
          $ch = curl_init();
          
          // URL to call
          $url = "https://api.keccel.com/sms/v1/message.asp?token=K54GTBD3RWUTCUK&from=BIAKUUZA&to=". urlencode($param1) . "&message=". urlencode($message);
          
          // Set cURL options
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // To return the response as a string
          
          // Execute the cURL request
          $response = curl_exec($ch);
          
          // Check if the request was successful
          if ($response === false) {
          // echo "cURL Error: " . curl_error($ch);
          } else {
           
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: 'SMS sent succefully',
                    timer: 2500,
                    showConfirmButton: false
                });
            </script>";
          
          }
          
          // Close cURL session
          curl_close($ch);
          
          
          }
          
          
?>
<style>
  @media print {
    .no-print {
      display: none;
    }
  }
</style>
<?php if (isset($_GET['sms']) && $_GET['sms'] === 'success'): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Succès',
    text: 'SMS envoyé avec succès',
    timer: 2500,
    showConfirmButton: false
});
</script>
<?php endif; ?>
   
   <!--function printpopup invoice editable-->
    <script>
    function openTicketPopup() {
        Swal.fire({
            title: 'Infos ticket PBcars',
            html: `
                <input id="champ1" class="swal2-input" placeholder="CUSTOMER NAME">
                <input id="champ2" class="swal2-input" placeholder="AMOUNT">
                <input id="champ3" class="swal2-input" placeholder="REFERENCE">
            `,
            confirmButtonText: 'Imprimer',
            showCancelButton: true,
            cancelButtonText: 'Annuler',
            preConfirm: () => {
                return {
                    champ1: document.getElementById('champ1').value,
                    champ2: document.getElementById('champ2').value,
                    champ3: document.getElementById('champ3').value
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                printTicket(result.value);
            }
        });
    }
    </script>
    <script>
        function printTicket(data) {
            const win = window.open('', '', 'width=300,height=600');
        
            win.document.write(`
                <html>
                <head>
                    <title>Ticket</title>
                    <style>
                        body {
                            width: 58mm;
                            font-family: monospace;
                            font-size: 12px;
                        }
                        .center { text-align: center; }
                        .line { border-top: 1px dashed #000; margin: 8px 0; }
                    </style>
                </head>
                <body>
                    <div class="center">
                        <strong>MA SOCIÉTÉ</strong><br>
                        ----------------------
                    </div>
        
                    <p>Client : ${data.champ1}</p>
                    <p>Montant : ${data.champ2}</p>
                    <p>Réf : ${data.champ3}</p>
        
                    <div class="line"></div>
        
                    <div class="center">
                        Merci pour votre achat
                    </div>
        
                    <script>
                        window.print();
                        window.onafterprint = () => window.close();
                    <\/script>
                </body>
                </html>
            `);
        
            win.document.close();
        }
        </script>


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

<!--<button id="exportBtn" class="btn btn-success mb-3">-->
<!--    <i class="bi bi-file-earmark-excel"></i> Exporter Excel-->
<!--</button>-->

<div class="table-responsive">
    <table id="tableData" class="table table-bordered">
        <!-- ton tableau existant avec ses données -->
    </table>
</div> <br>
<ul class="nav nav-tabs">
  
  <li class="nav-item">
    <a class="nav-link <?=($section =='table') ? 'active':''?>" aria-current="page" href="index.php?pg=admin&tab=sales">
	    Table View
	</a>
	
  </li>
  <li class="nav-item">
    <!-- <a class="nav-link <?=($section =='graph') ? 'active':''?>" href="index.php?pg=admin&tab=sales&s=graph">
	    Graph View
	</a> -->
  </li>
  
</ul>
<br>

<?php if($section == 'table'):?>

<div>
	<form class="row float-end" class="no-print">
			<div class="col">
				<label for="start">Date start:</label>
				<input class="form-control" id="start" type="date" name="start" value="<?=!empty($_GET['start']) ? $_GET['start']:''?>">
			</div>
			<div class="col">
				<label for="end">Date End:</label>
				<input class="form-control" id="end" type="date" name="end" value="<?=!empty($_GET['end']) ? $_GET['end']:''?>">
			</div>
			<div class="col">
				<label for="limit">line:</label>
				<input style="max-width: 80px" class="form-control" id="limit" type="number" min="1" name="limit" value="<?=!empty($_GET['limit']) ? $_GET['limit']:'20'?>">
			</div>
			
			<button style="max-width:50px" class="btn col btn-primary btn-sm">Go <i class="fa fa-chevron-right"></i></button>
			<input type="hidden" name="pg" value="admin">
			<input type="hidden" name="tab" value="sales">
	</form>
	<div class="clearfix" ></div>
</div>

<div class="table-responsive" id="tableData">
	<h2>Total sales : $<?=number_format($sales_total,2)?></h2>
	<table class="table table-striped table-hover">
		<tr>
			<th>Barcode</th><th>Facture No</th><th>Details</th><th>Qty</th><th>U Price</th><th>Total to paid</th><th>Total paid</th><th>Balance</th><th>Caissier</th><th>Date sales</th>
			<th>
                <div class="d-flex gap-2">
                    <a href="index.php?pg=home" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Add sales
                    </a>
                     
                    <form method="POST">
                        <button type="submit" name="action" value="sms" class="btn btn-danger btn-sm">
                            <i class="fa fa-check"></i> Close Sales
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-secondary btn-sm" onclick="openTicketPopup()">
                        <i class="fa fa-print"></i> Ticket
                    </button>
<!--                     
                      <button onclick="exportTableToExcel()" class="btn btn-success">
                    Exporter en Excel
                </button> -->
                </div>
					
				
			</th>

			<th>
	
			</th>
		</tr>

		<?php if (!empty($sales)):?>
			<?php foreach ($sales as $sale):?>
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
				<td>
                    <?php if(Auth::access('admin')):?>
					<a href="index.php?pg=sale-edit&id=<?=$sale['id']?>">
						<button class="btn btn-success btn-sm">Edit</button>
					</a>
					<a href="index.php?pg=sale-delete&id=<?=$sale['id']?>">
						<button class="btn btn-danger btn-sm">Delete</button>
					</a>
                    <?php endif;?>
					<a href="index.php?pg=product-facture&id=<?=$sale['receipt_no']?>" target="_blank">
						<button class="btn btn-secondary btn-sm">Receipt</button>
					</a>
	                <button class="btn btn-info btn-sm text-white" onclick="show_boss_modal({
                    id: '<?=esc($sale['id'])?>',
                    description: '<?=esc($sale['description'])?>',
                    total: '<?=esc($sale['total'])?>'
                   })">
    <i class="fa fa-user-tie"></i> Voir Boss
</button>
					
				</td>
			</tr>
			<?php endforeach;?>
		<?php endif;?>
		
	</table>

<?php


?>

</div>

<?php else:?>


	<?php 

		$graph = new Graph();

		$data = generate_daily_data($today_records);
		$graph->title = "Today's sales";
		$graph->xtitle = "Hours of the day";
		$graph->styles = "width:80%;margin:auto;display:block";
		$graph->display($data);

	?>
	<br>

	<?php 

		$data = generate_monthly_data($thismonth_records);
		$graph->title = "This month's sales";
		$graph->xtitle = "Days of the month";
		$graph->styles = "width:80%;margin:auto;display:block";
		$graph->display($data);

	?>
	<br>

	<?php 

		$data = generate_yearly_data($thisyear_records);
		$graph->title = "This year's sales";
		$graph->xtitle = "Months of the year";
		$graph->styles = "width:80%;margin:auto;display:block";
		$graph->display($data);

	?>
	<br>
 


<?php endif;?>

<script>
// Variables globales pour retenir les infos de la ligne sélectionnée
var boss_sale_id = null;
var boss_product_name = "";

// 1. Fonction pour ouvrir le modal
function show_boss_modal(data) {
    boss_sale_id = data.id;
    boss_product_name = data.description;
    
    // Remplit les champs
    document.getElementById("js-facture-reel").value = data.total;
    document.getElementById("js-montant-boss").value = data.total; // Pré-rempli par défaut
    document.getElementById("js-note-boss").value = ""; 
    
    // Affiche le modal
    document.querySelector(".js-boss-modal").style.display = "flex";
}

// 2. Fonction pour envoyer le rapport (XMLHttpRequest)
function save_boss_report() {
    var m_boss = document.getElementById("js-montant-boss").value;
    var m_reel = document.getElementById("js-facture-reel").value;
    var note   = document.getElementById("js-note-boss").value;

    if(m_boss === "" || m_boss < 0) {
        Swal.fire("Erreur", "Amount invalid", "error");
        return;
    }

    var obj = { 
        data_type: "save_boss_report", 
        sale_id: boss_sale_id,
        productname: boss_product_name,
        montant_reel: m_reel,
        montant_boss: m_boss,
        note: note
    };
 
    var ajax = new XMLHttpRequest();
    ajax.open('post', 'index.php?pg=ajax', true);

    ajax.onload = function() {
        if (ajax.status === 200) {
            try {
                var res = JSON.parse(ajax.responseText);
                if(res.data_type === "save_boss_report") {
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


<script>
document.getElementById("exportBtn").addEventListener("click", function () {
    let table = document.getElementById("tableData");

    let workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });

    XLSX.writeFile(workbook, "export_table.xlsx");
});
</script>

