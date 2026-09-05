<?php
/**
 * Example of a PROTECTED page — this is the pattern to reuse for
 * Cart/Wishlist pages: check the session first, before running any query.
 */

require('../session/config.php');
session_start();
require 'auth.php';

// --- Authorization guard ---
// Checks login status, idle timeout, and rotates the session ID
// periodically. Reuse this one line at the top of every protected page.
require_login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    	<title>Dashboard</title>
</head>
<body>
    	<h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    	<p>You are logged in. This is where Cart/Wishlist features would go.</p>
    	<p><a href="logout.php">Log out</a></p>
</body>
</html>