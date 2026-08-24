<?php
session_start();
$pageTitle = "Add New Product";

if (!isset($_SESSION['email'])) { header('Location: login.php'); exit(); }

$message_out = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $condition = trim($_POST['condition_status'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $image_url = trim($_POST['image_url'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    if ($title === '' || $price <= 0) {
        $message_out = '<p class="error">Please fill in the required fields.</p>';
    } else {
        $conn = mysqli_connect('localhost', 'root', '', 'gamegear_db');

        if ($conn) {
            $title = mysqli_real_escape_string($conn, $title);
            $category = mysqli_real_escape_string($conn, $category);
            $condition = mysqli_real_escape_string($conn, $condition);
            $image_url = mysqli_real_escape_string($conn, $image_url);
            $description = mysqli_real_escape_string($conn, $description);

			$sql = "INSERT INTO products (title, price, category, condition_status, image_url, description, is_featured, is_available) 
			        VALUES ('$title', '$price', '$category', '$condition', '$image_url', '$description', '$is_featured', '$is_available')";

					if (mysqli_query($conn, $sql)) {
	                	$message_out = '<div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; border: 1px solid #c3e6cb; margin-bottom: 20px; text-align: center; font-weight: bold;">
	                                    	✅ Product added successfully! <br><br>
	                                    	<a href="dashboard.php" style="color: #155724; text-decoration: underline;">Return to Dashboard</a>
	                                	</div>';
		            } else {
		                $message_out = '<div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; border: 1px solid #f5c6cb; margin-bottom: 20px; text-align: center; font-weight: bold;">
		                                    ❌ Failed to add product.
		                                </div>';
		            }
            mysqli_close($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="../style/style.css">
  <title>Add Product | Admin</title>
</head>
<body>
  <?php include('../content/header.php'); ?>
  <?php include('../content/navigation.php'); ?>

<div id="contentWrapper">
    <div class="form-container">
        <h2>Add New Product</h2>
        <?php echo $message_out; ?>

        <form action="" method="post">
            <div class="form-group">
                <label>Product Title</label>
                <input type="text" name="title" required>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label>Price (RM)</label>
                    <input type="number" step="0.01" name="price" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Category</label>
                    <select name="category">
                        <option value="console">Console</option>
                        <option value="pc">PC Parts</option>
                        <option value="game">Games</option>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Condition</label>
                    <input type="text" name="condition_status" placeholder="e.g. Used - Good">
                </div>
            </div>

            <div class="form-group">
                <label>Image URL</label>
                <input type="text" name="image_url" placeholder="e.g. ps4.jpg">
            </div>
			
			<div style="margin-bottom: 15px;">
			    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Description</label>
			    <textarea name="description" rows="5" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit;"></textarea>
			</div>

            <div class="form-group">
                <label>Visibility Toggles</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="is_featured" value="1"> Show on Featured Page</label>
                    <label><input type="checkbox" name="is_available" value="1" checked> Allow Purchasing</label>
                </div>
            </div>

            <button type="submit" class="cta-button" style="width:100%;">Save Product</button>
			
			<div style="text-align: center; margin-top: 15px;">
				<a href="dashboard.php" style="color: #666; text-decoration: underline;">Cancel</a>
			</div>
        </form>
    </div>
</div>
  <?php include('../content/footer.php'); ?>
</body>
</html>