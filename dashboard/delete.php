<?php
include '../database/db.php';

if (isset($_GET['table']) && isset($_GET['id'])) {
    $table = $_GET['table'];
    $id = $_GET['id'];
    $pk = getPrimaryKey($pdo, $table);

    $stmt = $pdo->prepare("DELETE FROM $table WHERE $pk = ?");
    $stmt->execute([$id]);

    header("Location: index.php?table=$table");
    exit;
}
?>