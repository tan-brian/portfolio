<?php
RequirePage::librairie('Password');

class Usager extends Crud{

  public $table='usager';
  public $primaryKey='idUsager';

  public function checkUser($username, $password){

    $sql = "SELECT * FROM $this->table WHERE nomUsager = ?";
    $stmt = $this->prepare($sql);
    $stmt->execute(array($username));
    $count=$stmt->rowCount();

    if($count==1){
      $user = $stmt->fetch();
      $dbPassword = $user['motDePasse'];

        if(PasswordHash::checkPassword($password,$dbPassword)){
          return $user;
        }else{
          return false;
        }
    }else{
      return false;
    }
  }

  // Vérifier si le nom usager existe déjà
  public function selectUser($username){
    
    $sql = "SELECT * FROM $this->table WHERE nomUsager = ?";
    $stmt = $this->prepare($sql);
    $stmt->execute(array($username));
    return $stmt->rowCount();

  }

  
}


 ?>
