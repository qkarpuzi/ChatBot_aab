<?php
include '../database/db.php';

$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? null;

// Marrja e emrit të Primary Key (p.sh. location_id)
$pk = getPrimaryKey($pdo, $table);

if (empty($table)) {
    die("❌ Tabela nuk është specifikuar.");
}

// 1. MARRJA E STRUKTURËS SË TABELËS (Për PostgreSQL)
try {
    $stmt = $pdo->prepare("
        SELECT 
            column_name AS \"Field\", 
            data_type AS \"Type\", 
            is_nullable AS \"Null\",
            column_default AS \"Default\"
        FROM information_schema.columns 
        WHERE table_name = :table 
        AND table_schema = 'public'
        ORDER BY ordinal_position
    ");
    $stmt->execute(['table' => $table]);
    $columnsInfo = $stmt->fetchAll();

    if (!$columnsInfo) {
        die("❌ Tabela '$table' nuk u gjet ose nuk ka kolona.");
    }
} catch (Exception $e) {
    die("❌ Gabim gjatë marrjes së strukturës: " . $e->getMessage());
}

// 2. MARRJA E TË DHËNAVE EKZISTUESE (Nëse jemi në Edit)
$existingData = [];
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE $pk = ?");
    $stmt->execute([$id]);
    $existingData = $stmt->fetch();
}

// 3. PËRPUNIMI I TË DHËNAVE (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = $_POST['data'];
    
    // RREGULL KRELYESOR: Heqim ID-në dhe created_at nga Query i Insertit/Updatit
    // Kjo lejon PostgreSQL të përdorë SERIAL (auto-increment) pa shkaktuar "Duplicate Key"
    if (isset($data[$pk])) unset($data[$pk]); 
    if (isset($data['created_at'])) unset($data['created_at']);

    // Trajtimi i vlerave bosh (Kthimi në NULL për të shmangur gabimet në Boolean/Numra)
    foreach ($data as $key => $value) {
        if ($value === '') {
            $data[$key] = null; 
        }
    }

    $columns = array_keys($data);
    $values = array_values($data);

    try {
        if ($id) {
            // UPDATE
            $setClause = implode(", ", array_map(function($col) { return "$col = ?"; }, $columns));
            $sql = "UPDATE $table SET $setClause WHERE $pk = ?";
            $values[] = $id; 
            $pdo->prepare($sql)->execute($values);
        } else {
            // INSERT
            $colsString = implode(", ", $columns);
            $placeholders = implode(", ", array_fill(0, count($columns), "?"));
            $sql = "INSERT INTO $table ($colsString) VALUES ($placeholders)";
            $pdo->prepare($sql)->execute($values);
        }
        
        header("Location: index.php?table=$table");
        exit;
    } catch (PDOException $e) {
        // Nëse ke gabimin "Duplicate Key", duhet të sinkronizosh Sequence-in në Supabase
        die("❌ Gabim gjatë ruajtjes: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Edito' : 'Shto'; ?> - <?php echo ucfirst($table); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .form-card { max-width: 650px; margin: 50px auto; border: none; border-radius: 12px; }
        .card-header { border-radius: 12px 12px 0 0 !important; }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow form-card">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0 text-center"><?php echo $id ? 'Edito Rekordin' : 'Shto Rekord të Ri'; ?></h4>
            <p class="mb-0 text-center opacity-75">Tabela: <?php echo htmlspecialchars($table); ?></p>
        </div>
        <div class="card-body p-4">
            <form method="POST">
                <?php foreach ($columnsInfo as $col): 
                    $colName = $col['Field'];
                    $colType = $col['Type'];
                    
                    // MOS shfaq ID-në ose created_at në formë
                    if ($colName == $pk || $colName == 'created_at') continue;

                    $val = $existingData[$colName] ?? '';
                ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold"><?php echo str_replace('_', ' ', ucfirst($colName)); ?></label>
                        
                        <?php if ($colName == 'faq_id'): // Dropdown për lidhjen me FAQ ?>
                            <?php 
                                $fStmt = $pdo->query("SELECT faq_id, question FROM faq ORDER BY faq_id");
                                $faqs = $fStmt->fetchAll();
                            ?>
                            <select name="data[faq_id]" class="form-select">
                                <option value="">-- Zgjidh një pyetje --</option>
                                <?php foreach ($faqs as $f): ?>
                                    <option value="<?php echo $f['faq_id']; ?>" <?php echo ($val == $f['faq_id']) ? 'selected' : ''; ?>>
                                        ID: <?php echo $f['faq_id']; ?> - <?php echo htmlspecialchars(substr($f['question'], 0, 50)); ?>...
                                    </option>
                                <?php endforeach; ?>
                            </select>

                        <?php elseif ($colType == 'boolean'): // Fushat True/False ?>
                            <select name="data[<?php echo $colName; ?>]" class="form-select">
                                <option value="">-- Pa përcaktuar --</option>
                                <option value="true" <?php echo ($val === true || $val === 't') ? 'selected' : ''; ?>>Po (True)</option>
                                <option value="false" <?php echo ($val === false || $val === 'f') ? 'selected' : ''; ?>>Jo (False)</option>
                            </select>

                        <?php elseif (strpos($colType, 'text') !== false): // TextArea për fusha të gjata ?>
                            <textarea name="data[<?php echo $colName; ?>]" class="form-control" rows="3"><?php echo htmlspecialchars($val); ?></textarea>

                        <?php else: // Inputet standarde ?>
                            <?php 
                                $inputType = 'text';
                                if (strpos($colType, 'int') !== false || strpos($colType, 'num') !== false) $inputType = 'number';
                                if (strpos($colName, 'date') !== false) $inputType = 'date';
                                if (strpos($colName, 'password') !== false) $inputType = 'password';
                            ?>
                            <input type="<?php echo $inputType; ?>" step="any" name="data[<?php echo $colName; ?>]" 
                                   class="form-control" value="<?php echo htmlspecialchars($val); ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                
                <div class="mt-4 d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">Ruaj të dhënat</button>
                    <a href="index.php?table=<?php echo $table; ?>" class="btn btn-light border">Anulo</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>