<?php
$pageTitle = "Admin Dashboard";
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'gamegear_db'; 
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <title>Admin Dashboard | GameGear Exchange</title>
</head>
<body>
    <?php include('../content/header.php'); ?>
    <?php include('../content/navigation.php'); ?>

    <div id="contentWrapper">
        <div class="form-container" style="max-width: 1000px;">
            <h1 style="color: rgb(30, 136, 229); text-align: center;">Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h1>
            <p style="text-align: center;">You have successfully authenticated into the GameGear Exchange Admin Dashboard.</p>
            

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; margin-top: 40px;">
                <h2>Manage Announcements</h2>
                <div>
                    <a href='create.php' class="submit-btn" style="text-decoration:none; padding: 8px 15px; width: auto; display: inline-block;">+ Create New Post</a>
                </div>
            </div>
            
            <?php
            if (!$conn) {
                echo '<div class="error">Could not connect to the database: ' . mysqli_connect_error() . '</div>';
            } else {
                $sql = "SELECT * FROM announcement ORDER BY posted DESC";
                $result = mysqli_query($conn, $sql);
                
                if (!$result) {
                    echo '<p class="error">Failed to load posts.</p>';
                } elseif (mysqli_num_rows($result) === 0) {
                    echo '<p>No announcements found.</p>';
                } else {
                    echo "<div style='overflow-x: auto;'><table style='width:100%; border-collapse: collapse;' class='purchase-table'>";
                    echo "<thead><tr><th>Subject</th><th>Message</th><th>Type</th><th>Posted</th><th colspan='2'>Actions</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td><strong>" . htmlspecialchars($row['subject']) . "</strong></td>";
                        echo "<td>" . htmlspecialchars(substr($row['message'], 0, 50)) . "...</td>"; 
                        echo "<td>" . ($row['type'] == 'P' ? 'Platform Update' : 'General News') . "</td>";
                        echo "<td>" . $row['posted'] . "</td>";
                        echo "<td><a href='update.php?id=" . $row['id'] . "' style='color: rgb(30, 136, 229); font-weight: bold;'>Edit</a></td>";
                        echo "<td><a href='delete.php?id=" . $row['id'] . "' style='color:#ff3333; font-weight: bold;' onclick='return confirm(\"Are you sure you want to delete this announcement?\");'>Delete</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table></div>";
                }
            }
            ?>

            <hr style="border: 0; border-top: 1px solid #dee2e6; margin: 50px 0;">

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Manage Products</h2>
                <div>
                    <a href='create_product.php' class="submit-btn" style="text-decoration:none; padding: 8px 15px; width: auto; display: inline-block;">+ Add New Product</a>
                </div>
            </div>

            <?php
            if ($conn) {
                $sql_prod = "SELECT * FROM products ORDER BY posted DESC";
                $result_prod = mysqli_query($conn, $sql_prod);
                
                if (!$result_prod) {
                    echo '<p class="error">Failed to load products.</p>';
                } elseif (mysqli_num_rows($result_prod) === 0) {
                    echo '<p>No products found.</p>';
                } else {
                    echo "<div style='overflow-x: auto;'><table style='width:100%; border-collapse: collapse;' class='purchase-table'>";
                    echo "<thead><tr><th>Image</th><th>Product Name</th><th>Price (RM)</th><th>Visibility Status</th><th colspan='2'>Actions</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = mysqli_fetch_assoc($result_prod)) {
                        echo "<tr>";
						echo '<td><img src="../images/' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['title']) . '" style="width: 80px; height: auto; border-radius: 4px;"></td>';
                        echo "<td><strong>" . htmlspecialchars($row['title']) . "</strong><br><span style='font-size: 0.85em; color: #666;'>" . htmlspecialchars($row['category']) . "</span></td>";
                        echo "<td>" . number_format($row['price'], 2) . "</td>";
                        
                        echo "<td>";
                        if ($row['is_featured'] == 1) { echo "<span style='background: gold; color: #000; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; margin-right: 5px;'>Featured</span><br>"; }
                        if ($row['is_available'] == 1) { echo "<span style='background: #28a745; color: #fff; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;'>Available</span>"; }
                        else { echo "<span style='background: #6c757d; color: #fff; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;'>Hidden/Sold</span>"; }
                        echo "</td>";

                        echo "<td><a href='update_product.php?id=" . $row['id'] . "' style='color: rgb(30, 136, 229); font-weight: bold;'>Edit</a></td>";
                        echo "<td><a href='delete_product.php?id=" . $row['id'] . "' style='color:#ff3333; font-weight: bold;' onclick='return confirm(\"Are you sure you want to delete this product?\");'>Delete</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table></div>";
                }
            }
            ?>
			
			<hr style="border: 0; border-top: 1px solid #dee2e6; margin: 50px 0;">

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Manage Contact Messages</h2>
            </div>

			<?php
            if ($conn) {
                $sql_msgs = "SELECT * FROM contact_messages ORDER BY created_at DESC"; 
                $result_msgs = mysqli_query($conn, $sql_msgs);
                
                if (!$result_msgs) {
                    echo '<p class="error">Failed to load messages.</p>';
                } elseif (mysqli_num_rows($result_msgs) === 0) {
                    echo '<p>No new messages.</p>';
                } else {
                    echo "<div style='overflow-x: auto;'><table style='width:100%; border-collapse: collapse;' class='purchase-table'>";
                    echo "<thead><tr><th>Name</th><th>Contact Info</th><th>Type</th><th>Message</th><th>Date</th><th>Action</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = mysqli_fetch_assoc($result_msgs)) {
                        echo "<tr>";
                        echo "<td><strong>" . htmlspecialchars($row['salutation']) . " " . htmlspecialchars($row['name']) . "</strong></td>";
                        echo "<td><a href='mailto:" . htmlspecialchars($row['email']) . "' style='color: rgb(30, 136, 229);'>" . htmlspecialchars($row['email']) . "</a><br><span style='font-size:0.85em; color:#666;'>" . htmlspecialchars($row['phone']) . "</span></td>";
                        echo "<td><span style='background-color: #e9ecef; padding: 3px 8px; border-radius: 12px; font-size: 12px;'>" . htmlspecialchars($row['enquiry_type']) . "</span></td>";
                        echo "<td>" . nl2br(htmlspecialchars($row['message'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>"; 
                        echo "<td><a href='delete_message.php?id=" . $row['id'] . "' style='color:#ff3333; font-weight: bold;' onclick='return confirm(\"Are you sure you want to delete this message?\");'>Delete</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table></div>";
                }
            }
            ?>
			
			<hr style="border: 0; border-top: 1px solid #dee2e6; margin: 50px 0;">

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Manage Purchases</h2>
            </div>

            <?php
            if ($conn) {
                $sql_orders = "SELECT * FROM purchases ORDER BY purchase_date DESC";
                $result_orders = mysqli_query($conn, $sql_orders);
                
                if (!$result_orders) {
                    echo '<p class="error">Failed to load purchases.</p>';
                } elseif (mysqli_num_rows($result_orders) === 0) {
                    echo '<p>No purchases have been made yet.</p>';
                } else {
                    echo "<div style='overflow-x: auto;'><table style='width:100%; border-collapse: collapse;' class='purchase-table'>";
                    echo "<thead><tr><th>Buyer Email</th><th>Items Purchased</th><th>Total (RM)</th><th>Date</th><th>Action</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = mysqli_fetch_assoc($result_orders)) {
                        echo "<tr>";
                        echo "<td><a href='mailto:" . htmlspecialchars($row['buyer_email']) . "' style='color: rgb(30, 136, 229);'>" . htmlspecialchars($row['buyer_email']) . "</a></td>";
                        echo "<td>" . htmlspecialchars($row['purchased_items']) . "</td>";
                        echo "<td style='font-weight: bold; color: rgb(30, 136, 229);'>RM " . number_format($row['total_price'], 2) . "</td>";
                        echo "<td>" . htmlspecialchars($row['purchase_date']) . "</td>";
                        echo "<td><a href='delete_purchase.php?id=" . $row['id'] . "' style='color:#ff3333; font-weight: bold;' onclick='return confirm(\"Are you sure you want to delete this purchase record?\");'>Delete</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table></div>";
                }
				mysqli_close($conn);
            }
            ?>

            <div style="text-align: center; margin-top: 40px;">
                <a href='logout.php' style="color: #ff3333; text-decoration: underline; font-weight: bold; font-size: 16px;">Log Out of Dashboard</a>
            </div>

        </div>
    </div>

    <?php include('../content/footer.php'); ?>
</body>
</html>