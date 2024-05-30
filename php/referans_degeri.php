<?php
include 'veritabani.php';
$db = Database::connect();

if (!$db) {
    die("Bağlantı hatası: Veritabanı bağlantısı kurulamadı.");
}

$id = 1; 
$sql = "SELECT referans_sayisi FROM referans_tablosu WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$referans_sayisi = $stmt->fetch(PDO::FETCH_ASSOC);

// Elle JSON verisi dönüyoruz
header('Content-Type: application/json');
echo '{"referans_sayisi":' . $referans_sayisi['referans_sayisi'] . '}';
?>
