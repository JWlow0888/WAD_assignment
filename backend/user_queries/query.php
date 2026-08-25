<?php
/**
 * user_queries.php
 * Small helper library for user-specific relational queries.
 * Centralizing this avoids retyping "WHERE user_id = ?" everywhere and
 * makes it obvious in code review that every query here is user-scoped.
 *
 * Include after db.php + auth.php, on any page that reads/writes
 * data belonging to the logged-in user.
 */

/**
 * Fetch all rows from a user-owned table for the CURRENT session user.
 * Never accepts a user_id parameter — it's always pulled from $_SESSION,
 * so there's no way to call this with someone else's id by mistake.
 */
function get_user_rows(PDO $pdo, string $table, string $order_by = 'id'): array
{
    // $order_by is never taken from user input — only pass hardcoded
    // column names from your own code, never $_GET/$_POST, or this
    // becomes a SQL injection point despite using a prepared statement.
    $sql = "SELECT * FROM {$table} WHERE user_id = ? ORDER BY {$order_by}";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetchAll();
}

/**
 * Count how many rows a user has in a given table
 * (e.g. cart item count for a nav badge).
 */
function count_user_rows(PDO $pdo, string $table): int
{
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM {$table} WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return (int)$stmt->fetch()['total'];
}

/**
 * Example: a user's saved/wishlist items joined with the item catalog,
 * showing the same JOIN + user-scoping pattern as view.php.
 */
function get_user_wishlist(PDO $pdo): array
{
    $stmt = $pdo->prepare(
        'SELECT w.id, i.title AS name, i.price, w.created_at
         FROM wishlist_items w
         JOIN listings i ON i.listing_id = w.item_id
         WHERE w.user_id = ?
         ORDER BY w.created_at DESC'
    );
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetchAll();
}
?>