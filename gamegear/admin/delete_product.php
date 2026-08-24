<?php
session_start();
$pageTitle = "Delete Product";

if (!isset($_SESSION['email'])) { header('Location: login.php'); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../style/style.css">
  <title>Delete Product | GameGear Exchange</title>
</head>
<body>
  <?php include('../content/header.php'); ?>
  <?php include('../content/navigation.php'); ?>

<div id="contentWrapper">
    <div class="form-container" style="text-align:center;">
        <h2 style="margin-top: 0; color: #ff3333;">Delete Product</h2>
        <?php

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            echo '<p class="error">Invalid product ID.</p>';
        } else {

            $conn = mysqli_connect('localhost', 'root', '', 'gamegear_db');

            if (!$conn) {
                die('<p class="error">Could not connect to the database.</p>');
            } else {
                $sql = "DELETE FROM products WHERE id = $id";

                if (mysqli_query($conn, $sql) && mysqli_affected_rows($conn) > 0) {
                    echo '<div class="success-message" style="background-color: #fff3f3; border-color: #ffcccc; color: #cc0000;">
                            Product has been permanently deleted from the database.
                          </div>';
                } else {
                    echo '<p class="error">Product not found or could not be deleted.</p>';
                }
                mysqli_close($conn);
            }
        }
        ?>
        <br><br>
        <a href="dashboard.php" class="cta-button" style="text-decoration:none; padding: 10px 20px;">Back to Dashboard</a>
    </div>
</div>

  <?php include('../content/footer.php'); ?>
</body>
</html>