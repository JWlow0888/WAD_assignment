<?php include('../includes/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <title>GameGear Exchange - Edit Listing</title>
</head>
<body>
    <?php include('../content/header.php'); ?>
    <?php include('../content/navigation.php'); ?>

    <div id="contentWrapper">
        <h1>Edit Listing</h1>

        <?php
        if (!isset($_GET['id'])) {
            echo "<p>No listing selected. <a href='index.php'>Back to Listings</a></p>";
        } else {
            $id = mysqli_real_escape_string($conn, $_GET['id']);


            // UPDATE: Handle form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_listing'])) {
                $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
                $title       = mysqli_real_escape_string($conn, $_POST['title']);
                $description = mysqli_real_escape_string($conn, $_POST['description']);
                $price       = mysqli_real_escape_string($conn, $_POST['price']);
                $condition   = mysqli_real_escape_string($conn, $_POST['item_condition']);
                $status      = mysqli_real_escape_string($conn, $_POST['status']);

                $sql = "UPDATE listings SET
                            category_id = '$category_id',
                            title = '$title',
                            description = '$description',
                            price = '$price',
                            item_condition = '$condition',
                            status = '$status'
                        WHERE listing_id = '$id'";

                if (mysqli_query($conn, $sql)) {
                    echo "<p class='success-msg'>Listing updated successfully!</p>";
                } else {
                    echo "<p class='error-msg'>Error: " . mysqli_error($conn) . "</p>";
                }
            }

            // Fetch current listing data to pre-fill the form
            $sql = "SELECT * FROM listings WHERE listing_id = '$id'";
            $result = mysqli_query($conn, $sql);
            $listing = mysqli_fetch_assoc($result);

            if ($listing) {
                ?>
                <form action="edit.php?id=<?php echo $id; ?>" method="post">

                    <label for="category_id">Category:</label>
                    <select name="category_id" id="category_id">
                        <?php
                        $cat_result = mysqli_query($conn, "SELECT * FROM categories");
                        while ($cat = mysqli_fetch_assoc($cat_result)) {
                            $selected = ($cat['category_id'] == $listing['category_id']) ? "selected" : "";
                            echo "<option value='" . $cat['category_id'] . "' $selected>" . htmlspecialchars($cat['category_name']) . "</option>";
                        }
                        ?>
                    </select>

                    <label for="title">Title:</label>
                    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($listing['title']); ?>" required>

                    <label for="description">Description:</label>
                    <textarea name="description" id="description" rows="3"><?php echo htmlspecialchars($listing['description']); ?></textarea>

                    <label for="price">Price (RM):</label>
                    <input type="number" step="0.01" name="price" id="price" value="<?php echo $listing['price']; ?>" required>

                    <label for="item_condition">Condition:</label>
                    <select name="item_condition" id="item_condition">
                        <?php
                        $conditions = array('New', 'Like New', 'Used - Good', 'Used - Fair');
                        foreach ($conditions as $c) {
                            $selected = ($c == $listing['item_condition']) ? "selected" : "";
                            echo "<option value='$c' $selected>$c</option>";
                        }
                        ?>
                    </select>

                    <label for="status">Status:</label>
                    <select name="status" id="status">
                        <option value="Available" <?php echo ($listing['status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                        <option value="Sold" <?php echo ($listing['status'] == 'Sold') ? 'selected' : ''; ?>>Sold</option>
                    </select>

                    <button type="submit" name="update_listing" class="button">Update Listing</button>
                </form>
                <?php
            } else {
                echo "<p>Listing not found.</p>";
            }
        }
        ?>

        <a href="index.php">&larr; Back to Listings</a>
    </div>

    <?php include('../content/footer.php'); ?>
</body>
</html>
