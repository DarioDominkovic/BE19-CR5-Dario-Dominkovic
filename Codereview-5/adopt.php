<?php
session_start();
require_once "db_connect.php";

if (isset($_GET['animal_id'])) {
    $animal_id = $_GET['animal_id'];
    $user_id = $_SESSION["user"];
    $adoption_date = date("Y-m-d");

    // Check if the given animal_id exists in the animal table
    $check_sql = "SELECT * FROM animal WHERE animal_id = ?";
    $stmt = $connect->prepare($check_sql);
    $stmt->bind_param("i", $animal_id);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Animal exists, proceed with the adoption process
        $insert_sql = "INSERT INTO pet_adoption (user_id, pet_id, adoption_date) VALUES (?, ?, ?)";
        $stmt = $connect->prepare($insert_sql);
        $stmt->bind_param("iis", $user_id, $animal_id, $adoption_date);
        
        if ($stmt->execute()) {
            header("Location: home.php"); // Redirect to the home page after successful adoption
            exit;
        } else {
            echo "Error adopting the pet: " . $stmt->error;
        }
    } else {
        // Animal does not exist, redirect back to home page or show an error message
        header("Location: home.php"); // You can redirect to home or display an error message like "Invalid animal ID"
        exit;
    }
} else {
    header("Location: home.php");
    exit;
}
