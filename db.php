<?php
// db.php
$host = 'localhost'; // Typically 'localhost' or '127.0.0.1' for local development
$username = 'root'; // Default XAMPP MySQL username
$password = ''; // Default XAMPP MySQL password is empty unless you set one
$dbname = 'ecom'; // Your database name

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

