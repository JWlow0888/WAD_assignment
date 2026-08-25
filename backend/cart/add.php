<?php
/**
 * cart_add.php
 * Adds an item to the CURRENT user's cart.
 *
 * Authorization note: there's nothing to "own" yet since we're creating
 * a new row — but we still never trust a user_id from the form. We take
 * it from the session, where it can't be tampered with by the client.
 */

require('../session/config.php');
session_start();
require('../registration/db.php');
require('../session/auth.php');

require_login();

$item_id  = (int)($_POST['item_id'] ?? 0);
$quantity = max(1, (int)($_POST['quantity'] ?? 1));

if ($item_id <= 0) {
	http_response_code(400);
    	exit('Invalid item.');
}

// Optional: confirm the item actually exists in the catalog before adding
$stmt = $pdo->prepare('SELECT listing_id FROM listings WHERE listing_id = ?');
$stmt->execute([$item_id]);
if (!$stmt->fetch()) {
    	http_response_code(404);
    	exit('Item not found.');
}

// If it's already in the cart, increase quantity instead of duplicating
$stmt = $pdo->prepare('SELECT id, quantity FROM cart_items WHERE user_id = ? AND item_id = ?');
$stmt->execute([$_SESSION['user_id'], $item_id]);
$existing = $stmt->fetch();

if ($existing) {
    	$stmt = $pdo->prepare('UPDATE cart_items SET quantity = quantity + ? WHERE id = ?');
    	$stmt->execute([$quantity, $existing['id']]);
} else {
    	$stmt = $pdo->prepare(
        	'INSERT INTO cart_items (user_id, item_id, quantity) VALUES (?, ?, ?)'
    	);
    	// user_id comes from $_SESSION, never from $_POST
    	$stmt->execute([$_SESSION['user_id'], $item_id, $quantity]);
}

header('Location: view.php');
exit;
?>