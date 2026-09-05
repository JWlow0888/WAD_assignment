<?php
/**
 * cart_view.php
 * Lists ONLY the current user's cart items.
 *
 * Authorization note: the WHERE clause is scoped to $_SESSION['user_id'].
 * There is no "user_id" parameter accepted from the URL/form at all —
 * the query structurally can't return someone else's cart, because we
 * never ask the client who they are; we already know from the session.
 */

require('../session/config.php');
session_start();
require('../registration/db.php');
require('../session/auth.php');

require_login();

$stmt = $pdo->prepare(
    	'SELECT ci.id, ci.quantity, i.name, i.price
     	FROM cart_items ci
     	JOIN items i ON i.id = ci.item_id
     	WHERE ci.user_id = ?'
);
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll();

$total = 0;
foreach ($cart_items as $row) {
    	$total += $row['price'] * $row['quantity'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
</head>
<body>
    <h1>Your Cart</h1>

    <?php if (empty($cart_items)): ?>
        	<p>Your cart is empty.</p>
    <?php else: ?>
        	<table border="1" cellpadding="8">
            		<tr>
                		<th>Item</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th>
            		</tr>
            		<?php foreach ($cart_items as $row): ?>
               		<tr>
                    		<td><?= htmlspecialchars($row['name']) ?></td>
                    		<td>$<?= number_format($row['price'], 2) ?></td>
                    		<td>
                        		<form method="POST" action="update.php" style="display:inline;">
                            		<input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                            		<input type="number" name="quantity" value="<?= (int)$row['quantity'] ?>" min="1" style="width:50px;">
                            		<button type="submit">Update</button>
                        		</form>
                    		</td>
                    		<td>$<?= number_format($row['price'] * $row['quantity'], 2) ?></td>
                    		<td>
                        		<form method="POST" action="remove.php" style="display:inline;">
                            		<input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                            		<button type="submit">Remove</button>
                        		</form>
                    		</td>
                	</tr>
            		<?php endforeach; ?>
        	</table>
        <p><strong>Total: $<?= number_format($total, 2) ?></strong></p>
    <?php endif; ?>

    <p><a href="dashboard.php">Back to dashboard</a></p>
</body>
</html>