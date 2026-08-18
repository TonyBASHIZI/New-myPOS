<?php require views_path('partials/header');?>

	<div id="carouselProduits" class="carousel slide mb-5" data-bs-ride="carousel">
  
  <!-- Indicateurs (les petits points) -->
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselProduits" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#carouselProduits" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#carouselProduits" data-bs-slide-to="2"></button>
  </div>

  <!-- Images -->
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="https://images.pexels.com/photos/4489749/pexels-photo-4489749.jpeg" class="d-block w-100" style="height: 350px; object-fit: cover;">
    </div>
    <div class="carousel-item">
      <img src="https://images.pexels.com/photos/4489749/pexels-photo-4489749.jpeg" class="d-block w-100" style="height: 350px; object-fit: cover;">
    </div>
    <div class="carousel-item">
      <img src="https://images.pexels.com/photos/4489749/pexels-photo-4489749.jpeg" class="d-block w-100" style="height: 350px; object-fit: cover;">
    </div>
  </div>

  <!-- Flèches -->
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselProduits" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>

  <button class="carousel-control-next" type="button" data-bs-target="#carouselProduits" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>

</div>

	<div class="container my-5">
    <h3 class="text-center mb-4">Produits PBCars</h3>

    <div class="row g-4">

        <!-- PRODUIT 1 -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm">
                <img src="../public/assets/imgpresentation/imgpres2.jpeg" class="card-img-top" alt="Produit 1">
                <div class="card-body text-center">
                    <h5 class="card-title">Produit PBCars</h5>
                    <p class="card-text text-success fw-bold">GOMA</p>
                    
                </div>
            </div>
        </div>

        <!-- PRODUIT 2 -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm">
                <img src="../public/assets/imgpresentation/imgpres4.jpeg" class="card-img-top" alt="Produit 2">
                <div class="card-body text-center">
                    <h5 class="card-title">Produit PBCars</h5>
                    <p class="card-text text-success fw-bold">GOMA</p>
                    
                </div>
            </div>
        </div>

        <!-- PRODUIT 3 -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm">
                <img src="../public/assets/imgpresentation/imgpres6.jpeg" class="card-img-top" alt="Produit 3">
                <div class="card-body text-center">
                    <h5 class="card-title">Produit PBCars</h5>
                    <p class="card-text text-success fw-bold">GOMA</p>
                    
                </div>
            </div>
        </div>

    </div>
    <div class="row g-4">

        <!-- PRODUIT 4 -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm">
                <img src="../public/assets/imgpresentation/imgpres3.jpeg" class="card-img-top" alt="Produit 1">
                <div class="card-body text-center">
                    <h5 class="card-title">Produit PBCars</h5>
                    <p class="card-text text-success fw-bold">GOMA</p>
                    
                </div>
            </div>
        </div>

        <!-- PRODUIT 5 -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm">
                <img src="../public/assets/imgpresentation/imgpres5.jpeg" class="card-img-top" alt="Produit 2">
                <div class="card-body text-center">
                    <h5 class="card-title">Produit PBCars</h5>
                    <p class="card-text text-success fw-bold">GOMA</p>
                    
                </div>
            </div>
        </div>

        <!-- PRODUIT 6 -->
        <div class="col-12 col-md-4">
            <div class="card shadow-sm">
                <img src="../public/assets/imgpresentation/imgpres1.jpeg" class="card-img-top" alt="Produit 3">
                <div class="card-body text-center">
                    <h5 class="card-title">Produit PBCars</h5>
                    <p class="card-text text-success fw-bold">GOMA</p>
                    
                </div>
            </div>
        </div>

    </div>
</div>

<div class="bg-dark text-light text-center py-3 mt-5">
    <p class="mb-1">Contactez-nous : +243 000 000 000 | Email : info@pb-cars.com</p>
    <p class="mb-0">Adresse : 123 Rue Principale, Goma, RDC</p>
    <small>&copy; PBCars Shop de Pièces de Rechange</small>
</div>

	<br>
		<center>
			<h1>Access Denied!</h1>
			<div><?=Auth::getMessage()?></div>
		</center>
	<br>
	
<?php require views_path('partials/footer');?>