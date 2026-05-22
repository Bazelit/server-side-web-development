<?php
/**
 * trig_functions.php
 *
 * Тригонометрические функции для калькулятора.
 *
 * Функция calcTrig($name, $x) вычисляет результат через
 * символическую ссылку (variable function / call_user_func).
 *
 * Поддерживаемые функции:
 *   sin(x), cos(x), tan(x), cot(x)
 *   asin(x), acos(x), atan(x)
 *
 * Все углы задаются в ГРАДУСАХ.
 */

// ── Базовые тригонометрические функции (аргумент в градусах) ──────────

function fn_sin(float $x): float {
    return sin(deg2rad($x));
}

function fn_cos(float $x): float {
    return cos(deg2rad($x));
}

function fn_tan(float $x): float {
    // tan не определён при 90 + 180k градусах
    $cos = cos(deg2rad($x));
    if (abs($cos) < 1e-12) {
        throw new Exception('tan не определён при данном угле');
    }
    return tan(deg2rad($x));
}

function fn_cot(float $x): float {
    // cot(x) = cos(x) / sin(x)
    $sin = sin(deg2rad($x));
    if (abs($sin) < 1e-12) {
        throw new Exception('cot не определён при данном угле (sin = 0)');
    }
    return cos(deg2rad($x)) / $sin;
}

// ── Обратные тригонометрические функции (результат в градусах) ────────

function fn_asin(float $x): float {
    if ($x < -1 || $x > 1) {
        throw new Exception('asin: аргумент должен быть в диапазоне [-1, 1]');
    }
    return rad2deg(asin($x));
}

function fn_acos(float $x): float {
    if ($x < -1 || $x > 1) {
        throw new Exception('acos: аргумент должен быть в диапазоне [-1, 1]');
    }
    return rad2deg(acos($x));
}

function fn_atan(float $x): float {
    return rad2deg(atan($x));
}

// ── Символическая диспетчерская функция ──────────────────────────────
/**
 * calcTrig(string $name, float $x): float
 *
 * Принимает название тригонометрической функции и её аргумент.
 * Вызов выполняется через символическую ссылку (variable function):
 *   $func = 'fn_' . $name;
 *   return $func($x);
 *
 * @param  string $name  — имя функции: sin, cos, tan, cot, asin, acos, atan
 * @param  float  $x     — аргумент в градусах
 * @return float          — результат вычисления
 * @throws Exception      — при неизвестном имени или недопустимом аргументе
 */
function calcTrig(string $name, float $x): float {
    $allowed = ['sin', 'cos', 'tan', 'cot', 'asin', 'acos', 'atan'];

    $name = strtolower(trim($name));

    if (!in_array($name, $allowed, true)) {
        throw new Exception("Неизвестная тригонометрическая функция: '$name'");
    }

    // Символическая ссылка: имя функции формируется динамически
    $func = 'fn_' . $name;   // например: 'fn_sin', 'fn_cot'

    return $func($x);         // вызов через переменную-функцию (variable function)
}
