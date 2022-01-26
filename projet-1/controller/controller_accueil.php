<?php
RequirePage::model('Crud');
RequirePage::model('Enchere');
RequirePage::model('Timbre');
RequirePage::model('Mise');

class AccueilController{

  //Page accueil
 public function index(){
  
  $enchere = new Enchere();

  $queryFiniBientot = $enchere->selectEnchereFinissantBientot();
  
  $timbre = new Timbre();


  // Ajouts récents
  $queryRecent = $enchere->selectEnchere(5);
  $arrayRecentImages = array();

  foreach($queryRecent as $enchere){

    $queryTimbre = $timbre->selectFk($enchere->idEnchere, 1, 'idEnchere', 'asc');
    
    array_push($arrayRecentImages, $queryTimbre[0]->imagePrincipale);
  }

  // Enchère finissant bientôt
  $mise = new Mise();
    
  $arrayMise = array();
  $arrayFiniBientotImages = array();
  
  foreach($queryFiniBientot as $enchere){

    $queryTimbre = $timbre->selectFk($enchere->idEnchere, 1, 'idEnchere', 'asc');
    array_push($arrayFiniBientotImages, $queryTimbre[0]->imagePrincipale);

    $queryMise = $mise->selectFk($enchere->idEnchere, 1, 'offre', 'desc');
    
    if(empty( $queryMise[0]->offre)) {
      array_push($arrayMise, 0);
    }else {
      array_push($arrayMise, $queryMise[0]->offre);
    }
       
  }

   return Twig::render('accueil/index.html', ['pageTitre' => 'Accueil', 'recents' => $queryRecent, 'recentsImages' => $arrayRecentImages, 'bientotFini' => $queryFiniBientot, 'imageBientotFini' => $arrayFiniBientotImages, 'offres' => $arrayMise]);

 }

}

 ?>
