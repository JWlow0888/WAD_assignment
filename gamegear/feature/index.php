<?php
$pageTitle = "⚡ Weekend Flash Clearance ⚡";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <title>Featured Items | GameGear Exchange</title>
</head>
<body>
    <?php include('../content/header.php'); ?>
    <?php include('../content/navigation.php'); ?>

<div id="contentWrapper">
    <div class="filter-controls" style="text-align: center; margin-bottom: 40px;">
        <button class="filter-btn active" onclick="filterItems('all', this)">All Featured</button>
        <?php
        $cat_conn = mysqli_connect('localhost', 'root', '', 'gamegear_exchange');
        if ($cat_conn) {
            $cat_result = mysqli_query($cat_conn, "SELECT * FROM categories ORDER BY category_name");
            while ($cat = mysqli_fetch_assoc($cat_result)) {
                $slug = strtolower(htmlspecialchars($cat['category_name']));
                echo '<button class="filter-btn" onclick="filterItems(\'' . $slug . '\', this)">' . htmlspecialchars($cat['category_name']) . '</button>';
            }
            mysqli_close($cat_conn);
        }
        ?>
    </div>

    <div class="featured-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">
        
        <?php
        $conn = mysqli_connect('localhost', 'root', '', 'gamegear_exchange');
        if ($conn) {
            $sql = "SELECT l.*, c.category_name FROM listings l JOIN categories c ON c.category_id = l.category_id WHERE l.is_featured = 1 ORDER BY l.created_at DESC";
            $result = mysqli_query($conn, $sql);
            
            while ($row = mysqli_fetch_assoc($result)) {
                $cat = strtolower(htmlspecialchars($row['category_name']));
                
                echo '<div class="item-card product-item" data-category="'. $cat .'" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column;">';
                
				echo '<img src="../images/' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['title']) . '" style="width: 100%; height: 250px; object-fit: cover;">';
                
                echo '  <div class="card-body" style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column;">';
                echo '      <h3 style="margin-top: 0; font-size: 1.3em;">'. htmlspecialchars($row['title']) .'</h3>';
                echo '      <p style="margin: 5px 0;">Condition: <strong>'. htmlspecialchars($row['item_condition']) .'</strong></p>';
                echo '      <div class="price-box" style="margin-bottom: 15px;">';
                echo '          <h5 style="color: rgb(30, 136, 229); font-size: 1.4em; margin: 10px 0;">RM '. number_format($row['price'], 2) .'</h5>';
                echo '      </div>';
                
                echo '      <div style="margin-top: auto;">';
                echo '          <a href="../listings/details.php?id='. $row['listing_id'] .'" class="cta-button" style="display: block; text-align: center;">View Details</a>';
                echo '      </div>';
                echo '  </div>';
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