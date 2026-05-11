<?php
$pdo = getDB();
$message = '';

// Get all contacts sorted by surname then name
$contacts = $pdo->query("SELECT id, surname, name FROM contacts ORDER BY surname ASC, name ASC")->fetchAll();

// Determine current record id
if (isset($_GET['id'])) {
    $currentId = (int)$_GET['id'];
} elseif (!empty($contacts)) {
    $currentId = (int)$contacts[0]['id'];
} else {
    $currentId = 0;
}

// Handle POST (save edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['button'])) {
    $editId = (int)($_POST['edit_id'] ?? 0);
    $fields = ['surname','name','lastname','gender','date','phone','location','email','comment'];
    $data = [];
    foreach ($fields as $f) {
        $data[$f] = trim($_POST[$f] ?? '');
    }

    if (empty($data['surname']) || empty($data['name'])) {
        $message = '<p class="error">Ошибка: запись не сохранена (фамилия и имя обязательны)</p>';
    } else {
        try {
            $stmt = $pdo->prepare(
                "UPDATE contacts SET surname=:surname, name=:name, lastname=:lastname,
                 gender=:gender, date=:date, phone=:phone, location=:location,
                 email=:email, comment=:comment WHERE id=:id"
            );
            $data['id'] = $editId;
            $stmt->execute($data);
            $message = '<p class="success">Запись сохранена</p>';
            $currentId = $editId;
            // Refresh contacts list
            $contacts = $pdo->query("SELECT id, surname, name FROM contacts ORDER BY surname ASC, name ASC")->fetchAll();
        } catch (Exception $e) {
            $message = '<p class="error">Ошибка: запись не сохранена</p>';
        }
    }
}

// Load current row
$row = [];
if ($currentId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = :id");
    $stmt->execute(['id' => $currentId]);
    $row = $stmt->fetch() ?: [];
}

$button = 'Сохранить';
?>

<h2 style="text-align:center;margin-top:20px;">Редактирование записи</h2>
<?= $message ?>

<div style="display:flex;gap:20px;align-items:flex-start;justify-content:center;flex-wrap:wrap;">

    <?php if (!empty($contacts)): ?>
    <div class="div-edit">
        <?php foreach ($contacts as $c): ?>
            <?php $isCurrentRow = ((int)$c['id'] === $currentId); ?>
            <div class="<?= $isCurrentRow ? 'currentRow' : '' ?>">
                <a href="index.php?action=edit&id=<?= $c['id'] ?>">
                    <?= htmlspecialchars($c['surname'] . ' ' . $c['name']) ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <p>Нет записей для редактирования.</p>
    <?php endif; ?>

    <?php if (!empty($row)): ?>
    <form name="form_edit" method="post" action="index.php?action=edit&id=<?= $currentId ?>">
        <input type="hidden" name="edit_id" value="<?= $currentId ?>">
        <div class="column">
            <div class="add">
                <label>Фамилия</label>
                <input type="text" name="surname" placeholder="Фамилия" value="<?= htmlspecialchars($row['surname'] ?? '') ?>">
            </div>
            <div class="add">
                <label>Имя</label>
                <input type="text" name="name" placeholder="Имя" value="<?= htmlspecialchars($row['name'] ?? '') ?>">
            </div>
            <div class="add">
                <label>Отчество</label>
                <input type="text" name="lastname" placeholder="Отчество" value="<?= htmlspecialchars($row['lastname'] ?? '') ?>">
            </div>
            <div class="add">
                <label>Пол</label>
                <select name="gender">
                    <option value="мужской" <?= (($row['gender'] ?? '') === 'мужской') ? 'selected' : '' ?>>мужской</option>
                    <option value="женский" <?= (($row['gender'] ?? '') === 'женский') ? 'selected' : '' ?>>женский</option>
                </select>
            </div>
            <div class="add">
                <label>Дата рождения</label>
                <input type="date" name="date" value="<?= htmlspecialchars($row['date'] ?? '') ?>">
            </div>
            <div class="add">
                <label>Телефон</label>
                <input type="text" name="phone" placeholder="Телефон" value="<?= htmlspecialchars($row['phone'] ?? '') ?>">
            </div>
            <div class="add">
                <label>Адрес</label>
                <input type="text" name="location" placeholder="Адрес" value="<?= htmlspecialchars($row['location'] ?? '') ?>">
            </div>
            <div class="add">
                <label>Email</label>
                <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($row['email'] ?? '') ?>">
            </div>
            <div class="add">
                <label>Комментарий</label>
                <textarea name="comment" placeholder="Краткий комментарий"><?= htmlspecialchars($row['comment'] ?? '') ?></textarea>
            </div>
            <button type="submit" name="button" value="<?= $button ?>" class="form-btn"><?= $button ?></button>
        </div>
    </form>
    <?php elseif (empty($contacts)): ?>
        <!-- no records -->
    <?php else: ?>
        <p>Выберите запись для редактирования.</p>
    <?php endif; ?>

</div>
