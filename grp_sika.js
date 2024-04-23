function generateReport() {
    const dateRange = document.getElementById('dateRange').value;
    const categories = Array.from(document.getElementById('categoryFilter').selectedOptions).map(option => option.value);
    const visualizationType = document.getElementById('visualization').value;
  
    // Create the request payload
    const requestData = {
      dateRange: dateRange,
      categories: categories,
      visualizationType: visualizationType
    };
  
    // Make a POST request to the backend PHP script
    fetch('reportGenerator.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(requestData)
    })
      .then(response => response.json())
      .then(data => {
        // Update the UI with the returned data
        document.getElementById('reportOutput').innerText = data.summary;
      })
      .catch(error => {
        console.error('Error:', error);
      });
       // Function to toggle the visibility of the 'otherCategoryInput' based on the selection
  function toggleOtherCategoryField() {
    var categorySelect = document.getElementById('categoryFilter');
    var otherCategoryInput = document.getElementById('otherCategoryInput');
    var isOthersSelected = Array.from(categorySelect.options)
      .filter(option => option.value === 'others' && option.selected).length > 0;

    // Show the input field if 'Others' is selected, otherwise hide it
    otherCategoryInput.style.display = isOthersSelected ? 'block' : 'none';
  }

  // Include the function for 'Generate Report' button
  function generateReport() {
    // Example function to handle the report generation
    console.log("Report generation logic goes here.");
  }
  }