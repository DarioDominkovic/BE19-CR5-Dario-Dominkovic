<?php
session_start();

require_once "db_connect.php";
require_once "file_upload.php";

$id = $_GET["id"]; // taking the value of id from the URL 

$sql = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($connect, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "User not found.";
    exit;
}

$row = mysqli_fetch_assoc($result);

$backBtn = "home.php";

if (isset($_SESSION["adm"])) {
    $backBtn = "dashboard.php";
}

if (isset($_POST["update"])) {
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $date_of_birth = $_POST["date_of_birth"];
    $picture = fileUpload($_FILES["picture"]);

    /* checking if a picture has been selected in the input for the image */
    if ($_FILES["picture"]["error"] == 0) {
        /* checking if the picture name of the product is not avatar.png to remove it from pictures folder */
        if ($row["picture"] != "product.png") {
            unlink("pictures/$row[picture]");
        }
        $sql = "UPDATE users SET first_name = '$fname', last_name = '$lname', picture = '$picture[0]', date_of_birth = '$date_of_birth', email = '$email' WHERE id = {$id}";
    } else {
        $sql = "UPDATE users SET first_name = '$fname', last_name = '$lname', date_of_birth = '$date_of_birth', email = '$email' WHERE id = {$id}";
    }

    if (mysqli_query($connect, $sql)) {
        echo "<div class='alert alert-success' role='alert'>
        Profile has been updated, {$picture[1]}
      </div>";
        header("refresh: 3; url=$backBtn");
    } else {
        echo "<div class='alert alert-danger' role='alert'>
        Error found, {$picture[1]}
      </div>";
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pet Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        body {
            background-color: #333232;
        }

        h1 {
            padding-top: 30px;
            color: #f5f5f5;
            font-weight: 700;
        }

        label {
            font-size: 30px;
            color: #f5f5f5;
            text-transform: uppercase
        }

        h2 {
            color: #f5f5f5;
            font-weight: 700;
        }

        h5 {
            color: #9fafa9;
        }

        .navbar {
            height: 100px;
            background-color: #7b9c43;
            padding: 20px 50px;
        }

        .navbar .logo {
            width: 50px;
        }

        .navbar h2 {
            padding-left: 20px;
        }

        .navbar .nav-item {
            padding-right: 20px;
        }

        .navbar .user-img {
            width: 50px;
        }

        .navbar .nav-link {
            color: #f5f5f5;
            font-size: larger;
            font-weight: 700;
        }

        .update-btn {
            background-color: #7b9c43;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg ">

        <img class="logo" src="pictures/pet-shop.png" alt="Logo">
        <h2 class="me-auto">PetStore</h2>
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="logout.php?logout">LOGOUT</a>
            </li>
        </ul>
    </nav>

    <h1 class="text-center">UPDATE YOUR PROFIL</h1>
    <div class="container">
        <form method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="mb-3 mt-3">
                <label for="fname" class="form-label">First name</label>
                <input type="text" class="form-control" id="fname" name="fname" placeholder="First name" value="<?= $row["first_name"] ?>">
            </div>
            <div class="mb-3">
                <label for="lname" class="form-label">Last name</label>
                <input type="text" class="form-control" id="lname" name="lname" placeholder="Last name" value="<?= $row["last_name"] ?>">
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date of birth</label>
                <input type="date" class="form-control" id="date" name="date_of_birth" value="<?= $row["date_of_birth"] ?>">
            </div>
            <div class="mb-3">
                <label for="picture" class="form-label">Profile picture</label>
                <input type="file" class="form-control" id="picture" name="picture">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email address" value="<?= $row["email"] ?>">
            </div>
            <button name="update" type="submit" class="update-btn btn ">UPDATE</button>
            <a href="<?= $backBtn ?>" class="btn btn-secondary">BACK</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>