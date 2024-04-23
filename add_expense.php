<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addExpense"])) {
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

    // Get expense details from the form
    $amount = $_POST["amount"];
    $currency = $_POST["currency"];
    $category = $_POST["category"];
    $date = $_POST["date"];
    $location = $_POST["location"];
    $description = $_POST["description"];
    $receipt_path = "";

    // Handle image upload if a file is selected
    if (!empty($_FILES["receipt"]["name"])) {
        $targetDir = "uploads/"; // Directory where images will be stored
        $targetFile = $targetDir . basename($_FILES["receipt"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check file size
        if ($_FILES["receipt"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // If everything is ok, try to upload file
            if (move_uploaded_file($_FILES["receipt"]["tmp_name"], $targetFile)) {
                $receipt_path = $targetFile;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Convert amount based on currency
    if ($currency == "LBP") {
        $amount /= 90000; // Convert LBP to USD
        $currency = "USD";
    } elseif ($currency == "EUR") {
        $amount *= 0.94; // Convert EUR to USD
        $currency = "USD";
    }

    // Insert expense into database
    $sql = "INSERT INTO expenses (username, amount, currency, category, date, location, description, receipt_path) 
            VALUES ('{$_SESSION['username']}', '$amount', '$currency', '$category', '$date', '$location', '$description', '$receipt_path')";

    if ($conn->query($sql) === TRUE) {
        // Update total_expenses field in users table
        $updateTotalExpensesSQL = "UPDATE users SET total_expenses = total_expenses + $amount WHERE username = '{$_SESSION['username']}'";
        if ($conn->query($updateTotalExpensesSQL) === TRUE) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error updating total expenses: " . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EX - Add Expense</title>
    <link rel="stylesheet" href="add_expense.css">
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
        <section>
            <div class="container">
                <h2>Add Expense</h2>
                <form action="add_expense.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" id="amount" name="amount" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="currency">Currency:</label>
                        <select id="currency" name="currency" required>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <option value="LBP">LBP</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="category">Category:</label>
                        <select id="category" name="category" required>
                            <option value="Food">Food</option>
                            <option value="Transportation">Transportation</option>
                            <option value="Entertainment">Entertainment</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group" id="otherCategoryGroup" style="display: none;">
                        <label for="otherCategory">Other Category:</label>
                        <input type="text" id="otherCategory" name="customCategory">
                    </div>
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location (optional):</label>
                        <input type="text" id="location" name="location">
                    </div>
                    <div class="form-group">
                        <label for="description">Description (optional):</label>
                        <textarea id="description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="receipt">Receipt Image (optional):</label>
                        <input type="file" id="receipt" name="receipt">
                    </div>
                    <button type="submit" name="addExpense" class="button">Add Expense</button>
                </form>
            </div>
        </section>
    </main>
    <script src="add_expense.js"></script>
</body>
</html>
