<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
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

// Fetch recurring payments for the user
$sql = "SELECT amount, currency, category, frequency FROM recurring_expenses WHERE username = '{$_SESSION['username']}'";
$result = $conn->query($sql);

// Check if there are any recurring payments
if ($result->num_rows > 0) {
    // Initialize an empty array to store the recurring payments
    $recurringPayments = array();

    // Fetch each recurring payment and add it to the array
    while ($row = $result->fetch_assoc()) {
        $recurringPayments[] = $row;
    }
} else {
    // No recurring payments found
    $recurringPayments = array();
}

// Fetch latest transactions for the user
$sql = "SELECT amount, currency, category, date FROM expenses WHERE username = '{$_SESSION['username']}' ORDER BY date DESC LIMIT 3";
$result = $conn->query($sql);

// Check if there are any transactions
if ($result->num_rows > 0) {
    // Initialize an empty array to store the transactions
    $latestTransactions = array();

    // Fetch each transaction and add it to the array
    while ($row = $result->fetch_assoc()) {
        $latestTransactions[] = $row;
    }
} else {
    // No transactions found
    $latestTransactions = array();
}

$sql = "SELECT budget, total_expenses FROM users WHERE username = '{$_SESSION['username']}'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $budget = $row['budget'];
    $totalExpenses = $row['total_expenses'];
} else {
    die("User not found");
}

$remainingBudget = $budget - $totalExpenses;
$remainingBudgetFormatted = number_format($remainingBudget, 2);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Wise Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Expense Wise</h1>
            <nav>
                <ul>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="#">Settings</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <section class="budget">
            <div class="container">
                <h2>Remaining Budget</h2>
                <p>$<?php echo $remainingBudgetFormatted; ?></p>
            </div>
        </section>
        <section class="recurring-payments">
            <div class="container">
                <h2>Recurring Payments</h2>
                <?php if (!empty($recurringPayments)) : ?>
                    <ul>
                        <?php foreach ($recurringPayments as $payment) : ?>
                            <li><?php echo $payment['amount']; ?> <?php echo $payment['currency']; ?> - <?php echo $payment['category']; ?> (<?php echo $payment['frequency']; ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <ul>No recurring payments</ul>
                <?php endif; ?>
            </div>
        </section>
        <section class="transactions">
            <div class="container">
                <h2>Recent Transactions</h2>
                <?php if (!empty($latestTransactions)) : ?>
                    <ul>
                        <?php foreach ($latestTransactions as $transaction) : ?>
                            <li><?php echo $transaction['amount']; ?> <?php echo $transaction['currency']; ?> - <?php echo $transaction['category']; ?> (<?php echo $transaction['date']; ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <ul>No recent transactions</ul>
                <?php endif; ?>
            </div>
        </section>
        <section class="statistics">
            <div class="container">
                <h2>Statistics</h2>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: <?php echo ($totalExpenses / $budget) * 100; ?>%" aria-valuenow="<?php echo ($totalExpenses / $budget) * 100; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p>Spent: $<?php echo number_format($totalExpenses, 2); ?> out of $<?php echo number_format($budget, 2); ?></p>

            </div>
        </section>
        <div class="buttons">
            <div class="container">
                <a href="add_expense.php" class="button">Add Expense</a>
                <a href="grp_yara.php" class="button">Add Goal</a>
                <a href="grp_malak.php" class="button">Add Recurring Payment</a>
                <a href="grp_sika.php" class="button">Generate Reports</a>
            </div>
        </div>
    </main>
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Expense Wise. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
