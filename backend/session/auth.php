<?php
/**
 * auth.php
 * Reusable session/authorization helpers.
 * Include this on every protected page AFTER session_start().
 */

/**
 * Call at the top of any page that requires login.
 * Also enforces an idle timeout, and periodically rotates the
 * session ID even for an already-logged-in user (limits the window
 * an attacker has if a session ID is ever leaked).
 */
function require_login(): void
{
	if (empty($_SESSION['user_id'])) {
        	header('Location: login.php');
        	exit;
    	}

    	// --- Idle timeout: log out after 30 minutes of no activity ---
    	$timeout = 1800; // seconds
    	if (!empty($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        	session_unset();
        	session_destroy();
        	header('Location: login.php?timeout=1');
        	exit;
    	}
    	$_SESSION['last_activity'] = time();

   	 // --- Periodic session ID rotation (every 15 min of use) ---
    	if (empty($_SESSION['created_at'])) {
        	$_SESSION['created_at'] = time();
    	} elseif (time() - $_SESSION['created_at'] > 900) {
        	session_regenerate_id(true);
        	$_SESSION['created_at'] = time();
    	}
}


/**
 * Authorization check: does the given resource (e.g. a cart row)
 * actually belong to the logged-in user?
 *
 * Use this before ANY update/delete on a user-owned row — being logged in
 * proves who you are, not that you're allowed to touch a specific record.
 *
 * Example query for a cart item:
 *   SELECT user_id FROM cart_items WHERE id = ?
 */
function owns_resource(PDO $pdo, string $table, string $id_column, int $resource_id): bool
{
	$stmt = $pdo->prepare("SELECT user_id FROM {$table} WHERE {$id_column} = ?");
    	$stmt->execute([$resource_id]);
    	$row = $stmt->fetch();

    	return $row && (int)$row['user_id'] === (int)$_SESSION['user_id'];
}
?>