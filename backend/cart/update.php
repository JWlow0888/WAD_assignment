<?php
/**
 * cart_update.php
 * Changes the quantity of an existing cart row.
 *
 * Authorization note: this is the important one. The form posts a
 * cart_items.id, which the client CAN tamper with (e.g. change it to
 * someone else's row id in devtools before submitting). owns_resource()
 * is what stops that from working — it re-checks against the database
 * that the row's user_id actually matches the current session, no
 * matter what id was submitted.
 */

require('../session/config.php');
session_start();
require('../registration/db.php');
require('../session/auth.php');

require_login();

$cart_item_id = (int)($_POST['id'] ?? 0);
$quantity     = max(1, (int)($_POST['quantity'] ?? 1));

if ($cart_item_id <= 0) {
    	http_response_code(400);
    	exit('Invalid item id.');
}

if (!owns_resource($pdo, 'cart_items', 'id', $cart_item_id)) {
    	http_response_code(403);
    	exit('You do not have permission to update this item.');
}

$stmt = $pdo->prepare('UPDATE cart_items SET quantity = ? WHERE id = ? AND user_id = ?');
$stmt->execute([$quantity, $cart_item_id, $_SESSION['user_id']]);

header('Location: view.php');
exit;
?>