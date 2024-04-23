<?php
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

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Fetch report data from the database
        $dateRange = $_POST['dateRange'];
        $categories = isset($_POST['categories']) ? $_POST['categories'] : array();
        $visualizationType = $_POST['visualizationType'];

        // Prepare the SQL query with dynamic placeholders for categories
        $placeholders = str_repeat('?,', count($categories) - 1) . '?';
        $sql = "SELECT * FROM expenses WHERE date >= ? AND category IN ($placeholders)";
        $stmt = $conn->prepare($sql);

        // Create array of types for bind_param
        $types = str_repeat('s', count($categories) + 1);

        // Bind parameters
        $bindParams = array_merge([$types, $dateRange], $categories);
        call_user_func_array(array($stmt, 'bind_param'), $bindParams);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch data and format as JSON
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Close the statement
        $stmt->close();

        // Return data as JSON response
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (Exception $e) {
        // Return error as JSON response
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    // Return error if request method is not POST
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method Not Allowed. Please use POST.']);
}

// Close the database connection
$conn->close();
?>
