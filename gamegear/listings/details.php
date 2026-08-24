<?php
$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$item = null;
$pageTitle = "Item Not Found";

if ($item_id > 0) {
    $conn = mysqli_connect('localhost', 'root', '', 'gamegear_db');
    if ($conn) {
        $sql = "SELECT * FROM products WHERE id = $item_id LIMIT 1";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $item = mysqli_fetch_assoc($result);
            $pageTitle = $item['title']; 
        }
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css"> 
    <title><?php echo htmlspecialchars($pageTitle); ?> | GameGear Exchange</title>
</head>
<body>
  <?php include('../content/header.php'); ?>
  <?php include('../content/navigation.php'); ?>

<div id="contentWrapper">
    <?php if ($item): ?>
    <div class="details-layout" style="display: flex; gap: 40px; max-width: 1100px; margin: 0 auto; align-items: flex-start;">
        
        <div class="details-image-section" style="flex: 1; width: 50%;">
            <img src="../images/<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" style="width: 100%; height: auto; max-height: 500px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 1px solid #dee2e6;">
        </div>

        <div class="details-info-section" style="flex: 1; width: 50%; text-align: left; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #dee2e6;">
            
            <div class="tags" style="margin-bottom: 10px;">
                <span style="background-color: rgb(33, 37, 41); color: #fff; padding: 5px 12px; border-radius: 20px; font-size: 0.85em; font-weight: bold; margin-right: 10px; text-transform: uppercase;">
                    <?php echo htmlspecialchars($item['category']); ?>
                </span>
                <span style="background-color: rgb(255, 209, 102); color: rgb(33, 37, 41); padding: 5px 12px; border-radius: 20px; font-size: 0.85em; font-weight: bold;">
                    <?php echo htmlspecialchars($item['condition_status']); ?>
                </span>
            </div>
            
            <h2 style="margin-top: 15px; font-size: 2.2em; color: rgb(33, 37, 41); margin-bottom: 5px;">
                <?php echo htmlspecialchars($item['title']); ?>
            </h2>
            
            <div style="font-size: 2em; font-weight: bold; color: rgb(30, 136, 229); margin-bottom: 20px;">
                RM <?php echo number_format($item['price'], 2); ?>
            </div>

            <div style="margin-bottom: 30px;">
                <h3 style="border-bottom: 2px solid #f0f4f8; padding-bottom: 10px;">Item Description</h3>
                <p style="line-height: 1.6; color: #444;">
                    <?php echo nl2br(htmlspecialchars($item['description'])); ?>
                </p>
            </div>
            
            <?php if ($item['is_available'] == 1): ?>
                <div class="action-buttons" style="display: flex; gap: 15px;">

                    <a href="../purchase/purchase.php?id=<?php echo $item['id']; ?>" class="cta-button" style="flex-grow: 1; text-align: center; padding: 15px; font-size: 16px;">Buy Now</a>
                </div>
            <?php else: ?>
                <div style="background-color: #ffebee; color: #c62828; padding: 15px; border-radius: 4px; text-align: center; font-weight: bold; border: 1px solid #ffcdd2;">
                    This item is currently out of stock or no longer available.
                </div>
            <?php endif; ?>

        </div>
    </div>
    
    <?php else: ?>
        <div style="text-align: center; padding: 50px;">
            <h2>Oops! We couldn't find that gear.</h2>
            <a href="../feature/index.php" class="cta-button">Back to Featured</a>
        </div>
    <?php endif; ?>
</div>

  <?php include('../content/footer.php'); ?>
</body>
</html>