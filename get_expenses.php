<?php
// Database connection
$servername = "localhost";
$username = "id22053998_database_ex";
$password = "Qazplm.1104";
$database = "id22053998_expensewise";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

// Fetch expenses from the database
$sql = "SELECT id, date, description, amount FROM expenses";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $expenses = array();
    while($row = $result->fetch_assoc()) {
        $expenses[] = $row;
    }
    echo json_encode(array("expenses" => $expenses));
} else {
    echo json_encode(array("expenses" => []));
}

$conn->close();
?>

