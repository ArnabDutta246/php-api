<?php
class Database{
  private $host = 'localhost';
  private $db_name ='api_db';
  private $username = "root";
  private $pass = '';
  public $conn;

  public function getConnection(){

    $this->conn = null;
    try{
      $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->pass);
    }catch(PDOException $exception){
       echo "Connection error:".$exception->getMessag();
    }
    return $this->conn;
  }
}
?>