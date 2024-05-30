<?php
  include 'veritabani.php';
  
  
  if (!empty($_POST)) {
    
    $id = $_POST['id'];
    
    $myObj = (object)array();
    
    
    $pdo = Database::connect();
    $sql = 'SELECT * FROM esp32_table_dht11_leds_update WHERE id="' . $id . '"';
    foreach ($pdo->query($sql) as $row) {
      $date = date_create($row['tarih']);
      $dateFormat = date_format($date,"d-m-Y");
      
      $myObj->id = $row['id'];
      $myObj->temperature = $row['sıcaklık'];
      $myObj->humidity = $row['nem'];
      $myObj->status_read_sensor_dht11 = $row['status_read_sensor_dht11'];
      $myObj->ls_time = $row['saat'];
      $myObj->ls_date = $dateFormat;
      
      $myJSON = json_encode($myObj);
      
      echo $myJSON;
    }
    Database::disconnect();
    
  }
 
?>