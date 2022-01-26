<?php

RequirePage::model('Crud');
RequirePage::model('Usager');
RequirePage::model('Role');
RequirePage::model('Pays');
RequirePage::model('Enchere');
RequirePage::model('Timbre');
RequirePage::model('Mise');
RequirePage::model('Condition');
RequirePage::model('Image');
RequirePage::librairie('Validation');

class UsagerController{

// Formulaire de login si l'usager n'est pas login
 public function index(){
    if(Session::login()){
       RequirePage::redirect();
    }
    return Twig::render('usager/login.html', ['pageTitre' => 'Authentification']);
   
 }

 // Vérifier si le mot de passe et le nom de l'usager sont corrects
 public function login(){

   if(isset($_REQUEST['nomUsager']) && isset($_REQUEST['motDePasse'])){
      $usager = new Usager();
      
      if($resultat = $usager->checkUser($_REQUEST['nomUsager'], $_REQUEST['motDePasse'])){
       

         session_regenerate_id();
         $_SESSION['username'] = $resultat['nomUsager'];
         $_SESSION['idUsager'] = $resultat['idUsager'];
         $_SESSION['roleId'] = $resultat['idRole'];
         $_SESSION['idPays'] = $resultat['idPays'];
         $_SESSION['fingerprint'] = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);

         RequirePage::redirect();
    
       }else{

         return Twig::render('usager/login.html', [ 'erreur' => 'Nom usager ou mot de passe incorrect.', 'post' => $_POST, 'pageTitre' => 'Authentification']);
       }

    }

 }

 public function logout() {
   session_destroy();
   RequirePage::redirect();
 }

 public function formInscription(){
   if(Session::login()){
      RequirePage::redirect();
   }

   $pays = new Pays();
   $query = $pays->select();
   return Twig::render('usager/inscription.html', ['pays' => $query, 'pageTitre' => 'Inscription']);
    
   
 }

 // Insertion de l'usager dans la table usager de la BD
 public function inscription(){
    
  $val = new Validation;

  $val->name('prenom')->value($_POST['prenom'])->pattern('alpha')->required();
  $val->name('nom')->value($_POST['nom'])->pattern('alpha')->required();
  $val->name('nomUsager')->value($_POST['nomUsager'])->pattern('alphanum')->required();
  $val->name('motDePasse')->value($_POST['motDePasse'])->required();

  $usager = new Usager();

  // Vérifier si le nom usager est unique
  $nomUnique = $usager->selectUser($_REQUEST['nomUsager']);

  if($val->isSuccess() && $nomUnique == 0){
      unset($_REQUEST['__test']);
      unset($_REQUEST['PHPSESSID']);
      
      $hashPassword = PasswordHash::hashPassword($_REQUEST['motDePasse']);
      $_REQUEST['motDePasse'] = $hashPassword;
    
      $usager->insert($_REQUEST);
    
      RequirePage::redirect('usager');
    
  }else{
    $erreurs = $val->getErrors();
    
    if($nomUnique != 0) array_push( $erreurs, "Le nom usager existe déjà");
    
    $pays = new Pays();
    $query = $pays->select();

    return Twig::render('usager/inscription.html', ['pays' => $query, 'erreurs' => $erreurs, 'post' => $_POST, 'pageTitre' => 'Inscription']);

  }

 }

 // Profil de l'usager
 public function profil(){
  
   if(Session::login()) {
     $usager = new Usager();
     $query = $usager->selectId($_SESSION['idUsager']);
 
     $pays = new Pays();
     $queryPays = $pays->select();
 
     return Twig::render('usager/profil.html', ['pageTitre' => 'Mon profil', 'pays' => $queryPays, 'usager' =>$query]);
   }else {
     RequirePage::redirect();
   }
  }
 
   public function modifierProfil() {
 
     // Valider le formulaire pour modifier le profil de l'usager
     $val = new Validation;
 
     $val->name('prenom')->value($_POST['prenom'])->pattern('alpha')->required();
     $val->name('nom')->value($_POST['nom'])->pattern('alpha')->required();
   
     if($val->isSuccess() && strcmp($_POST['motDePasse'], $_POST['motDePasse2']) == 0 ) {
       unset($_REQUEST['__test']);
       unset($_REQUEST['PHPSESSID']);
       
       unset($_REQUEST['motDePasse2']);
       
       if(empty($_POST['motDePasse'])){
         unset($_REQUEST['motDePasse']);
       }else{
         $hashPassword = PasswordHash::hashPassword($_REQUEST['motDePasse']); 
         $_REQUEST['motDePasse'] = $hashPassword;
       }
 
       $usager = new Usager();
       $query = $usager->selectId($_REQUEST['idUsager']);
       
       $usager->update($_REQUEST);
       if($_SESSION['roleId'] == 1) {
        RequirePage::redirect('usager/modifierUsager/'.$_REQUEST['idUsager']);
       }else {
        RequirePage::redirect('usager/profil/');
       }
      
     }else {
       $erreurs = $val->getErrors();
 
       if(empty($erreurs)) {
         $erreurs = array();
       }
 
       if(strcmp($_POST['motDePasse'], $_POST['motDePasse2']) !== 0) {
         array_push($erreurs, 'Les mots de passe ne correspondent pas');
       }
       $usager = new Usager();
       $query = $usager->selectId($_REQUEST['idUsager']);
 
       $pays = new Pays();
       $queryPays = $pays->select();
       if($_SESSION['roleId'] == 1) {
        return Twig::render('usager/admin.html', ['pageTitre' => 'Profil de '. $query->nomUsager, 'pays' => $queryPays, 'usager' =>$query, 'erreurs' => $erreurs]);
       }else {
        return Twig::render('usager/profil.html', ['pageTitre' => 'Mon profil', 'pays' => $queryPays, 'usager' =>$query, 'erreurs' => $erreurs]);
       }
     }
   }
 
   public function supprimerCompte($id) {
     
     //Vérifier si l'usager est authentifié
     if(Session::login()){
       
       $usager = new Usager();
 
       $usager->delete($id);
       // Si l'usager est l'administrateur, retourner au panneau d'administration. Sinon, fermer la session après la suppression du compte
       if($_SESSION['roleId'] == 1) {
        RequirePage::redirect('usager/admin/');
       }else {
       RequirePage::redirect('usager/logout/');
       }
     }else {

      RequirePage::redirect();
     }
    }
 
  // Panneau d'administration
  public function admin() {

     if(Session::login() && $_SESSION['roleId'] == 1){
      return Twig::render('usager/admin.html', ['pageTitre' => "Panneau d'administration"]);
    
    }else {

     RequirePage::redirect();
    }
   }

   // Rechercher des usager, timbres et enchères pour l'administrateur
   public function rechercher() {
 
    switch($_REQUEST['adminSelect']) {
      case 'usager' : 
        $usager = new Usager();
        if(empty($_REQUEST['adminRecherche'])) {
        
          $query = $usager->search(null, 2);
        }
        else {
          $query = $usager->search($_REQUEST['adminRecherche']);
        }
        return Twig::render('usager/admin.html', ['pageTitre' => "Panneau d'administration" , 'usagers' => $query, 'recherche' => $_POST]);

        case 'enchere' : 
          $enchere = new Enchere();
          if(empty($_REQUEST['adminRecherche'])) {
          
            $query = $enchere->select();
          }
          else {
            $query = $enchere->search($_REQUEST['adminRecherche']);
          }
          return Twig::render('usager/admin.html', ['pageTitre' => "Panneau d'administration" , 'encheres' => $query, 'recherche' => $_POST]);

        case 'timbre' : 
          $timbre = new Timbre();
          if(empty($_REQUEST['adminRecherche'])) {
          
            $query = $timbre->select();
          }
          else {
            $query = $timbre->search($_REQUEST['adminRecherche']);
          }
          return Twig::render('usager/admin.html', ['pageTitre' => "Panneau d'administration" , 'timbres' => $query, 'recherche' => $_POST]);
        
     }
  }

  // Modification d'un usager pour l'administateur
  public function modifierUsager($id) {
    if(Session::login() && $_SESSION['roleId'] == 1){
      $usager = new Usager();
      $query = $usager->selectId($id);

      $pays = new Pays();
      $queryPays = $pays->select();
      return Twig::render('usager/admin.html', ['pageTitre' => "Panneau d'administration" , 'usager' => $query, 'pays' =>$queryPays]);
    }else {

      RequirePage::redirect();
     }
  }

  // Modification d'une enchère pour l'administateur
  public function modifierEnchere($id) {
    if(Session::login() && $_SESSION['roleId'] == 1){
      $enchere = new Enchere();
      $queryEnchere = $enchere->selectId($id);

      $timbre = new Timbre();
      $queryTimbre = $timbre->selectFk($id);
      
      $queryTimbreSansEnchere = $timbre->selectTimbre($queryTimbre[0]->idUsager);

      $mise = new Mise();
      $queryMise = $mise->count($id);
      
      return Twig::render('usager/admin.html', ['pageTitre' => "Panneau d'administration" , 'enchere' => $queryEnchere, 'timbresSansEnchere' => $queryTimbreSansEnchere, 'timbresAvecEnchere' => $queryTimbre, 'mise'=> $queryMise]);
    }else {

      RequirePage::redirect();
     }
  }

  // Modification d'un timbre pour l'administateur
  public function modifierTimbre($id) {
    if(Session::login() && $_SESSION['roleId'] == 1){
      $timbre = new Timbre();
      $query = $timbre->selectId($id);
      $condition = new Condition();
      $queryCondition = $condition->select(); 
      
      $image = new Image();
      $queryImage = $image->selectFk($id);
    
      $pays = new Pays();
      $queryPays = $pays->select();
      
      $mise = new Mise();
      $queryMise = $mise->count($query->idEnchere);

      return Twig::render('usager/admin.html', ['pageTitre' => "Panneau d'administration" , 'timbre' => $query, 'conditions' => $queryCondition, 'pays' => $queryPays, 'images'=> $queryImage, 'mise' => $queryMise]);
    }else {

      RequirePage::redirect();
     }
  }
}


 ?>
