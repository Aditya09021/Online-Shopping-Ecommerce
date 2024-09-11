<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header class="header">
        <div class="flex">
            <a href="admin_pannel.php" class="logo"><img src="img/logo.png" alt="Logo"></a>
            <nav class="navbar">
                <a href="admin_pannel.php">Home</a>
                <a href="admin_product.php">Product</a>
                <a href="admin_order.php">Order</a>
                <a href="admin_user.php">Users</a>
                <a href="admin_message.php">Messages</a>
            </nav>
            <div class="icons">
                <i class="bi bi-person" id="user-btn"></i>
                <i class="bi bi-list" id="menu-btn"></i>
            </div>
            <div class="user-box">
                <p>Username: <span><?php echo $_SESSION['user_name'] ; ?></span></p>
                <p>Email: <span><?php echo  $_SESSION['user_email']; ?></span></p>
                <form method="post">
                    <button type="submit" name="logout" class="logout-btn">Log Out</button>
                </form>
            </div>
        </div>
    </header>
</body>
</html>
