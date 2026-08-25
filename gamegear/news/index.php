<?php
$pageTitle = "Announcement";

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'gamegear_exchange';
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <title>Announcements | GameGear Exchange</title>
</head>
<body>
    <?php include('../content/header.php'); ?>
    <?php include('../content/navigation.php'); ?>

    <div id="contentWrapper">
        <div class="news-container">
            <?php
            if (!$conn) {
                // Failsafe if the database goes down
                echo '<div class="error" style="text-align:center; padding: 40px; color: red;">Our news feed is currently offline for maintenance. Please check back later.</div>';
            } else {
                // Fetch announcements from newest to oldest
                $sql = "SELECT * FROM announcement ORDER BY posted DESC";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    // Loop through each announcement and generate a card
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Apply different badge styles based on the post type (P or N)
                        if ($row['type'] == 'P') {
                            $typeBadge = '<span class="badge category-badge">Platform Update</span>';
                        } else {
                            $typeBadge = '<span class="badge condition-badge">General News</span>';
                        }

                        // Format the DATETIME from the database into a readable format
                        $formattedDate = date('F j, Y, g:i a', strtotime($row['posted']));

                        echo '<div class="news-card">';
                        echo '  <div class="news-header">';
                        echo '      <h3 style="margin:0; font-size: 1.6em; color: rgb(30, 136, 229);">' . htmlspecialchars($row['subject']) . '</h3>';
                        echo        $typeBadge;
                        echo '  </div>';
                        echo '  <div class="news-meta" style="font-size: 0.85em; color: rgb(140, 140, 140); margin-bottom: 15px; font-style: italic;">Posted on ' . $formattedDate . '</div>';
                        
                        // nl2br ensures line breaks in the database are rendered as <br> tags in HTML
                        echo '  <div class="news-body"><p style="margin:0; color: rgb(33, 37, 41);">' . nl2br(htmlspecialchars($row['message'])) . '</p></div>';
                        echo '</div>';
                    }
                } else {
                    // Empty state if the admin hasn't posted anything yet
                    echo '<div style="text-align:center; padding: 50px; background: #fff; border-radius: 8px; border: 1px solid rgb(225, 230, 238);">';
                    echo '  <h3 style="color: rgb(30, 136, 229);">No announcements at this time.</h3>';
                    echo '  <p style="color: rgb(33, 37, 41);">Stay tuned for upcoming gear drops and platform updates!</p>';
                    echo '</div>';
                }
                mysqli_close($conn);
            }
            ?>
        </div>
    </div>

    <?php include('../content/footer.php'); ?>
</body>
</html>