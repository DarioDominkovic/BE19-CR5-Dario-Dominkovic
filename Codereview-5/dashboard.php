<?php
session_start();

if (!isset($_SESSION["user"]) && !isset($_SESSION["adm"])) {
    header("Location: login.php");
}

if (isset($_SESSION["user"])) {
    header("Location: home.php");
}

require_once "db_connect.php";

$sql = "SELECT * FROM users WHERE id = {$_SESSION["adm"]}"; // selecting logged-in user details from the session user 

$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);

$sqlUsers = "SELECT * FROM users WHERE status != 'adm'";
$resultUsers = mysqli_query($connect, $sqlUsers);

$layout = "";

if (mysqli_num_rows($resultUsers) > 0) {
    while ($userRow = mysqli_fetch_assoc($resultUsers)) {
        $avatarPath = "pictures/avatar.png";

        $layout .= "<div>
            <div class='card'>
                <img src='pictures/{$userRow['picture']}' class='user-img' alt='{$userRow['first_name']}'>
                <div class='card-body'>
                    <h5 class='card-title'>{$userRow['first_name']} {$userRow['last_name']}</h5>
                    <p class='card-text'>{$userRow['email']}</p>
                    <a href='update.php?id={$userRow['id']}' class='btn btn-warning'>Update</a>
                </div>
            </div>
        </div>";
    }
} else {
    $layout .= "No results found!";
}

mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome <?= $row["first_name"] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        .info {
            padding: 30px 0;
            text-transform: uppercase;
        }

        .navbar {
            padding: 20px 50px;
        }

        .nav-link {
            text-transform: uppercase;
            font-weight: 700;
        }

        .card .user-img {
            height: 400px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">

            <?php
            $avatarFile = "pictures/admin.png";
            ?>
            <img src="<?= $avatarFile ?>" alt="user pic" width="30" height="24">

            <ul class="navbar-nav ">
                <li class="nav-item text-center ">
                    <a class="nav-link" aria-current="page" href="animals/index.php">SHOW ALL PETS</a>
                </li>
                <li class="nav-item text-center ">
                    <a class="nav-link" href="update.php?id=<?= $row["id"] ?>">EDIT YOUR PROFIL</a>
                </li>
                <li class="nav-item text-center ">
                    <a class="nav-link" href="logout.php?logout">LOGOUT</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="info">
        <h2 class="text-center">administrator</h2>
        <h2 class="text-center">Welcome <?= $row["first_name"] . " " . $row["last_name"] ?></h2>
    </div>
    <div class="container">
        <div class="row row-cols-3">
            <?= $layout ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>