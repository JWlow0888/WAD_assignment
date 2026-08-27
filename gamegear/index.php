<?php
$pageTitle = "Certified Secondhand Quality";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <title>Home | GameGear Exchange</title>
</head>
<body>
  	<?php include('content/header.php'); ?>
 	<?php include('content/navigation.php'); ?>
<div id="contentWrapper">
	<article>
		<p>The GameGear Exchange is a secondhand gaming equipment marketplace website where every console and component goes through our rigorous testing process. Game with absolute peace of mind.</p>
		<img src="game.jpg" width="960" height="300">
		<ul>
			<li>✔️ Deep-cleaned components</li>
			<li>✔️ Diagnostic hardware testing</li>
			<li>✔️ 30-Day Money-Back Guarantee</li>
		</ul>
	</article>

<hr style="border: 0; border-top: 1px solid #dee2e6; margin: 50px auto; max-width: 800px;">

    <div style="max-width: 800px; margin: 0 auto 50px auto; background-color: #e3f2fd; border-left: 6px solid rgb(30, 136, 229); padding: 20px 25px; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); text-align: left;">
        <?php
        $conn = mysqli_connect('localhost', 'root', '', 'gamegear_exchange');
        
        if ($conn) {
            // Fetch only the most recent Platform Update (type 'P')
            $sql_announce = "SELECT subject, message, posted FROM announcement WHERE type = 'P' ORDER BY posted DESC LIMIT 1";
            $result_announce = mysqli_query($conn, $sql_announce);
            
            if (mysqli_num_rows($result_announce) > 0) {
                $announcement = mysqli_fetch_assoc($result_announce);
                
                echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">';
                echo '  <h3 style="margin: 0; color: rgb(30, 136, 229); font-size: 1.4em;">📢 ' . htmlspecialchars($announcement['subject']) . '</h3>';
                
                // Formats the date to look clean (e.g., Aug 27, 2026)
                echo '  <span style="font-size: 0.9em; background: #fff; padding: 4px 10px; border-radius: 12px; color: #555; font-weight: bold; border: 1px solid #bbdefb;">' . date('M d, Y', strtotime($announcement['posted'])) . '</span>';
                echo '</div>';
                
                echo '<p style="margin: 0; color: #333; line-height: 1.6;">' . nl2br(htmlspecialchars($announcement['message'])) . '</p>';
            } else {
                // Fallback message if no announcements exist yet
                echo '<h3 style="margin: 0 0 10px 0; color: rgb(30, 136, 229); font-size: 1.4em;">📢 Welcome to GameGear Exchange!</h3>';
                echo '<p style="margin: 0; color: #333;">Check back here for the latest platform updates, feature drops, and community news.</p>';
            }
            mysqli_close($conn);
        }
        ?>
    </div>

    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="font-size: 2em; color: rgb(33, 37, 41);">🔥 Featured Gear</h2>
        <p style="color: #666; font-size: 1.1em;">Check out some of our best currently available setups and accessories.</p>
    </div>

    <!-- Flexbox grid to center the product cards -->
    <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 25px; margin-bottom: 40px; padding: 0 20px;">
        <?php
        // Connect to the database
        $conn = mysqli_connect('localhost', 'root', '', 'gamegear_exchange');
        
        if ($conn) {
            // Query for up to 3 available items where is_featured is set to 1
            $sql = "SELECT l.*, c.category_name 
                    FROM listings l 
                    JOIN categories c ON l.category_id = c.category_id 
                    WHERE l.status = 'Available' AND l.is_featured = 1 
                    ORDER BY l.created_at DESC 
                    LIMIT 3";
                    
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="product-card" style="border: 1px solid #dee2e6; padding: 20px; width: 280px; text-align: center; border-radius: 10px; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: transform 0.2s;">';
                    
                    echo '  <img src="images/' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['title']) . '" style="width: 100%; height: 180px; object-fit: cover; border-radius: 6px; margin-bottom: 15px;">';
                    
                    echo '  <h3 style="margin: 0 0 5px 0; font-size: 1.2em; color: #333;">' . htmlspecialchars($row['title']) . '</h3>';
                    
                    echo '</div>';
                }
            } else {
                echo '<p style="color: #666;">New featured gear coming soon!</p>';
            }
            mysqli_close($conn);
        }
        ?>
    </div>

    <div style="text-align: center; margin-bottom: 60px;">
        <a href="feature/index.php" class="submit-btn" style="text-decoration: none; padding: 15px 30px; font-size: 18px; background-color: rgb(33, 37, 41);">View More Details Here ➔</a>
    </div>

    <div class="quick-access-bar">
        <strong>Quick Access:</strong> 
        <a href="/gamegear/admin/register.php">Admin Register & Login</a>
        <a href="/gamegear/admin/dashboard.php">Admin Dashboard</a>
        <a href="/gamegear/listings/index.php">Browse Full Marketplace</a>
    </div>

</div>
	<?php include('content/footer.php'); ?>
</body>
</html>