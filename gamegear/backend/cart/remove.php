<?php
/**
 * cart_remove.php
 * Deletes a row from the CURRENT user's cart, with an ownership check.
 *
 * This is the pattern to copy for any Cart/Wishlist action (add, remove,
 * update quantity, etc). The key idea: being logged in only proves WHO
 * you are. It does NOT prove the row you're about to touch is yours.
 * That second check is what owns_resource() does.
 *
 * Expects: cart_items table with columns (id, user_id, item_id, ...)
 * — confirm exact column names with Person 4.
 */

require('../session/config.php');
session_start();
require('../registration/db.php');
require('../session/auth.php');

require_login(); // must be logged in at all

$wishlist_item_id = (int)($_POST['id'] ?? 0);

if ($wishlist_item_id <= 0) {
    	http_response_code(400);
    	exit('Invalid item id.');
}

// --- Ownership check before mutating anything ---
if (!owns_resource($pdo, 'cart_items', 'id', $wishlist_item_id)) {
    	// Either the row doesn't exist, or it belongs to someone else.
    	// Respond the same way in both cases so we don't leak which.
    	http_response_code(403);
    	exit('You do not have permission to remove this item.');
}

$stmt = $pdo->prepare('DELETE FROM cart_items WHERE id = ? AND user_id = ?');
$stmt->execute([$wishlist_item_id, $_SESSION['user_id']]);

header('Location: view.php');
exit;
?>