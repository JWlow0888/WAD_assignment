<?php include('../includes/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <title>GameGear Exchange - Item Details</title>
</head>
<body>
    <?php include('../content/header.php'); ?>
    <?php include('../content/navigation.php'); ?>

    <div id="contentWrapper">

        <?php
        if (!isset($_GET['id'])) {
            echo "<p>No item selected. <a href='../listings/index.php'>Browse listings</a></p>";
        } else {
            $id = mysqli_real_escape_string($conn, $_GET['id']);

            // READ: join listings with categories and users so we can
            // show category name and seller info, not just IDs
            $sql = "SELECT listings.*, categories.category_name, users.full_name, users.email
                    FROM listings
                    JOIN categories ON listings.category_id = categories.category_id
                    JOIN users ON listings.seller_id = users.user_id
                    WHERE listings.listing_id = '$id'";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $item = mysqli_fetch_assoc($result);
                ?>
                <article id="itemDetails">
                    <h1><?php echo htmlspecialchars($item['title']); ?></h1>

                    <?php if ($item['image_path'] != "") { ?>
                        <img src="../<?php echo $item['image_path']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" width="400">
                    <?php } ?>

                    <p class="price">RM <?php echo number_format($item['price'], 2); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category_name']); ?></p>
                    <p><strong>Condition:</strong> <?php echo htmlspecialchars($item['item_condition']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($item['status']); ?></p>
                    <p><strong>Description:</strong><br><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                    <p><strong>Seller:</strong> <?php echo htmlspecialchars($item['full_name']); ?> (<?php echo htmlspecialchars($item['email']); ?>)</p>
                    <p><strong>Posted on:</strong> <?php echo date("d M Y", strtotime($item['created_at'])); ?></p>

                    <a href="../listings/edit.php?id=<?php echo $item['listing_id']; ?>" class="button">Edit Listing</a>
                    <a href="../listings/index.php?delete=<?php echo $item['listing_id']; ?>" class="button" onclick="return confirm('Delete this listing?')">Delete Listing</a>
                </article>
                <?php
            } else {
                echo "<p>Item not found.</p>";
            }
        }
        ?>

    </div>

    <?php include('../content/footer.php'); ?>
</body>
</html>
