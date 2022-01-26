<?php
class RequirePage {

    public static $absolute_path = '/projet-1/';
  
    static function model($page){
      return require_once 'model/'.$page.'.php';
    }

    static public function redirect($url = null)
    {
      header("Location:". RequirePage::$absolute_path.$url);
      exit;
    }

    static function controller($page){
      return require_once 'controller/'.$page.'.php';
    }

    static function librairie($page){
      return require_once 'librairie/'.$page.'.php';
    }
    
}






 ?>
