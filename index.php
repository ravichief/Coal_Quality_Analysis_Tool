<?php
$submitt = false;
$inputSampleName = "";
$inputMoistureContent = "";
$inputCalorificValue = "";
$inputAshContent = "";
$resultMoisture = "";
$resultCalorific = "";
$resultAsh = "";

if (isset($_POST['coalSampleName'])) {
     // Database configuration
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "coal_quality_analysis";
    $con = mysqli_connect($server, $username, $password, $database);
    if (!$con) {
        die("Connection failed due to " . mysqli_connect_error());
    }

    // Function to calculate the moisture content result
    function calculateMoistureResult($moistureContent)
    {
        if ($moistureContent < 10) {
            return 'Low';
        } else if ($moistureContent >= 10 && $moistureContent <= 20) {
            return 'Medium';
        } else {
            return 'High';
        }
    }

    // Function to calculate the calorific value result
    function calculateCalorificResult($calorificValue)
    {
        if ($calorificValue >= 5000 && $calorificValue <= 7000) {
            return 'Good';
        } else {
            return 'Poor';
        }
    }

    // Function to calculate the ash content result
    function calculateAshResult($ashContent)
    {
        if ($ashContent < 10) {
            return 'Low';
        } else if ($ashContent >= 10 && $ashContent <= 20) {
            return 'Medium';
        } else {
            return 'High';
        }
    }

    $sampleName = $_POST['coalSampleName'];
    $moistureContent = floatval($_POST['moistureContent']);
    $calorificValue = floatval($_POST['calorificValue']);
    $ashContent = floatval($_POST['ashContent']);

    // Insert input data into the "inputs" table
    $insertInputQuery = "INSERT INTO inputs (coal_sample_name, moisture_content, calorific_value, ash_content)
                         VALUES ('$sampleName', $moistureContent, $calorificValue, $ashContent)";

    if ($con->query($insertInputQuery) === true) {
        $submitt = true;
        $inputSampleName = $sampleName;
        $inputMoistureContent = $moistureContent;
        $inputCalorificValue = $calorificValue;
        $inputAshContent = $ashContent;
    } else {
        echo "ERROR: $insertInputQuery <br> " . $con->error;
    }

    // Perform the analysis and get the results
    $moistureResult = calculateMoistureResult($moistureContent);
    $calorificResult = calculateCalorificResult($calorificValue);
    $ashResult = calculateAshResult($ashContent);

    // Get the ID of the inserted input row
    $inputId = $con->insert_id;

    // Insert results into the "results" table
    $insertResultQuery = "INSERT INTO results (input_id, moisture_result, calorific_result, ash_result)
                          VALUES ($inputId, '$moistureResult', '$calorificResult', '$ashResult')";

    if ($con->query($insertResultQuery) === true) {
        $submitt = true;
        $resultMoisture = $moistureResult;
        $resultCalorific = $calorificResult;
        $resultAsh = $ashResult;
    } else {
        echo "ERROR: $insertResultQuery <br> " . $con->error;
    }

    $con->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Coal Quality Analysis Tool</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    .container {
        background-image: url('2019-12-24.jpg');
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        position: relative;
        opacity: 0.8;
    }

    img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0.8;
        z-index: -1;
    }

    .title {
        margin-top: 40px;
        text-align: left;
        font-family: 'Trebuchet MS';
        font-weight: bolder;
        font-size: 60px;
        color: #ffffff;
        background-color: #0967ff;
        position: relative;
        z-index: 1;
    }

    .content-container {
        display: flex;
        justify-content: space-between;
        width: 800px;
        position: relative;
        z-index: 1;
    }

    .form-container {
        margin-top: 100px;
        background-color: #e6ece1;
        border-radius: 10px;
        border: 1.5px solid rgb(4, 184, 255);
        box-shadow: 0px 0 30px rgba(89, 130, 136, 0.6);
        padding: 30px;
        width: 1500px;
        height: 430px;
        position: relative;
        z-index: 1;
    }

    .input-group {
        margin-bottom: 20px;
    }

    .input-group label {
        display: block;
        margin-bottom: 5px;
        color: #042243;
        font-weight: bold;
        font-size: 19px;
    }

    .input-group input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #a14b4b;
        border-radius: 5px;
        background-color: #c0c6d2;
        font-weight: bold;
        font-size: 13px;
    }

    .input-group input[type="submit"] {
        padding: 10px 20px;
        background-color: #93f797;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .analysis-container {
        margin-top: 100px;
        background-color: #ddddf2;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(130, 89, 136, 0.6);
        border: 1.5px solid #a14b4b;
        padding: 30px;
        margin-left: 30px;
        width: 1500px;
        height: 430px;
        position: relative;
        z-index: 1;
    }

    .analysis-container h3,
    .content-container h3 {
        margin-bottom: 30px;
        color: #27264b;
        font-size: 24px;
        border-bottom: 1px solid #0c096b;
        padding-bottom: 7px;
    }

    .result-row {
        margin-bottom: 95px;
    }

    .result-label {
        color: #3d2179;
        font-weight: bold;
        font-size: 19px;
    }

    .result-value {
        color: #87603e;
        font-size: 17px;
    }

    .sub {
        background-color: #b2e8eb;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <!-- <img src="2019-12-24.jpg" alt="B.C.C.L Koyla Bhawan Dhanabd"> -->
    <div class="container">
        <h2 class="title">Coal Quality Analysis Tool</h2>
        <div class="content-container">
            <div class="form-container">
                <h3>Enter Values</h3>
                <form action="" method="post">
                    <div class="input-group">
                        <label for="coalSampleName">Sample Name:</label>
                        <input type="text" id="coalSampleName" name="coalSampleName" required
                            value="<?php echo $inputSampleName; ?>">
                    </div>
                    <div class="input-group">
                        <label for="moistureContent">Moisture Content:</label>
                        <input type="text" id="moistureContent" name="moistureContent" required
                            value="<?php echo $inputMoistureContent; ?>">
                    </div>
                    <div class="input-group">
                        <label for="calorificValue">Calorific Value:</label>
                        <input type="text" id="calorificValue" name="calorificValue" required
                            value="<?php echo $inputCalorificValue; ?>">
                    </div>
                    <div class="input-group">
                        <label for="ashContent">Ash Content:</label>
                        <input type="text" id="ashContent" name="ashContent" required
                            value="<?php echo $inputAshContent; ?>">
                    </div>
                    <input class="sub" type="submit" value="Submit">
                    <input class="sub" id="resetButton" type="reset" value="Reset">
                </form>
            </div>
            <div class="analysis-container">
                <h3>Analysis Results</h3>
                <div class="result-row" data-type="moisture">
                    <span class="result-label">Moisture Content:</span>
                    <span class="result-value">
                        <b><?php echo $resultMoisture; ?></b>
                    </span>
                </div>
                <div class="result-row" data-type="calorific">
                    <span class="result-label">Calorific Value:</span>
                    <span class="result-value">
                        <b><?php echo $resultCalorific; ?></b>
                    </span>
                </div>
                <div class="result-row" data-type="ash">
                    <span class="result-label">Ash Content:</span>
                    <span class="result-value">
                        <b><?php echo $resultAsh; ?></b>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>