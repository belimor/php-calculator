<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple PHP Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 50px auto;
            text-align: center;
        }
        input[type="number"], select {
            padding: 5px;
            margin: 5px;
            width: 100px;
        }
        input[type="submit"] {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .result {
            margin-top: 20px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <h2>Simple Calculator</h2>
    <form method="POST" action="">
        <input type="number" name="num1" placeholder="First Number" required step="any">
        <select name="operator">
            <option value="add">+</option>
            <option value="subtract">-</option>
            <option value="multiply">×</option>
            <option value="divide">÷</option>
        </select>
        <input type="number" name="num2" placeholder="Second Number" required step="any">
        <br>
        <input type="submit" value="Calculate">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $num1 = floatval($_POST["num1"]);
        $num2 = floatval($_POST["num2"]);
        $operator = $_POST["operator"];
        $result = "";

        switch ($operator) {
            case "add":
                $result = $num1 + $num2;
                $symbol = "+";
                break;
            case "subtract":
                $result = $num1 - $num2;
                $symbol = "-";
                break;
            case "multiply":
                $result = $num1 * $num2;
                $symbol = "×";
                break;
            case "divide":
                if ($num2 == 0) {
                    $result = "Error: Division by zero!";
                } else {
                    $result = $num1 / $num2;
                    $symbol = "÷";
                }
                break;
            default:
                $result = "Invalid operator!";
        }

        if (is_numeric($result)) {
            echo "<div class='result'>$num1 $symbol $num2 = " . number_format($result, 2) . "</div>";
        } else {
            echo "<div class='result'>$result</div>";
        }
    }
    ?>
</body>
</html>
