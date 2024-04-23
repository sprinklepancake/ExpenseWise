<?php
// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if form fields are set
    if (isset($_POST['budget'])) {
        // Decode JSON data sent from the frontend
        $budgetData = json_decode($_POST['budget'], true);

        // Validate and save budget data (dummy implementation)
        saveBudget($budgetData);

        // Redirect back to the form page or display a success message
        header("Location: dashboard.php");
        exit();
    } else {
        // Handle case where form fields are missing
        echo "Error: Budget data is missing.";
    }
} else {
    // Handle case where request method is not POST
    echo "Error: Invalid request method.";
}

// Function to save budget data (dummy implementation)
function saveBudget($data) {
    // Save budget data to a file or database (dummy implementation)
    $filename = 'budget_data.json';
    file_put_contents($filename, json_encode($data));
    echo "Budget data saved successfully.";
}
?>
