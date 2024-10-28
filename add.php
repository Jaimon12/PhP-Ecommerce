<?php
// Include header and database connection
include 'header.php';
include 'db.php';

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for form data and errors
$product_name = $product_description = $product_price = "";
$product_name_err = $product_description_err = $product_price_err = $image_err = "";

// Process form submission on POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate product name
    if (empty(trim($_POST["product_name"]))) {
        $product_name_err = "Please enter product name.";
    } else {
        $product_name = trim($_POST["product_name"]);
    }

    // Validate product description
    if (empty(trim($_POST["product_description"]))) {
        $product_description_err = "Please enter product description.";
    } else {
        $product_description = trim($_POST["product_description"]);
    }

    // Validate product price
    if (empty(trim($_POST["product_price"]))) {
        $product_price_err = "Please enter product price.";
    } else {
        $product_price = trim($_POST["product_price"]);
        // Validate price format
        if (!is_numeric($product_price) || $product_price <= 0) {
            $product_price_err = "Please enter a valid product price.";
        }
    }

    // Validate and upload image
    if ($_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . "/uploads/";
        $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is an actual image
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if ($check === false) {
            $image_err = "File is not an image.";
        }

        // Check file size
        if ($_FILES["product_image"]["size"] > 500000) {
            $image_err = "Sorry, your file is too large.";
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $image_err = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }

        // Check if $image_err is empty before saving
        if (empty($image_err)) {
            if (!move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                $image_err = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $image_err = "File upload error.";
    }

    // Insert product into database if no errors
    if (empty($product_name_err) && empty($product_description_err) && empty($product_price_err) && empty($image_err)) {
        // Prepare SQL insert statement
        $sql = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters to the prepared statement
            $stmt->bind_param("ssds", $param_product_name, $param_product_description, $param_product_price, $param_product_image);

            // Set parameters
            $param_product_name = $product_name;
            $param_product_description = $product_description;
            $param_product_price = $product_price;
            $param_product_image = basename($_FILES["product_image"]["name"]);

            // Execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to shop.php after successful insertion
                header("location: shop.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>
    <h1>Add Product</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div>
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" value="<?php echo $product_name; ?>">
            <span><?php echo $product_name_err; ?></span>
        </div>
        <div>
            <label for="product_description">Product Description:</label>
            <textarea id="product_description" name="product_description"><?php echo $product_description; ?></textarea>
            <span><?php echo $product_description_err; ?></span>
        </div>
        <div>
            <label for="product_price">Product Price:</label>
            <input type="text" id="product_price" name="product_price" value="<?php echo $product_price; ?>">
            <span><?php echo $product_price_err; ?></span>
        </div>
        <div>
            <label for="product_image">Product Image:</label>
            <input type="file" id="product_image" name="product_image">
            <span><?php echo $image_err; ?></span>
        </div>
        <div>
            <input type="submit" value="Add Product">
        </div>
    </form>
</body>
</html>
