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

// Delete user logic
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
   
    mysqli_query($conn, "DELETE FROM users WHERE id = '$delete_id'") or die('query failed');
    $message[] = 'User removed successfully';
    header('location: admin_users.php');
}

include 'header.php';

// Statistics for total users
$select_users = mysqli_query($conn, "SELECT * FROM users") or die('query failed');
$total_users = mysqli_num_rows($select_users);
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
            if (mysqli_num_rows($select_users) > 0) {
                while ($fetch_user = mysqli_fetch_assoc($select_users)) {
            ?>
                    <div class="box">
                        <p>user id: <span><?php echo $fetch_user['id']; ?></span></p>
                        <p>name: <span><?php echo $fetch_user['name']; ?></span></p>
                        <p>email: <span><?php echo $fetch_user['email']; ?></span></p>
                        <p>user type: <span style="color:<?php echo ($fetch_user['user_type'] == 'admin') ? 'orange' : 'black'; ?>;"><?php echo $fetch_user['user_type']; ?></span></p>
                        <a href="admin_user.php?delete=<?php echo $fetch_user['id']; ?>" onclick="return confirm('delete this user');">Delete</a>
                    </div>
            <?php
                }
            } else {
                echo '
                    <div class="empty">
                        <p>No users added yet!</p>
                    </div>
                ';
            }
            ?>
        </div>
    </section>
    <div class="line "></div>
    </div>
    </body>

</html>
