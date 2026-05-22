<?php
/**
 * Бэкенд калькулятора — расширенная версия (все бонусные задания)
 *
 * Поддерживает:
 *  Базовые операции:  + - * / ( )
 *  Степень:           ^ (правая ассоциативность)
 *  Корень:            sqrt(x)
 *  Логарифмы:        ln(x), log(x) — натуральный и десятичный
 *  Факториал:        fact(x)
 *  Константы:        pi, e
 *  Унарный минус:    -x, -(...)
 *  Дробные числа:    3.14
 *
 * Вычисление — рекурсивный парсер (Recursive Descent) без eval().
 */

header('Content-Type: application/json; charset=utf-8');

// ────────────────────────────────────────────────────────
// 0. Подключаем модуль тригонометрических функций
// ────────────────────────────────────────────────────────
require_once __DIR__ . '/trig_functions.php';

// ────────────────────────────────────────────────────────
// 1. Получаем выражение: из POST, либо из expression.txt
// ────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Ожидается POST-запрос']);
    exit;
}

// Если expr не передан — читаем из файла Task/expression.txt
$expr = trim($_POST['expr'] ?? '');

if ($expr === '') {
    $exprFile = __DIR__ . '/expression.txt';
    if (file_exists($exprFile)) {
        $expr = trim(file_get_contents($exprFile));
    }
};

if ($expr === '') {
    echo json_encode(['error' => 'Выражение не задано']);
    exit;
}

