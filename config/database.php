<?php
$host = 'db'; // MySQL sunucu adresi
$db = 'phpgames';    // Veritabanı adı
$user = 'root';      // Veritabanı kullanıcı adı
$pass = '123456';    // Veritabanı şifresi

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass);
    // Bağlantı başarılıysa buraya kod ekleyebilirsiniz
    // Örneğin: echo "Veritabanına başarıyla bağlandı!";
} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}
?>
