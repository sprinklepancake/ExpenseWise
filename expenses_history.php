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

// Fetch user information
$username = $_SESSION['username']; // Change variable name to username
$sql_user = "SELECT * FROM users WHERE username = '$username'"; // Use single quotes around username
$result_user = $conn->query($sql_user);
if ($result_user->num_rows > 0) {
    $user_info = $result_user->fetch_assoc();
} else {
    die("User not found");
}

// Fetch expenses for the current month
$currentMonth = date('Y-m');
$sql_expenses = "SELECT * FROM expenses WHERE username = '$username' AND DATE_FORMAT(date, '%Y-%m') = '$currentMonth'"; // Use single quotes around username
$result_expenses = $conn->query($sql_expenses);
$expenses = [];
if ($result_expenses->num_rows > 0) {
    while ($row = $result_expenses->fetch_assoc()) {
        $expenses[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses History</title>
    <link rel="stylesheet" href="expenses_history.css">
</head>
<body>
    <header>
        <h1>Expenses History</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="expenses-history">
            <h2>Expenses History for <?php echo date('F Y'); ?></h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Description</th>
                            <th>Receipt</th> <!-- Added column for receipt button -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expenses as $expense): ?>
                            <tr>
                                <td><?php echo $expense['date']; ?></td>
                                <td><?php echo $expense['amount']; ?></td>
                                <td><?php echo $expense['category']; ?></td>
                                <td><?php echo $expense['location']; ?></td>
                                <td><?php echo $expense['description']; ?></td>
                                <td>
                                    <?php if (!empty($expense['receipt_path'])): ?>
                                        <a href="<?php echo $expense['receipt_path']; ?>" target="_blank">View Receipt</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Expense Wise. All Rights Reserved.</p>
    </footer>
</body>
</html>
