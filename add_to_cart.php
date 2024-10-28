<?php
// add_to_cart.php

// Ensure db.php has the database connection setup correctly
include 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit;
}

// Sanitize input to prevent SQL injection
$user_id = intval($_SESSION['user_id']);
$product_id = intval($_GET['id']);

// Prepare SQL statement to insert into cart table
$stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");

if ($stmt) {
    // Bind parameters and execute the statement
    $stmt->bind_param("ii", $user_id, $product_id);

    if ($stmt->execute()) {
        // Redirect to shop.php after successful insertion
        header("Location: shop.php");
        exit;
    } else {
        // Handle execution error
        echo "Error: " . $conn->error;
    }

    // Close statement
    $stmt->close();
} else {
    // Handle prepare statement error
    echo "Prepare statement error: " . $conn->error;
}

// Close connection
$conn->close();
?>
