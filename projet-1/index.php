<?php

session_start();
require_once __DIR__.'/librairie/RequirePage.php';
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/librairie/Twig.php';
require_once __DIR__.'/librairie/Session.php';

//recuperer le chemin (URL) et metre dans un tableau
 $url = isset($_SERVER['PATH_INFO']) ? explode ('/',ltrim($_SERVER['PATH_INFO'], '/')) : '/' ;

if($url=="/"){
//demande le controleur Index();
require_once __DIR__.'/controller/controller_accueil.php';
$controller = new AccueilController();
$controller->index();
}else{
  $requestUrl=$url[0];
  //recuperer le controleur
  $controllerPath = __DIR__.'/controller/controller_'.$requestUrl.'.php';

   if(file_exists($controllerPath)){
     require_once $controllerPath;

    $controllerName = ucfirst($requestUrl).'Controller';
  
    $controller = new $controllerName;
    
    //method + post
    if(isset($url[1])){
      $method = $url[1];
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $controller->$method();
          }
    //method + get
          if(isset($url[2])){
            $id =  $url[2];
            
            $controller->$method($id);
          }
    }else{
      $controller->index();
    }

   }else{
     RequirePage::redirect();
   }

}

 ?>
