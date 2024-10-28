<?php
// Include necessary files
include 'header.php'; // Adjust path as necessary
include 'db.php';     // Adjust path as necessary

// Initialize variables to store form data and errors
$product_name = $product_description = $product_price = "";
$product_name_err = $product_description_err = $product_price_err = "";

// Process form submission on POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate product name
    if (empty(trim($_POST["product_name"]))) {
        $product_name_err = "Please enter the product name.";
    } else {
        $product_name = trim($_POST["product_name"]);
    }

    // Validate product description
    if (empty(trim($_POST["product_description"]))) {
        $product_description_err = "Please enter the product description.";
    } else {
        $product_description = trim($_POST["product_description"]);
    }

    // Validate product price
    if (empty(trim($_POST["product_price"]))) {
        $product_price_err = "Please enter the product price.";
    } elseif (!is_numeric($_POST["product_price"])) {
        $product_price_err = "Please enter a valid number for the product price.";
    } else {
        $product_price = trim($_POST["product_price"]);
    }

    // Check input errors before updating into the database
    if (empty($product_name_err) && empty($product_description_err) && empty($product_price_err)) {
        // Prepare SQL update statement
        $sql = "UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssdi", $product_name, $product_description, $product_price, $param_id);

            // Set parameters
            $param_id = $_POST["product_id"];

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to shop.php after successful update
                header("location: shop.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
}

// Fetch product details for pre-filling the form
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Prepare SQL select statement
    $sql = "SELECT * FROM products WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind product ID as parameter to the prepared statement
        $stmt->bind_param("i", $param_id);

        // Set parameters
        $param_id = trim($_GET["id"]);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Store result
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                // Fetch row
                $row = $result->fetch_assoc();
                $product_name = $row["name"];
                $product_description = $row["description"];
                $product_price = $row["price"];
            } else {
                // Redirect to error page or handle appropriately
                echo "No records found.";
                exit;
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
</head>
<body>
    <h1>Update Product</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
        <div>
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>">
            <span><?php echo $product_name_err; ?></span>
        </div>
        <div>
            <label for="product_description">Product Description:</label>
            <textarea id="product_description" name="product_description"><?php echo htmlspecialchars($product_description); ?></textarea>
            <span><?php echo $product_description_err; ?></span>
        </div>
        <div>
            <label for="product_price">Product Price:</label>
            <input type="text" id="product_price" name="product_price" value="<?php echo htmlspecialchars($product_price); ?>">
            <span><?php echo $product_price_err; ?></span>
        </div>
        <div>
            <input type="submit" value="Update Product">
        </div>
    </form>
</body>
</html>

