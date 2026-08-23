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
        $errors = array();
        $success_msg = "";

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            echo "<p>No listing selected. <a href='index.php'>Back to Listings</a></p>";
        } else {
            $id = $_GET['id'];


            // UPDATE: Handle form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_listing'])) {

                if (trim($_POST['title']) == "") {
                    $errors[] = "Title cannot be empty.";
                }
                if (empty($_POST['price']) || !is_numeric($_POST['price']) || $_POST['price'] <= 0) {
                    $errors[] = "Price must be a positive number.";
                }

                if (empty($errors)) {
                    $category_id = $_POST['category_id'];
                    $title       = trim($_POST['title']);
                    $description = trim($_POST['description']);
                    $price       = $_POST['price'];
                    $condition   = $_POST['item_condition'];
                    $status      = $_POST['status'];

                    $new_image_uploaded = false;

                    // Optional: replace image if a new one was chosen
                    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                        $image_name = time() . "_" . basename($_FILES['image']['name']);
                        $target = "../uploads/" . $image_name;
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                            $image_path = "uploads/" . $image_name;
                            $new_image_uploaded = true;
                        }
                    }

                    if ($new_image_uploaded) {
                        $stmt = mysqli_prepare($conn, "UPDATE listings SET category_id=?, title=?, description=?, price=?, item_condition=?, status=?, image_path=? WHERE listing_id=?");
                        mysqli_stmt_bind_param($stmt, "issdsssi", $category_id, $title, $description, $price, $condition, $status, $image_path, $id);
                    } else {
                        // No new image chosen - keep the existing image_path untouched
                        $stmt = mysqli_prepare($conn, "UPDATE listings SET category_id=?, title=?, description=?, price=?, item_condition=?, status=? WHERE listing_id=?");
                        mysqli_stmt_bind_param($stmt, "issdssi", $category_id, $title, $description, $price, $condition, $status, $id);
                    }

                    if (mysqli_stmt_execute($stmt)) {
                        $success_msg = "Listing updated successfully!";
                    } else {
                        $errors[] = "Database error: " . mysqli_error($conn);
                    }
                    mysqli_stmt_close($stmt);
                }
            }

            if (!empty($errors)) {
                foreach ($errors as $err) {
                    echo "<p class='error-msg'>" . htmlspecialchars($err) . "</p>";
                }
            }
            if ($success_msg != "") {
                echo "<p class='success-msg'>" . htmlspecialchars($success_msg) . "</p>";
            }

            // Fetch current listing data to pre-fill the form
            $stmt = mysqli_prepare($conn, "SELECT * FROM listings WHERE listing_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $listing = mysqli_fetch_assoc($result);

            if ($listing) {
                ?>
                <form action="edit.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">

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

                    <label for="image">Replace Image (optional):</label>
                    <input type="file" name="image" id="image" accept="image/*">
                    <?php if ($listing['image_path'] != "") { ?>
                        <p>Current image:</p>
                        <img src="../<?php echo htmlspecialchars($listing['image_path']); ?>" width="150">
                    <?php } ?>

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