// ────────────────────────────────────────────────────────
// 2. Валидация — только допустимые символы и идентификаторы
// ────────────────────────────────────────────────────────
function validate(string $expr): bool {
    // Разрешены: цифры, . + - * / ^ ( ) пробелы и латинские буквы (для функций/констант)
    if (!preg_match('/^[0-9+\-*\/^().a-z\s]+$/i', $expr)) {
        return false;
    }

    // Разрешённые идентификаторы (включая тригонометрические функции)
    $allowed_ids = [
        'sqrt', 'ln', 'log', 'fact', 'pi', 'e',
        'sin', 'cos', 'tan', 'cot', 'asin', 'acos', 'atan'
    ];

    // Проверяем все слова в выражении — только из разрешённого списка
    $words = [];
    preg_match_all('/[a-z]+/i', $expr, $words);
    foreach ($words[0] as $word) {
        if (!in_array(strtolower($word), $allowed_ids)) {
            return false;
        }
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
    echo json_encode(['error' => 'Недопустимые символы или выражение']);
    exit;
}

// ────────────────────────────────────────────────────────
// 3. Пользовательские функции для операций
// ────────────────────────────────────────────────────────
function op_add(float $a, float $b): float { return $a + $b; }
function op_sub(float $a, float $b): float { return $a - $b; }
function op_mul(float $a, float $b): float { return $a * $b; }
function op_div(float $a, float $b): float {
    if ($b == 0) throw new Exception('Деление на ноль');
    return $a / $b;
}
function op_pow(float $a, float $b): float { return pow($a, $b); }

function fn_sqrt(float $x): float {
    if ($x < 0) throw new Exception('Корень из отрицательного числа');
    return sqrt($x);
}
function fn_ln(float $x): float {
    if ($x <= 0) throw new Exception('ln определён только для x > 0');
    return log($x);
}
function fn_log(float $x): float {
    if ($x <= 0) throw new Exception('log определён только для x > 0');
    return log10($x);
}
function fn_fact(float $x): float {
    $n = (int) round($x);
    if ($n < 0)   throw new Exception('Факториал не определён для отрицательных чисел');
    if ($n > 170) throw new Exception('Число слишком велико для факториала');
    $result = 1.0;
    for ($i = 2; $i <= $n; $i++) $result = op_mul($result, (float)$i);
    return $result;
}

// ────────────────────────────────────────────────────────
// 4. Рекурсивный парсер (Recursive Descent)
//
//  Грамматика:
//    expr   → term   { ('+' | '-') term }
//    term   → power  { ('*' | '/') power }
//    power  → unary  [ '^' power ]          ← правая ассоциативность
//    unary  → '-' unary | primary
//    primary → number | constant | function '(' expr ')' | '(' expr ')'
//    number  → [0-9]+ ('.' [0-9]+)?
//    constant → 'pi' | 'e'
//    function → 'sqrt' | 'ln' | 'log' | 'fact'
// ────────────────────────────────────────────────────────

$pos   = 0;
$input = '';

function skipSpaces(): void {
    global $pos, $input;
    while ($pos < strlen($input) && $input[$pos] === ' ') $pos++;
}

function peek(): string {
    global $pos, $input;
    skipSpaces();
    return $input[$pos] ?? '';
}

// expr → term { ('+' | '-') term }
function parseExpr(): float {
    global $pos, $input;
    $result = parseTerm();
    while (true) {
        $ch = peek();
        if ($ch === '+') { $pos++; $result = op_add($result, parseTerm()); }
        elseif ($ch === '-') { $pos++; $result = op_sub($result, parseTerm()); }
        else break;
    }
    return $result;
}

// term → power { ('*' | '/') power }
function parseTerm(): float {
    global $pos;
    $result = parsePower();
    while (true) {
        $ch = peek();
        if ($ch === '*') { $pos++; $result = op_mul($result, parsePower()); }
        elseif ($ch === '/') { $pos++; $result = op_div($result, parsePower()); }
        else break;
    }
    return $result;
}

// power → unary [ '^' power ]  (правая ассоциативность)
function parsePower(): float {
    global $pos;
    $base = parseUnary();
    if (peek() === '^') {
        $pos++;
        $exp = parsePower();   // рекурсия для правой ассоциативности
        return op_pow($base, $exp);
    }
    return $base;
}

// unary → '-' unary | primary
function parseUnary(): float {
    global $pos;
    if (peek() === '-') {
        $pos++;
        return op_sub(0.0, parseUnary());
    }
    return parsePrimary();
}

// Читает буквенный идентификатор (функцию или константу)
function readIdent(): string {
    global $pos, $input;
    skipSpaces();
    $id = '';
    while ($pos < strlen($input) && ctype_alpha($input[$pos])) {
        $id .= $input[$pos++];
    }
    return strtolower($id);
}

// primary → function '(' expr ')' | constant | '(' expr ')' | number
function parsePrimary(): float {
    global $pos, $input;
    skipSpaces();
    $ch = $input[$pos] ?? '';

    // Буква — функция или константа
    if (ctype_alpha($ch)) {
        $savedPos = $pos;
        $id = readIdent();

        // Константы
        if ($id === 'pi') return M_PI;
        if ($id === 'e')  return M_E;

        // Функции ожидают '('
        $trig = ['sin', 'cos', 'tan', 'cot', 'asin', 'acos', 'atan'];
        $known = array_merge(['sqrt', 'ln', 'log', 'fact'], $trig);
        if (!in_array($id, $known)) {
            throw new Exception("Неизвестный идентификатор: '$id'");
        }
        skipSpaces();
        if (($input[$pos] ?? '') !== '(') {
            throw new Exception("После '$id' ожидалась '('");
        }
        $pos++; // пропускаем '('
        $arg = parseExpr();
        skipSpaces();
        if (($input[$pos] ?? '') !== ')') {
            throw new Exception("Ожидалась ')' после аргумента '$id'");
        }
        $pos++; // пропускаем ')'

        switch ($id) {
            case 'sqrt': return fn_sqrt($arg);
            case 'ln':   return fn_ln($arg);
            case 'log':  return fn_log($arg);
            case 'fact': return fn_fact($arg);
            // Тригонометрические функции — вызов через calcTrig() (символическая ссылка)
            case 'sin': case 'cos': case 'tan': case 'cot':
            case 'asin': case 'acos': case 'atan':
                return calcTrig($id, $arg);
        }
    }

    // Скобки
    if ($ch === '(') {
        $pos++;
        $val = parseExpr();
        skipSpaces();
        if (($input[$pos] ?? '') !== ')') {
            throw new Exception('Ожидалась закрывающая скобка');
        }
        $pos++;
        return $val;
    }

    // Число
    if (ctype_digit($ch) || $ch === '.') {
        $num = '';
        $dots = 0;
        while ($pos < strlen($input) && (ctype_digit($input[$pos]) || $input[$pos] === '.')) {
            if ($input[$pos] === '.') {
                $dots++;
                if ($dots > 1) throw new Exception('Неверный формат числа');
            }
            $num .= $input[$pos++];
        }
        return (float)$num;
    }

    throw new Exception("Неожиданный символ: '$ch'");
}

// ────────────────────────────────────────────────────────
// 5. Вычисляем и возвращаем результат
// ────────────────────────────────────────────────────────
try {
    $input = $expr;
    $pos   = 0;

    $result = parseExpr();

    skipSpaces();
    if ($pos < strlen($input)) {
        throw new Exception("Лишний символ: '{$input[$pos]}'");
    }

    if (is_nan($result) || is_infinite($result)) {
        throw new Exception('Результат не является числом');
    }

    // Форматируем: убираем лишние нули у дробей
    if (floor($result) == $result && abs($result) < 1e15) {
        $formatted = number_format($result, 0, '.', '');
    } else {
        $formatted = rtrim(rtrim(number_format($result, 10, '.', ''), '0'), '.');
    }

    // Возвращаем JSON — фронтенд обновит GET-параметр result
    echo json_encode(['result' => $formatted]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
