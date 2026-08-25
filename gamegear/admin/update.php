<?php
session_start();
$pageTitle = "Update Announcement";

if (!isset($_SESSION['email'])) { header('Location: login.php'); exit(); }

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'gamegear_exchange';
$message_out = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $type = isset($_POST['type']) ? strtoupper(trim($_POST['type'])) : '';

    if ($id <= 0 || $subject === '' || $message === '' || $type === '') {
        $message_out = '<p class="error">Invalid input. Please fill in all fields.</p>';
    } else {
        $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
        if ($conn) {
            $subject = mysqli_real_escape_string($conn, $subject);
            $message = mysqli_real_escape_string($conn, $message);
            $type = mysqli_real_escape_string($conn, $type);

            $sql = "UPDATE announcement SET subject='$subject', message='$message', type='$type' WHERE id=$id";

            if (mysqli_query($conn, $sql)) {
                $message_out = '<div class="success-message">Post updated successfully. <a href="dashboard.php">Back to Dashboard</a></div>';
            } else {
                $message_out = '<p class="error">Failed to update post.</p>';
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
  <title>Update Post | GameGear Exchange</title>
</head>
<body>
<?php include('../content/header.php'); ?>
<?php include('../content/navigation.php'); ?>

<div id="contentWrapper">
    <div class="form-container">
        <h2>Update Post</h2>
        <?php echo $message_out; ?>

        <?php
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || strpos($message_out, 'Failed') !== false || strpos($message_out, 'Invalid') !== false) {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);

            if ($id > 0) {
                $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
                if ($conn) {
                    $sql = "SELECT subject, message, type FROM announcement WHERE id=$id LIMIT 1";
                    $result = mysqli_query($conn, $sql);

                    if ($result && mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $subjectValue = htmlspecialchars($row['subject']);
                        $messageValue = htmlspecialchars($row['message']);
                        $typeValue = htmlspecialchars($row['type']);
        ?>
        <form action="update.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" maxlength="255" value="<?php echo $subjectValue; ?>" required>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="8" required><?php echo $messageValue; ?></textarea>
            </div>

            <div class="form-group">
                <label for="type">Post Type</label>
                <select id="type" name="type">
                    <option value="P" <?php echo $typeValue === 'P' ? 'selected' : ''; ?>>Platform Update</option>
                    <option value="N" <?php echo $typeValue === 'N' ? 'selected' : ''; ?>>General News</option>
                </select>
            </div>

            <button type="submit" name="update_post" class="cta-button" style="width:100%;">Update Post</button>
            <a href="dashboard.php" style="display:block; text-align:center; margin-top:15px;">Cancel</a>
        </form>
        <?php
                    } else {
                        echo '<p class="error">Post not found.</p><a href="dashboard.php">Back to Dashboard</a>';
                    }
                    mysqli_close($conn);
                }
            } else {
                echo '<p class="error">No post selected.</p><a href="dashboard.php">Back to Dashboard</a>';
            }
        }
        ?>
    </div>
</div>
<?php include('../content/footer.php'); ?>
</body>
</html>