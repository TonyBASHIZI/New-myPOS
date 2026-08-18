<?php require views_path('partials/header');?>

<div style="color:#444">
	<center class="p-2"><h4><i class="fa fa-user-shield"></i> Admin</h4></center>

	<div class="container-fluid row">
		<div class="col-12 col-sm-4 col-md-3 col-lg-2">
			<ul class="list-group">
				<a href="index.php?pg=admin&tab=dashboard">
			  		<li class="list-group-item <?=!$tab || $tab == 'dashboard'?'active':''?>" ><i class="fa fa-th-large"></i> Dashboard</li>
			  	</a>
			  	<a href="index.php?pg=admin&tab=users">
			  		<li class="list-group-item <?=$tab=='users'?'active':''?>"><i class="fa fa-users"></i> Utilisateur</li>
				</a>
				<a href="index.php?pg=admin&tab=categories">
			  		<li class="list-group-item <?=$tab =='categories'?'active':''?>"><i class="fa fa-folder"></i> Categories</li>
				</a>
			  	<a href="index.php?pg=admin&tab=products">
			  		<li class="list-group-item <?=$tab=='products'?'active':''?>"><i class="fa fa-hamburger"></i> Produits</li>
				</a>
				
				<a href="index.php?pg=admin&tab=approvisionnement">
			  		<li class="list-group-item <?=$tab=='approvisionnement'?'active':''?>"><i class="fa fa-hamburger"></i> Approv</li>
				</a>

				<a href="index.php?pg=admin&tab=journal">
			  		<li class="list-group-item <?=$tab=='journal'?'active':''?>"><i class="fa fa-hamburger"></i> Journal</li>
				</a>
				<a href="index.php?pg=admin&tab=sales">
			  		<li class="list-group-item <?=$tab=='sales'?'active':''?>"><i class="fa fa-money-bill-wave"></i> Ventes</li>
				</a>

				
				<!-- <a href="index.php?pg=admin&tab=rapport">
			  		<li class="list-group-item <?=$tab=='rapport'?'active':''?>"><i class="fa fa-money-bill-wave"></i> Rapport</li>
				</a> -->
				
				
			  	<a href="index.php?pg=logout">
			  		<li class="list-group-item"><i class="fa fa-sign-out-alt"></i> Logout</li>
				</a>
			</ul>
		</div>
		<div class="border col p-3">
			
			<h4><?=strtoupper($tab)?></h4>

			<?php  

				switch ($tab) {
					case 'products':
						// code...
						require views_path('admin/products');
						break;
					Case 'categories':

						require views_path('admin/categories');
						break;
					case 'users':
						// code...
						require views_path('admin/users');
						break;

					case 'sales':
						// code...
						require views_path('admin/sales');
						break;
					
					case 'approvisionnement':
							// code...
							require views_path('admin/approvisionnement');
							break;
							
					case 'journal':
							// code...
							require views_path('admin/journal');
							break;		
		
					case 'rapport':
								// code...
								require views_path('admin/rapport');
								break;	
					
					default:
						// code...
						require views_path('admin/dashboard');
						break;
				}


			?>
		</div>
	</div>
</div>
<?php require views_path('partials/footer');?>