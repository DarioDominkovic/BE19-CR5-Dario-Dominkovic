<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: home.php");
}

if (isset($_SESSION["adm"])) {
    header("Location: dashboard.php");
}

require_once "db_connect.php";

$error = false;

function cleanInputs($input)
{
    $data = trim($input);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);

    return $data;
}

$email = "";
$emailError = $passError = "";
if (isset($_POST["login"])) {
    $email = cleanInputs($_POST["email"]);
    $password = cleanInputs($_POST["password"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter a valid email address";
    }

    if (empty($password)) {
        $error = true;
        $passError = "Password can't be empty!";
    }

    if (!$error) {
        $password = hash("sha256", $password);

        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";

        $result = mysqli_query($connect, $sql);

        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) == 1) {
            if ($row["status"] == "adm") {
                $_SESSION["adm"] = $row["id"];
                header("Location: dashboard.php");
            } else {
                $_SESSION["user"] = $row["id"];
                header("Location: home.php");
            }
        } else {
            echo "<div class='alert alert-danger'>
                        <p>Something went wrong, please try again later ...</p>
                      </div>";
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        body {
            background-color: #333232;
        }

        h1 {
            padding: 50px 0px;
            background-color: #7b9c43;
            color: #f5f5f5;
            font-weight: 700;
        }

        h5 {
            color: #9fafa9;
        }

        label {
            font-size: 30px;
            color: #f5f5f5;
            text-transform: uppercase
        }

        span {
            text-transform: uppercase;
            padding-left: 10%;
        }

        .container {
            background-color: #9fafa9;
            padding: 200px 300px;
        }
    </style>
</head>

<body>
    <h1 class="text-center">LOGIN PAGE</h1>
    <div class="container">
        <form method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email address" value="<?= $email ?>">
                <span class="text-danger"><?= $emailError ?></span>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
                <span class="text-danger"><?= $passError ?></span>
            </div>
            <button name="login" type="submit" class="btn btn-primary">LOGIN</button>

            <span> you don't have an account? <a href="register.php">register here</a></span>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>