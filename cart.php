<?php
// cart.php
include 'header.php';
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity > 0) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $quantity, $cart_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: cart.php");
    exit;
}

// Handle item removal
if (isset($_GET['remove_id'])) {
    $cart_id = intval($_GET['remove_id']);

    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $stmt->close();

    header("Location: cart.php");
    exit;
}

// Fetch cart items
$result = $conn->query("SELECT cart.*, products.name, products.price, products.image FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_id = $user_id");

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<h1>Your Shopping Cart</h1>
<div class="cart-container">
    <?php
    if ($result->num_rows > 0) {
        echo "<table class='cart-table'>";
        echo "<thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
              </thead>
              <tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>";
            if (!empty($row['image'])) {
                echo "<img src='uploads/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' class='product-image'>";
            }
            echo "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>₹" . htmlspecialchars($row['price']) . "</td>";
            echo "<td>
                    <form action='cart.php' method='POST'>
                        <input type='hidden' name='cart_id' value='" . htmlspecialchars($row['id']) . "'>
                        <input type='number' name='quantity' value='" . htmlspecialchars($row['quantity']) . "' min='1'>
                        <button type='submit'>Update</button>
                    </form>
                  </td>";
            echo "<td><a href='cart.php?remove_id=" . htmlspecialchars($row['id']) . "' class='remove'>Remove</a></td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
        echo "<a href='checkout.php' class='checkout'>Proceed to Checkout</a>";
    } else {
        echo "<p>Your cart is empty.</p>";
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
    <title>Cart</title>
    <style>
        .cart-container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .cart-table th, .cart-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        .cart-table th {
            background-color: #f2f2f2;
        }
        .cart-table .product-image {
            max-width: 100px;
            height: auto;
        }
        .cart-item form {
            display: inline;
        }
        .cart-item .remove {
            color: #007BFF;
            text-decoration: none;
        }
        .cart-item .remove:hover {
            text-decoration: underline;
        }
        .checkout {
            color: #007BFF;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }
        .checkout:hover {
            text-decoration: underline;
        }
                body {
            line-height: 1.6;
            background-image: url('download.jpg'); /* Adjust the path to your background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #333;
        }
    </style>
</head>
<body>
</body>
</html>
