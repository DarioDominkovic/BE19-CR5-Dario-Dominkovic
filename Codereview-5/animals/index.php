<?php
require_once "../db_connect.php";
require_once "../file_upload.php";

session_start();
if (!isset($_SESSION["adm"])) {
    header("Location: ../login.php");
}

$sql = "SELECT * FROM animal";
$result = mysqli_query($connect, $sql);

$cards = "";

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Check if the image value is 0
        $imageSrc = $row["image"] === '0' ? '../pictures/animal.png' : '../pictures/' . $row["image"];

        $cards .= "<div class='col-md-4 mb-3'>
                    <div class='card'>
                        <img src='{$imageSrc}' class='card-img-top' alt='{$row["name"]}'>
                        <div class='card-body'>
                            <h5 class='card-title'>{$row["name"]}</h5>
                            <a href='details.php?animal_id={$row["animal_id"]}' class='btn btn-primary'>Details</a>
                            <a href='updateList.php?animal_id={$row["animal_id"]}' class='btn btn-warning'>Update</a>
                            <a href='delete.php?animal_id={$row["animal_id"]}' class='btn btn-danger'>Delete</a>
                        </div>
                    </div>
                </div>";
    }
} else {
    $cards = "<p>No results found</p>";
}


mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Animals List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        .navbar {
            padding: 20px 50px;
        }

        .nav-link {
            text-transform: uppercase;
            font-weight: 700;
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">

            <?php
            $avatarFile = "../pictures/admin.png";
            ?>
            <img src="<?= $avatarFile ?>" alt="user pic" width="30" height="24">

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../dashboard.php">DASHBOARD</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="create.php">CREATE NEW PET PAGE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php?logout">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h1 class="mt-5">Animals List</h1>
        <div class="row">
            <?= $cards ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>