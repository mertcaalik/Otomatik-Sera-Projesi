<?php
// veritabani.php dosyasını dahil edelim
include 'veritabani.php';

// Bağlantıyı oluşturalım
$db = Database::connect();

// Bağlantıyı kontrol edelim
if (!$db) {
    die("Bağlantı hatası: Veritabanı bağlantısı kurulamadı.");
}

// POST yöntemiyle gönderilen referans sayısını ve güncellenecek ID'yi alalım
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['referans'])) {
        $referans_sayisi = $_POST['referans'];
        $id = 1; // Sadece id değeri 1 olan kaydı güncelleyeceğiz

        // Veritabanında güncelleme sorgusu
        $sql = "UPDATE referans_tablosu SET referans_sayisi = :referans WHERE id = :id";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':referans', $referans_sayisi);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            echo "Veri güncellendi!";
            // Sayfayı yenilemek için JavaScript kullanımı
            echo "<script>window.location.href = 'http://localhost/seraprojesi/';</script>";
            exit(); // Yönlendirme yapıldıktan sonra scriptin devam etmemesi için exit kullanılır
        } else {
            echo "Hata: Veri güncellenirken bir sorun oluştu.";
        }
    } else {
        echo "Hata: Gerekli parametreler eksik!";
    }
}
?>