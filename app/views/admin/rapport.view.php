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
   






</head>
<!-- =============== Design & Develop By = MJ MARAZ   ====================== -->

<body>
    
    <header class="header_part">
        <img src="assets/images/logo.png" alt="" class="img-fluid">
        
    </header>
    <!-- =======  Data-Table  = Start  ========================== -->
     
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="data_table">
					

                    <table id="example" class="table table-striped table-bordered">
                        <button class="btn btn-primary btn-sm">Add new<a href="index.php?pg=product-new"> <i class="fa fa-plus"></i></button>
                        <thead class="table-dark">

                      
                            <tr class="table-primary">
                                <th>Barcode</th>
                                <th>Nom produit</th>
                                <th>Quantite</th>
                                <th>prix unitaire</th>
                                <th>Image</th>
                                <th>Effectuer par</th>
                                <th>date</th>
                              
                            </tr>
                        </thead>
                      
                        <tbody>
                        <?php if (!empty($sales)):?>
                            <?php foreach ($sales as $sale):?>
                            <tr>
                                <td><?=esc($sale['barcode'])?></td>
                                <td><?=esc($sale['description'])?></td>
                                <td><?=esc($sale['qty'])?></td>                             
                                <td><?=esc($sale['amount'])?></td>
                                <td><img src="<?=crop($sale['image'])?>" style="width: 100%;max-width:100px;" ></td>
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


    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/datatables.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/custom.js"></script>




</body>

</html>
