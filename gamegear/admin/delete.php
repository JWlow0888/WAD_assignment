<?php
session_start();
$pageTitle = "Delete Post";

if (!isset($_SESSION['email'])) { header('Location: login.php'); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="../style/style.css">
  <title>Delete Post | GameGear Exchange</title>
</head>
<body>
  <?php include('../content/header.php'); ?>
  <?php include('../content/navigation.php'); ?>

<div id="contentWrapper">
    <div class="form-container" style="text-align:center;">
        <h2>Delete Post</h2>
        <?php
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            echo '<p class="error">Invalid post ID.</p>';
        } else {
            $conn = mysqli_connect('localhost', 'root', '', 'gamegear_db');

            if (!$conn) {
                die('<p class="error">Could not connect to the database.</p>');
            } else {
                $sql = "DELETE FROM announcement WHERE id = $id";

                if (mysqli_query($conn, $sql) && mysqli_affected_rows($conn) > 0) {
                    echo '<div class="success-message">Post deleted successfully.</div>';
                } else {
                    echo '<p class="error">Post not found or could not be deleted.</p>';
                }
                mysqli_close($conn);
            }
        }
        ?>
        <br><br>
        <a href="dashboard.php" class="cta-button">Back to Dashboard</a>
    </div>
</div>

  <?php include('../content/footer.php'); ?>
</body>
</html>