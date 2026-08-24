<?php
$pageTitle = "Featured Items On Sale";
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
    <div style="text-align: center; margin-bottom: 30px;">
        <h2>⚡ Weekend Flash Clearance ⚡</h2>
        <p>Certified pre-owned gear at maximum markdown.</p>
    </div>

    <div class="filter-controls" style="text-align: center; margin-bottom: 40px;">
        <button class="filter-btn active" onclick="filterItems('all', this)">All Featured</button>
        <button class="filter-btn" onclick="filterItems('console', this)">Consoles</button>
        <button class="filter-btn" onclick="filterItems('pc', this)">PC Parts</button>
        <button class="filter-btn" onclick="filterItems('game', this)">Games</button>
    </div>

    <div class="featured-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">
        
        <?php
        $conn = mysqli_connect('localhost', 'root', '', 'gamegear_db');
        if ($conn) {
            $sql = "SELECT * FROM products WHERE is_featured = 1 ORDER BY posted DESC";
            $result = mysqli_query($conn, $sql);
            
            while ($row = mysqli_fetch_assoc($result)) {
                $cat = strtolower(htmlspecialchars($row['category']));
                
                echo '<div class="item-card product-item" data-category="'. $cat .'" style="background: #fff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column;">';
                
				echo '<img src="../images/' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['title']) . '" style="width: 100%; height: 250px; object-fit: cover;">';
                
                echo '  <div class="card-body" style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column;">';
                echo '      <h3 style="margin-top: 0; font-size: 1.3em;">'. htmlspecialchars($row['title']) .'</h3>';
                echo '      <p style="margin: 5px 0;">Condition: <strong>'. htmlspecialchars($row['condition_status']) .'</strong></p>';
                echo '      <div class="price-box" style="margin-bottom: 15px;">';
                echo '          <h5 style="color: rgb(30, 136, 229); font-size: 1.4em; margin: 10px 0;">RM '. number_format($row['price'], 2) .'</h5>';
                echo '      </div>';
                
                echo '      <div style="margin-top: auto;">';
                echo '          <a href="../listings/details.php?id='. $row['id'] .'" class="cta-button" style="display: block; text-align: center;">View Details</a>';
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

<script>
function filterItems(category, buttonElement) {
    const buttons = document.querySelectorAll('.filter-btn');
    buttons.forEach(btn => btn.style.backgroundColor = '#ffffff');
    buttons.forEach(btn => btn.style.color = '#000000');
    
    buttonElement.style.backgroundColor = 'rgb(30, 136, 229)';
    buttonElement.style.color = '#ffffff';

    const cards = document.querySelectorAll('.product-item');
    
    cards.forEach(card => {
        const itemCat = card.getAttribute('data-category');
        
        if (category === 'all' || itemCat === category) {
            card.style.display = 'flex'; 
        } else {
            card.style.display = 'none'; 
        }
    });
}

window.onload = function() {
    const firstButton = document.querySelector('.filter-btn');
    if(firstButton) {
        firstButton.style.backgroundColor = 'rgb(30, 136, 229)';
        firstButton.style.color = '#ffffff';
    }
};
</script>

</body>
</html>