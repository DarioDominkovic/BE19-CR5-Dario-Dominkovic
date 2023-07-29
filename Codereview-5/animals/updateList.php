<?php
session_start();

require_once "../db_connect.php";
require_once "../file_upload.php";

if (isset($_SESSION["user"])) {
    header("Location: ../home.php");
}

if (!isset($_SESSION["user"]) && !isset($_SESSION["adm"])) {
    header("Location: ../login.php");
}

$id = $_GET["animal_id"]; // to take the value from the parameter "id" in the url 
$sql = "SELECT * FROM animal WHERE animal_id = ?"; // finding the product 
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);  // fetching the data 


if (isset($_POST["update"])) {
    $name = $_POST["name"];
    $type = $_POST["type"];
    $age = $_POST["age"];
    $gender = isset($_POST["gender"]) ? $_POST["gender"] : "";
    $vaccine = isset($_POST["vaccine"]) ? $_POST["vaccine"] : "";
    $picture = $_FILES["picture"]["name"] ? fileUpload($_FILES["picture"], "animals") : "animal.png";

    if ($_FILES["picture"]["error"] == 0) {
        if ($row["image"] != "animal.png") {
            unlink("../pictures/{$row["image"]}");
        }
        $sql = "UPDATE animal SET name = ?, type = ?, age = ?, gender = ?, vaccine = ?, image = ? WHERE animal_id = ?";
    } else {
        $sql = "UPDATE animal SET name = ?, type = ?, age = ?, gender = ?, vaccine = ? WHERE animal_id = ?";
    }

    $stmt = mysqli_prepare($connect, $sql);
    if ($_FILES["picture"]["error"] == 0) {
        mysqli_stmt_bind_param($stmt, "ssisssi", $name, $type, $age, $gender, $vaccine, $picture, $id);
    } else {
        mysqli_stmt_bind_param($stmt, "ssissi", $name, $type, $age, $gender, $vaccine, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo "<div class='alert alert-success' role='alert'>
            Animal has been updated successfully.
          </div>";
        header("refresh: 3; url= index.php");
    } else {
        echo "<div class='alert alert-danger' role='alert'>
            Error updating the animal.
          </div>";
    }
}

mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Animal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-5">
        <h2>Update Animal</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3 mt-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" aria-describedby="name" name="name" value="<?php echo $row['name']; ?>">
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-control" id="type" name="type">
                    <option value="small" <?php if ($row['type'] === 'small') echo 'selected'; ?>>Small</option>
                    <option value="large" <?php if ($row['type'] === 'large') echo 'selected'; ?>>Large</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" class="form-control" id="age" aria-describedby="age" name="age" value="<?php echo $row['age']; ?>">
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" <?php if ($row['gender'] === 'male') echo 'checked'; ?>>
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" <?php if ($row['gender'] === 'female') echo 'checked'; ?>>
                    <label class="form-check-label" for="female">Female</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="vaccine" class="form-label">Vaccine</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="vaccine" id="yes" value="yes" <?php if ($row['vaccine'] === 'yes') echo 'checked'; ?>>
                    <label class="form-check-label" for="yes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="vaccine" id="no" value="no" <?php if ($row['vaccine'] === 'no') echo 'checked'; ?>>
                    <label class="form-check-label" for="no">No</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="picture" class="form-label">Picture</label>
                <input type="file" class="form-control" id="picture" aria-describedby="picture" name="picture">
            </div>
            <button name="update" type="submit" class="btn btn-primary">UPDATE</button>
            <a href="index.php" class="btn btn-warning">BACK TO PETLIST</a>
        </form>
    </div>

</body>

</html>