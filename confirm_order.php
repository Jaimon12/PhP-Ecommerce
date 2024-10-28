<?php
// confirm_order.php
include 'header.php';
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$cart_items = $_SESSION['cart'];
$total = $_SESSION['total'];
$order_details = $_SESSION['order_details'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle order submission
    $conn->begin_transaction();

    try {
        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, name, phone, address) VALUES (?, ?, 'Pending', ?, ?, ?)");
        $stmt->bind_param("idsss", $user_id, $total, $order_details['name'], $order_details['phone'], $order_details['address']);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Insert order items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($cart_items as $item) {
            $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }
        $stmt->close();

        // Clear cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        header("Location: thank_you.php");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Failed to process your order. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
</head>
<body>
    <main>
        <h1>Order Confirmation</h1>
        <form action="confirm_order.php" method="POST">
            <button type="submit">Confirm Order</button>
        </form>
    </main>
</body>
</html>

<?php
$conn->close();
include 'footer.php';
?>
