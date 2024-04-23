<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database credentials
$host = "localhost";
$username = "id22053998_database_ex";
$password = "Qazplm.1104";
$dbname = "id22053998_expensewise";

// PDO options
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    // Create a new PDO instance without dbname to check if database exists
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password, $options);

    // Create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $pdo->exec("USE `$dbname`");

    // Create the reports table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS reports (
        id INT AUTO_INCREMENT PRIMARY KEY,
        date_range VARCHAR(50),
        categories TEXT,
        visualization_type VARCHAR(50),
        data TEXT
    )");

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
function insertReportData($pdo, $dateRange, $categories, $visualizationType, $data) {
    $sql = "INSERT INTO reports (date_range, categories, visualization_type, data) VALUES (:dateRange, :categories, :visualizationType, :data)";
    $stmt = $pdo->prepare($sql);
    
    // Convert categories array to JSON string for storage
    $categoriesJson = json_encode($categories);
    
    $stmt->bindParam(':dateRange', $dateRange);
    $stmt->bindParam(':categories', $categoriesJson);
    $stmt->bindParam(':visualizationType', $visualizationType);
    $stmt->bindParam(':data', $data);
    
    $stmt->execute();
    return $pdo->lastInsertId(); // Return the ID of the inserted record
}

// Check if the server request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect input data from the POST request
    $dateRange = filter_var($_POST['dateRange'], FILTER_SANITIZE_STRING);
    $categories = $_POST['categories']; // Assuming this is an array
    $visualizationType = filter_var($_POST['visualizationType'], FILTER_SANITIZE_STRING);
    $data = $_POST['data']; // Assuming this is a string of data

    // Insert report data into the database
    $reportId = insertReportData($pdo, $dateRange, $categories, $visualizationType, $data);

    // Return the ID of the newly inserted report
    echo json_encode(['success' => 'Report added successfully.', 'reportId' => $reportId]);
} /*else {
    // Handle incorrect access method
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method Not Allowed. Please use POST.']);
}*/
?>

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customizable Reports</title>
  <link rel="stylesheet" href="grp_sika.css">
</head>

<body>
  <div class="report-container">
    <h1>Generate Report</h1>
    <form id="reportForm">
      <label for="dateRange">Date Range:</label>
      <select id="dateRange">
        <option value="lastMonth">Last Week</option>
        <option value="lastMonth">Last Month</option>
        <option value="yearToDate">Last Year</option>
      </select>

      <label for="categoryFilter">Category Filter:</label>
      <select id="categoryFilter" multiple onchange="toggleOtherCategoryField()">
        <option value="food">Food</option>
        <option value="transport">Transportation</option>
        <option value="entertainment">Entertainment</option>
        <option value="others">Others</option>
      </select>

      <!-- Text input for specifying the 'Others' category -->
      <div id="otherCategoryInput" style="display: none;">
        <label for="otherCategory">Specify Other Category:</label>
        <input type="text" id="otherCategory">
      </div>

      <label for="visualization">Visualization Type:</label>
      <select id="visualization">
        <option value="pieChart">Pie Chart</option>
        <option value="barGraph">Bar Graph</option>
      </select>

      <button type="button" onclick="generateReport()">Generate Report</button>
    </form>
    <div id="reportOutput"></div>
    </main>
  </div><script src="grp_sika.js"></script>
 </body>
</html>
