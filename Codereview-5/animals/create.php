<?php
session_start();

if (!isset($_SESSION["user"]) && !isset($_SESSION["adm"])) {
    header("Location: ../login.php");
    exit; // Stop execution if the user is not logged in
}

require_once "../db_connect.php";
require_once "../file_upload.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create"])) {
    $name = $_POST["name"];
    $type = $_POST["type"];
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $vaccine = $_POST["vaccine"];

    // Validate "vaccine" and "gender" fields
    if ($vaccine !== "yes" && $vaccine !== "no") {
        echo "<div class='alert alert-danger' role='alert'>
            Invalid value for vaccine. Please enter 'yes' or 'no'.
          </div>";
        exit; // Stop execution if validation fails
    }

    if ($gender !== "male" && $gender !== "female") {
        echo "<div class='alert alert-danger' role='alert'>
            Invalid value for gender. Please enter 'male' or 'female'.
          </div>";
        exit; // Stop execution if validation fails
    }

    // Check if a file was uploaded
    if (isset($_FILES["picture"]) && $_FILES["picture"]["error"] === UPLOAD_ERR_OK) {
        // File was uploaded successfully, process the uploaded image
        $picture = fileUpload($_FILES["picture"], "animal");
    } else {
        // No file was uploaded, use the default image
        $picture = ["../pictures/animal.png", "Default Image"];
    }

    // Prepare the SQL statement using a prepared statement
    $stmt = mysqli_prepare($connect, "INSERT INTO animal (name, type, age, gender, vaccine, image) 
    VALUES (?, ?, ?, ?, ?, ?)");

    // Bind the parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "ssissi", $name, $type, $age, $gender, $vaccine, $picture[0]);

    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        echo "<div class='alert alert-success' role='alert'>
            New animal record has been created successfully.
          </div>";
        header("refresh: 3; url= index.php");
        exit; // Stop execution after successful insertion
    } else {
        echo "<div class='alert alert-danger' role='alert'>
            Error occurred while creating a new animal record.
          </div>";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>

<body>
    <div class="container mt-5">
        <h2>Create a new pet page</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3 mt-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" aria-describedby="name" name="name">
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-control" id="type" name="type">
                    <option value="small">Small</option>
                    <option value="large">Large</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" class="form-control" id="age" aria-describedby="age" name="age">
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="male">
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                    <label class="form-check-label" for="female">Female</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="vaccine" class="form-label">Vaccine</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="vaccine" id="yes" value="yes">
                    <label class="form-check-label" for="yes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="vaccine" id="no" value="no">
                    <label class="form-check-label" for="no">No</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="picture" class="form-label">Picture</label>
                <input type="file" class="form-control" id="picture" aria-describedby="picture" name="picture">
            </div>
            <button name="create" type="submit" class="btn btn-primary">Create page</button>
            <a href="index.php" class="btn btn-warning">Back to home page</a>
        </form>

    </div>
</body>

</html>