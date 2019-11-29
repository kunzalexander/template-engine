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

########## START SEARCH FUNCTIONS ##########
  public function search_all($table, $field="", $valueArr=""){
    $valueNum = "";
    if(!empty($valueArr)){
      $valueNum = count($valueArr);
    }
    $stmt = $this->conn->stmt_init();
    $sql = $this->__selectFieldTable($table);
    $sql .= $this->__whereFieldValue($field, $valueNum);
    $this->__prepareBindParam($stmt, $sql, $valueArr);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();

  }

########## END SEARCH FUNCTIONS ##########

########## START INSERT FUNCTIONS ##########

########## END INSERT FUNCTIONS ##########

########## START PREPARED SQL SELECT FUNCTIONS ##########
  private function __selectFieldTable($table, $field=""){
    $select = "SELECT ";
    $from = " FROM ";
    if (!empty($field)) {
      return $select . $field . $from . $this->__escape($table);
    }else {
      return $select . "*" . $from . $this->__escape($table);
    }
  }

  private function __whereFieldValue($field="", $valueNum=""){
      $where = " WHERE ";
      $leftIn = " IN ( ";
      $rightIn = " )";
      $param = $this->__paramNum($valueNum);
      if(!(empty($field) AND empty($valueNum))){
        return $where . $this->__escape($field) . $leftIn . $param . $rightIn;
      }
      else{
        return;
      }
  }

  private function __paramNum($valueNum){
    $param = "?";
    if ($valueNum > 1) {
      for ($i=1; $i<$valueNum ; $i+1) {
        $param .= "," . $param;
      }
    }
    return $param;
  }

########## END PREPARED SQL SELECT FUNCTIONS ##########

########## START SECURITY FUNCTIONS ##########
  private function __escape($str){
    return $this->conn->real_escape_string($str);
  }

  private function __prepareBindParam($stmt, $sql, $valueArr=""){

    if(!$stmt->prepare($sql)) {
      echo "error: " . $sql;
    }
    elseif(empty($valueArr)){
      return;
    }
    else{
      $stmt->bind_param("s", $valueArr);
    }
  }

########## START SECURITY FUNCTIONS ##########
}
?>
