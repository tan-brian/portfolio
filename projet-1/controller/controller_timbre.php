<?php
RequirePage::model('Crud');
RequirePage::model('Condition');
RequirePage::model('Timbre');
RequirePage::model('Image');
RequirePage::model('Mise');
RequirePage::model('Pays');
RequirePage::librairie('Validation');

class TimbreController{

 public function index(){

  //Vérifier si l'usager est authentifié
  if(Session::login()){
  
    $timbre = new Timbre();
    $queryTimbre = $timbre->selectTimbreUser($_SESSION['idUsager']);

    $condition = new Condition();
    $query = $condition->select(); 

    $pays = new Pays();
    $queryPays = $pays->select(); 

    return Twig::render('timbre/gererTimbre.html', ['conditions' => $query , 'pays' => $queryPays, 'timbres' => $queryTimbre, 'pageTitre' => 'Ajouter un timbre']);
  }else {
    RequirePage::redirect();
  }
 }

 public function gererTimbre(){
 
  // Valider le formulaire d'ajout / modification de timbre
  $val = new Validation;

  $val->name('nomTimbre')->value($_POST['nomTimbre'])->required();
  $val->name('dateCreation')->value($_POST['dateCreation'])->required();
  $val->name('longueur')->value($_POST['longueur'])->pattern('int')->required();
  $val->name('largeur')->value($_POST['largeur'])->pattern('int')->required();
  $val->name('tirage')->value($_POST['tirage'])->pattern('int')->required();
  $val->name('couleur')->value($_POST['couleur'])->pattern('alpha')->required();
  
  if(!isset($_REQUEST['idTimbre'])) {
    $val->name('imagePrincipale')->value($_FILES['imagePrincipale']['name'])->pattern('file')->required();
  }

  if($val->isSuccess()){
    unset($_REQUEST['__test']);
    unset($_REQUEST['PHPSESSID']);
    
    $timbre = new Timbre();
    $img = $_FILES['imagePrincipale']['name'];

    if( !isset($_POST['certifie']) ) {
      $_REQUEST['certifie'] = null;

    }
    
    // Ajouter un timbre
    if(!isset($_REQUEST['idTimbre'])) {  
 
      $id = $timbre->insert($_REQUEST);
      
      $query = $timbre->selectId($id);
      $query->imagePrincipale = "imgPrinc-{$id}-{$img}";
      
      $array = json_decode(json_encode($query),true);
      $timbre->update($array); 

      copy($_FILES['imagePrincipale']['tmp_name'], "./assets/img/imgPrinc-{$id}-{$img}");

      $url = 'timbre';

    }else {

      // Modifier un timbre
      if($img != ''){

        // Changer l'image principale si on sélectionne une autre
        $query = $timbre->selectId($_REQUEST['idTimbre']);
        unlink("./assets/img/{$query->imagePrincipale}");
        
        copy($_FILES['imagePrincipale']['tmp_name'], "./assets/img/imgPrinc-{$_REQUEST['idTimbre']}-{$img}");
        $_REQUEST['imagePrincipale'] = "imgPrinc-{$_REQUEST['idTimbre']}-{$img}";
      }
      $id = $timbre->update($_REQUEST);
      $url = 'timbre/modifierTimbre/'.$id;

      if($_SESSION['roleId'] == 1) {
        $url = 'usager/modifierTimbre/'.$id;
      }
    }

    // Images supplémentaires
    if($_FILES['imageSupplementaire']['name'][0] != ''){
      $image = new Image();
      
      foreach($_FILES['imageSupplementaire']['name'] as $key=>$img) {
        $tableau = array();
        $tableau['nomImage'] = "imgsup-{$id}-{$img}";
        $tableau['idTimbre'] = $id;

        $image->insert($tableau);
        copy($_FILES['imageSupplementaire']['tmp_name'][$key], "./assets/img/imgsup-{$id}-{$img}");
      }
    }

    RequirePage::redirect($url);
  }else {
    $erreurs = $val->getErrors();
    $titre = 'Ajouter un timbre';
    $queryImage = null;
    if(isset($_REQUEST['idTimbre'])) {
      $image = new Image();
      $queryImage = $image->selectFk($_REQUEST['idTimbre']);
      $titre = 'Modifier un timbre';
    } 
    
    $timbre = new Timbre();
    $queryTimbre = $timbre->selectTimbreUser($_SESSION['idUsager']);

    $pays = new Pays();
    $queryPays = $pays->select();

    $condition = new Condition();
    $query = $condition->select(); 

    if($_SESSION['roleId'] == 1) {
      $queryTimbre = $timbre->selectId($_REQUEST['idTimbre']);
      return Twig::render('usager/admin.html', ['pageTitre' => "Panneau d'administration" , 'timbre' => $queryTimbre, 'conditions' => $query, 'pays' => $queryPays, 'images'=> $queryImage, 'erreurs' => $erreurs]);
    }else {
      return Twig::render('timbre/gererTimbre.html', ['conditions' => $query, 'pays'=> $queryPays, 'timbres' => $queryTimbre ,'titre' => $titre, 'images' => $queryImage, 'erreurs' => $erreurs, 'post' => $_POST]);
    }
  }

 }

