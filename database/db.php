<?php
$host = 'localhost';
$db   = 'aab_chatbot_db'; 
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
     // Heqim "Strict Mode" për të shmangur gabimin 'Data truncated'
     $pdo->exec("SET sql_mode=''");
} catch (\PDOException $e) {
     die("Gabim në lidhje: " . $e->getMessage());
}

function getPrimaryKey($pdo, $table) {
    try {
        $stmt = $pdo->query("SHOW KEYS FROM `$table` WHERE Key_name = 'PRIMARY'");
        $result = $stmt->fetch();
        return $result['Column_name'] ?? 'id';
    } catch (Exception $e) {
        return 'id';
    }
}
?>

