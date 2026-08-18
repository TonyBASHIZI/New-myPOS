<?php 

$errors = [];
$success = false;

$voirboss = new Voirboss(); 


$id = $_POST['id_voir'] ?? null;

$row = $voirboss->first(['id' => $id]); 


if(!empty($_SERVER['HTTP_REFERER']) && !strstr($_SERVER['HTTP_REFERER'], "voirboss-edit"))
{
    $_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
  
    $errors = $voirboss->validate($_POST, $id); 

    if(empty($errors))
    {
        
        $voir->update($id, $_POST);

        echo "
        <script>
            alert('Traitement réussi');

            // recharge la page principale
            window.opener.location.reload();

            // ferme popup
            window.close();
        </script>
        ";

        exit;
    }
    else
    {
        echo "
        <script>
            alert('Erreur lors du traitement');
        </script>
        ";
    }
}
