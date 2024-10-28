<?php
// admin.php
include 'header.php';
include 'db.php';
session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products
$result = $conn->query("SELECT * FROM products");

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
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

        .add-button, .orders-button {
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
        .add-button:hover, .orders-button:hover {
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
                
                <li><a href="admin.php">Manage Products</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Manage Products</h1>
        <a href="add.php" class="add-button">+ Add New Product</a>
        <a href="admin_orders.php" class="orders-button">View Orders</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>₹" . htmlspecialchars($row['price']) . "</td>";
                        echo "<td>";
                        echo "<a href='update.php?id=" . htmlspecialchars($row['id']) . "' class='action-link'>Update</a> | ";
                        echo "<a href='delete.php?delete_id=" . htmlspecialchars($row['id']) . "' class='action-link'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No products found.</td></tr>";
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
