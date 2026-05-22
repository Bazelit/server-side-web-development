<?php
require_once __DIR__ . '/db.php';

class Recipe
{
    private static function convertToUtf8($data)
    {
        if (is_array($data)) {
            foreach ($data as &$value) {
                if (is_string($value)) {
                    if (!mb_check_encoding($value, 'UTF-8')) {
                        $value = mb_convert_encoding($value, 'UTF-8', 'Windows-1251');
                    }
                }
            }
        }
        return $data;
    }
    
    public static function all($q = null)
    {
        $db = get_db();
        if ($q) {
            $stmt = $db->prepare("SELECT * FROM recipes WHERE title ILIKE :q OR ingredients::text ILIKE :q ORDER BY created_at DESC");
            $like = '%' . $q . '%';
            $stmt->execute([':q' => $like]);
        } else {
            $stmt = $db->query("SELECT * FROM recipes ORDER BY created_at DESC");
        }
        $results = $stmt->fetchAll();
        return self::convertToUtf8($results);
    }

    public static function find($id)
    {
        $db = get_db();
        $stmt = $db->prepare("SELECT * FROM recipes WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return self::convertToUtf8($result);
    }
    
    public static function create($data)
    {
        $db = get_db();
        $stmt = $db->prepare("INSERT INTO recipes (title, description, ingredients, steps, servings) VALUES (:title, :description, :ingredients::jsonb, :steps, :servings)");
        $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'] ?? '',
            ':ingredients' => $data['ingredients_json'],
            ':steps' => $data['steps'] ?? '',
            ':servings' => $data['servings'],
        ]);
        return $db->lastInsertId('recipes_id_seq');
    }

    public static function delete($id)
    {
        $db = get_db();
        $stmt = $db->prepare("DELETE FROM recipes WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>