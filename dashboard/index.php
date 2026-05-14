<?php 
include '../database/db.php';
 ?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Chatbot Admin Dashboard</a>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="row">
        <!-- Sidebar me Tabelat -->
        <div class="col-md-2">
            <div class="list-group">
                <?php
                $tables = ['admin', 'chat_messages', 'default_responses', 'directions', 'faq', 'faq_categories', 'faq_keywords', 'keywords', 'locations'];
                $current_table = $_GET['table'] ?? 'locations';
                
                foreach ($tables as $t) {
                    $active = ($t == $current_table) ? 'active' : '';
                    echo "<a href='?table=$t' class='list-group-item list-group-item-action $active'>".ucfirst($t)."</a>";
                }
                ?>
            </div>
        </div>

        <!-- Pjesa e të Dhënave -->
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tabela: <?php echo ucfirst($current_table); ?></h5>
                    <a href="form.php?table=<?php echo $current_table; ?>" class="btn btn-primary btn-sm">Shto Rekord të Ri</a>
                </div>
                <div class="card-body overflow-auto">
                    <?php
                    $pk = getPrimaryKey($pdo, $current_table);
                    $stmt = $pdo->query("SELECT * FROM $current_table ORDER BY $pk ASC ");
                    $rows = $stmt->fetchAll();

                    if (count($rows) > 0) {
                        echo '<table class="table table-bordered table-hover text-nowrap">';
                        echo '<thead class="table-dark"><tr>';
                        foreach (array_keys($rows[0]) as $col) echo "<th>$col</th>";
                        echo '<th>Veprime</th></tr></thead><tbody>';

                        foreach ($rows as $row) {
                            echo "<tr>";
                            foreach ($row as $val) echo "<td>" . htmlspecialchars(substr($val ?? '', 0, 50)) . "</td>";
                            
                            $id = $row[$pk];
                            echo "<td>
                                    <a href='form.php?table=$current_table&id=$id' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='delete.php?table=$current_table&id=$id' class='btn btn-danger btn-sm' onclick='return confirm(\"Je i sigurt?\")'>Fshi</a>
                                  </td>";
                            echo "</tr>";
                        }
                        echo '</tbody></table>';
                    } else {
                        echo "<p>Nuk ka të dhëna në këtë tabelë.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>