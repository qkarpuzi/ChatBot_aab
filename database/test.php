<?php
require_once 'db.php';

try {
    $stmt = $pdo->query("SELECT version() as ver");
    $row = $stmt->fetch();
    
    echo "<h2 style='color:green'>✅ LIDHJA U KRYE ME SUKSES!</h2>";
    echo "<strong>PostgreSQL Version:</strong> " . $row['ver'];
    echo "<br><br><a href='../index.php'>← Kthehu në faqen kryesore</a>";
} 
catch (Exception $e) {
    echo "<h2 style='color:red'>❌ GABIM LIDHJEJE:</h2>" . $e->getMessage();
}
?>