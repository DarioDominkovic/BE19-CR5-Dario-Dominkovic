<?php
session_start();

if (!isset($_SESSION["user"]) && !isset($_SESSION["adm"])) { // if the session user and the session adm have no value
    header("Location: login.php"); // redirect the user to the home page
}

if (isset($_SESSION["user"])) { // if a session "user" is exist and have a value
    header("Location: home.php"); // redirect the user to the user page
}

require_once "../db_connect.php";

$id = $_GET["animal_id"]; // to take the value from the parameter "id" in the url 
$sql = "SELECT * FROM animal WHERE animal_id = $id"; // finding the product 
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);  // fetching the data 
if ($row["picture"] != "animal.png") { // if the picture is not product.png (the detault picture) we will delete the picture
    unlink("../pictures/$row[picture]");
}

$delete = "DELETE FROM animal WHERE animal_id = $id"; // query to delete a record from the database

if (mysqli_query($connect, $delete)) {
    header("Location: index.php");
} else {
    echo "Error";
}

mysqli_close($connect);
