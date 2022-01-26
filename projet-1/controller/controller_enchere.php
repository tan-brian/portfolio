<?php
RequirePage::model('Crud');
RequirePage::model('Enchere');
RequirePage::model('Timbre');
RequirePage::model('Image');
RequirePage::model('Pays');
RequirePage::model('Mise');
RequirePage::model('Usager');
RequirePage::model('Commentaire');
RequirePage::model('Favori');
RequirePage::librairie('Validation');

class EnchereController{

 public function index(){
   RequirePage::redirect();

 }

 public function enchereId($id) {
  $enchere = new Enchere();
  $query = $enchere->selectId($id);

  if(!empty($query)){

    $timbre = new Timbre();
    $queryTimbres = $timbre->selectFk($id);
   
    $queryEnchereIds= $timbre->selectEnchereUser($queryTimbres[0]->idUsager);

    $commentaire = new Commentaire();
    $nbCommentaireTotal = 0;
    $nbCommentairePositifTotal = 0;
    $nbCommentaire12moisTotal = 0;
    $arrayCommentaire = array();

    // Obtenir les commentaires du vendeur
    foreach ($queryEnchereIds as $enchere) {
 
      $queryCommentaire = $commentaire->selectJoinFk($enchere->idEnchere, 'idUsager', 'date', 'desc', 10);
      
      foreach($queryCommentaire as $comment) {
        array_push($arrayCommentaire, $comment);
      }

      $nbCommentaire = $commentaire->count($enchere->idEnchere);

      $nbCommentaireTotal += $nbCommentaire[0];
      
      $nbCommentairePositif = $commentaire->count($enchere->idEnchere, 'reaction', 'positive');
      $nbCommentairePositifTotal += $nbCommentairePositif[0];
      
      $date = new DateTime(date("Y-m-d H:i:s"));
      $date->modify('-12 months');
      
      $nbCommentaire12mois = $commentaire->count($enchere->idEnchere ,null ,null ,$date->format('Y-m-d') ) ;
      $nbCommentaire12moisTotal += $nbCommentaire12mois[0];
    }

    $keys = array_column($arrayCommentaire, 'date');
    array_multisort($keys, SORT_DESC, $arrayCommentaire);
    array_slice($arrayCommentaire, 0, 10);

    $usager = new Usager();
    $vendeur = $usager->selectId($queryTimbres[0]->idUsager);

    $image = new Image();

    $pays = new Pays();
    $queryPays = $pays->select();

    $array = array();

    // obtenir les images de l'enchère
    foreach( $queryTimbres as $timbre){

      array_push($array, $timbre->imagePrincipale);

      $queryImages = $image->selectFk($timbre->idTimbre);
      
      for ($i=0; $i < count($queryImages) ; $i++) { 
        array_push($array, $queryImages[$i]->nomImage);
      }
    }

    // Obtenir les offres de l'enchère
    $mise = new Mise();
    $queryMise = $mise->selectFk($id, 6, 'offre', 'desc');
   
    $queryFavori = null;
    
    if(isset($_SESSION['idUsager'])){
      $favori = new Favori();
      $queryFavori = $favori->favorisId($query->idEnchere, $_SESSION['idUsager']);
    }
    
    return Twig::render('enchere/enchereId.html', ['enchere' => $query , 'mises' => $queryMise, 'pays' => $queryPays, 'timbre' => $queryTimbres[0], 'images' => $array , 'pageTitre' => $query->nomEnchere, 'vendeur' => $vendeur, 'commentaires' =>$arrayCommentaire, 'nbCommentaire' => $nbCommentaireTotal, 'nbCommentairePositif' => $nbCommentairePositifTotal, 'nbCommentaire12mois' => $nbCommentaire12moisTotal, 'favori' => $queryFavori]);
  }else {
    RequirePage::redirect();
  }
 }

