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
        $insert_product = mysqli_query($conn, "INSERT INTO products (name, price, product_details, image) VALUES ('$product_name', '$product_price', '$product_detail', '$image_folder')");

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

// Delete product logic
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $select_delete_image = mysqli_query($conn, "SELECT image FROM products WHERE id = '$delete_id'") or die('query failed');
    $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
    unlink('image/' . $fetch_delete_image['image']); // Fix: Add 'image/' before the image file name
    mysqli_query($conn, "DELETE FROM products WHERE id = '$delete_id'") or die('query failed');
    mysqli_query($conn, "DELETE FROM cart WHERE pid = '$delete_id'") or die('query failed'); // Fix: Added missing `=`
    mysqli_query($conn, "DELETE FROM wishlist WHERE pid = '$delete_id'") or die('query failed');
    header('location: admin_product.php');
}

if (isset($_POST['updte_product'])) {
    $update_id = $_POST['update_id'];
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_price = mysqli_real_escape_string($conn, $_POST['update_price']);
    $update_detail = mysqli_real_escape_string($conn, $_POST['update_details']);
    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_folder = 'image/' . $update_image;

    $update_query = mysqli_query($conn, "UPDATE products SET name='$update_name', price='$update_price', product_details='$update_detail', image='$update_image' WHERE id = '$update_id'") or die('query failed');
    
    if ($update_query) {
        move_uploaded_file($update_image_tmp_name, $update_image_folder);
        header('location:admin_product.php');
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
            <div class="line"></div>
            <section class="update-container">
                <?php
                if (isset($_GET['edit'])) {
                    $edit_id = $_GET['edit'];
                    $edit_query = mysqli_query($conn, "SELECT * FROM products WHERE id = '$edit_id'") or die('query failed');
                    if (mysqli_num_rows($edit_query) > 0) {
                        while ($fetch_edit = mysqli_fetch_assoc($edit_query)) {
                ?>
                            <form method="POST" enctype="multipart/form-data">
                                <img src="image/<?php echo $fetch_edit['image']; ?>">
                                <input type="hidden" name="update_id" value="<?php echo $fetch_edit['id']; ?>">
                                <input type="text" name="update_name" value="<?php echo $fetch_edit['name']; ?>">
                                <input type="number" name="update_price" min="0" value="<?php echo $fetch_edit['price']; ?>">
                                <textarea name="update_details"><?php echo $fetch_edit['product_details']; ?></textarea>
                                <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png, image/webp">
                                <input type="submit" name="updte_product" value="update" class="edit">
                                <input type="reset" name="" value="cancel" class="option-btn btn" id="close-fort">
                            </form>
                <?php
                        }
                    }
                    echo "<script> document.querySelector('.update-container').style.display='block';</script>";
                }
                ?>
            </section>
            <div class="box">
                <h3><?php echo $total_pending; ?></h3>
                <p>Total Pendings</p>
            </div>
            <div class="box">
                <div class="line2"></div>
                <section class="add-products form-container">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="input-field">
                            <label>Product Name</label>
                            <input type="text" name="name" required>
                        </div>
                        <div class="input-field">
                            <label>Product Price</label>
                            <input type="text" name="price" required>
                        </div>
                        <div class="input-field">
                            <label>Product Detail</label>
                            <textarea name="detail" required></textarea>
                        </div>
                        <div class="input-field">
                            <label>Product Image</label>
                            <input type="file" name="image" accept="image/*" required>
                        </div>
                        <input type="submit" name="add_product" value="Add Product" class="btn">
                    </form>
                    <div class="line3"></div>
                    <?php
                    if (!empty($message)) {
                        foreach ($message as $msg) {
                            echo '
                            <div class="message">
                                <span>' . $msg . '</span>
                                <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                            </div>
                            ';
                        }
                    }
                    ?>
                </section>
            </div>
        </div>
    </section>
    <script type="text/javascript" src="script.js"></script>
</body>

</html>
