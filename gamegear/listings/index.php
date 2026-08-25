<?php
$pageTitle = "Product Listings & Details";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <title>Listings & Details | GameGear Exchange</title>
</head>
<body>
  	<?php include('../content/header.php'); ?>
 	<?php include('../content/navigation.php'); ?>

<div id="contentWrapper">

<table style="width: 100%;">
    <caption>Available GameGear Marketplace Listings and Further Details</caption>
    <thead>
        <tr>
            <th>Image</th>
            <th>Item Details</th>
            <th>Category</th>
            <th>Condition</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="productTableBody">
        <?php
        $conn = mysqli_connect('localhost', 'root', '', 'gamegear_exchange');
        if ($conn) {

            $sql = "SELECT l.*, c.category_name FROM listings l JOIN categories c ON c.category_id = l.category_id ORDER BY l.created_at DESC";
            $result = mysqli_query($conn, $sql);
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr class="product-item" data-category="'. strtolower(htmlspecialchars($row['category_name'])) .'">';
                
				echo '  <td><img src="../images/' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['title']) . '" style="width: 100px; height: auto;"></td>';
                
                echo '  <td><strong>'. htmlspecialchars($row['title']) .'</strong></td>';
                echo '  <td>'. htmlspecialchars($row['category_name']) .'</td>';
                echo '  <td>'. htmlspecialchars($row['item_condition']) .'</td>';
                echo '  <td class="new-price">RM '. number_format($row['price'], 2) .'</td>';
                

                echo '  <td><a href="details.php?id='. $row['listing_id'] .'" class="cta-button" style="padding: 8px 12px; font-size: 14px; text-decoration: none;">View Details</a></td>';
                
                echo '</tr>';
            }
            mysqli_close($conn);
        }
        ?>
    </tbody>
</table>

</div>
	<?php include('../content/footer.php'); ?>
</body>
</html>