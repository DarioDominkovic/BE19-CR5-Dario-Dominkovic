<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

require_once "db_connect.php";

$sql_user = "SELECT * FROM users WHERE id = {$_SESSION["user"]}";
$result_user = mysqli_query($connect, $sql_user);
$row_user = mysqli_fetch_assoc($result_user);

if (isset($_GET['animal_id'])) {
    $animal_id = $_GET['animal_id'];

    $sql = "SELECT * FROM animal WHERE animal_id = $animal_id";
    $result = $connect->query($sql);

    if ($result !== false && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $name = $row['name'];
        $type = $row['type'];
        $age = $row['age'];
        $gender = $row['gender'];
        $vaccine = $row['vaccine'];
        $image = $row['image'];

        // Check if the image value is 0 and replace it with the default animal.png
        $imagePath = $image !== '0' ? "pictures/$image" : "pictures/animal.png";
    } else {
        header("Location: home.php");
        exit;
    }
} else {
    header("Location: home.php");
    exit;
}

mysqli_close($connect);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #333232;
        }

        h5 {
            color: #f5f5f5;
        }

        .container {
            padding: 50px 500px;
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

        .details {
            padding-top: 50px;
            color: white;
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
                    <a class="nav-link active" aria-current="page" href="/home.php">HOME</a>
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

    <h2 class="details text-center">Details</h2>

    <div class="container">

        <div class="card">
            <img src="<?php echo $imagePath; ?>" class="card-img-top" alt="<?php echo $name; ?>">

            <div class="card-body">
                <h5 class="card-title"><?php echo $name; ?></h5>
                <p class="card-text"><strong>Type:</strong> <?php echo $type; ?></p>
                <p class="card-text"><strong>Age:</strong> <?php echo $age; ?></p>
                <p class="card-text"><strong>Gender:</strong> <?php echo $gender; ?></p>
                <p class="card-text"><strong>Vaccine:</strong> <?php echo $vaccine; ?></p>
                <a href="adopt.php?animal_id=<?php echo $animal_id; ?>" class="btn btn-success">TAKE ME HOME</a>
                <a href="home.php" class="btn btn-danger">BACK</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>