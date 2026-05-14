<?php 
$host = 'localhost';
$db   = 'aab_chatbot_db'; // Ndryshoje me emrin e databazës tënde
$user = 'root'; 
$pass = '';     
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("Gabim në lidhje: " . $e->getMessage());
}

// KJO ËSHTË PJESA QË MUNGON DHE SHKAKTON GABIMIN
// Funksion për të gjetur çelësin primar (Primary Key) të tabelës
if (!function_exists('getPrimaryKey')) {
    function getPrimaryKey($pdo, $table) {
        $stmt = $pdo->query("SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'");
        $result = $stmt->fetch();
        return $result['Column_name'] ?? 'id';
    }
}
?>