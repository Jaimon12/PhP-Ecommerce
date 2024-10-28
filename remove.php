<?php
include 'header.php';
include 'db.php';


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission to delete product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if product ID is set and valid
    if (isset($_POST["product_id"]) && is_numeric($_POST["product_id"])) {
        $product_id = $_POST["product_id"];

        // Prepare SQL delete statement
        $sql = "DELETE FROM products WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind product ID as parameter to the prepared statement
            $stmt->bind_param("i", $product_id);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to products.php after successful deletion
                header("location: products.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    } else {
        echo "Invalid product ID.";
    }
}

// Close connection
$conn->close();
?>
