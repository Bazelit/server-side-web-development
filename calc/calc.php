<?php
/**
 * Бэкенд калькулятора
 * Принимает POST-параметр "expr", вычисляет выражение,
 * возвращает JSON с результатом (как если бы это был GET-параметр).
 *
 * Вычисление — рекурсивный парсер без eval().
 * Поддерживает: + - * / ( )
 */

header('Content-Type: application/json; charset=utf-8');

// ───────────────────────────────────────────────
// 1. Получаем выражение из POST
// ───────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Ожидается POST-запрос']);
    exit;
}

$expr = trim($_POST['expr'] ?? '');

if ($expr === '') {
    echo json_encode(['error' => 'Выражение не задано']);
    exit;
}

// ───────────────────────────────────────────────
// 2. Валидация — только допустимые символы
// ───────────────────────────────────────────────
function validate(string $expr): bool {
    // Разрешены: цифры, . + - * / ( ) пробел
    if (!preg_match('/^[0-9+\-*\/().\s]+$/', $expr)) {
        return false;
    }

    // Проверка баланса скобок
    $depth = 0;
    for ($i = 0; $i < strlen($expr); $i++) {
        if ($expr[$i] === '(') $depth++;
        if ($expr[$i] === ')') $depth--;
        if ($depth < 0) return false;
    }

    return $depth === 0;
}

if (!validate($expr)) {
    echo json_encode(['error' => 'Недопустимые символы или скобки']);
    exit;
}

// ───────────────────────────────────────────────
// 3. Пользовательские функции для операций
// ───────────────────────────────────────────────
function add(float $a, float $b): float { return $a + $b; }
function sub(float $a, float $b): float { return $a - $b; }
function mul(float $a, float $b): float { return $a * $b; }
function div(float $a, float $b): float {
    if ($b == 0) throw new Exception('Деление на ноль');
    return $a / $b;
}

// ───────────────────────────────────────────────
// 4. Рекурсивный парсер (Recursive Descent)
//
//  Грамматика:
//    expr   → term   { ('+' | '-') term }
//    term   → factor { ('*' | '/') factor }
//    factor → '-' factor | '(' expr ')' | number
// ───────────────────────────────────────────────

// Глобальная позиция в строке
$pos = 0;
$input = '';

function skipSpaces(): void {
    global $pos, $input;
    while ($pos < strlen($input) && $input[$pos] === ' ') $pos++;
}

// expr → term { ('+' | '-') term }
function parseExpr(): float {
    global $pos, $input;

    $result = parseTerm();

    while ($pos < strlen($input)) {
        skipSpaces();
        $ch = $input[$pos] ?? '';

        if ($ch === '+') {
            $pos++;
            $result = add($result, parseTerm());
        } elseif ($ch === '-') {
            $pos++;
            $result = sub($result, parseTerm());
        } else {
            break;
        }
    }

    return $result;
}

// term → factor { ('*' | '/') factor }
function parseTerm(): float {
    global $pos, $input;

    $result = parseFactor();

    while ($pos < strlen($input)) {
        skipSpaces();
        $ch = $input[$pos] ?? '';

        if ($ch === '*') {
            $pos++;
            $result = mul($result, parseFactor());
        } elseif ($ch === '/') {
            $pos++;
            $result = div($result, parseFactor());
        } else {
            break;
        }
    }

    return $result;
}

// factor → '-' factor | '(' expr ')' | number
function parseFactor(): float {
    global $pos, $input;
    skipSpaces();

    $ch = $input[$pos] ?? '';

    // Унарный минус
    if ($ch === '-') {
        $pos++;
        return sub(0, parseFactor());
    }

    // Скобки
    if ($ch === '(') {
        $pos++; // пропускаем '('
        $val = parseExpr();
        skipSpaces();
        if (($input[$pos] ?? '') !== ')') {
            throw new Exception('Ожидалась закрывающая скобка');
        }
        $pos++; // пропускаем ')'
        return $val;
    }

    // Число (целое или дробное)
    if (ctype_digit($ch) || $ch === '.') {
        $num = '';
        while ($pos < strlen($input) && (ctype_digit($input[$pos]) || $input[$pos] === '.')) {
            $num .= $input[$pos++];
        }
        return (float)$num;
    }

    throw new Exception("Неожиданный символ: '$ch'");
}

// ───────────────────────────────────────────────
// 5. Вычисляем и возвращаем результат
// ───────────────────────────────────────────────
try {
    $input = $expr;
    $pos   = 0;

    $result = parseExpr();

    // Форматируем: убираем лишние нули у дробей
    if (floor($result) == $result && abs($result) < 1e15) {
        $formatted = number_format($result, 0, '.', '');
    } else {
        $formatted = rtrim(rtrim(number_format($result, 8, '.', ''), '0'), '.');
    }

    // Возвращаем результат — фронтенд поместит его в GET-параметр
    echo json_encode(['result' => $formatted]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
