<?php
// list_orders.php
include 'header.php';
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch orders for the logged-in user
$sql = "SELECT orders.*, users.username FROM orders JOIN users ON orders.user_id = users.id WHERE orders.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <style>
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
    <main>
        <h1>Your Orders</h1>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Username</th>
                    <th>Total Amount</th>
                  
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Ensure that all keys exist before accessing them
                        $orderId = htmlspecialchars($row['id']);
                        $username = htmlspecialchars($row['username']);
                        $totalAmount = htmlspecialchars($row['total_amount']);
                        // Updated to match database column name
                        $name = htmlspecialchars($row['NAME']);     // Updated to match database column name
                        $phone = htmlspecialchars($row['phone']);
                        $address = htmlspecialchars($row['address']);
                        $createdAt = htmlspecialchars($row['created_at']);

                        echo "<tr>";
                        echo "<td>{$orderId}</td>";
                        echo "<td>{$username}</td>";
                        echo "<td>{$totalAmount}</td>";
                        
                        echo "<td>{$name}</td>";
                        echo "<td>{$phone}</td>";
                        echo "<td>{$address}</td>";
                        echo "<td>{$createdAt}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>

<?php
$conn->close();
include 'footer.php';
?>