 public function miser() {
  unset($_REQUEST['__test']);
  unset($_REQUEST['PHPSESSID']);
  $mise = new Mise(); 

  // Mise rapide
  if(isset($_REQUEST['offre'])){

    $mise->insert($_REQUEST);
    RequirePage::redirect('enchere/enchereId/'. $_REQUEST['idEnchere']);
  }else {

    $enchere = new Enchere();
    $query = $enchere->selectId($_REQUEST['idEnchere']);

    $queryMise = $mise->selectFk($_REQUEST['idEnchere'], 6, 'idMise', 'desc');

    $val = new Validation;
    $valid = false;

    // Valider les inputs et voir si l'offre est supérieur d'au moins 5$ à l'offre courant
    if(isset($_REQUEST['mise-direct'])) {
      $val->name('mise-direct')->value($_POST['mise-direct'])->pattern('int')->required();

       if(is_numeric($_REQUEST['mise-direct']) && $_REQUEST['mise-direct'] >= $_REQUEST['mise-minimale']){
        $valid = true;
      }
    }else {
      $val->name('mise-auto')->value($_POST['mise-auto'])->pattern('int')->required();

      if(is_numeric($_REQUEST['mise-auto']) && $_REQUEST['mise-auto'] >= $_REQUEST['mise-minimale']){
        $valid = true;
      }
    }
    if($val->isSuccess() && $valid){
    
      if(isset($_REQUEST['mise-direct'])){
        $_REQUEST['offre'] = $_REQUEST['mise-direct'];
        unset($_REQUEST['mise-direct']);
      }else {
        $_REQUEST['offre'] = $_REQUEST['mise-auto'];
        unset($_REQUEST['mise-auto']);
      }
      
      unset($_REQUEST['mise-minimale']);

      $mise->insert($_REQUEST);
      RequirePage::redirect('enchere/enchereId/'. $_REQUEST['idEnchere']);
    }else {
      $erreurs = $val->getErrors();

      if(empty($erreurs)){
        $erreurs = array();

        if(!$valid){
          array_push($erreurs, "L'offre doit être d'au moins ". $_REQUEST['mise-minimale'] . " $" );
        }
      }  
     
      $timbre = new Timbre();
      $queryTimbres = $timbre->selectFk($_REQUEST['idEnchere']);

      $queryEnchereIds= $timbre->selectEnchereUser($queryTimbres[0]->idUsager);

      $commentaire = new Commentaire();
      $nbCommentaireTotal = 0;
      $nbCommentairePositifTotal = 0;
      $nbCommentaire12moisTotal = 0;
      $arrayCommentaire = array();
      
      foreach ($queryEnchereIds as $enchere) {
   
        $queryCommentaire = $commentaire->selectJoinFk($enchere->idEnchere, 'idUsager', 'date', 'desc', 10);
        
        foreach($queryCommentaire as $comment) {
          array_push($arrayCommentaire, $comment);
        }
  
        $nbCommentaire = $commentaire->count($enchere->idEnchere);
  
        $nbCommentaireTotal += $nbCommentaire[0];
        
        $nbCommentairePositif = $commentaire->count($enchere->idEnchere, 'reaction', 'positive');
        $nbCommentairePositifTotal += $nbCommentairePositif[0];
        
        $date = new DateTime(date("Y-m-d H:i:s"));
        $date->modify('-12 months');
        
        $nbCommentaire12mois = $commentaire->count($enchere->idEnchere ,null ,null ,$date->format('Y-m-d') ) ;
        $nbCommentaire12moisTotal += $nbCommentaire12mois[0];
      }
  
      $keys = array_column($arrayCommentaire, 'date');
      array_multisort($keys, SORT_DESC, $arrayCommentaire);
      array_slice($arrayCommentaire, 0, 10);

      $usager = new Usager();
      $vendeur = $usager->selectId($queryTimbres[0]->idUsager);

      $image = new Image();

      $pays = new Pays();
      $queryPays = $pays->select();

      $array = array();

      foreach( $queryTimbres as $timbre){

        array_push($array, $timbre->imagePrincipale);

        $queryImages = $image->selectFk($timbre->idTimbre);
        
        for ($i=0; $i < count($queryImages) ; $i++) { 
          array_push($array, $queryImages[$i]->nomImage);
        }
      }
      
      return Twig::render('enchere/enchereId.html', ['enchere' => $query , 'mises' => $queryMise, 'pays' => $queryPays, 'timbre' => $queryTimbres[0], 'images' => $array , 'pageTitre' => $query->nomEnchere, 'vendeur' => $vendeur, 'erreurs' => $erreurs, 'post' => $_POST, 'commentaires' =>$arrayCommentaire, 'nbCommentaire' => $nbCommentaireTotal, 'nbCommentairePositif' => $nbCommentairePositifTotal, 'nbCommentaire12mois' => $nbCommentaire12moisTotal]);
    }
  }
 }

