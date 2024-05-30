<?php
  include 'veritabani.php';
  
  
  if (!empty($_POST)) {
    
    $id = $_POST['id'];
    
    $myObj = (object)array();
    
    
    $pdo = Database::connect();
    $sql = 'SELECT * FROM toprak_nem_verileri WHERE id="' . $id . '"';
    foreach ($pdo->query($sql) as $row) {
      
      $myObj->id = $row['id'];
      $myObj->humidity = $row['toprak_nem_degeri'];
      
      $myJSON = json_encode($myObj);
      
      echo $myJSON;
    }
    Database::disconnect();
    
  }
 
?>