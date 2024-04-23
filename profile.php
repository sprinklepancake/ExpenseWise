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
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT * FROM users WHERE id = $user_id";
$result_user = $conn->query($sql_user);
if ($result_user->num_rows > 0) {
    $user_info = $result_user->fetch_assoc();
} else {
    die("User not found");
}

// Handle increasing budget
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["increaseBudget"])) {
    $amount = $_POST["amount"];
    // Update budget in the database
    $sql_update_budget = "UPDATE users SET budget = budget + $amount WHERE id = $user_id";
    if ($conn->query($sql_update_budget) === TRUE) {
        // Redirect to profile page
        header("Location: profile.php");
        exit();
    } else {
        echo "Error updating budget: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo $user_info['username']; ?></h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="user-info">
            <h2>User Information</h2>
            <p>Username: <?php echo $user_info['username']; ?></p>
            <p>Email: <?php echo $user_info['email']; ?></p>
            <p>Budget: <?php echo $user_info['budget']; ?></p>
            <p>Total Expenses: <?php echo $user_info['total_expenses']; ?></p>
        </section>
        <section class="budget">
            <h2>Budget Overview</h2>
            <form action="profile.php" method="POST">
                <label for="amount">Increase Budget:</label>
                <input type="number" id="amount" name="amount" min="0" required>
                <button type="submit" name="increaseBudget">Increase Budget</button>
            </form>
        </section>
        <section class="expenses">
            <h2>Expenses Overview</h2>
            <div class="navigation">
                <a href="expenses_history.php" class="btn">View Expenses History</a>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Expense Wise. All Rights Reserved.</p>
    </footer>
</body>
</html>