 public function modifierTimbre($id) {

  $timbre = new Timbre();
  $queryTimbreId = $timbre->selectId($id);
  $mise = new Mise();
  
  if(is_null($queryTimbreId->idEnchere)){
    $queryMise[0] = 0;
  }else {
    $queryMise = $mise->count($queryTimbreId->idEnchere);
  }

  
   //Vérifier si l'usager est authentifié et il est le propriétaire du timbre
  if(Session::login() && $_SESSION['idUsager'] == $queryTimbreId->idUsager && $queryMise[0] <= 0) {
    $queryTimbre = $timbre->selectTimbreUser($_SESSION['idUsager']);

    $condition = new Condition();
    $query = $condition->select(); 
    
    $image = new Image();
    $queryImage = $image->selectFk($id);
  
    $pays = new Pays();
    $queryPays = $pays->select();

    return Twig::render('timbre/gererTimbre.html', ['conditions' => $query , 'pays' => $queryPays, 'timbres' => $queryTimbre, 'post' => $queryTimbreId, 'images' => $queryImage, 'pageTitre' => 'Modifier un timbre']);
  }else {
    
    if(!Session::login() || $_SESSION['idUsager'] != $queryTimbreId->idUsager) {
      RequirePage::redirect();
    }
    // Ne pas permettre de modifier les timbres liés à des enchères avec des offres
    if($queryMise[0] > 0) {
      $queryTimbre = $timbre->selectTimbreUser($_SESSION['idUsager']);

      $condition = new Condition();
      $query = $condition->select(); 
      
      $image = new Image();
      $queryImage = $image->selectFk($id);
    
      $pays = new Pays();
      $queryPays = $pays->select();
  
      return Twig::render('timbre/gererTimbre.html', ['conditions' => $query , 'pays' => $queryPays, 'timbres' => $queryTimbre, 'post' => $queryTimbreId, 'images' => $queryImage, 'pageTitre' => 'Modifier un timbre', 'erreurs' => ['Les timbres liés à des enchères avec des offres ne peuvent être modifiés']]);
    }
  }
 }

 public function supprimerImgSupplementaire($id) {
  $image = new Image();
  $query = $image->selectId($id);
  
  $timbre = new Timbre();
  $queryTimbre = $timbre->selectId($query->idTimbre);

  //Vérifier si l'usager est authentifié et il est le propriétaire du timbre
  if(Session::login() && $_SESSION['idUsager'] == $queryTimbre->idUsager || $_SESSION['roleId'] == 1){
    unlink("./assets/img/{$query->nomImage}");
    
    $image->delete($id);
    
    if($_SESSION['roleId'] == 1) {
      RequirePage::redirect("usager/modifierTimbre/{$query->idTimbre}");
    }else {
      RequirePage::redirect("timbre/modifierTimbre/{$query->idTimbre}");
    }
  }else {
    RequirePage::redirect();
  }
 }

 public function supprimerTimbre($id) {

  $timbre = new Timbre();
  $query = $timbre->selectId($id);
  
  //Vérifier si l'usager est authentifié et il est le propriétaire du timbre
  if(Session::login() && $_SESSION['idUsager'] == $query->idUsager || $_SESSION['roleId'] == 1){
    unlink("./assets/img/{$query->imagePrincipale}");

    $image = new Image();
    $queryImage = $image->selectFk($id);

    foreach($queryImage as $image) {
      unlink("./assets/img/{$image->nomImage}");
    }

    $timbre->delete($id);
    
    if($_SESSION['roleId'] == 1) {
      RequirePage::redirect("usager/admin/");
    }else {
      RequirePage::redirect("timbre");
    }
  }else {
    RequirePage::redirect();
  }
 }

}

 ?>
