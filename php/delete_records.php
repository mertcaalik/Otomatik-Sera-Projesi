<?php
include 'veritabani.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $input = json_decode(file_get_contents('php://input'), true);
  $ids = $input['ids'];
  
  if (!empty($ids)) {
    $pdo = Database::connect();
    $placeholders = rtrim(str_repeat('?, ', count($ids)), ', ');
    $sql = "DELETE FROM esp32_table_dht11_leds_record WHERE id IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($ids)) {
      echo json_encode(['status' => 'success']);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Deletion failed']);
    }
    Database::disconnect();
  } else {
    echo json_encode(['status' => 'error', 'message' => 'No IDs provided']);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
