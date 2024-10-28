<?php
// shop.php
include 'header.php';
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select all products
$result = $conn->query("SELECT * FROM products");

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<h1>Shop</h1>
<div class="products-container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            if (!empty($row['image'])) {
                echo "<img src='uploads/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' class='product-image'>";
            }
            echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
            echo "<p>" . htmlspecialchars($row['description']) . "</p>";
            echo "<p>Price: ₹" . htmlspecialchars($row['price']) . "</p>";
            echo "<a href='add_to_cart.php?id=" . htmlspecialchars($row['id']) . "'>Add to Cart</a>";
            echo "</div>";
        }
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
</div>

<?php
$conn->close();
include 'footer.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <style>
        .products-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .product {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            width: calc(33.333% - 40px);
            box-sizing: border-box;
            text-align: center;
        }
        .product img {
            max-width: 100%;
            height: auto;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .product h2 {
            margin: 0 0 10px;
        }
        .product p {
            margin: 0 0 10px;
        }
        .product a {
            color: #007BFF;
            text-decoration: none;
        }
        .product a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
</body>
</html>
