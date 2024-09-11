<?php
include 'connection.php';
session_start();

$message = [];

// Check if the admin_id is not set or if the admin_name is not set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
    header('location:login.php');
    exit(); // Ensure to exit after sending the header
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
    exit(); // Ensure to exit after sending the header
}



// Delete product logic
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
   
    mysqli_query($conn, "DELETE FROM cart WHERE pid = '$delete_id'") or die('query failed'); // Fix: Added missing `=`
    mysqli_query($conn, "DELETE FROM wishlist WHERE pid = '$delete_id'") or die('query failed');
    header('location: admin_product.php');
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
    <section class="show-products">
        <div class="box-container">
            <?php
            $select_products = mysqli_query($conn, "SELECT * FROM products") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
            ?>
                    <div class="box">
                        <img src="image/<?php echo $fetch_products['image']; ?>">
                        <p>price: $<?php echo $fetch_products['price']; ?> </p>
                        <h4><?php echo $fetch_products['name']; ?></h4>
                        <details> <?php echo $fetch_products['product_details']; ?></details>
                        <a href="admin_product.php?edit=<?php echo $fetch_products['id']; ?>" class="edit">edit</a>
                        <a href="admin_product.php?delete=<?php echo $fetch_products['id']; ?>" class="delete" onclick=" return confirm('want to delete this product');">delete</a>
                    </div>
            <?php
                }
            } else {
                echo '
                <div class="empty">
                    <p>no products added yet!</p>
                </div>
                ';
            }
            ?>
          
                <div class="line2"></div>
                <section class="message-container">
                    <h1 class="title">unread message</h1>
                    <div class="box-container">
                        <?php
                        $select_message = mysqli_query($conn, "SELECT * FROM message") or die('query failed');
                        if (mysqli_num_rows($select_message) > 0) {
                            while ($fetch_message = mysqli_fetch_assoc($select_message)) {
                        ?>
                                <div class="box">
                                    <p>user id: <span><?php echo $fetch_message['id']; ?></span></p>
                                    <p>name: <span><?php echo $fetch_message['name']; ?></span></p>
                                    <p>email: <span><?php echo $fetch_message['email']; ?></span></p>
                                    <p><?php echo $fetch_message['message']; ?></p>
                                    <a href="admin_message.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('delete this message');">Delete</a>
                                </div>
                        <?php
                            }
                        } else {
                            echo '
                                <div class="empty">
                                    <p>No product added yet!!</p>
                                </div>
                                ';
                        }
                        ?>
                    </div>
                </section>
                <div class="line "></div>
            </div>
        </div>
    </section>
    <script type="text/javascript" src="script.js"></script>
</body>

</html>
