<?php
require_once __DIR__ . '/../src/Recipe.php';
require_once __DIR__ . '/../src/config.php';
include __DIR__ . '/../templates/header.php';

$error = null;
$logged = false;

// Проверяем сессию или куки для сохранения входа
session_start();
if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
    $logged = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    $pw = $_POST['password'] ?? '';
    if ($pw === ADMIN_PASSWORD) {
        $logged = true;
        $_SESSION['admin_logged'] = true;
    } else {
        $error = 'Неправильный пароль';
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    $logged = false;
    header('Location: /admin.php');
    exit;
}

if (!$logged) {
    // show login form
    ?>
    <h2>Вход в админку</h2>
    <?php if ($error) echo '<p style="color:red;">' . htmlspecialchars($error) . '</p>'; ?>
    <form method="post">
      <label>Пароль: <input type="password" name="password"></label>
      <button type="submit">Войти</button>
    </form>
    <?php
    include __DIR__ . '/../templates/footer.php';
    exit;
}

$recipes = Recipe::all();
?>
<h2>Админка — управление рецептами</h2>

<h3>Добавить рецепт</h3>
<form method="post" action="/add_recipe.php">
  <p><label>Название: <input type="text" name="title" required></label></p>
  <p><label>Описание: <br><textarea name="description" rows="3"></textarea></label></p>
  <p><label>Порции: <input type="number" name="servings" value="1"></label></p>
  <p>Ингредиенты (каждый с новой строки в формате: имя|количество|единица)</p>
  <p><textarea name="ingredients_text" rows="5" placeholder="Мука|200|г"></textarea></p>
  <p><label>Шаги приготовления: <br><textarea name="steps" rows="6"></textarea></label></p>
  <p><button type="submit">Добавить</button></p>
</form>

<h3>Существующие рецепты</h3>
<?php foreach ($recipes as $r): ?>
  <div style="border:1px solid #ddd;padding:8px;margin:8px 0;">
    <strong><?php echo htmlspecialchars($r['title'] ?? ''); ?></strong>
    <div><a href="/recipe.php?id=<?php echo (int)$r['id']; ?>">Просмотр</a> |
    <a href="/delete.php?id=<?php echo (int)$r['id']; ?>" onclick="return confirm('Удалить?');">Удалить</a></div>
  </div>
<?php endforeach; ?>

<p><a href="/admin.php?logout=1">Выйти</a></p>

<?php include __DIR__ . '/../templates/footer.php'; ?>