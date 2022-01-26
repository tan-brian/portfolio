<?php

class Crud extends PDO{

    protected $table;
    protected $primaryKey;
  
    public function __construct(){
      parent::__construct("mysql:host=localhost;dbname=projet-1", "root", "");
    }
  
    public function select($fieldOrder = null, $order = null){
      if($fieldOrder == null){
        $sql = "SELECT * FROM `$this->table`";
      }else{
        $sql = "SELECT * FROM `$this->table` ORDER BY $fieldOrder $order";
      }

     $stmt = $this->query($sql);

    return $stmt->fetchAll(PDO::FETCH_CLASS);
  
    }


    public function search($keyword = null, $idRole = null) {

      if($idRole != null) {
        $sql = "SELECT * FROM `$this->table` where idRole = $idRole";
      }else if($keyword == null) {
        $sql = "SELECT * FROM `$this->table`";    
      }
      else {
        $sql = "SELECT * FROM `$this->table` where nom".ucfirst($this->table)." LIKE '%$keyword%'";
      }
      $stmt = $this->query($sql);

      return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
    
    public function count($id ,$fieldname = null, $field = null, $date = null) {

      if($date != null) {
        $sql = "SELECT count(*) FROM $this->table WHERE $this->foreignKey = $id and date > $date";

      }else if($field == null) {
        $sql = "SELECT count(*) FROM $this->table WHERE $this->foreignKey = $id";
      }else {
        $sql = "SELECT count(*) FROM $this->table WHERE $this->foreignKey = $id and $fieldname = '$field'";
      } 
      
      if($id != null){
        $stmt = $this->query($sql);
        return $stmt->fetch();
      }else {
        return 0;
      }
    }
  
    public function selectId($id){
      $sql = "SELECT * FROM $this->table WHERE $this->primaryKey = $id";
      $stmt = $this->query($sql);
      return $stmt->fetchObject();
  
    }



    public function selectFk($id, $limit = null, $fieldOrder = null, $order = null ){
      if($limit != null) {
        $sql = "SELECT * FROM $this->table WHERE $this->foreignKey = $id  ORDER BY $fieldOrder $order LIMIT $limit";
      }else{
        $sql = "SELECT * FROM $this->table WHERE $this->foreignKey = $id";
      }
      $stmt = $this->query($sql);
      return $stmt->fetchAll(PDO::FETCH_CLASS);
  
    }

    public function selectJoinFk($id, $fieldJoin, $fieldOrder = null, $order = null, $limit = null){
      if($limit == null){
        $sql = "SELECT * FROM $this->table JOIN $this->tableJoin ON $this->table.$fieldJoin = $this->tableJoin.$fieldJoin WHERE $this->foreignKey = $id";
      }else{
        $sql = "SELECT * FROM $this->table JOIN $this->tableJoin ON $this->table.$fieldJoin = $this->tableJoin.$fieldJoin WHERE $this->foreignKey = $id ORDER BY $fieldOrder $order LIMIT $limit";
      }
      $stmt = $this->query($sql);
      return $stmt->fetchAll(PDO::FETCH_CLASS);
    }


    public function insert($data){
      $fieldName = implode(", ",array_keys($data));
      $fieldValues = ':'.implode(", :", array_keys($data));
  
      $sql = "INSERT INTO $this->table ($fieldName) VALUES ($fieldValues)";
      $stmt = $this->prepare($sql);
  
      foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
      }
  
      if(!$stmt->execute()){
        return $stmt->errorInfo();
      }
      return $this->lastInsertId();
    }

    public function update($data){

      $fieldDetails=null;
      foreach($data as $key=>$value){
        $fieldDetails .="$key=:$key,";
      }
      $fieldDetails = rtrim($fieldDetails, ',');
  
      $stmt = $this->prepare("UPDATE $this->table SET $fieldDetails WHERE $this->primaryKey={$data[$this->primaryKey]}");

      foreach($data as $key=>$value){
        $stmt->bindValue(":$key", $value);
      }
  
      if(!$stmt->execute()){
        print_r($stmt->errorInfo());
      }else{
        return $data[$this->primaryKey];
      }
  
    }
  
    public function delete($id){
  
      $sql="Delete from $this->table WHERE $this->primaryKey=$id";
  
      if(!$this->query($sql)){
        print_r($this);
      }
  
    }


  }
  

?>