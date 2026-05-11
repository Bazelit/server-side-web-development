<?php
$pdo = getDB();
$message = '';

// Handle delete
if (isset($_GET['del_id'])) {
    $delId = (int)$_GET['del_id'];
    $stmt = $pdo->prepare("SELECT surname FROM contacts WHERE id = :id");
    $stmt->execute(['id' => $delId]);
    $found = $stmt->fetch();

    if ($found) {
        $delSurname = $found['surname'];
        try {
            $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = :id");
            $stmt->execute(['id' => $delId]);
            $message = '<p class="success">Запись с фамилией ' . htmlspecialchars($delSurname) . ' удалена</p>';
        } catch (Exception $e) {
            $message = '<p class="error">Ошибка: запись не удалена</p>';
        }
    } else {
        $message = '<p class="error">Запись не найдена</p>';
    }
}

// Load contacts list
$contacts = $pdo->query("SELECT id, surname, name, lastname FROM contacts ORDER BY surname ASC")->fetchAll();
?>

<h2 style="text-align:center;margin-top:20px;">Удаление записи</h2>
<?= $message ?>

<?php if (empty($contacts)): ?>
    <p style="text-align:center;color:#666;">Нет записей для удаления.</p>
<?php else: ?>
    <div class="div-edit" style="margin:20px auto;">
        <?php foreach ($contacts as $c): ?>
            <div>
                <a href="index.php?action=delete&del_id=<?= $c['id'] ?>"
                   onclick="return confirm('Удалить запись <?= htmlspecialchars(addslashes($c['surname'])) ?>?')">
                    <?= htmlspecialchars($c['surname'] . ' ' . mb_substr($c['name'], 0, 1) . '.' . ($c['lastname'] ? mb_substr($c['lastname'], 0, 1) . '.' : '')) ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
