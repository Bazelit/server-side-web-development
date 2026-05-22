<?php
require_once __DIR__ . '/../src/Recipe.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id) {
    Recipe::delete($id);
}
header('Location: /admin.php');
exit;
