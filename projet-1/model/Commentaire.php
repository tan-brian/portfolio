<?php

class Commentaire extends Crud{

  public $table='commentaire';
  public $primaryKey='idCommentaire';
  public $foreignKey='idEnchere';
  public $tableJoin = 'usager';
}


 ?>
