<?php
$host = 'aws-0-eu-west-1.pooler.supabase.com';
$port = '5432';
$db   = 'postgres';
$user = 'postgres.vvnjnnrfiamqwhateovt'; 
$pass = 'F#x6$bmA&mfMZvs';

$dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} 
catch (\PDOException $e) {
    die("Gabim lidhje: " . $e->getMessage());
}
?>