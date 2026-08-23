<?php include('../includes/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <title>GameGear Exchange - Browse Listings</title>
</head>
<body>
    <?php include('../content/header.php'); ?>
    <?php include('../content/navigation.php'); ?>

    <div id="contentWrapper">
        <h1>Browse Listings</h1>

        <?php

        // CREATE: Handle new listing submission
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_listing'])) {
            $seller_id   = mysqli_real_escape_string($conn, $_POST['seller_id']);
            $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
            $title       = mysqli_real_escape_string($conn, $_POST['title']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $price       = mysqli_real_escape_string($conn, $_POST['price']);
            $condition   = mysqli_real_escape_string($conn, $_POST['item_condition']);

            // Handle image upload (optional)
            $image_path = "";
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image_name = time() . "_" . basename($_FILES['image']['name']);
                $target = "../uploads/" . $image_name;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $image_path = "uploads/" . $image_name;
                }
            }

            $sql = "INSERT INTO listings (seller_id, category_id, title, description, price, item_condition, image_path)
                    VALUES ('$seller_id', '$category_id', '$title', '$description', '$price', '$condition', '$image_path')";

            if (mysqli_query($conn, $sql)) {
                echo "<p class='success-msg'>Listing added successfully!</p>";
            } else {
                echo "<p class='error-msg'>Error: " . mysqli_error($conn) . "</p>";
            }
        }


        // DELETE: Handle listing deletion
        if (isset($_GET['delete'])) {
            $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
            $sql = "DELETE FROM listings WHERE listing_id = '$delete_id'";

            if (mysqli_query($conn, $sql)) {
                echo "<p class='success-msg'>Listing deleted.</p>";
            } else {
                echo "<p class='error-msg'>Error deleting: " . mysqli_error($conn) . "</p>";
            }
        }
        ?>

        <!-- CREATE FORM: Post a New Listing -->
        <section id="addListingForm">
            <h2>Post a New Listing</h2>
            <form action="index.php" method="post" enctype="multipart/form-data">

                <label for="seller_id">Seller ID:</label>
                <input type="number" name="seller_id" id="seller_id" required>

                <label for="category_id">Category:</label>
                <select name="category_id" id="category_id" required>
                    <?php
                    $cat_result = mysqli_query($conn, "SELECT * FROM categories");
                    while ($cat = mysqli_fetch_assoc($cat_result)) {
                        echo "<option value='" . $cat['category_id'] . "'>" . htmlspecialchars($cat['category_name']) . "</option>";
                    }
                    ?>
                </select>

                <label for="title">Title:</label>
                <input type="text" name="title" id="title" required>

                <label for="description">Description:</label>
                <textarea name="description" id="description" rows="3"></textarea>

                <label for="price">Price (RM):</label>
                <input type="number" step="0.01" name="price" id="price" required>

                <label for="item_condition">Condition:</label>
                <select name="item_condition" id="item_condition">
                    <option value="New">New</option>
                    <option value="Like New">Like New</option>
                    <option value="Used - Good">Used - Good</option>
                    <option value="Used - Fair">Used - Fair</option>
                </select>

                <label for="image">Item Image:</label>
                <input type="file" name="image" id="image" accept="image/*">

                <button type="submit" name="add_listing" class="button">Add Listing</button>
            </form>
        </section>

        <!-- READ FILTER: Filter by Category -->
        <section id="filterSection">
            <form action="index.php" method="get">
                <label for="filter_category">Filter by Category:</label>
                <select name="category_id" id="filter_category" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php
                    $cat_result2 = mysqli_query($conn, "SELECT * FROM categories");
                    while ($cat2 = mysqli_fetch_assoc($cat_result2)) {
                        $selected = (isset($_GET['category_id']) && $_GET['category_id'] == $cat2['category_id']) ? "selected" : "";
                        echo "<option value='" . $cat2['category_id'] . "' $selected>" . htmlspecialchars($cat2['category_name']) . "</option>";
                    }
                    ?>
                </select>
            </form>
        </section>

        <!-- READ: Listing Grid -->
        <section id="listingGrid">
            <?php
            if (isset($_GET['category_id']) && $_GET['category_id'] != "") {
                $cat_filter = mysqli_real_escape_string($conn, $_GET['category_id']);
                $sql = "SELECT * FROM listings WHERE category_id = '$cat_filter' ORDER BY created_at DESC";
            } else {
                $sql = "SELECT * FROM listings ORDER BY created_at DESC";
            }

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='listingCard'>";

                    if ($row['image_path'] != "") {
                        echo "<img src='../" . $row['image_path'] . "' alt='" . htmlspecialchars($row['title']) . "'>";
                    }

                    echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                    echo "<p class='price'>RM " . number_format($row['price'], 2) . "</p>";
                    echo "<p class='condition'>" . htmlspecialchars($row['item_condition']) . "</p>";
                    echo "<p class='status'>Status: " . htmlspecialchars($row['status']) . "</p>";
                    echo "<a href='../details/index.php?id=" . $row['listing_id'] . "'>View Details</a> | ";
                    echo "<a href='edit.php?id=" . $row['listing_id'] . "'>Edit</a> | ";
                    echo "<a href='index.php?delete=" . $row['listing_id'] . "' onclick=\"return confirm('Delete this listing?')\">Delete</a>";

                    echo "</div>";
                }
            } else {
                echo "<p>No listings found.</p>";
            }
            ?>
        </section>

    </div>

    <?php include('../content/footer.php'); ?>
</body>
</html>