 // Ajouter un commentaire
 public function commenter() {
   
    unset($_REQUEST['__test']);
    unset($_REQUEST['PHPSESSID']);
  
    $commentaire = new Commentaire();

    $commentaire->insert($_REQUEST);

    RequirePage::redirect('enchere/enchereId/'. $_REQUEST['idEnchere']);
 }

 // Supprimer un commentaire
 public function supprimerCommentaire($id) {
  $commentaire = new Commentaire();

  $query = $commentaire->selectId($id);
  $commentaire->delete($id);

  RequirePage::redirect('enchere/enchereId/'. $query->idEnchere);
 }
 
 // Modifier un commentaire
 public function modifierCommentaire() {

  unset($_REQUEST['__test']);
  unset($_REQUEST['PHPSESSID']);
  
  $commentaire = new Commentaire();
  
  $commentaire->update($_REQUEST);

  RequirePage::redirect('enchere/enchereId/'. $_REQUEST['idEnchere']);
 }


  public function ajouterEnchere(){
 
   //Vérifier si l'usager est authentifié 
   if(Session::login()) {
     $enchere = new Enchere();
 
     $array = array();
     
     $timbre = new Timbre();
 
     // Obtenir les enchères du l'usager authentifié
     $query = $timbre->selectEnchereUser($_SESSION['idUsager']);
 
     foreach( $query as $timbreQuery) {
 
       array_push($array, $enchere->selectId($timbreQuery->idEnchere));
     }
     // Obtenir les timbres du l'usager authentifié qui ne sont pas dans des enchères
     $queryTimbre = $timbre->selectTimbre($_SESSION['idUsager']);
 
     return Twig::render('enchere/gererEnchere.html', ['encheres' => $array, 'timbres' => $queryTimbre, 'titre' => 'Ajouter une enchère',  'pageTitre' => 'Ajouter une enchère']);
   }else {
     RequirePage::redirect();
   }
  }
 
  public function gererEnchere(){
 
   // Valider le formulaire d'ajout / modification d'enchère
   $val = new Validation;
 
   $val->name('nomEnchere')->value($_POST['nomEnchere'])->required();
   $val->name('dateDebut')->value($_POST['dateDebut'])->required();
   $val->name('dateFin')->value($_POST['dateFin'])->required();
   $val->name('prixPlancher')->value($_POST['prixPlancher'])->pattern('int');
   $val->name('description')->value($_POST['description'])->required();
   
   if($val->isSuccess() && isset($_REQUEST['timbres'])){
     unset($_REQUEST['__test']);
     unset($_REQUEST['PHPSESSID']);
     $timbres = $_REQUEST['timbres'];
 
     unset($_REQUEST['timbres']);
     
     $timbre = new Timbre();
 
     $enchere = new Enchere();
 
     // Ajouter une enchère
     if(!isset($_REQUEST['idEnchere'])) {
       $idEnchere = $enchere->insert($_REQUEST);
       $url = 'enchere/enchereId/'.$idEnchere;
     }else {
 
       // Modifier une enchère
       $queryTimbres = $timbre->selectFk($_REQUEST['idEnchere']);
       
       foreach($queryTimbres as $query){   
        $query->idEnchere = null;
        $array = json_decode(json_encode($query),true);
        $timbre->update($array);
       }
 
       $idEnchere = $enchere->update($_REQUEST);
      
       $url = 'enchere/modifierEnchere/'.$idEnchere;

       // Si l'usager est l'administrateur, se diriger au panneau d'aministration après la modification
       if($_SESSION['roleId'] == 1){
        $url = 'usager/modifierEnchere/'.$idEnchere;
      }
     }
     
     // Lier chaque timbre sélectionné à l'enchère par son id
     
     foreach($timbres as $idTimbre){
       $query = $timbre->selectId($idTimbre);
       $query->idEnchere = $idEnchere;
       $array = json_decode(json_encode($query),true);
       $timbre->update($array);
 
     }
 
     RequirePage::redirect($url);
   }else {
     
     $erreurs = $val->getErrors(); 
     
     if(empty($erreurs)){
       $erreurs = array();
     }
     
     $queryMesTimbre = null;
     $titre = 'Ajouter une enchère'; 
     $array = array();
     $enchere = new Enchere();
 
     $timbre = new Timbre();
 
     // Obtenir les enchères du l'usager authentifié
     $query = $timbre->selectEnchereUser($_SESSION['idUsager']);
  
     foreach( $query as $timbreQuery) {
   
       array_push($array, $enchere->selectId($timbreQuery->idEnchere));
     }
  
     
     if(!isset($_REQUEST['timbres'])){
       array_push($erreurs, 'Veuillez sélectionner un timbre');
 
     }
     
     if(isset($_REQUEST['idEnchere'])) {
       $queryMesTimbre = $timbre->selectFk($_REQUEST['idEnchere']);
       $titre = 'Modifier une enchère';
     }
 
     $queryTimbre = $timbre->selectTimbre($_SESSION['idUsager']);

     $mise = new Mise();
      $queryMise = $mise->count($_REQUEST['idEnchere']);

     if($_SESSION['roleId'] == 1) {
       $queryEnchere = $enchere->selectId($_REQUEST['idEnchere']);
      return Twig::render('usager/admin.html', ['pageTitre' => "Panneau d'administration" , 'enchere' => $queryEnchere, 'timbresSansEnchere' => $queryTimbre, 'timbresAvecEnchere' => $queryMesTimbre, 'mise'=> $queryMise, 'erreurs' => $erreurs]);
     }else {
     return Twig::render('enchere/gererEnchere.html', [ 'mesTimbres' => $queryMesTimbre, 'encheres' => $array, 'timbres' => $queryTimbre, 'erreurs' => $erreurs, 'post' => $_POST, 'pageTitre' => $titre]);
     }
   }
 
  }
 
