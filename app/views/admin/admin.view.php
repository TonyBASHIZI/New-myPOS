<?php require views_path('partials/header');?>

<div style="color:#444">
	<center class="p-2"><h4><i class="fa fa-user-shield"></i> Admin</h4></center>

	<div class="container-fluid row">
		<div class="col-12 col-sm-4 col-md-3 col-lg-2">
			<ul class="list-group">
				<?php if(Auth::access('admin')):?>
				<a href="index.php?pg=admin&tab=dashboard">
			  		<li class="list-group-item <?=!$tab || $tab == 'dashboard'?'active':''?>" class="nav-item"
                    style="border-radius:8px;"
                    onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='';"><i class="fa fa-th-large"></i> Dashboard</li>
			  	</a>
				
			  	<a href="index.php?pg=admin&tab=users">
			  		<li class="list-group-item <?=$tab=='users'?'active':''?>" class="nav-item"
                    style="border-radius:8px;"
                    onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='';"><i class="fa fa-users" class="nav-item"></i> Users</li>
				</a>
				
				<a href="index.php?pg=admin&tab=categories">
			  		<li class="list-group-item <?=$tab =='categories'?'active':''?>" class="nav-item"
                    style="border-radius:8px;"
                    onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='';"><i class="fa fa-folder"></i> Categories</li>
				</a>
				<a href="index.php?pg=admin&tab=stocks">
			  		<li class="list-group-item <?=$tab=='stocks'?'active':''?>" class="nav-item"
                    style="border-radius:8px;"
                    onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='';"><i class="fa fa-box"></i> Stock</li>
				</a>
			  	<a href="index.php?pg=admin&tab=products">
			  		<li class="list-group-item <?=$tab=='products'?'active':''?>" class="nav-item"
                    style="border-radius:8px;"
                    onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='';"><i class="fa fa-box"></i> Products</li>
				</a>
				<!--<a href="index.php?pg=admin&tab=productshistorique">-->
			 <!-- 		<li class="list-group-item <?=$tab=='productshistorique'?'active':''?>"><i class="fa fa-box"></i> Log Products</li>-->
				<!--</a>-->
				<!--<a href="index.php?pg=admin&tab=approvisionnement">-->
			 <!-- 		<li class="list-group-item <?=$tab=='approvisionnement'?'active':''?>"><i class="fa fa-boxes"></i> Stock Supply</li>-->
				<!--</a>-->

				<!--<a href="index.php?pg=admin&tab=journal">-->
			 <!-- 		<li class="list-group-item <?=$tab=='journal'?'active':''?>"><i class="fa fa-clipboard-list"></i> Supply Log</li>-->
				<!--</a>-->
				<a href="index.php?pg=admin&tab=transfert">
			  		<li class="list-group-item <?=$tab=='transfert'?'active':''?>" class="nav-item"
                    style="border-radius:8px;"
                    onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='';"><i class="fa fa-clipboard-list"></i> Transfert history</li>
				</a>
				<a href="index.php?pg=admin&tab=orders">
			  		<li class="list-group-item <?=$tab=='orders'?'active':''?>" class="nav-item"
                    style="border-radius:8px;"
                    onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='';"><i class="fa fa-shopping-cart"></i> Orders</li>
				</a>
				<a href="index.php?pg=admin&tab=sales">
			  		<li class="list-group-item <?=$tab=='sales'?'active':''?>" class="nav-item"
                    style="border-radius:8px;"
                    onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='';"><i class="fa fa-shopping-cart"></i> Sales</li>
				</a>
				<a href="index.php?pg=admin&tab=saleshistorique">
			  		<li class="list-group-item <?=$tab=='saleshistorique'?'active':''?>" class="nav-item"
                    style="border-radius:8px;"
                    onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='';"><i class="fa fa-shopping-cart"></i> History Sales</li>
				</a>
				<a href="index.php?pg=admin&tab=stock">
			  		<li class="list-group-item <?=$tab=='stock'?'active':''?>" class="nav-item"
                    style="border-radius:8px;"
                    onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='';"><i class="fa fa-exclamation-triangle"></i> Low Stock</li>
				</a>
				<a href="index.php?pg=admin&tab=depense">
			  		<li class="list-group-item <?=$tab=='depense'?'active':''?>" class="nav-item"
                    style="border-radius:8px;"
                    onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='';"><i class="fa fa-wallet"></i> Expenses</li>
				</a>
				<a href="index.php?pg=admin&tab=voirboss">
			  		<li class="list-group-item <?=$tab=='voirboss'?'active':''?>" class="nav-item"
                    style="border-radius:8px;"
                    onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='';"><i class="fa fa-wallet"></i> Voir Boss</li>
				</a>
			<?php endif;?>

		<?php if(!Auth::access('admin')):?>
		<a href="index.php?pg=admin&tab=sales">
			  		<li class="list-group-item <?=$tab=='sales'?'active':''?>" class="nav-item"
                    style="border-radius:8px;"
                    onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='';"><i class="fa fa-shopping-cart"></i> Sales</li>
				</a>
				<?php endif;?>
				<!-- <a href="index.php?pg=admin&tab=rapport">
			  		<li class="list-group-item <?=$tab=='rapport'?'active':''?>"><i class="fa fa-money-bill-wave"></i> Rapport</li>
				</a> -->
				
				
			  	<a href="index.php?pg=logout">
			  		<li class="list-group-item" class="nav-item"
                    style="border-radius:8px;"
                    onmouseover="this.style.backgroundColor='red'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor=''; this.style.color='';"><i class="fa fa-sign-out-alt"></i> Logout</li>
				</a>
			</ul>
		</div>
		<div class="border col p-3">
			
			<h4><?=strtoupper($tab)?></h4>

			<?php  
			
			    switch ($tab) {

			    	case 'stocks':
						// code...
						require views_path('admin/stocks');
						break;
				    
				    case 'productshistorique':
						// code...
						require views_path('admin/historiqueproducts');
						break;
						
					case 'orders':
					    require views_path('admin/orders');
					    break;
									    
				    case 'saleshistorique':
						// code...
						require views_path('admin/historiquesales');
						break;
				    
					case 'voirboss':
						// code...
						require views_path('admin/voirboss');
						break;
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

					case 'stock':
								// code...
								require views_path('admin/stock');
								break;			
					
					case 'depense':
								// code...
								require views_path('admin/depense');
								break;
					case 'transfert':
								// code...
								require views_path('admin/transfert');
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