<?php
// login.php
include 'header.php';
include 'db.php';
session_start();

$login_err = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role']; // Assuming you have a role column
                header("Location: home.php");
                exit;
            } else {
                $login_err = "Invalid username or password";
            }
        } else {
            $login_err = "Invalid username or password";
        }

        $stmt->close();
    } else {
        $login_err = "Please fill in both fields";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (!empty($login_err)): ?>
        <div class="alert alert-danger"><?php echo $login_err; ?></div>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>

<?php
include 'footer.php';
?>

