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
        </tr>
    </thead>
    <tbody id="productTableBody">

        <tr class="product-item" data-category="console" data-price="1200" data-title="playstation 4 pro 1tb">
            <td><img src="ps4.jpg" style="width: 80px; border-radius: 4px;"></td>
            <td><strong>PlayStation 4 Pro (1TB)</strong></td>
            <td>Console</td>
            <td>Used - Good</td>
            <td class="new-price">RM 1,200</td>
        </tr>


        <tr class="product-item" data-category="pc" data-price="850" data-title="msi gtx 1660 super">
            <td><img src="1660.jpg" style="width: 80px; border-radius: 4px;"></td>
            <td><strong>MSI GTX 1660 Super</strong></td>
            <td>PC Parts</td>
            <td>Refurbished</td>
            <td class="new-price">RM 850</td>
        </tr>


        <tr class="product-item" data-category="game" data-price="150" data-title="the legend of zelda breath of the wild switch">
            <td><img src="game.jpg" style="width: 80px; border-radius: 4px;"></td>
            <td><strong>Zelda: Breath of the Wild (Switch)</strong></td>
            <td>Games</td>
            <td>Like New</td>
            <td class="new-price">RM 150</td>
        </tr>


        <tr class="product-item" data-category="console" data-price="1800" data-title="xbox series s">
            <td><img src="xbox.jpg" style="width: 80px; border-radius: 4px;"></td>
            <td><strong>Xbox Series S 512GB</strong></td>
            <td>Console</td>
            <td>Open Box</td>
            <td class="new-price">RM 1,800</td>
        </tr>
    </tbody>
</table>

</div>
	<?php include('../content/footer.php'); ?>
</body>
</html>