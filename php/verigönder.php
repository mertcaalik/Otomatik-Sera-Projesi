<?php
  require 'veritabani.php';
  
  
  if (!empty($_POST)) {

    $id = $_POST['id'];
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];
    $status_read_sensor_dht11 = $_POST['status_read_sensor_dht11'];
    
    date_default_timezone_set("Asia/Jakarta"); 
    $tm = date("H:i:s");
    $dt = date("Y-m-d");
    
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE esp32_table_dht11_leds_update SET sıcaklık = ?, nem = ?, status_read_sensor_dht11 = ?, saat = ?, tarih = ? WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($temperature,$humidity,$status_read_sensor_dht11,$tm,$dt,$id));
    Database::disconnect();
     
    
    $id_key;
    $board = $_POST['id'];
    $found_empty = false;
    
    $pdo = Database::connect();
    
    while ($found_empty == false) {
      $id_key = generate_string_id(10);
      
      $sql = 'SELECT * FROM esp32_table_dht11_leds_record WHERE id="' . $id_key . '"';
      $q = $pdo->prepare($sql);
      $q->execute();
      
      if (!$data = $q->fetch()) {
        $found_empty = true;
      }
    }
   
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
		$sql = "INSERT INTO esp32_table_dht11_leds_record (id,board,sıcaklık,nem,status_read_sensor_dht11,saat,tarih) values(?, ?, ?, ?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($id_key,$board,$temperature,$humidity,$status_read_sensor_dht11,$tm,$dt));
   
    
    Database::disconnect();
    
  }
  
  function generate_string_id($strength = 16) {
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen($permitted_chars);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
      $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
      $random_string .= $random_character;
    }
    return $random_string;
  }
if (!empty($_POST['soil_moisture'])) {
    $soil_moisture = $_POST['soil_moisture'];
    $zaman = date('Y-m-d H:i:s');

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Sadece id değeri 1 olan kaydı güncelle
    $update_sql = "UPDATE toprak_nem_verileri SET zaman = ?, toprak_nem_degeri = ? WHERE id = ?";
    $update_q = $pdo->prepare($update_sql);
    $update_q->execute(array($zaman, $soil_moisture, 1)); // Burada id değerini sabit olarak belirtiyoruz

    if ($update_q->rowCount() > 0) {
        echo "Kayıt güncellendi";
    } else {
        echo "Kayıt bulunamadı veya güncelleme yapılmadı";
    }

    Database::disconnect();
} else {
    echo "Hatalı istek!";
}
?>

?>