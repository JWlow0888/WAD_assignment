<?php
session_start();
$pageTitle = "Update Product";

if (!isset($_SESSION['email'])) { header('Location: login.php'); exit(); }

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'gamegear_db';
$message_out = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $condition = trim($_POST['condition_status'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $image_url = trim($_POST['image_url'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    if ($id <= 0 || $title === '' || $price <= 0) {
        $message_out = '<p class="error" style="text-align:center;">Invalid input. Please fill in the required fields.</p>';
    } else {
        $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
        if ($conn) {
            $title = mysqli_real_escape_string($conn, $title);
            $category = mysqli_real_escape_string($conn, $category);
            $condition = mysqli_real_escape_string($conn, $condition);
            $image_url = mysqli_real_escape_string($conn, $image_url);
            $description = mysqli_real_escape_string($conn, $description);

            $sql = "UPDATE products 
                    SET title='$title', category='$category', condition_status='$condition', 
                        price='$price', description='$description', image_url='$image_url', 
                        is_featured='$is_featured', is_available='$is_available' 
                    WHERE id=$id";

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
                    $sql = "SELECT * FROM products WHERE id=$id LIMIT 1";
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
                    <select name="category">
                        <option value="console" <?php echo ($row['category'] == 'console') ? 'selected' : ''; ?>>Console</option>
                        <option value="pc" <?php echo ($row['category'] == 'pc') ? 'selected' : ''; ?>>PC Parts</option>
                        <option value="game" <?php echo ($row['category'] == 'game') ? 'selected' : ''; ?>>Games</option>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Condition</label>
                    <input type="text" name="condition_status" value="<?php echo htmlspecialchars($row['condition_status']); ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Image URL</label>
                <input type="text" name="image_url" value="<?php echo htmlspecialchars($row['image_url']); ?>" placeholder="e.g. ps4.jpg">
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="5"><?php echo htmlspecialchars($row['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Visibility Toggles</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="is_featured" value="1" <?php echo ($row['is_featured'] == 1) ? 'checked' : ''; ?>> Show on Featured Page</label>
                    <label><input type="checkbox" name="is_available" value="1" <?php echo ($row['is_available'] == 1) ? 'checked' : ''; ?>> Allow Purchasing</label>
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