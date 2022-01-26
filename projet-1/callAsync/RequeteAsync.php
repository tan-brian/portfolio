<?php	
	require_once("../model/Crud.php");
    require_once("../model/Favori.php");

    $favori = new Favori();

    $commande = $_POST['commande'];

    unset($_POST['commande']);
    if($commande == 'insert')
        $favori->insert($_POST);
    else {
        $favori->deleteFavori($_POST['idEnchere'], $_POST['idUsager']);
    }
    
   
?>