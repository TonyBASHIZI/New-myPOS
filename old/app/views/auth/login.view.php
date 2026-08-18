<?php view('partials/header');?>
   
<!-- CORRECTION DE LA STRUCTURE BOOTSTRAP -->
<!-- Utilisation de .container-fluid pour une largeur flexible -->
<div class="container-fluid">
    <!-- Utilisation de .row pour la grille et .justify-content-center pour centrer le contenu -->
    <div class="row justify-content-center mt-5">
        <!-- Définition de la largeur dans une colonne unique -->
        <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4">
            <!-- Ajout d'une carte pour un meilleur style -->
            <div class="card shadow-lg p-4">
                
                <form method="post">
                    <center class="mb-4">
                        <!-- Logo centré et titre -->
                        <h1><img src="../public/assets/imgpresentation/logoPBcars.jpeg" width="90px" alt="Logo"></i></h1>
                        <h3 class="mb-1">Login</h3>
                        <div class="text-muted"><?=esc(defined('APP_NAME') ? APP_NAME : 'APP Name')?></div>
                    </center>
                    
                    <br>
                
                    <!-- Affichage des erreurs générales (si besoin) -->
                    <?php if(!empty($errors)): ?>
                        <div class="alert alert-danger p-2 small">
                            Veuillez corriger les erreurs ci-dessous.
                        </div>
                    <?php endif; ?>

                    <!-- Champ Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label visually-hidden">Email</label>
                        <input value="<?=set_value('email')?>" autocomplete="off" name="email" type="email" class="form-control <?=!empty($errors['email']) ? 'border-danger':''?>" id="email" placeholder="Email" autofocus>
                        <?php if(!empty($errors['email'])):?>
                            <small class="text-danger"><?=$errors['email']?></small>
                        <?php endif;?>
                    </div> 

                    <!-- Champ Mot de passe -->
                    <div class="mb-4">
                        <!-- J'ai simplifié l'input-group qui est souvent trop complexe pour un mot de passe simple -->
                        <label for="password" class="form-label visually-hidden">Password</label>
                        <input value="<?=set_value('password')?>" name="password" type="password" class="form-control <?=!empty($errors['password']) ? 'border-danger':''?>" id="password" placeholder="Mot de passe">
                        <?php if(!empty($errors['password'])):?>
                            <small class="text-danger col-12"><?=$errors['password']?></small>
                        <?php endif;?>
                    </div>

                    <div class="d-grid">
                        <!-- d-grid pour que le bouton prenne toute la largeur -->
                        <button class="btn btn-primary btn-lg" style="font-size: 20px;">Login</button>
                    </div>
                </form>
            </div>
            <!-- Fin de la carte -->
        </div>
    </div>
</div>

<?php view('partials/footer');?>