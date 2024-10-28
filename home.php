<?php
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select two products
$sql = "SELECT * FROM products LIMIT 2";
$result = $conn->query($sql);

// Close connection (optional, if not needed elsewhere)
$conn->close();
?>

<?php include 'header.php'; ?>

<h1>Welcome to Our E-Commerce Store</h1>
<a href="shop.php">Shop Now</a>
<div class="products-container">
    <?php
    // Check if there are products to display
    if ($result && $result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<h2>" . htmlspecialchars($row["name"]) . "</h2>";
            echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
            echo "<p>Price: ₹" . htmlspecialchars($row["price"]) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
</div>

<?php include 'footer.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        .products-container {
            display: flex;
            gap: 20px;
        }
        .product {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }
       
    </style>
</head>
<body>
</body>
</html>
