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

<div style="text-align: center; margin-bottom: 30px; margin-top: 20px;">
    <input 
        type="text" 
        id="searchBox" 
        onkeyup="searchListings()" 
        placeholder="🔍 Search for game gear by name..." 
        style="width: 80%; max-width: 600px; padding: 12px 20px; font-size: 16px; border: 2px solid #dee2e6; border-radius: 25px;">
</div>

<div id="productGrid" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px;">
<table style="width: 100%; max-width: 1200px; margin: 0 auto;">
    <caption>Available GameGear Marketplace Listings and Further Details</caption>
    <thead>
        <tr>
            <th style="width: 12%; text-align: center;">Image</th>
            <th style="width: 32%;">Item Details</th>
            <th style="width: 20%; text-align: center;">Category</th>
            <th style="width: 13%; text-align: center;">Condition</th>
            <th style="width: 13%; text-align: center;">Price</th>
	    <th style="width: 10%; text-align: center; white-space: nowrap;">Action</th>
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
                echo '  <td style="text-align: center;"><img src="../images/' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['title']) . '" style="width: 100px; height: auto;"></td>';
                echo '  <td><strong>'. htmlspecialchars($row['title']) .'</strong></td>';
                echo '  <td style="text-align: center;">'. htmlspecialchars($row['category_name']) .'</td>';
                echo '  <td style="text-align: center;">'. htmlspecialchars($row['item_condition']) .'</td>';
                echo '  <td class="new-price" style="text-align: center; font-weight: bold; color: #5ea825;">RM '. number_format($row['price'], 2) .'</td>';
                echo '  <td style="text-align: center; white-space: nowrap;"><a href="details.php?id='. $row['listing_id'] .'" class="cta-button" style="padding: 8px 12px; font-size: 14px; text-decoration: none; background-color: #82e043; color: #111111; font-weight: bold; border-radius: 4px;">View Details</a></td>';
                
                echo '</tr>';
                }
                mysqli_close($conn);
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function searchListings() {
        var text = document.getElementById("searchBox").value;
        var ajax = new XMLHttpRequest();

        ajax.open("GET", "search_listings.php?query=" + encodeURIComponent(text), true);
        ajax.send();

        ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
                document.getElementById("productTableBody").innerHTML = ajax.responseText;
            }
        };
    }
</script>

</table>

</div>
	<?php include('../content/footer.php'); ?>
</body>
</html>