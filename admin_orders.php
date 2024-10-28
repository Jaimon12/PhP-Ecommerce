<?php
// admin_orders.php
include 'header.php';
include 'db.php';
session_start();

// Check if the user is an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch all orders
$sql = "SELECT orders.id, orders.user_id, orders.total_amount, orders.status, orders.name, orders.phone, orders.address, orders.created_at, users.username, users.email 
        FROM orders 
        JOIN users ON orders.user_id = users.id";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        echo "Order status updated successfully.";
    } else {
        echo "Error updating order status: " . $stmt->error;
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders</title>
    <style>
        body, h1, h2, h3, p, ul {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            line-height: 1.6;
            background-image: url('images.jpg'); /* Adjust the path to your background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #333;
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
        .action-link {
            color: #007BFF;
            text-decoration: none;
        }
        .action-link:hover {
            text-decoration: underline;
        }

        .add-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .add-button:hover {
            background-color: #0056b3;
        }

        main {
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background for better readability */
            border-radius: 10px;
        }

        footer {
            background: rgba(51, 51, 51, 0.9); /* Semi-transparent background for footer */
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="admin.php">Home</a></li>
                <li><a href="admin_orders.php">Manage Orders</a></li>
                
            </ul>
        </nav>
    </header>
    <main>
        <h1>Manage Orders</h1>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Total Amount</th>
                    
                    <th>Customer Details</th>
                    <th>Order Date</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . " (" . htmlspecialchars($row['email']) . ")</td>";
                        echo "<td>₹" . htmlspecialchars($row['total_amount']) . "</td>";
                      
                        echo "<td>";
                        echo "Name: " . htmlspecialchars($row['name']) . "<br>";
                        echo "Phone: " . htmlspecialchars($row['phone']) . "<br>";
                        echo "Address: " . htmlspecialchars($row['address']);
                        echo "</td>";
                        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                        echo "<td>";
                        echo "<form action='admin_orders.php' method='POST' style='display:inline-block;'>";
                        echo "<input type='hidden' name='order_id' value='" . htmlspecialchars($row['id']) . "'>";
                        
                       
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>&copy; 2024 Your Company</p>
    </footer>
</body>
</html>

<?php
$conn->close();
include 'footer.php';
?>
