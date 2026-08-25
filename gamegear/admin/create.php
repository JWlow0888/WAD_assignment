<?php
session_start();
$pageTitle = "Create Announcement";

if (!isset($_SESSION['email'])) { header('Location: login.php'); exit(); }

$message_out = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $type = isset($_POST['type']) ? strtoupper(trim($_POST['type'])) : '';

    if ($subject === '' || $message === '' || $type === '') {
        $message_out = '<p class="error">Please fill in all fields.</p>';
    } else {
        $conn = mysqli_connect('localhost', 'root', '', 'gamegear_exchange');

        if (!$conn) {
            die('Could not connect: ' . mysqli_connect_error());
        } else {
            $subject = mysqli_real_escape_string($conn, $subject);
            $message = mysqli_real_escape_string($conn, $message);
            $type = mysqli_real_escape_string($conn, $type);

            $sql = "INSERT INTO announcement (subject, message, type, posted) VALUES ('$subject', '$message', '$type', NOW())";

            if (mysqli_query($conn, $sql)) {
                $message_out = '<div class="success-message">Post created successfully. <a href="dashboard.php">Back to Dashboard</a></div>';
            } else {
                $message_out = '<p class="error">Failed to create post.</p>';
            }
            mysqli_close($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="../style/style.css">
  <title>Create Post | GameGear Exchange</title>
</head>
<body>
  <?php include('../content/header.php'); ?>
  <?php include('../content/navigation.php'); ?>

<div id="contentWrapper">
    <div class="form-container">
        <h2>Create New Post</h2>
        <?php echo $message_out; ?>

        <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST' || strpos($message_out, 'Failed') !== false || strpos($message_out, 'Please fill') !== false): ?>
        <form action="create.php" method="post">
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" maxlength="255" required>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="8" required></textarea>
            </div>

            <div class="form-group">
                <label for="type">Post Type</label>
                <select id="type" name="type">
                    <option value="P">Platform Update</option>
                    <option value="N">General News</option>
                </select>
            </div>

            <button type="submit" name="create_post" class="cta-button" style="width:100%;">Create Post</button>
            <a href="dashboard.php" style="display:block; text-align:center; margin-top:15px;">Cancel</a>
        </form>
        <?php endif; ?>
    </div>
</div>
  <?php include('../content/footer.php'); ?>
</body>
</html>