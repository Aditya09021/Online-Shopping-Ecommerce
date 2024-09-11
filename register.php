<?php

include 'connection.php';
session_start();
$admin_id = $_SESSION['user_name'];
if (!isset($admin_id)) { header('location: login.php');

}


if (isset($_POST['logout'])) {
session_destroy();
header('location: login.php');

}
?>

<style type="text/css">
<?php

include 'main.css';

?>
</style>

<!DOCTYPE html>
<html lang="en">
<head>
<style>
    
        body {
            margin: 65%; /* Remove default margin */
            padding: 10px; /* Remove default padding */
        }

        header {
            background-color: #3498db; /* Set background color for the header */
            color: #fff; /* Set text color */
            text-align: center; /* Center-align text */
            padding: 20px; /* Add padding to the header */
            margin-bottom: 20px; /* Add margin to create space below the header */
        }

        h1 {
            font-size: 36px; /* Set the font size of the heading */
            margin: 52%; /* Remove margin for the heading */
        }
    </style>
</head>
<body>
<header>
        <h1>WELCOME TO Online SHOPPING</h1>
    </header>
<?php

include 'header.php';

?>
<style type="text/css">
    body {
        background-color: orangered; /* Set your desired background color here */
        margin: 100%; /* Remove default margin to fill the entire viewport */
        padding: 12rem; /* Remove default padding */
        font-family: Arial, sans-serif; /* Choose an appropriate font-family */
    }

    /* Add additional styles as needed */
</style>

</body>

</html>