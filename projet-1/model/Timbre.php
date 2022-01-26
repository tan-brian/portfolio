<?php

class Timbre extends Crud{

  public $table='timbre';
  public $primaryKey='idTimbre';
  public$foreignKey= 'idEnchere';

  // Obtenir tous les enchères d'un usager
  public function selectEnchereUser($id){
    $sql = "SELECT DISTINCT idEnchere FROM $this->table WHERE idUsager = $id AND idEnchere IS NOT NULL";
    $stmt = $this->query($sql);
    return $stmt->fetchAll(PDO::FETCH_CLASS);

  }

  // Obtenir tous les timbres d'un usager
  public function selectTimbreUser($userId){
    $sql = "SELECT * FROM $this->table WHERE idUsager = $userId";
    $stmt = $this->query($sql);
    return $stmt->fetchAll(PDO::FETCH_CLASS);

  }

// Obtenir les timbres liés à une enchère
  public function selectTimbre($id){
    $sql = "SELECT * FROM $this->table WHERE $this->foreignKey IS NULL AND idUsager = $id";
    $stmt = $this->query($sql);
    return $stmt->fetchAll(PDO::FETCH_CLASS);

  }

}


 ?>
