<?php
session_start();

if (isset($_SESSION["adm"])) {
    header("Location: dashboard.php");
}

if (!isset($_SESSION["user"]) && !isset($_SESSION["adm"])) {
    header("Location: login.php");
}

$filter = "";
if (isset($_GET["filter"])) {
    if ($_GET["filter"] === "senior") {
        $filter = "senior";
    } elseif ($_GET["filter"] === "all") {
        $filter = "all";
    }
}

require_once "db_connect.php";

$sql_user = "SELECT * FROM users WHERE id = {$_SESSION["user"]}";
$result_user = mysqli_query($connect, $sql_user);
$row_user = mysqli_fetch_assoc($result_user);

$sql_animals = "SELECT * FROM animal";
if ($filter === "senior") {
    $sql_animals .= " WHERE age > 8";
}

$result_animals = mysqli_query($connect, $sql_animals);

$animals = [];
while ($row_animal = mysqli_fetch_assoc($result_animals)) {
    $animals[] = $row_animal;
}

mysqli_close($connect);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        body {
            background-color: #333232;
        }

        h2 {
            color: #f5f5f5;
            font-weight: 700;
        }

        h5 {
            color: #9fafa9;
        }

        .navbar {
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

        .welcome-text {
            padding-top: 30px;
        }

        .all {
            background-color: #7b9c43;
        }

        .senior {
            background-color: #9fafa9;
        }

        .card {
            border-radius: 20px;
            border: 10px #9fafa9 solid;
        }

        .card-body {
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            background-color: #9fafa9;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg ">
        <div class="container-fluid">
            <img class="logo" src="pictures/pet-shop.png" alt="Logo">
            <h2 class="me-auto">PetStore</h2>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="#">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="update.php?id=<?php echo $_SESSION["user"]; ?>">EDIT</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="logout.php?logout">LOGOUT</a>
                </li>
            </ul>
            <img class="user-img card-img-top" src="pictures/<?php echo $row_user['picture']; ?>" alt="<?php echo $row_user['first_name']; ?>">
        </div>
    </nav>

    <h2 class="welcome-text text-center">Welcome <?php echo $row_user["first_name"] . " " . $row_user["last_name"]; ?>!</h2>
    <h5 class="text-center"><?php echo $row_user["email"] ?>!</h5>

    <div class="text-center mt-4">
        <a href="?filter=all" class="all btn">Show All Animals</a>
        <a href="?filter=senior" class="senior btn ">Show Senior Animals</a>
    </div>

    <div class="container mt-4">
        <div class="row">
            <?php
            foreach ($animals as $animal) {
                $imagePath = $animal['image'] !== '0' ? "pictures/{$animal['image']}" : "pictures/animal.png";
            ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="<?php echo $imagePath; ?>" class="card-img-top" alt="<?php echo $animal['name']; ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo $animal['name']; ?></h5>
                            <a href="details.php?animal_id=<?php echo $animal['animal_id']; ?>" class="btn btn-success">DETAILS</a>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>