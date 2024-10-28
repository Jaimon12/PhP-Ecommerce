<?php
// remove_from_cart.php
include 'db.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo "Product removed from cart successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Product ID is not set.";
}

$conn->close();
?>

