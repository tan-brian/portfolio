<?php

class Enchere extends Crud{

  public $table='enchere';
  public $primaryKey='idEnchere';

  // Obtenir les enchères actives
  public function selectEnchere($limit = null){
    if($limit != null) {
      $sql = "SELECT * FROM `$this->table` WHERE dateFin > CURRENT_DATE AND dateDebut <= CURRENT_DATE ORDER BY dateDebut DESC LIMIT $limit";
    }else {
      $sql = "SELECT * FROM `$this->table` WHERE dateFin > CURRENT_DATE AND dateDebut <= CURRENT_DATE ORDER BY idEnchere DESC";
    }
    $stmt = $this->query($sql);

    return $stmt->fetchAll(PDO::FETCH_CLASS);

  }
  
  // Obtenir 5 enchères qui finissent bientôt
  public function selectEnchereFinissantBientot() {

    $sql = "SELECT *, TIMESTAMPDIFF(hour, now(), dateFin) AS heure FROM `$this->table` HAVING heure < 30 AND heure > 0 LIMIT 5";
    $stmt = $this->query($sql);

    return $stmt->fetchAll(PDO::FETCH_CLASS);
  }
}


 ?>
