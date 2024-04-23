<?php
// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
$servername = "localhost";
$username = "id22053998_database_ex";
$password = "Qazplm.1104";
$database = "id22053998_expensewise";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["saveExpense"])) {
    // Retrieve form data
    $expenseName = $_POST["expenseName"];
    $category = $_POST["category"];
    $amount = $_POST["amount"];
    $currency = $_POST["currency"];
    $frequency = $_POST["frequency"];
    $startDate = $_POST["startDate"];
    $endDate = isset($_POST["endDate"]) ? $_POST["endDate"] : null;

    if ($currency == "LBP") {
        $amount /= 90000; // Convert LBP to USD
        $currency = "USD";
    } elseif ($currency == "EUR") {
        $amount *= 0.94; // Convert EUR to USD
        $currency = "USD";
    }

    // Get the username from the session
    $username = $_SESSION['username'];

    // Insert data into database
    $sql = "INSERT INTO recurring_expenses (expense_name, category, amount, currency, frequency, start_date, end_date, total_deduction, username) 
            VALUES ('$expenseName', '$category', '$amount', 'USD', '$frequency', '$startDate', ";

    if ($endDate) {
        $sql .= "'$endDate', ";
    } else {
        $sql .= "NULL, ";
    }

    $sql .= "'0', '$username')"; // Set total_deduction to 0 for now

    if ($conn->query($sql) === TRUE) {
        // Query executed successfully
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Manager</title>
    <link rel="stylesheet" href="grp_malak.css">
</head>

<body>
    <header>
        <div class="header-content">
            <h1 class="logo">Expense Manager</h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="#">Settings</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <section id="manageRecurringExpenses">
            <h2>Manage Recurring Expenses</h2>
            <div id="addExpenseForm">
                <form action="grp_malak.php" method="POST" enctype="multipart/form-data" onsubmit="return validateDatesAndSubmit(event)">
                    <input type="text" id="expenseName" name="expenseName" placeholder="Expense Name" required>
                    <select id="category" name="category" required onchange="toggleOtherCategoryInput()">
                        <option value="">Select Category</option>
                        <option value="Food">Food</option>
                        <option value="Transportation">Transportation</option>
                        <option value="Entertainment">Entertainment</option>
                        <option value="Others">Others</option>
                    </select>
                    <input type="text" id="otherCategory" name="otherCategory" placeholder="Specify Category" style="display: none;">
                    <input type="number" id="amount" name="amount" placeholder="Amount" step="0.01">
                    <select id="currency" name="currency" required>
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="LBP">LBP</option>
                    </select>
                    <select id="frequency" name="frequency" required>
                        <option value="">Frequency</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Monthly">Monthly</option>
                        <option value="Yearly">Yearly</option>
                    </select>
                    <label for="startDate">Start Date:</label>
                    <input type="date" id="startDate" name="startDate" required>
                    <div id="startDateError" class="error" style="display:none;"></div>
                    <label for="endDate">End Date :</label>
                    <input type="date" id="endDate" name="endDate" placeholder="End Date (optional)">
                    <div id="endDateError" class="error" style="display:none;"></div>
                    <button type="submit" name="saveExpense">Save Expense</button>
                </form>
            </div>
        </section>
    </main>
</body>

</html>
