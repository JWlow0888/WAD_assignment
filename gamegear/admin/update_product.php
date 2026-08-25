<?php
session_start();
$pageTitle = "Update Product";

if (!isset($_SESSION['email'])) { header('Location: login.php'); exit(); }

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'gamegear_exchange';
$message_out = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $title = trim($_POST['title'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $condition = trim($_POST['condition_status'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $image_url = trim($_POST['image_url'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_available = isset($_POST['is_available']) ? 1 : 0;
    $status = $is_available ? 'Available' : 'Sold';

    if ($id <= 0 || $title === '' || $price <= 0 || $category_id <= 0) {
        $message_out = '<p class="error" style="text-align:center;">Invalid input. Please fill in the required fields.</p>';
    } else {
        $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
        if ($conn) {
            $title = mysqli_real_escape_string($conn, $title);
            $condition = mysqli_real_escape_string($conn, $condition);
            $image_url = mysqli_real_escape_string($conn, $image_url);
            $description = mysqli_real_escape_string($conn, $description);
            $status = mysqli_real_escape_string($conn, $status);

            $sql = "UPDATE listings 
                    SET title='$title', category_id=$category_id, item_condition='$condition', 
                        price='$price', description='$description', image_path='$image_url', 
                        is_featured='$is_featured', status='$status' 
                    WHERE listing_id=$id";

            if (mysqli_query($conn, $sql)) {
                $message_out = '<div class="success-message" style="text-align:center;">Product updated successfully.<br><br><a href="dashboard.php" class="cta-button" style="display:inline-block; padding: 10px 20px; text-decoration:none;">Back to Dashboard</a></div>';
            } else {
                $message_out = '<p class="error" style="text-align:center;">Failed to update product.</p>';
            }
            mysqli_close($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../style/style.css">
  <title>Update Product | Admin</title>
</head>
<body>
<?php include('../content/header.php'); ?>
<?php include('../content/navigation.php'); ?>

<div id="contentWrapper">
    <div class="form-container">
        <h2 style="text-align:center; margin-top:0;">Update Product</h2>
        <?php echo $message_out; ?>

        <?php
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || strpos($message_out, 'Failed') !== false || strpos($message_out, 'Invalid') !== false) {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);

            if ($id > 0) {
                $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
                if ($conn) {
                    $sql = "SELECT * FROM listings WHERE listing_id=$id LIMIT 1";
                    $result = mysqli_query($conn, $sql);

                    if ($result && mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
        ?>
        
        <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="form-group">
                <label>Product Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label>Price (RM)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Category</label>
                    <select name="category_id">
                        <?php
                        $cat_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name");
                        while ($cat = mysqli_fetch_assoc($cat_result)) {
                            $selected = ($cat['category_id'] == $row['category_id']) ? 'selected' : '';
                            echo '<option value="' . $cat['category_id'] . '" ' . $selected . '>' . htmlspecialchars($cat['category_name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Condition</label>
                    <select name="condition_status">
                        <?php
                        $conditions = array('New', 'Like New', 'Used - Good', 'Used - Fair');
                        foreach ($conditions as $c) {
                            $selected = ($c == $row['item_condition']) ? 'selected' : '';
                            echo "<option value='$c' $selected>$c</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Image URL</label>
                <input type="text" name="image_url" value="<?php echo htmlspecialchars($row['image_path']); ?>" placeholder="e.g. ps4.jpg">
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="5"><?php echo htmlspecialchars($row['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Visibility Toggles</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="is_featured" value="1" <?php echo ($row['is_featured'] == 1) ? 'checked' : ''; ?>> Show on Featured Page</label>
                    <label><input type="checkbox" name="is_available" value="1" <?php echo ($row['status'] == 'Available') ? 'checked' : ''; ?>> Allow Purchasing</label>
                </div>
            </div>

            <button type="submit" class="submit-btn" style="width:100%;">Save Updates</button>
            <a href="dashboard.php" style="display:block; text-align:center; margin-top:15px; color:#666;">Cancel</a>
        </form>
        
        <?php
                    } else {
                        echo '<p class="error" style="text-align:center;">Product not found.</p><div style="text-align:center;"><a href="dashboard.php">Back to Dashboard</a></div>';
                    }
                    mysqli_close($conn);
                }
            } else {
                echo '<p class="error" style="text-align:center;">No product selected.</p><div style="text-align:center;"><a href="dashboard.php">Back to Dashboard</a></div>';
            }
        }
        ?>
    </div>
</div>

<?php include('../content/footer.php'); ?>
</body>
</html>