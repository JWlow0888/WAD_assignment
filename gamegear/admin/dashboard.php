<?php
$pageTitle = "Admin Dashboard";
session_start();

// Redirect to login if the session is not set
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'gamegear_db'; 
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <title>Admin Dashboard | GameGear Exchange</title>
</head>
<body>
    <?php include('../content/header.php'); ?>
    <?php include('../content/navigation.php'); ?>

    <div id="contentWrapper">
        <div class="form-container" style="max-width: 900px;">
            <h1 style="color: rgb(30, 136, 229);">Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h1>
            <p>You have successfully authenticated into the GameGear Exchange Admin Dashboard.</p>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; margin-top: 40px;">
                <h2>Manage Announcements</h2>
                <div>
                    <a href='create.php' class="submit-btn" style="text-decoration:none; padding: 8px 15px;">+ Create New Post</a>
                    <a href='logout.php' style="margin-left: 15px; color: #ff3333; text-decoration: none; font-weight: bold;">Logout</a>
                </div>
            </div>
            
            <?php
            // The $conn variable now exists and will work here
            if (!$conn) {
                die('<div class="error">Could not connect to the database: ' . mysqli_connect_error() . '</div>');
            } else {
                $sql = "SELECT * FROM announcement ORDER BY posted DESC";
                $result = mysqli_query($conn, $sql);
                
                if (!$result) {
                    echo '<p class="error">Failed to load posts.</p>';
                } elseif (mysqli_num_rows($result) === 0) {
                    echo '<p>No announcements found.</p>';
                } else {
                    echo "<table style='width:100%;'>";
                    echo "<thead><tr><th>Subject</th><th>Message</th><th>Type</th><th>Posted</th><th colspan='2'>Actions</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td><strong>" . htmlspecialchars($row['subject']) . "</strong></td>";
                        echo "<td>" . htmlspecialchars(substr($row['message'], 0, 50)) . "...</td>"; // Truncate long messages
                        echo "<td>" . ($row['type'] == 'P' ? 'Platform Update' : 'General News') . "</td>";
                        echo "<td>" . $row['posted'] . "</td>";
                        echo "<td><a href='update.php?id=" . $row['id'] . "' style='color: rgb(30, 136, 229);'>Edit</a></td>";
                        echo "<td><a href='delete.php?id=" . $row['id'] . "' style='color:#ff3333;' onclick='return confirm(\"Are you sure you want to delete this?\");'>Delete</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                }
                mysqli_close($conn);
            }
            ?>
        </div>
    </div>

    <?php include('../content/footer.php'); ?>
</body>
</html>