<?php
class Database{
  private $host,
          $username,
          $password,
          $db,
          $conn;

  public function __construct($connArr){
    $this->host = $connArr[0];
    $this->username = $connArr[1];
    $this->password = $connArr[2];
    $this->db = $connArr[3];
    try {
      $this->__connect_db();
    } catch (Exception $e) {
      echo "Exception: " . $e->getMessage();
    }

  }

  private function __connect_db(){
    $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db);
    if($this->conn->connect_error){
      throw new Exception("Connection Failed: ".$this->conn->connect_error);
    }
  }

  public function select_all_search($table, $field="", $value=""){
    $stmt = $this->conn->stmt_init();
    $sql = $this->__selectFieldTable($table);
    if(! (empty($field) AND empty($value)) ){
      $sql .= $this->__whereFieldValue($field);
      if(!$stmt->prepare($sql)) {
        echo "error: " . $sql;
      }
      else{
        $stmt->bind_param("s", $value);
      }
    }elseif(!$stmt->prepare($sql)){
      echo "prepare error " . $sql;
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();

  }

  private function __whereFieldValue($field){
      $where = " WHERE ";
      $leftIn = " IN ( ";
      $rightIn = " )";
      $param = "?";
      return $where . $this->__escape($field) . $leftIn . $param . $rightIn;
  }

  private function __selectFieldTable($table, $field=""){
    $select = "SELECT ";
    $from = " FROM ";
    if (!empty($field)) {
      return $select . $field . $from . $this->__escape($table);
    }else {
      return $select . "*" . $from . $this->__escape($table);
    }
  }
  private function __escape($str){
    return $this->conn->real_escape_string($str);
  }
}
?>
