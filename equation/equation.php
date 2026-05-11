<?php
/**
 * Решатель линейных уравнений вида: a OP x = b | x OP a = b
 * Уравнение: 6 / X = 2
 */

// ─── 1. Входные данные ────────────────────────────────────────────────────────
$equation = "6/X=2";

echo "╔══════════════════════════════════════════════╗\n";
echo "║   РЕШЕНИЕ УРАВНЕНИЯ: $equation              ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

// ─── 2. Нормализация строки ───────────────────────────────────────────────────
$equation = strtoupper(str_replace(' ', '', $equation)); // убираем пробелы
echo "Нормализованное уравнение: $equation\n\n";

// ─── 3. Разбиение на левую и правую части ────────────────────────────────────
if (!str_contains($equation, '=')) {
    die("Ошибка: отсутствует знак '=' в уравнении.\n");
}

[$leftPart, $rightPart] = explode('=', $equation, 2);
echo "Левая часть:  $leftPart\n";
echo "Правая часть: $rightPart\n\n";

// ─── 4. Определение оператора ─────────────────────────────────────────────────
$operators = ['+', '-', '*', '/'];
$operator  = null;

foreach ($operators as $op) {
    if (str_contains($leftPart, $op)) {
        $operator = $op;
        break;
    }
}

if ($operator === null) {
    die("Ошибка: оператор (+, -, *, /) не найден в левой части.\n");
}

$opNames = ['+' => 'сложение', '-' => 'вычитание', '*' => 'умножение', '/' => 'деление'];
echo "Обнаруженный оператор: '$operator' ({$opNames[$operator]})\n\n";

// ─── 5. Разбиение на операнды ─────────────────────────────────────────────────
$operands = explode($operator, $leftPart, 2);
$operand1 = trim($operands[0]);
$operand2 = trim($operands[1]);

echo "Операнд 1: $operand1\n";
echo "Операнд 2: $operand2\n\n";

// ─── 6. Определение расположения переменной X ────────────────────────────────
$varPos = null;

if ($operand1 === 'X') {
    $varPos = 'LEFT';   // X стоит слева:  X OP a = b
} elseif ($operand2 === 'X') {
    $varPos = 'RIGHT';  // X стоит справа: a OP X = b
} else {
    die("Ошибка: переменная X не найдена в левой части уравнения.\n");
}

echo "Расположение переменной X: " . ($varPos === 'LEFT' ? "ЛЕВЫЙ операнд" : "ПРАВЫЙ операнд") . "\n\n";

// ─── 7. Извлечение числовых значений ─────────────────────────────────────────
$knownOperand = ($varPos === 'LEFT') ? (float)$operand2 : (float)$operand1;
$result       = (float)$rightPart;

echo "Известный операнд: $knownOperand\n";
echo "Правая часть (b):  $result\n\n";

// ─── 8. Вычисление X ──────────────────────────────────────────────────────────
$x = null;

if ($varPos === 'LEFT') {
    // X OP a = b  →  X = inverseOp(b, a)
    switch ($operator) {
        case '+': $x = $result - $knownOperand;  break; // X + a = b → X = b - a
        case '-': $x = $result + $knownOperand;  break; // X - a = b → X = b + a
        case '*': 
            if ($knownOperand == 0) die("Ошибка: деление на ноль при нахождении X.\n");
            $x = $result / $knownOperand;  break;        // X * a = b → X = b / a
        case '/': $x = $result * $knownOperand;  break; // X / a = b → X = b * a
    }
} else {
    // a OP X = b  →  X = inverseOp(b, a)
    switch ($operator) {
        case '+': $x = $result - $knownOperand;  break; // a + X = b → X = b - a
        case '-': $x = $knownOperand - $result;  break; // a - X = b → X = a - b
        case '*': 
            if ($knownOperand == 0) die("Ошибка: деление на ноль при нахождении X.\n");
            $x = $result / $knownOperand;  break;        // a * X = b → X = b / a
        case '/': 
            if ($result == 0) die("Ошибка: деление на ноль при нахождении X.\n");
            $x = $knownOperand / $result;  break;        // a / X = b → X = a / b
    }
}

// ─── 9. Вывод результата ──────────────────────────────────────────────────────
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "  ОТВЕТ: X = $x\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// ─── 10. Проверка ─────────────────────────────────────────────────────────────
$check = null;
switch ($operator) {
    case '+': $check = ($varPos === 'LEFT') ? $x + $knownOperand : $knownOperand + $x; break;
    case '-': $check = ($varPos === 'LEFT') ? $x - $knownOperand : $knownOperand - $x; break;
    case '*': $check = ($varPos === 'LEFT') ? $x * $knownOperand : $knownOperand * $x; break;
    case '/': $check = ($varPos === 'LEFT') ? $x / $knownOperand : $knownOperand / $x; break;
}

echo "Проверка: подставляем X = $x в уравнение $equation\n";
echo "  $leftPart = $check (ожидается $result)\n";
echo "  Проверка: " . (abs($check - $result) < 1e-9 ? "✓ ВЕРНО" : "✗ ОШИБКА") . "\n";
