<?php
/**
 * db.php
 * Single shared database connection using PDO.
 * Every other script includes this file to get $pdo.
 */

$dbHost = 'localhost';
$dbName = 'gamegear_exchange';
$dbUser = 'root';
$dbPass = '';


try {
    $pdo = new PDO(
    	"mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
    	$dbUser,
    	$dbPass,
    	[
    		// Throw exceptions on error instead of failing silently
        	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        	// Return rows as associative arrays
        	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        	// Use real prepared statements (safer against SQL injection)
        	PDO::ATTR_EMULATE_PREPARES => false,
    	]
    );

} catch (PDOException $e) {
    	// In production, log this instead of echoing it
    	die('Database connection failed: ' . $e->getMessage());
}
