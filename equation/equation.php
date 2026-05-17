<?php
/**
 * Решатель линейных уравнений вида: a OP x = b | x OP a = b
 * Теперь с веб-интерфейсом
 */

// Функция решения уравнения
function solveEquation($equation) {
    // Нормализация строки
    $equation = strtoupper(str_replace(' ', '', $equation));
    
    // Проверка наличия знака '='
    if (!str_contains($equation, '=')) {
        return ['error' => 'Отсутствует знак "=" в уравнении.'];
    }
    
    [$leftPart, $rightPart] = explode('=', $equation, 2);
    
    // Определение оператора
    $operators = ['+', '-', '*', '/'];
    $operator = null;
    
    foreach ($operators as $op) {
        if (str_contains($leftPart, $op)) {
            $operator = $op;
            break;
        }
    }
    
    if ($operator === null) {
        return ['error' => 'Оператор (+, -, *, /) не найден в левой части.'];
    }
    
    // Разбиение на операнды
    $operands = explode($operator, $leftPart, 2);
    $operand1 = trim($operands[0]);
    $operand2 = trim($operands[1]);
    
    // Определение расположения переменной X
    $varPos = null;
    
    if ($operand1 === 'X') {
        $varPos = 'LEFT';
    } elseif ($operand2 === 'X') {
        $varPos = 'RIGHT';
    } else {
        return ['error' => 'Переменная X не найдена в левой части уравнения.'];
    }
    
    // Извлечение числовых значений
    $knownOperand = ($varPos === 'LEFT') ? (float)$operand2 : (float)$operand1;
    $result = (float)$rightPart;
    
    // Вычисление X
    $x = null;
    
    try {
        if ($varPos === 'LEFT') {
            // X OP a = b
            switch ($operator) {
                case '+': $x = $result - $knownOperand; break;
                case '-': $x = $result + $knownOperand; break;
                case '*': 
                    if ($knownOperand == 0) throw new Exception('Деление на ноль.');
                    $x = $result / $knownOperand; break;
                case '/': $x = $result * $knownOperand; break;
            }
        } else {
            // a OP X = b
            switch ($operator) {
                case '+': $x = $result - $knownOperand; break;
                case '-': $x = $knownOperand - $result; break;
                case '*': 
                    if ($knownOperand == 0) throw new Exception('Деление на ноль.');
                    $x = $result / $knownOperand; break;
                case '/': 
                    if ($result == 0) throw new Exception('Деление на ноль.');
                    $x = $knownOperand / $result; break;
            }
        }
        
        // Проверка
        $check = null;
        switch ($operator) {
            case '+': $check = ($varPos === 'LEFT') ? $x + $knownOperand : $knownOperand + $x; break;
            case '-': $check = ($varPos === 'LEFT') ? $x - $knownOperand : $knownOperand - $x; break;
            case '*': $check = ($varPos === 'LEFT') ? $x * $knownOperand : $knownOperand * $x; break;
            case '/': $check = ($varPos === 'LEFT') ? $x / $knownOperand : $knownOperand / $x; break;
        }
        
        return [
            'equation' => $equation,
            'leftPart' => $leftPart,
            'rightPart' => $rightPart,
            'operator' => $operator,
            'operand1' => $operand1,
            'operand2' => $operand2,
            'varPos' => $varPos,
            'knownOperand' => $knownOperand,
            'result' => $result,
            'x' => $x,
            'check' => $check,
            'verified' => abs($check - $result) < 1e-9
        ];
        
    } catch (Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

// Обработка AJAX-запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    header('Content-Type: application/json');
    $equation = $_POST['equation'] ?? '';
    $result = solveEquation($equation);
    echo json_encode($result);
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Решатель уравнений | X решатель</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 600px;
            width: 100%;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .content {
            padding: 30px;
        }

        .input-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .equation-input {
            width: 100%;
            padding: 15px;
            font-size: 20px;
            font-family: 'Courier New', monospace;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            transition: all 0.3s;
            text-align: center;
        }

        .equation-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .examples {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .example-btn {
            background: #f0f0f0;
            border: none;
            padding: 6px 12px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 12px;
            font-family: monospace;
            transition: all 0.3s;
        }

        .example-btn:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        .solve-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .solve-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .solve-btn:active {
            transform: translateY(0);
        }

        .result {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            display: none;
        }

        .result.show {
            display: block;
            animation: slideDown 0.4s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .result-header {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }

        .answer {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 20px;
        }

        .answer h3 {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .answer .x-value {
            font-size: 36px;
            font-weight: bold;
            font-family: monospace;
        }

        .details {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
            font-size: 14px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
        }

        .detail-value {
            font-family: monospace;
            color: #333;
        }

        .check {
            margin-top: 15px;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
        }

        .check.valid {
            background: #d4edda;
            color: #155724;
        }

        .check.invalid {
            background: #f8d7da;
            color: #721c24;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #dc3545;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #667eea;
        }

        .footer {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        @media (max-width: 480px) {
            .content {
                padding: 20px;
            }
            
            .answer .x-value {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📐 Решатель уравнений</h1>
            <p>Линейные уравнения вида a OP x = b или x OP a = b</p>
        </div>
        
        <div class="content">
            <div class="input-group">
                <label>📝 Введите уравнение:</label>
                <input type="text" id="equation" class="equation-input" placeholder="6/X=2" value="6/X=2">
                <div class="examples">
                    <button class="example-btn" data-equation="6/X=2">6/X=2</button>
                    <button class="example-btn" data-equation="X+5=12">X+5=12</button>
                    <button class="example-btn" data-equation="X-3=7">X-3=7</button>
                    <button class="example-btn" data-equation="4*X=20">4*X=20</button>
                    <button class="example-btn" data-equation="X/4=3">X/4=3</button>
                    <button class="example-btn" data-equation="15-X=8">15-X=8</button>
                </div>
            </div>
            
            <button class="solve-btn" onclick="solve()">🚀 Решить уравнение</button>
            
            <div id="result" class="result"></div>
        </div>
        
        <div class="footer">
            ✨ Поддерживаются операции: + , - , * , / | Переменная X в любом регистре
        </div>
    </div>

    <script>
        async function solve() {
            const equation = document.getElementById('equation').value;
            const resultDiv = document.getElementById('result');
            
            if (!equation.trim()) {
                showError('Пожалуйста, введите уравнение');
                return;
            }
            
            resultDiv.innerHTML = '<div class="loading">⏳ Решаем уравнение...</div>';
            resultDiv.classList.add('show');
            
            try {
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'equation=' + encodeURIComponent(equation)
                });
                
                const data = await response.json();
                
                if (data.error) {
                    showError(data.error);
                } else {
                    showResult(data);
                }
            } catch (error) {
                showError('Ошибка соединения. Попробуйте ещё раз.');
            }
        }
        
        function showError(message) {
            const resultDiv = document.getElementById('result');
            resultDiv.innerHTML = `
                <div class="error">
                    <strong>⚠️ Ошибка</strong><br>
                    ${message}
                </div>
            `;
        }
        
        function showResult(data) {
            const resultDiv = document.getElementById('result');
            
            const operatorSymbols = {
                '+': '+',
                '-': '−',
                '*': '×',
                '/': '÷'
            };
            
            const varPosText = data.varPos === 'LEFT' ? 'X в левой части' : 'X в правой части';
            
            resultDiv.innerHTML = `
                <div class="result-header">
                    📊 Решение уравнения: ${data.equation}
                </div>
                
                <div class="answer">
                    <h3>✅ Ответ</h3>
                    <div class="x-value">X = ${data.x}</div>
                </div>
                
                <div class="details">
                    <div class="detail-row">
                        <span class="detail-label">Левая часть:</span>
                        <span class="detail-value">${data.leftPart}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Правая часть:</span>
                        <span class="detail-value">${data.rightPart}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Оператор:</span>
                        <span class="detail-value">${data.operator} (${operatorSymbols[data.operator]})</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Операнд 1:</span>
                        <span class="detail-value">${data.operand1}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Операнд 2:</span>
                        <span class="detail-value">${data.operand2}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Расположение X:</span>
                        <span class="detail-value">${varPosText}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Известный операнд:</span>
                        <span class="detail-value">${data.knownOperand}</span>
                    </div>
                </div>
                
                <div class="check ${data.verified ? 'valid' : 'invalid'}">
                    ${data.verified ? '✓ Проверка пройдена: ' : '✗ Проверка не пройдена: '}
                    ${data.leftPart} = ${data.check} (ожидалось ${data.result})
                </div>
            `;
        }
        
        // Обработка примеров
        document.querySelectorAll('.example-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const equation = btn.dataset.equation;
                document.getElementById('equation').value = equation;
                solve();
            });
        });
        
        // Решение по Enter
        document.getElementById('equation').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                solve();
            }
        });
        
        // Автоматическое решение при загрузке
        window.addEventListener('load', () => {
            solve();
        });
    </script>
</body>
</html>