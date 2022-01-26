<?php

class Favori extends Crud{

  public $table='favori';

  // Supprimer un favori
  public function deleteFavori($idEnchere, $idUsager) {
    $sql="Delete from $this->table WHERE idEnchere=$idEnchere AND idUsager=$idUsager";

    if(!$this->query($sql)){
      print_r($this);
    }
  }

  // Obtenir tous les favoris d'un usager
  public function favoris($id) {
   
    $sql = "SELECT * FROM $this->table WHERE idUsager= $id";
    
    $stmt = $this->query($sql);
    return $stmt->fetchAll(PDO::FETCH_CLASS);
  }

  // Obtenir tous le favori d'une enchère spécifique d'un usager
  public function favorisId($idEnchere , $idUsager) {
   
    $sql = "SELECT * FROM $this->table WHERE idUsager= $idUsager AND idEnchere = $idEnchere";
    
    $stmt = $this->query($sql);
    return $stmt->fetchObject();
  }

  // Obtenir tous ls favoris de l'administrateur
  public function proprietaireFavoris() {
     
    $sql = "SELECT * FROM $this->table join usager on favori.idUsager = usager.idUsager where idRole = 1";
    
    $stmt = $this->query($sql);
    return $stmt->fetchAll(PDO::FETCH_CLASS);
  }
}

 ?>
