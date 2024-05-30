<?php
  include 'veritabani.php';
  
  
  if (!empty($_POST)) {
    
    $id = $_POST['id'];
    
    $myObj = (object)array();
    
    $pdo = Database::connect();
    $sql = 'SELECT * FROM toprak_nem_verileri WHERE id="' . $id . '"';
    foreach ($pdo->query($sql) as $row) {
 
      
      $myObj->kimlik = $row['id'];
      $myObj->nem = $row['toprak_nem_degeri'];
      $myObj->zaman = $row['zaman'];
      
      $myJSON = json_encode($myObj);
      
      echo $myJSON;
    }
    Database::disconnect();
  }
?>