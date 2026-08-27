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
$dbName = 'gamegear_exchange'; 
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <title>Admin Dashboard | GameGear Exchange</title>
    <style>
        /* New Sidebar Layout Styles */
        .admin-layout {
            display: flex;
            max-width: 1400px;
            margin: 40px auto;
            gap: 30px;
            padding: 0 20px;
            align-items: flex-start; /* Keeps sidebar at the top */
        }
        
        .admin-sidebar {
            flex: 0 0 250px; /* Fixed width sidebar */
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            position: sticky; 
            top: 20px; /* Sticks to the top when scrolling */
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .admin-nav-link {
            display: block;
            padding: 12px 15px;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 8px;
            cursor: pointer;
            background: #f8f9fa;
            border-left: 4px solid transparent;
            transition: all 0.2s;
        }

        .admin-nav-link:hover {
            background: #e9ecef;
        }

        /* The active tab style */
        .admin-nav-link.active {
            background: #e3f2fd;
            color: rgb(30,136,229);
            font-weight: bold;
            border-left: 4px solid rgb(30,136,229);
        }

        .admin-content {
            flex: 1; /* Takes up the remaining space */
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            min-height: 600px;
        }

        /* Hides sections by default for the tab system */
        .admin-section {
            display: none; 
        }
    </style>
</head>
<body>
    <?php include('../content/header.php'); ?>
    <?php include('../content/navigation.php'); ?>

    <div class="admin-layout">
        
        <div class="admin-sidebar">
            <h3 style="margin-top: 0; color: #111; border-bottom: 2px solid #f0f4f8; padding-bottom: 10px;">Admin Menu</h3>
            
            <a class="admin-nav-link active" id="nav-announcements" onclick="switchTab('announcements')">📢 Announcements</a>
            <a class="admin-nav-link" id="nav-products" onclick="switchTab('products')">📦 Manage Products</a>
            <a class="admin-nav-link" id="nav-messages" onclick="switchTab('messages')">✉️ Contact Messages</a>
            <a class="admin-nav-link" id="nav-purchases" onclick="switchTab('purchases')">🛒 Order Purchases</a>
            
            <hr style="border: 0; border-top: 1px solid #dee2e6; margin: 20px 0;">
            <a href="logout.php" class="admin-nav-link" style="color: #ff3333; text-align: center; font-weight: bold;">Log Out</a>
        </div>

        <div class="admin-content">
            <h1 style="color: rgb(30, 136, 229); margin-top: 0; font-size: 1.8em;">Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h1>
            <p style="color: #666; margin-bottom: 30px; border-bottom: 2px solid #f0f4f8; padding-bottom: 15px;">Select an option from the menu to manage your marketplace data.</p>
            
            <?php if (!$conn) { echo '<div class="error">Could not connect to the database.</div>'; } else { ?>

            <!-- TAB 1: ANNOUNCEMENTS -->
            <div id="sec-announcements" class="admin-section" style="display: block;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="margin:0;">Manage Announcements</h2>
                    <a href='create.php' class="submit-btn" style="text-decoration:none; padding: 8px 15px; width: auto; display: inline-block;">+ Create New Post</a>
                </div>
                <?php
                $sql = "SELECT * FROM announcement ORDER BY posted DESC";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) === 0) {
                    echo '<p>No announcements found.</p>';
                } else {
                    echo "<div style='overflow-x: auto;'><table style='width:100%; border-collapse: collapse;' class='purchase-table'>";
                    echo "<thead><tr><th>Subject</th><th>Message</th><th>Type</th><th>Posted</th><th colspan='2'>Actions</th></tr></thead><tbody>";
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
                ?>
            </div>

            <div id="sec-products" class="admin-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="margin:0;">Manage Products</h2>
                    <a href='create_product.php' class="submit-btn" style="text-decoration:none; padding: 8px 15px; width: auto; display: inline-block;">+ Add New Product</a>
                </div>
                <?php
                $sql_prod = "SELECT l.*, c.category_name FROM listings l JOIN categories c ON c.category_id = l.category_id ORDER BY l.created_at DESC";
                $result_prod = mysqli_query($conn, $sql_prod);
                if (mysqli_num_rows($result_prod) === 0) {
                    echo '<p>No products found.</p>';
                } else {
                    echo "<div style='overflow-x: auto;'><table style='width:100%; border-collapse: collapse;' class='purchase-table'>";
                    echo "<thead><tr><th>Image</th><th>Product Name</th><th>Price (RM)</th><th>Visibility Status</th><th colspan='2'>Actions</th></tr></thead><tbody>";
                    while ($row = mysqli_fetch_assoc($result_prod)) {
                        echo "<tr>";
                        echo '<td><img src="../images/' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['title']) . '" style="width: 80px; height: auto; border-radius: 4px;"></td>';
                        echo "<td><strong>" . htmlspecialchars($row['title']) . "</strong><br><span style='font-size: 0.85em; color: #666;'>" . htmlspecialchars($row['category_name']) . "</span></td>";
                        echo "<td>" . number_format($row['price'], 2) . "</td><td>";
                        if ($row['is_featured'] == 1) { echo "<span style='background: gold; color: #000; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; margin-right: 5px;'>Featured</span><br>"; }
                        if ($row['status'] == 'Available') { echo "<span style='background: #28a745; color: #fff; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;'>Available</span>"; }
                        else { echo "<span style='background: #6c757d; color: #fff; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;'>Hidden/Sold</span>"; }
                        echo "</td>";
                        echo "<td><a href='update_product.php?id=" . $row['listing_id'] . "' style='color: rgb(30, 136, 229); font-weight: bold;'>Edit</a></td>";
                        echo "<td><a href='delete_product.php?id=" . $row['listing_id'] . "' style='color:#ff3333; font-weight: bold;' onclick='return confirm(\"Are you sure you want to delete this product?\");'>Delete</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table></div>";
                }
                ?>
            </div>

            <div id="sec-messages" class="admin-section">
                <h2 style="margin-top:0; margin-bottom: 20px;">Manage Contact Messages</h2>
                <?php
                $sql_msgs = "SELECT * FROM contact_messages ORDER BY created_at DESC"; 
                $result_msgs = mysqli_query($conn, $sql_msgs);
                if (mysqli_num_rows($result_msgs) === 0) {
                    echo '<p>No new messages.</p>';
                } else {
                    echo "<div style='overflow-x: auto;'><table style='width:100%; border-collapse: collapse;' class='purchase-table'>";
                    echo "<thead><tr><th>Name</th><th>Contact Info</th><th>Type</th><th>Message</th><th>Date</th><th>Action</th></tr></thead><tbody>";
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
                ?>
            </div>

            <div id="sec-purchases" class="admin-section">
                <h2 style="margin-top:0; margin-bottom: 20px;">Manage Purchases</h2>
                <?php
                $sql_orders = "SELECT * FROM purchases ORDER BY purchase_date DESC";
                $result_orders = mysqli_query($conn, $sql_orders);
                if (mysqli_num_rows($result_orders) === 0) {
                    echo '<p>No purchases have been made yet.</p>';
                } else {
                    echo "<div style='overflow-x: auto;'><table style='width:100%; border-collapse: collapse;' class='purchase-table'>";
                    echo "<thead><tr><th>Buyer Info</th><th>Items Purchased</th><th>Total (RM)</th><th>Date</th><th>Action</th></tr></thead><tbody>";
                    while ($row = mysqli_fetch_assoc($result_orders)) {
                        echo "<tr>";
                        echo "<td><strong>" . htmlspecialchars($row['buyer_name'] ?? '') . "</strong><br><a href='mailto:" . htmlspecialchars($row['buyer_email'] ?? '') . "' style='color: rgb(30, 136, 229); font-size: 0.9em;'>" . htmlspecialchars($row['buyer_email'] ?? '') . "</a><br><span style='font-size: 0.9em; color: #666;'>" . htmlspecialchars($row['buyer_phone'] ?? '') . "</span></td>";
                        echo "<td>" . htmlspecialchars($row['purchased_items'] ?? '') . "</td>";
                        echo "<td style='font-weight: bold; color: rgb(30, 136, 229);'>RM " . number_format($row['total_price'] ?? 0, 2) . "</td>";
                        echo "<td>" . htmlspecialchars($row['purchase_date'] ?? '') . "</td>";
                        echo "<td><a href='delete_purchase.php?id=" . $row['id'] . "' style='color:#ff3333; font-weight: bold;' onclick='return confirm(\"Are you sure you want to delete this purchase record?\");'>Delete</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table></div>";
                }
                ?>
            </div>

            <?php mysqli_close($conn); } ?>

        </div>
    </div>

    <?php include('../content/footer.php'); ?>

</body>
</html>