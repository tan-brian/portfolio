<?php
RequirePage::model('Crud');
RequirePage::model('Enchere');
RequirePage::model('Timbre');
RequirePage::model('Pays');
RequirePage::model('Mise');
RequirePage::model('Condition');
RequirePage::model('Favori');

class CatalogueController{


 public function index(){
  $enchere = new Enchere();
  
  // Tous les enchères qui sont actives
  $query = $enchere->selectEnchere();

  $timbre = new Timbre();

  $mise = new Mise();

  $array = array();

  foreach($query as $enchere){

    $info[$enchere->idEnchere]['nbTimbre'] = $timbre->count($enchere->idEnchere); 
    $info[$enchere->idEnchere]['timbre'] = $timbre->selectFk($enchere->idEnchere, 1, 'idEnchere', 'asc');
    $info[$enchere->idEnchere]['offre'] = $mise->selectFk($enchere->idEnchere, 1, 'offre', 'desc');
    $info[$enchere->idEnchere]['nbOffre'] = $mise->count($enchere->idEnchere);
    array_push($array, $info);
  }

  $pays = new Pays();
  $queryPays = $pays->select();

  $condition = new Condition();
  $queryCondition = $condition->select();


  // Sélectionner les favoris de l'usuager authentifié
  $favori = new Favori();
  
  $queryFavori = null;
  
  if(isset($_SESSION['idUsager'])) {
   $queryFavori = $favori->favoris($_SESSION['idUsager']);
  } 

  $queryFavoriProprietaire = $favori->proprietaireFavoris();

   return Twig::render('catalogue/index.html', ['encheres' => $query, 'info' => $array, 'pageTitre' => 'Catalogue de produits', 'pays' => $queryPays, 'conditions' => $queryCondition, 'favoris' =>$queryFavori, 'favorisProprietaire' => $queryFavoriProprietaire]);

 }

}

 ?>
