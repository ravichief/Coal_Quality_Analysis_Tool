// Get the form element
    const form = document.getElementById('coalQualityForm');
    // Add event listener for form submit
    form.addEventListener('submit', function(event) {

        // Retrieve the input values
        const coalSampleName = document.getElementById('coalSampleName').value;
        const moistureContent = document.getElementById('moistureContent').value;
        const calorificValue = document.getElementById('calorificValue').value;
        const ashContent = document.getElementById('ashContent').value;

        // Update the result values
        document.getElementById('resultMoisture').textContent = moistureContent;
        document.getElementById('resultCalorific').textContent = calorificValue;
        document.getElementById('resultAsh').textContent = ashContent;
        form.reset();
        });