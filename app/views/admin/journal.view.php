<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Data Table </title>
    <meta content="" name="description">
    <meta content="Author" name="MJ Maraz">
    <link href="assets/images/favicon.png" rel="icon">
    <link href="assets/images/favicon.png" rel="apple-touch-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- ========================================================= -->


    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/datatables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- Export excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

   






</head>

<body>
    
    <header class="header_part">
        <img src="assets/images/logo.png" alt="" class="img-fluid">
        
    </header>

    <div>
	
</div>
    <!-- =======  Data-Table  = Start  ========================== -->
     
    <div class="container">
        <div class="row">
            <div class="mb-3">
                <button onclick="exportTableToExcel()" class="btn btn-success">
                    Exporter en Excel
                </button>
            </div>

            <div class="col-12">
                <div class="data_table">
					
                    <table id="example" class="table table-striped table-bordered">
                    
                        <thead class="table-dark">

                      
                            <tr class="table-primary">
                                <th>Barcode</th>
                                <th>Product name</th>
                                <th>Quantity supply</th>
                                <th>U price</th>
                                
                                <th>Did by</th>
                                <th>Date supply</th>
                              
                            </tr>
                        </thead>
                      
                        <tbody>
                        <?php if (!empty($journal)):?>
                            <?php foreach ($journal as $journal):?>
                            <tr>
                                <td><?=esc($journal['barcode'])?></td>
                                <td><?=esc($journal['description'])?></td>
                                <td><?=esc($journal['qty_appro'])?></td>                             
                                <td><?=esc($journal['amount'])?>$</td>
                                
                                <?php 
					$cashier = get_user_by_id($journal['user_id']);
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
				<td><?=date("jS M, Y",strtotime($journal['date']))?></td>

                            </tr>
                    
                        </tbody>
                              
              <?php endforeach;?>
                      <?php endif;?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- =======  Data-Table  = End  ===================== -->
    <!-- ============ Java Script Files  ================== -->

  <!-- <script>
     $('#example').DataTable({
    // ... autres options de DataTables (pagination, etc.)
    
    dom: 'Bfrtip', // Nécessaire pour les boutons
    buttons: [
        {
            extend: 'excel',
            text: 'Exporter vers Excel',
            exportOptions: {
                // Cette option est CRUCIALE : elle dit d'inclure toutes les pages.
                modifier: {
                    page: 'all'
                }
            }
        },
        {
            extend: 'print',
            text: 'Imprimer',
            exportOptions: {
                // Appliquer la même règle pour l'impression
                modifier: {
                    page: 'all'
                }
            }
        },
        // ... autres boutons (copy, pdf, csv)
    ]
});

  </script> -->

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/datatables.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/custom.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>


    <script>
    function exportTableToExcel() {
        var table = document.getElementById("example");
        var workbook = XLSX.utils.table_to_book(table, {sheet:"Feuille1"});
        XLSX.writeFile(workbook, "export_table.xlsx");
    }
    </script>

    <script>
    $(document).ready(function() {
        var table = $('#example').DataTable();

        $('#searchName').on('keyup', function() {
            table.column(1).search(this.value).draw();
        });
    });
    </script>
   


</body>

</html>
