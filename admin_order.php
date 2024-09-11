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
    mysqli_query($conn, "DELETE FROM users WHERE id = '$delete_id'") or die('query failed');
    $message[] = 'user removed successfully';
    header('location: admin_users.php');
}

include 'header.php';

// Statistics for total pendings
$select_pendings = mysqli_query($conn, "SELECT * FROM orders WHERE payment_status = 'pending'");
if (!$select_pendings) {
    die("Query failed: " . mysqli_error($conn));
}

$total_pending = mysqli_num_rows($select_pendings);

// Update payment status logic
if (isset($_POST['submit_payment'])) {
    $order_id = $_POST['order_id'];
    $new_payment_status = mysqli_real_escape_string($conn, $_POST['update_payment']);
    mysqli_query($conn, "UPDATE orders SET payment_status = '$new_payment_status' WHERE id = '$order_id'") or die('query failed');
    header('location: admin_order.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Admin Order</title>
    <!-- Add your additional head elements here -->
</head>

<body>
            <div class="line2"></div>
            <section class="order-container">
                <h1 class="title">total user account</h1>
                <div class="box-container">
                    <?php
                    $select_orders = mysqli_query($conn, "SELECT * FROM orders") or die('query failed');
                    if (mysqli_num_rows($select_orders) > 0) {
                        while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
                    ?>
                            <div class="box">
                                <p>user name: <span><?php echo $fetch_orders['name']; ?></span></p>
                                <p>user id: <span><?php echo isset($fetch_orders['user_id']) ? $fetch_orders['user_id'] : ''; ?></span></p>
                                <p>placed on: <span><?php echo $fetch_orders['placed_on']; ?></span></p>
                                <p>number <span><?php echo $fetch_orders['number']; ?></span></p>
                                <p>emailr <span><?php echo $fetch_orders['email']; ?></span></p>
                                <p>total price : <span><?php echo $fetch_orders['total_price']; ?></span></p>
                                <p>method : <span><?php echo $fetch_orders['method']; ?></span></p>
                                <p>address: <span><?php echo $fetch_orders['address']; ?></span></p>
                                <form method="post">
                                    <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                                    <select name="update_payment">
                                        <option disabled selected><?php echo $fetch_orders['payment_status']; ?></option>
                                        <option value="pending">Pending</option>
                                        <option value="complete">Complete</option>
                                    </select>
                                    <input type="submit" name="submit_payment" value="Update Payment" class="btn">
                                </form>
                                <a href="admin_order.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('delete this message');" class="delete">Delete</a>
                            </div>
                    <?php
                        }
                    } else {
                        echo '
                            <div class="empty">
                                <p>order placed yet!!</p>
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
