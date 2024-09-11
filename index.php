<?php
include 'connection.php';
$message = [];

if (isset($_POST['submit-btn'])) {
    $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $name = mysqli_real_escape_string($conn, $filter_name);

    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $email = mysqli_real_escape_string($conn, $filter_email);

    $filter_password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $password = mysqli_real_escape_string($conn, $filter_password);

    $filter_cpassword = filter_var($_POST['cpassword'], FILTER_SANITIZE_STRING);
    $cpassword = mysqli_real_escape_string($conn, $filter_cpassword);

    $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'");
    
    if (mysqli_num_rows($select_user) > 0) {
        $message[] = 'User already exists';
    } else {
        if ($password != $cpassword) {
            $message[] = 'Passwords do not match';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn, "INSERT INTO `users` (`name`, `email`, `password`) VALUES ('$name', '$email', '$hashed_password')") or die('Query failed');
            $message[] = 'User registered successfully';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Register page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="form-container">
        <?php
        foreach ($message as $msg) {
            echo '
            <div class="message">
                <span>' . $msg . '</span>
                <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
            </div>
            ';
        }
        ?>
        <form method="post">
            <h1>Register now</h1>
            <input type="text" name="name" placeholder="Enter your name" required>
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <input type="password" name="cpassword" placeholder="Confirm your password" required>
            <input type="submit" name="submit-btn" class="btn" value="Register now">
            <p>Already have an account? <a href="login.php">Login now</a></p>
        </form>
    </section>
</body>
</html>
