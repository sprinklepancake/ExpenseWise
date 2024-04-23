<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);
$servername = "localhost";
$username = "id22053998_database_ex";
$password = "Qazplm.1104";

// Attempt to connect to the MySQL server
try {
    $pdo = new PDO("mysql:host=$servername;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS id22053998_expensewise CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    
    // Select the newly created database
    $pdo->exec("USE id22053998_expensewise");

    // Create 'users' table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        total_expenses DECIMAL(10, 2) NOT NULL,
        budget DECIMAL(10, 2) NOT NULL
    )");
    $pdo->exec("CREATE TABLE IF NOT EXISTS FinancialGoals (
    GoalID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    GoalType VARCHAR(255) NOT NULL, -- e.g., vacation, debt payoff
    TargetAmount DECIMAL(10,2) NOT NULL,
    TargetDate DATE NOT NULL,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES users(id)
)");

    

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user details from the form
    $formUsername = $_POST['username']; // Avoid variable name collision with `$username`
    $formPassword = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security
    $formEmail = $_POST['email'];
    $total_expenses = $_POST['total_expenses'];
    $budget = $_POST['budget'];

    // Prepare and execute insert statement
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, total_expenses, budget) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $password, $email, $total_expenses, $budget]);

    echo "User successfully added.<br>";
    echo "Username: " . $username . "<br>";
    echo "Email: " . $email . "<br>";
    echo "Total Expenses: $" . $total_expenses . "<br>";
    echo "Budget: $" . $budget;
}
?>


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goal Setting and Tracking</title>
    <link rel="stylesheet" href="grp_yara.css">
</head>
<body>
    <div class="container">
        <h1>Goal Setting and Tracking</h1>
        <div id="goal-form">
            <label for="goal-type">Goal Type:</label>
            <input type="text" id="goal-type">
            <label for="target-amount">Target Amount:</label>
            <input type="number" id="target-amount">
            <label for="target-date">Target Date:</label>
            <input type="date" id="target-date">
            <button onclick="createGoal()">Create Goal</button>
        </div>
        <div id="goal-details" style="display:none;">
            <h2>Goal Details</h2>
            <p id="goal-type-display"></p>
            <p id="target-amount-display"></p>
            <p id="target-date-display"></p>
            <p id="monthly-savings"></p>
        </div>
    </div>
    <script src="grp_yara.js"></script>
</body>
</html>