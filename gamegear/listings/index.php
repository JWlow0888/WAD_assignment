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
        $errors = array();
        $success_msg = "";

        // CREATE: Handle new listing submission
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_listing'])) {

            // ---- Server-side validation ----
            if (empty($_POST['seller_id']) || !is_numeric($_POST['seller_id'])) {
                $errors[] = "A valid Seller ID is required.";
            }
            if (empty($_POST['category_id'])) {
                $errors[] = "Please choose a category.";
            }
            if (trim($_POST['title']) == "") {
                $errors[] = "Title cannot be empty.";
            }
            if (empty($_POST['price']) || !is_numeric($_POST['price']) || $_POST['price'] <= 0) {
                $errors[] = "Price must be a positive number.";
            }

            if (empty($errors)) {
                $seller_id   = $_POST['seller_id'];
                $category_id = $_POST['category_id'];
                $title       = trim($_POST['title']);
                $description = trim($_POST['description']);
                $price       = $_POST['price'];
                $condition   = $_POST['item_condition'];

                // Handle image upload (optional)
                $image_path = "";
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $image_name = time() . "_" . basename($_FILES['image']['name']);
                    $target = "../uploads/" . $image_name;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                        $image_path = "uploads/" . $image_name;
                    }
                }


                $stmt = mysqli_prepare($conn, "INSERT INTO listings (seller_id, category_id, title, description, price, item_condition, image_path)
                                                VALUES (?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "iissdss", $seller_id, $category_id, $title, $description, $price, $condition, $image_path);

                if (mysqli_stmt_execute($stmt)) {
                    $success_msg = "Listing added successfully!";
                    $_POST = array();
                } else {
                    $errors[] = "Database error: " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmt);
            }
        }


        // DELETE: Handle listing deletion

        if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
            $delete_id = $_GET['delete'];
            $stmt = mysqli_prepare($conn, "DELETE FROM listings WHERE listing_id = ?");
            mysqli_stmt_bind_param($stmt, "i", $delete_id);

            if (mysqli_stmt_execute($stmt)) {
                $success_msg = "Listing deleted.";
            } else {
                $errors[] = "Error deleting: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }

        if (!empty($errors)) {
            foreach ($errors as $err) {
                echo "<p class='error-msg'>" . htmlspecialchars($err) . "</p>";
            }
        }
        if ($success_msg != "") {
            echo "<p class='success-msg'>" . htmlspecialchars($success_msg) . "</p>";
        }
        ?>

        <!-- CREATE FORM: Post a New Listing -->
        <section id="addListingForm">
            <h2>Post a New Listing</h2>
            <form action="index.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                <label for="seller_id">Seller ID:</label>
                <input type="number" name="seller_id" id="seller_id"
                       value="<?php echo isset($_POST['seller_id']) ? htmlspecialchars($_POST['seller_id']) : ''; ?>" required>
                </div>

                <div class="form-group">
                <label for="category_id">Category:</label>
                <select name="category_id" id="category_id" required>
                    <?php
                    $cat_result = mysqli_query($conn, "SELECT * FROM categories");
                    while ($cat = mysqli_fetch_assoc($cat_result)) {
                        echo "<option value='" . $cat['category_id'] . "'>" . htmlspecialchars($cat['category_name']) . "</option>";
                    }
                    ?>
                </select>
                </div>

                <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title"
                       value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required>
                </div>

                <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" rows="3"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                <label for="price">Price (RM):</label>
                <input type="number" step="0.01" name="price" id="price"
                       value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>" required>
                </div>

                <div class="form-group">
                <label for="item_condition">Condition:</label>
                <select name="item_condition" id="item_condition">
                    <option value="New">New</option>
                    <option value="Like New">Like New</option>
                    <option value="Used - Good">Used - Good</option>
                    <option value="Used - Fair">Used - Fair</option>
                </select>
                </div>

                <div class="form-group">
                <label for="image">Item Image:</label>
                <input type="file" name="image" id="image" accept="image/*">
                </div>

                <button type="submit" name="add_listing" class="button">Add Listing</button>
            </form>
        </section>

        <!-- READ FILTER: Search / Category / Sort -->
        <section id="filterSection">
            <form action="index.php" method="get">
                <div>
                    <label for="search">Search:</label>
                    <input type="text" name="search" id="search" placeholder="Search by title..."
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>

                <div>
                    <label for="filter_category">Category:</label>
                    <select name="category_id" id="filter_category">
                        <option value="">All Categories</option>
                        <?php
                        $cat_result2 = mysqli_query($conn, "SELECT * FROM categories");
                        while ($cat2 = mysqli_fetch_assoc($cat_result2)) {
                            $selected = (isset($_GET['category_id']) && $_GET['category_id'] == $cat2['category_id']) ? "selected" : "";
                            echo "<option value='" . $cat2['category_id'] . "' $selected>" . htmlspecialchars($cat2['category_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label for="sort">Sort by:</label>
                    <select name="sort" id="sort">
                        <?php
                        $sort_options = array(
                            "newest" => "Newest First",
                            "price_low" => "Price: Low to High",
                            "price_high" => "Price: High to Low"
                        );
                        $current_sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
                        foreach ($sort_options as $val => $label) {
                            $selected = ($val == $current_sort) ? "selected" : "";
                            echo "<option value='$val' $selected>$label</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="button">Apply</button>
            </form>
        </section>

        <!-- READ: Listing Grid (search + filter + sort) -->
        <section id="listingGrid">
            <?php
            $conditions_sql = array();
            $params = array();
            $param_types = "";

            if (isset($_GET['search']) && trim($_GET['search']) != "") {
                $conditions_sql[] = "title LIKE ?";
                $params[] = "%" . $_GET['search'] . "%";
                $param_types .= "s";
            }

            if (isset($_GET['category_id']) && $_GET['category_id'] != "") {
                $conditions_sql[] = "category_id = ?";
                $params[] = $_GET['category_id'];
                $param_types .= "i";
            }

            $sql = "SELECT * FROM listings";
            if (!empty($conditions_sql)) {
                $sql .= " WHERE " . implode(" AND ", $conditions_sql);
            }

            $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
            if ($sort == 'price_low') {
                $sql .= " ORDER BY price ASC";
            } elseif ($sort == 'price_high') {
                $sql .= " ORDER BY price DESC";
            } else {
                $sql .= " ORDER BY created_at DESC";
            }

            if (!empty($params)) {
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, $param_types, ...$params);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
            } else {
                $result = mysqli_query($conn, $sql);
            }

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='listingCard'>";

                    if ($row['image_path'] != "") {
                        echo "<img src='../" . htmlspecialchars($row['image_path']) . "' alt='" . htmlspecialchars($row['title']) . "'>";
                    }

                    echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                    echo "<p class='price'>RM " . number_format($row['price'], 2) . "</p>";
                    echo "<p class='condition'>" . htmlspecialchars($row['item_condition']) . "</p>";
                    echo "<p class='status'>Status: " . htmlspecialchars($row['status']) . "</p>";
                    echo "<p class='views'>" . $row['views'] . " views</p>";
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