  public function modifierEnchere($id) {
 
   $timbre = new Timbre();
   $query = $timbre->selectFk($id);
 
   $mise = new Mise();
   $queryMise = $mise->count($id);
 
   //Vérifier si l'usager est authentifié et il est le propriétaire du timbre
   if(Session::login() && $_SESSION['idUsager'] == $query[0]->idUsager && $queryMise[0] <= 0 ) {
 
     $enchere = new Enchere();
     $queryEnchereId = $enchere->selectId($id);
 
     $array = array();
     
     // Obtenir les enchères du l'usager authentifié
     $query = $timbre->selectEnchereUser($_SESSION['idUsager']);
 
     foreach( $query as $timbreQuery) {
   
       array_push($array, $enchere->selectId($timbreQuery->idEnchere));
     }
 
     $queryTimbre = $timbre->selectTimbre($_SESSION['idUsager']);
     
     $queryMesTimbres = $timbre->selectFk($id);
     
     return Twig::render('enchere/gererEnchere.html', [ 'encheres' => $array, 'post' => $queryEnchereId, 'mesTimbres' => $queryMesTimbres , 'timbres' => $queryTimbre, 'pageTitre' => 'Modifier un enchère']);
   }else {
 
     if(!Session::login() || $_SESSION['idUsager'] != $query[0]->idUsager) {
       RequirePage::redirect();
     }else if($queryMise[0] > 0) {
 
       $enchere = new Enchere();
       $queryEnchereId = $enchere->selectId($id);
   
       $array = array();
       
       // Obtenir les enchères du l'usager authentifié
       $query = $timbre->selectEnchereUser($_SESSION['idUsager']);
   
       foreach( $query as $timbreQuery) {
     
         array_push($array, $enchere->selectId($timbreQuery->idEnchere));
       }
   
       $queryTimbre = $timbre->selectTimbre($_SESSION['idUsager']);
       
       $queryMesTimbres = $timbre->selectFk($id);
       
       return Twig::render('enchere/gererEnchere.html', [ 'encheres' => $array, 'post' => $queryEnchereId, 'mesTimbres' => $queryMesTimbres , 'timbres' => $queryTimbre, 'pageTitre' => 'Modifier un enchère', 'erreurs' => ['Les enchères qui ont des offres ne peuvent être modifiés']] );
     }
    
     
   }
  }
 
  public function supprimerEnchere($id) {
 
   $timbre = new Timbre();
   $query = $timbre->selectFk($id);
 
   //Vérifier si l'usager est authentifié et il est le propriétaire de l'enchère
   if(Session::login() && $_SESSION['idUsager'] == $query[0]->idUsager || $_SESSION['roleId'] == 1){
 
     foreach($query as $timbreQuery) {
       $timbreQuery->idEnchere = null;
       $array = json_decode(json_encode($timbreQuery),true);
       $timbre->update($array);
     }
 
     $enchere = new Enchere();
 
     $enchere->delete($id);
     
     if($_SESSION['roleId'] == 1) {
      RequirePage::redirect("usager/admin/");
     }else {
      RequirePage::redirect("enchere/ajouterEnchere/");
     }
   }else {
     RequirePage::redirect();
   }
  }
}

 ?>
