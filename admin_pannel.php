<?php
include 'connection.php';
session_start();

$message = [];

// Check if the user_id is not set or if the user_name is not set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
    header('location:login.php');
    exit(); // Ensure to exit after sending the header
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
    exit(); // Ensure to exit after sending the header
}

if (isset($_POST['add_product'])) {
    $product_name = mysqli_real_escape_string($conn, $_POST['name']);
    $product_price = mysqli_real_escape_string($conn, $_POST['price']);
    $product_detail = mysqli_real_escape_string($conn, $_POST['detail']);
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'image/' . $image;

    $select_product_name = mysqli_query($conn, "SELECT name FROM products WHERE name='$product_name'");

    if (mysqli_num_rows($select_product_name) > 0) {
        $message[] = 'Product name already exists';
    } else {
        $insert_product = mysqli_query($conn, "INSERT INTO products (name, price, product_details, image) VALUES ('$product_name', '$product_price', '$product_detail', '$image')");

        if ($insert_product) {
            if ($image_size > 2000000) {
                $message[] = 'Image size is too large';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Product added successfully';
            }
        } else {
            $message[] = 'Query failed';
        }
    }
}

include 'header.php';

// Statistics for total pendings
$select_pendings = mysqli_query($conn, "SELECT * FROM orders WHERE payment_status = 'pending'");
if (!$select_pendings) {
    die("Query failed: " . mysqli_error($conn));
}

$total_pending = mysqli_num_rows($select_pendings);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Admin Panel</title>
    <!-- Add your additional head elements here -->
</head>

<body>
    <div class="line4"></div>
    <section class="dashboard">
        <div class="box-container">
            <!-- ... (Previous HTML code) ... -->
            <!-- Statistics Boxes -->
            <div class="box">
                <?php
                $total_completed = 0;
                $select_completes = mysqli_query($conn, "SELECT * FROM orders WHERE payment_status ='complete'")
                    or die('query failed');
                while ($fetch_complete = mysqli_fetch_assoc($select_completes)) {
                    $total_completed += $fetch_complete['total_price'];
                }
                ?>
                <h3><?php echo $total_completed; ?></h3>
                <p>Order Completed</p>
            </div>

            <div class="box">
                <?php
                $select_orders = mysqli_query($conn, "SELECT * FROM orders WHERE payment_status ='pending'")
                    or die('query failed');
                $num_of_orders = mysqli_num_rows($select_orders);
                ?>
                <h3><?php echo $num_of_orders; ?></h3>
                <p>Orders Placed</p>
            </div>

            <div class="box">
                <?php
                $select_products = mysqli_query($conn, "SELECT * FROM products")
                    or die('query failed');
                $num_of_products = mysqli_num_rows($select_products);
                ?>
                <h3><?php echo $num_of_products; ?></h3>
                <p>Products Added</p>
            </div>
            <!-- ... (Continue with the rest of the HTML code) ... -->
        </div>
    </section>
    <script type="text/javascript" src="script.js"></script>
</body>

</html>
