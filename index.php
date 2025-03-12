<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize session variables if not set
if (!isset($_SESSION['display'])) $_SESSION['display'] = '0';
if (!isset($_SESSION['num1'])) $_SESSION['num1'] = '';
if (!isset($_SESSION['operator'])) $_SESSION['operator'] = '';
if (!isset($_SESSION['result'])) $_SESSION['result'] = ''; // For displaying result

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $display = $_SESSION['display'];
    $num1 = $_SESSION['num1'];
    $operator = $_SESSION['operator'];
    $result = $_SESSION['result'];

    // Handle digit input
    if (isset($_POST['digit'])) {
        $digit = $_POST['digit'];
        if ($display === '0' && $digit !== '.') {
            $display = $digit;
        } else {
            if ($digit === '.' && strpos($display, '.') !== false) {
                // Prevent multiple decimals
            } else {
                $display .= $digit;
            }
        }
        $result = ''; // Clear previous result on new input
    }

    // Handle operator input
    if (isset($_POST['operator'])) {
        if ($num1 === '') {
            $num1 = $display; // Store first number
            $operator = $_POST['operator'];
            $display = '0';
        } else if ($operator !== '' && $display !== '0') {
            // Chain calculations
            $calcResult = calculate($num1, $display, $operator);
            if (is_numeric($calcResult)) {
                $result = "$num1 " . getSymbol($operator) . " $display = " . number_format($calcResult, 2, '.', '');
                $num1 = $calcResult;
                $display = '0';
                $operator = $_POST['operator']; // Set new operator for chaining
            } else {
                $result = $calcResult;
                $display = 'Error';
                $num1 = '';
                $operator = '';
            }
        } else {
            $operator = $_POST['operator']; // Update operator if no calculation needed
        }
    }

    // Handle equals
    if (isset($_POST['equals'])) {
        $calcResult = calculate($num1, $display, $operator);
        $result = number_format($calcResult, 2, '.', '');
        $display = $result
        #if ($num1 !== '' && $operator !== '')
        #$calcResult = calculate($num1, $display, $operator);
        #if (is_numeric($calcResult)) {
        #    $display = "$num1 " . getSymbol($operator) . " $display = " . number_format($calcResult, 2, '.', '');
        #    $result = number_format($calcResult, 2, '.', '');
        #    $num1 = '';
        #    $operator = '';
        #} else {
        #    $result = $calcResult;
        #    $display = 'Error';
        #}
    }

    // Handle clear
    if (isset($_POST['clear'])) {
        $display = '0';
        $num1 = '';
        $operator = '';
        $result = '';
    }

    // Update session variables
    $_SESSION['display'] = $display;
    $_SESSION['num1'] = $num1;
    $_SESSION['operator'] = $operator;
    $_SESSION['result'] = $result;
}

function calculate($num1, $num2, $operator) {
    $num1 = floatval($num1);
    $num2 = floatval($num2);
    switch ($operator) {
        case 'add': return $num1 + $num2;
        case 'subtract': return $num1 - $num2;
        case 'multiply': return $num1 * $num2;
        case 'divide': return $num2 == 0 ? 'Error: Division by zero!' : $num1 / $num2;
        default: return 'Invalid operator!';
    }
}

function getSymbol($operator) {
    switch ($operator) {
        case 'add': return '+';
        case 'subtract': return '-';
        case 'multiply': return '×';
        case 'divide': return '÷';
        default: return '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced PHP Button Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 20px auto;
            text-align: center;
            background-color: #f5f5f5;
        }
        .calculator {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        button {
            padding: 15px;
            font-size: 1.2em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .number {
            background-color: #e0e0e0;
        }
        .number:hover {
            background-color: #d0d0d0;
        }
        .operator {
            background-color: #4CAF50;
            color: white;
        }
        .operator:hover {
            background-color: #45a049;
        }
        .equals {
            background-color: #2196F3;
            color: white;
        }
        .equals:hover {
            background-color: #1976D2;
        }
        .utility {
            background-color: #ff9800;
            color: white;
        }
        .utility:hover {
            background-color: #e68900;
        }
        .clear {
            background-color: #ff4444;
            color: white;
        }
        .clear:hover {
            background-color: #cc0000;
        }
        .display {
            grid-column: span 4;
            background-color: #333;
            color: white;
            padding: 20px;
            font-size: 2em;
            text-align: right;
            border-radius: 5px;
            margin-bottom: 10px;
            overflow-x: auto;
        }
        .result {
            margin-top: 20px;
            font-size: 1.5em;
            color: #333;
        }
    </style>
</head>
<body>
    <h2>Enhanced Calculator</h2>
    <form method="POST" action="">
        <div class="calculator">
            <div class="display"><?php echo htmlspecialchars($_SESSION['display']); ?></div>
            <button type="submit" name="digit" value="7" class="number">7</button>
            <button type="submit" name="digit" value="8" class="number">8</button>
            <button type="submit" name="digit" value="9" class="number">9</button>
            <button type="submit" name="operator" value="divide" class="operator">÷</button>
            <button type="submit" name="digit" value="4" class="number">4</button>
            <button type="submit" name="digit" value="5" class="number">5</button>
            <button type="submit" name="digit" value="6" class="number">6</button>
            <button type="submit" name="operator" value="multiply" class="operator">×</button>
            <button type="submit" name="digit" value="1" class="number">1</button>
            <button type="submit" name="digit" value="2" class="number">2</button>
            <button type="submit" name="digit" value="3" class="number">3</button>
            <button type="submit" name="operator" value="subtract" class="operator">-</button>
            <button type="submit" name="digit" value="0" class="number">0</button>
            <button type="submit" name="digit" value="." class="number">.</button>
            <button type="submit" name="backspace" value="back" class="utility">⌫</button>
            <button type="submit" name="operator" value="add" class="operator">+</button>
            <button type="submit" name="clear" value="clear" class="clear">C</button>
            <button type="submit" name="equals" value="equals" class="equals">=</button>
        </div>
    </form>
    <?php if (!empty($_SESSION['result'])): ?>
        <div class="result"><?php echo htmlspecialchars($_SESSION['result']); ?></div>
    <?php endif; ?>
</body>
</html>
