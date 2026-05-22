<?php
header('Content-Type: text/html; charset=utf-8');
ini_set('default_charset', 'utf-8');
mb_internal_encoding('UTF-8');

require_once __DIR__ . '/../src/Recipe.php';
include __DIR__ . '/../templates/header.php';

function fix_encoding($text) {
    if ($text === null) return '';
    if (!mb_check_encoding($text, 'UTF-8')) {
        $text = mb_convert_encoding($text, 'UTF-8', 'Windows-1251');
    }
    return $text;
}

$q = isset($_GET['q']) ? trim($_GET['q']) : null;
$recipes = Recipe::all($q);

// Конвертируем каждый рецепт
if (is_array($recipes)) {
    foreach ($recipes as &$r) {
        $r['title'] = fix_encoding($r['title']);
        $r['description'] = fix_encoding($r['description']);
    }
}

?>
<form class="search" method="get">
  <input type="text" name="q" placeholder="Поиск по названию или ингредиентам" value="<?php echo htmlspecialchars($q ?? '', ENT_QUOTES, 'UTF-8'); ?>" size="40">
  <button type="submit">Поиск</button>
</form>

<?php if (!$recipes): ?>
  <p>Рецепты не найдены.</p>
<?php else: ?>
  <?php foreach ($recipes as $r): ?>
    <div class="recipe">
      <h3><a href="/recipe.php?id=<?php echo (int)$r['id']; ?>"><?php echo htmlspecialchars($r['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></a></h3>
      <p><?php echo nl2br(htmlspecialchars($r['description'] ?? '', ENT_QUOTES, 'UTF-8')); ?></p>
      <p><strong>Порции:</strong> <?php echo (int)($r['servings'] ?? 0); ?></p>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<?php include __DIR__ . '/../templates/footer.php'; ?>