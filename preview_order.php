<?php
// preview_order.php
include 'header.php';
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch cart items and total from session
$cart_items = $_SESSION['cart'];
$total = $_SESSION['total'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save order details in session for final confirmation
    header("Location: confirm_order.php");
    exit;
}

$order_details = $_SESSION['order_details'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Preview</title>
    <style>
        .checkout-container {
            display: flex;
            flex-direction: row;
            gap: 20px;
        }
        .order-summary, .checkout-form {
            flex: 1;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #ddd;
        }
        .product-image {
            max-width: 100px;
            height: auto;
            display: block;
            margin-bottom: 10px;
        }
        .checkout-container h2, .checkout-container h3 {
            margin: 0 0 20px;
        }
        .checkout-container button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .checkout-container button:hover {
            background-color: #0056b3;
        }
        .checkout-form div {
            margin-bottom: 15px;
        }
        .checkout-form label {
            display: block;
            margin-bottom: 5px;
        }
        .checkout-form input, .checkout-form textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .checkout-form textarea {
            resize: vertical;
        }
    </style>
</head>
<body>
    <main>
        <h1>Order Preview</h1>
        <div class="checkout-container">
            <div class="order-summary">
                <h2>Order Summary</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($cart_items) > 0) {
                            foreach ($cart_items as $item) {
                                echo "<tr>";
                                echo "<td>";
                                if (!empty($item['image'])) {
                                    echo "<img src='uploads/" . htmlspecialchars($item['image']) . "' alt='" . htmlspecialchars($item['name']) . "' class='product-image'>";
                                }
                                echo "</td>";
                                echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                                echo "<td>₹" . htmlspecialchars($item['price']) . "</td>";
                                echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
                                echo "</tr>";
                            }
                            echo "<tr>";
                            echo "<td colspan='3'><strong>Total</strong></td>";
                            echo "<td>₹" . htmlspecialchars($total) . "</td>";
                            echo "</tr>";
                        } else {
                            echo "<tr><td colspan='4'>Your cart is empty.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="checkout-form">
                <h2>Confirm Your Details</h2>
                <form action="preview_order.php" method="POST">
                    <div>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($order_details['name']); ?>" readonly>
                    </div>
                    <div>
                        <label for="phone">Phone Number:</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($order_details['phone']); ?>" readonly>
                    </div>
                    <div>
                        <label for="address">Address:</label>
                        <textarea id="address" name="address" readonly><?php echo htmlspecialchars($order_details['address']); ?></textarea>
                    </div>
                    <button type="submit">Place Order</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>

<?php
$conn->close();
include 'footer.php';
?>
