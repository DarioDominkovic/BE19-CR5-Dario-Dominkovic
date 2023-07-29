<?php
require_once "../db_connect.php";

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
        $imagePath = $image !== '0' ? "../pictures/$image" : "../pictures/animal.png";
    } else {
        header("Location: home.php");
        exit;
    }
} else {
    header("Location: home.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        h2 {
            padding: 30px 0px;
        }

        .container {
            padding: 0px 500px;
        }

        .navbar {
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
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg ">
        <div class="container-fluid">
            <img class="logo" src="../pictures/pet-shop.png" alt="Logo">
            <h2 class="me-auto">PetStore</h2>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-img">
                    <a class="navbar-brand" href="#">
                        <img src="../pictures/admin.png" alt="user pic" width="30" height="24">
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <h2 class="text-center">Details</h2>

    <div class="container">

        <div class="card">
            <img src="<?php echo $imagePath; ?>" class="card-img-top" alt="<?php echo $name; ?>">

            <div class="card-body">
                <h5 class="card-title"><?php echo $name; ?></h5>
                <p class="card-text"><strong>Type:</strong> <?php echo $type; ?></p>
                <p class="card-text"><strong>Age:</strong> <?php echo $age; ?></p>
                <p class="card-text"><strong>Gender:</strong> <?php echo $gender; ?></p>
                <p class="card-text"><strong>Vaccine:</strong> <?php echo $vaccine; ?></p>
                <a href="index.php" class="btn btn-danger">Back to Pets</a>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>