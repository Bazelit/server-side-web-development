<?php
/**
 * Returns HTML string with the contacts table and pagination.
 * @param string $sort  - sort type: 'id', 'surname', 'date'
 * @param int    $page  - current page number (1-based)
 * @return string HTML content
 */
function getViewerContent(string $sort = 'id', int $page = 1): string {
    $pdo = getDB();
    $perPage = 10;

    $sortMap = [
        'id'      => 'id ASC',
        'surname' => 'surname ASC',
        'date'    => 'date ASC',
    ];
    $orderBy = $sortMap[$sort] ?? 'id ASC';

    // Total count
    $total = (int)$pdo->query('SELECT COUNT(*) FROM contacts')->fetchColumn();
    $totalPages = max(1, (int)ceil($total / $perPage));
    $page = max(1, min($page, $totalPages));
    $offset = ($page - 1) * $perPage;

    $stmt = $pdo->prepare("SELECT * FROM contacts ORDER BY $orderBy LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll();

    if (empty($rows)) {
        return '<p style="text-align:center;margin-top:30px;color:#666;">База данных пуста. Добавьте первую запись!</p>';
    }

    $html = '<div style="overflow-x:auto;margin:20px auto;max-width:98%;">';
    $html .= '<table>';
    $html .= '<thead><tr>
        <th>#</th>
        <th>Фамилия</th>
        <th>Имя</th>
        <th>Отчество</th>
        <th>Пол</th>
        <th>Дата рождения</th>
        <th>Телефон</th>
        <th>Адрес</th>
        <th>E-mail</th>
        <th>Комментарий</th>
    </tr></thead><tbody>';

    foreach ($rows as $i => $row) {
        $html .= '<tr>';
        $html .= '<td>' . ($offset + $i + 1) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['surname']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['lastname']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['gender']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['date']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['phone']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['location']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['email']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['comment']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table></div>';

    // Pagination
    if ($totalPages > 1) {
        $html .= '<div class="pagination">';
        for ($i = 1; $i <= $totalPages; $i++) {
            $class = ($i === $page) ? 'page-link active' : 'page-link';
            $html .= '<a href="index.php?action=view&sort=' . $sort . '&page=' . $i . '" class="' . $class . '">' . $i . '</a>';
        }
        $html .= '</div>';
    }

    return $html;
}
