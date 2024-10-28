<?php
// thank_you.php
include 'header.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <style>
        .thank-you-container {
            text-align: center;
            padding: 50px;
        }
        .thank-you-container h1 {
            margin-bottom: 20px;
        }
        .thank-you-container p {
            margin-bottom: 30px;
        }
        .thank-you-container a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .thank-you-container a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <main>
        <div class="thank-you-container">
            <h1>Thank You for Your Order!</h1>
            <p>Your order has been placed successfully. We will contact you soon with the delivery details.</p>
            <a href="shop.php">Continue Shopping</a>
        </div>
    </main>
</body>
</html>

<?php
include 'footer.php';
?>
