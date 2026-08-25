<?php
$pageTitle = "Purchase Products";
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'gamegear_exchange');
$available_products = [];

if ($conn) {
    $sql = "SELECT listing_id AS id, title, item_condition AS condition_status, price FROM listings WHERE status = 'Available' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $available_products[$row['id']] = [
                "title" => $row['title'],
                "condition" => $row['condition_status'],
                "price" => $row['price']
            ];
        }
    }
}

$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $buyer_email = trim($_POST['buyer_email'] ?? '');
    $selected_products = $_POST['products'] ?? [];

    if (empty($buyer_email)) {
        $errors['buyer_email'] = "Buyer Email is required for verification.";
    } elseif (!filter_var($buyer_email, FILTER_VALIDATE_EMAIL)) {
        $errors['buyer_email'] = "Please enter a valid email address.";
    }

    if (empty($selected_products)) {
        $errors['products'] = "Please select at least one item to purchase.";
    }

	if (empty($errors)) {
	        $total_price = 0;
	        $purchased_items_html = "<ul>";
	        $item_titles = [];
	        
	        foreach ($selected_products as $item_id) {
	            if (isset($available_products[$item_id])) {
	                $item = $available_products[$item_id];
	                $total_price += $item['price'];
	                $purchased_items_html .= "<li>" . htmlspecialchars($item['title']) . " - RM " . number_format($item['price'], 2) . "</li>";
	                $item_titles[] = $item['title'];
	            }
	        }
	        $purchased_items_html .= "</ul>";

	        $db_email = mysqli_real_escape_string($conn, $buyer_email);
	        $db_items = mysqli_real_escape_string($conn, implode(', ', $item_titles));
	        
	        $sql_insert = "INSERT INTO purchases (buyer_email, purchased_items, total_price) 
	                       VALUES ('$db_email', '$db_items', '$total_price')";
	        mysqli_query($conn, $sql_insert);

	        $successMessage = "
            <div class='success-message' style='text-align: left;'>
                <h2 style='text-align: center; margin-top: 0;'>Order Confirmed!</h2>
                <p><strong>Verified Account:</strong> " . htmlspecialchars($buyer_email) . "</p>
                <p><strong>Items Pending:</strong></p>
                $purchased_items_html
                <hr>
                <h3 style='text-align: right; color: rgb(30, 136, 229);'>Total Amount: RM " . number_format($total_price, 2) . "</h3>
                
                <div style='background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; padding: 15px; margin: 20px 0; text-align: center;'>
                    <p style='margin: 0; color: #333; font-weight: bold;'>
                        📩 Next Steps: The seller will contact you shortly at your verified email address to provide further payment details and finalize shipping arrangements.
                    </p>
                </div>

                <div style='text-align: center; margin-top: 20px;'>
                    <a href='purchase.php' class='submit-btn' style='display: inline-block; width: auto; padding: 10px 20px; text-decoration: none;'>Make Another Purchase</a>
                </div>
            </div>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css"> 
    <title>Purchase Products | GameGear Exchange</title>
    <style>

        .purchase-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
        }
        .purchase-table th {
            background-color: #111111;
            color: #ffffff;
            padding: 12px;
            text-align: center;
            border: 1px solid #dee2e6;
        }
        .purchase-table td {
            padding: 12px;
            border: 1px solid #dee2e6;
            text-align: center;
            color: #000000;
        }
        .purchase-table td:nth-child(3) {
            text-align: left; 
        }
        .form-container h3 {
            margin-bottom: 10px;
            color: #000;
        }
    </style>
</head>
<body>
    <?php include('../content/header.php'); ?>
    <?php include('../content/navigation.php'); ?>

    <div id="contentWrapper">
        <div class="form-container" style="max-width: 800px;">
            
            <?php if (!empty($successMessage)): ?>
                <?php echo $successMessage; ?>
            <?php else: ?>
            
            <form id="purchaseForm" action="" method="post" onsubmit="return validatePurchaseForm(event)">
                
                <!-- Account Verification Field -->
                <div class="form-group">
                    <label for="buyer_email" style="font-size: 1.2em;">Verify Your Buyer Email:</label>
                    <input type="email" id="buyer_email" name="buyer_email" value="<?php echo htmlspecialchars($_POST['buyer_email'] ?? ''); ?>" style="padding: 12px; font-size: 16px;">
                    <div id="emailError" class="error"><?php echo $errors['buyer_email'] ?? ''; ?></div>
                </div>

                <br>

                <h3>Available GameGear</h3>
                <div id="productError" class="error" style="margin-bottom: 10px;"><?php echo $errors['products'] ?? ''; ?></div>
                
                <div style="overflow-x: auto;">
                    <table class="purchase-table">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Item ID</th>
                                <th>Product Name</th>
                                <th>Price (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($available_products as $id => $item): ?>
                            <tr>
								<td>
								    <?php 					
									    $preselected_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
									    $isChecked = (isset($_POST['products']) && in_array($id, $_POST['products'])) || ($id === $preselected_id) ? 'checked' : ''; 
								    ?>
								    <input type="checkbox" name="products[]" value="<?php echo $id; ?>" <?php echo $isChecked; ?> style="transform: scale(1.3); cursor: pointer;">
								</td>
                                <td><?php echo $id; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($item['title']); ?></strong><br>
                                    <span style="font-size: 0.85em; color: #666;">Condition: <?php echo $item['condition']; ?></span>
                                </td>
                                <td><?php echo number_format($item['price'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <button type="submit" class="submit-btn" style="font-size: 18px; padding: 15px;">Checkout Selected Items</button>
            </form>
            
            <?php endif; ?>

        </div>
    </div>

    <?php include('../content/footer.php'); ?>

    <script>
    function validatePurchaseForm(event) {
        let isValid = true;
        
        document.getElementById('emailError').textContent = '';
        document.getElementById('productError').textContent = '';

        const email = document.getElementById('buyer_email').value.trim();
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        
        if (email === '') {
            document.getElementById('emailError').textContent = 'Please enter your email to verify your account.';
            isValid = false;
        } else if (!emailPattern.test(email)) {
            document.getElementById('emailError').textContent = 'Please enter a valid email format.';
            isValid = false;
        }

        const checkboxes = document.querySelectorAll('input[name="products[]"]');
        const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        
        if (!isChecked) {
            document.getElementById('productError').textContent = 'You must select at least one item to proceed to checkout.';
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
        return isValid;
    }
    </script>
</body>
</html>