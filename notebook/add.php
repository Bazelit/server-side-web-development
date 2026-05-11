<?php
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['button'])) {
    $pdo = getDB();
    $fields = ['surname','name','lastname','gender','date','phone','location','email','comment'];
    $data = [];
    foreach ($fields as $f) {
        $data[$f] = trim($_POST[$f] ?? '');
    }

    // Basic validation: surname and name are required
    if (empty($data['surname']) || empty($data['name'])) {
        $message = '<p class="error">Ошибка: запись не добавлена (фамилия и имя обязательны)</p>';
    } else {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO contacts (surname, name, lastname, gender, date, phone, location, email, comment)
                 VALUES (:surname, :name, :lastname, :gender, :date, :phone, :location, :email, :comment)"
            );
            $stmt->execute($data);
            $message = '<p class="success">Запись добавлена</p>';
            // Reset data after success
            $data = array_fill_keys($fields, '');
        } catch (Exception $e) {
            $message = '<p class="error">Ошибка: запись не добавлена</p>';
        }
    }
} else {
    $fields = ['surname','name','lastname','gender','date','phone','location','email','comment'];
    $data = array_fill_keys($fields, '');
}

$button = 'Добавить';
$row = $data;
?>

<h2 style="text-align:center;margin-top:20px;">Добавление записи</h2>
<?= $message ?>

<form name="form_add" method="post" action="index.php?action=add">
    <div class="column">
        <div class="add">
            <label>Фамилия</label>
            <input type="text" name="surname" placeholder="Фамилия" value="<?= htmlspecialchars($row['surname']) ?>">
        </div>
        <div class="add">
            <label>Имя</label>
            <input type="text" name="name" placeholder="Имя" value="<?= htmlspecialchars($row['name']) ?>">
        </div>
        <div class="add">
            <label>Отчество</label>
            <input type="text" name="lastname" placeholder="Отчество" value="<?= htmlspecialchars($row['lastname']) ?>">
        </div>
        <div class="add">
            <label>Пол</label>
            <select name="gender">
                <option value="мужской" <?= ($row['gender'] === 'мужской') ? 'selected' : '' ?>>мужской</option>
                <option value="женский" <?= ($row['gender'] === 'женский') ? 'selected' : '' ?>>женский</option>
            </select>
        </div>
        <div class="add">
            <label>Дата рождения</label>
            <input type="date" name="date" value="<?= htmlspecialchars($row['date']) ?>">
        </div>
        <div class="add">
            <label>Телефон</label>
            <input type="text" name="phone" placeholder="Телефон" value="<?= htmlspecialchars($row['phone']) ?>">
        </div>
        <div class="add">
            <label>Адрес</label>
            <input type="text" name="location" placeholder="Адрес" value="<?= htmlspecialchars($row['location']) ?>">
        </div>
        <div class="add">
            <label>Email</label>
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($row['email']) ?>">
        </div>
        <div class="add">
            <label>Комментарий</label>
            <textarea name="comment" placeholder="Краткий комментарий"><?= htmlspecialchars($row['comment']) ?></textarea>
        </div>
        <button type="submit" name="button" value="<?= $button ?>" class="form-btn"><?= $button ?></button>
    </div>
</form>
