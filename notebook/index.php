<?php
require_once 'db.php';
require_once 'menu.php';
require_once 'viewer.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$action = isset($_GET['action']) ? $_GET['action'] : 'view';

// Validate sort
$allowed_sorts = ['id', 'surname', 'date'];
if (!in_array($sort, $allowed_sorts)) $sort = 'id';

// Validate action
$allowed_actions = ['view', 'add', 'edit', 'delete'];
if (!in_array($action, $allowed_actions)) $action = 'view';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Записная книжка</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Lemonada:wght@400;600&family=Nunito:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<?php $menuData = getMenu($action, $sort); ?>
<header>
    <?= $menuData['menu'] ?>
</header>
<?php if (!empty($menuData['submenu'])): ?>
<div class="submenu-bar">
    <?= $menuData['submenu'] ?>
</div>
<?php endif; ?>

<main>
<?php
if ($action === 'view') {
    echo getViewerContent($sort, $page);
} elseif ($action === 'add') {
    require_once 'add.php';
} elseif ($action === 'edit') {
    require_once 'edit.php';
} elseif ($action === 'delete') {
    require_once 'delete.php';
}
?>
</main>

<footer></footer>

</body>
</html>
