<?php
/**
 * Returns HTML string with the main navigation menu.
 * @param string $currentAction  - active menu item ('view','add','edit','delete')
 * @param string $currentSort    - active sort option ('id','surname','date')
 * @return array ['menu' => string, 'submenu' => string]
 */
function getMenu(string $currentAction = 'view', string $currentSort = 'id'): array {
    $menuItems = [
        'view'   => 'Просмотр',
        'add'    => 'Добавление записи',
        'edit'   => 'Редактирование записи',
        'delete' => 'Удаление записи',
    ];

    $menu = '';
    foreach ($menuItems as $action => $label) {
        $class = ($currentAction === $action) ? 'select' : '';
        $menu .= '<a href="index.php?action=' . $action . '" class="' . $class . '">' . htmlspecialchars($label) . '</a>';
    }

    $submenu = '';
    if ($currentAction === 'view') {
        $sortItems = [
            'id'      => 'По порядку добавления',
            'surname' => 'По фамилии',
            'date'    => 'По дате рождения',
        ];
        $submenu .= '<div class="submenu">';
        foreach ($sortItems as $sort => $label) {
            $class = ($currentSort === $sort) ? 'select' : '';
            $submenu .= '<a href="index.php?action=view&sort=' . $sort . '" class="' . $class . '">' . htmlspecialchars($label) . '</a>';
        }
        $submenu .= '</div>';
    }

    return ['menu' => $menu, 'submenu' => $submenu];
}
