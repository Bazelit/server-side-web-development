<?php
// Принудительно устанавливаем UTF8
header('Content-Type: text/html; charset=utf-8');
ini_set('default_charset', 'utf-8');
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

require_once __DIR__ . '/../src/Recipe.php';
include __DIR__ . '/../templates/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$r = Recipe::find($id);

// Функция для принудительной конвертации в UTF8
function fix_encoding($text) {
    if ($text === null) return '';
    // Если текст уже в UTF8, оставляем, иначе конвертируем из Windows-1251
    if (!mb_check_encoding($text, 'UTF-8')) {
        $text = mb_convert_encoding($text, 'UTF-8', 'Windows-1251');
    }
    return $text;
}

if (!$r) {
    echo '<p>Рецепт не найден.</p>';
    include __DIR__ . '/../templates/footer.php';
    exit;
}

// Применяем конвертацию ко всем текстовым полям
$r['title'] = fix_encoding($r['title']);
$r['description'] = fix_encoding($r['description']);
$r['steps'] = fix_encoding($r['steps']);

$ingredients = json_decode($r['ingredients'], true);
// Конвертируем ингредиенты
if (is_array($ingredients)) {
    foreach ($ingredients as &$ing) {
        $ing['name'] = fix_encoding($ing['name']);
        $ing['unit'] = fix_encoding($ing['unit'] ?? '');
    }
}

$servings = isset($_GET['servings']) ? max(1,(int)$_GET['servings']) : (int)$r['servings'];
$scale = $servings / max(1,(int)$r['servings']);

?>
<h2><?php echo htmlspecialchars($r['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
<p><?php echo nl2br(htmlspecialchars($r['description'], ENT_QUOTES, 'UTF-8')); ?></p>
<p><strong>Оригинальные порции:</strong> <?php echo (int)$r['servings']; ?></p>

<form method="get" id="servingsForm">
  <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
  <label>Укажите порции:
    <input type="number" name="servings" value="<?php echo $servings; ?>" min="1">
  </label>
  <button type="submit">Пересчитать</button>
  <small>Ингредиенты пересчитываются в соответствии с числом порций.</small>
</form>

<h3>Ингредиенты</h3>
<ul class="ingredients">
<?php foreach ($ingredients as $ing): ?>
  <?php $qty = isset($ing['qty']) ? $ing['qty'] * $scale : ''; ?>
  <li><?php echo htmlspecialchars($ing['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?> — <?php echo htmlspecialchars((string)$qty, ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars($ing['unit'] ?? '', ENT_QUOTES, 'UTF-8'); ?></li>
<?php endforeach; ?>
</ul>

<h3>Приготовление</h3>
<pre><?php echo htmlspecialchars($r['steps'], ENT_QUOTES, 'UTF-8'); ?></pre>

<?php include __DIR__ . '/../templates/footer.php'; ?>