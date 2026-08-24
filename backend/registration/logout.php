<?php
/**
 * Destroys the session completely — clears session data,
 * removes the session cookie, and destroys the session on the server.
 */

require('../session/config.php');
session_start();

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    	$params = session_get_cookie_params();
    	setcookie(
        	session_name(),
        	'',
        	time() - 42000,
        	$params['path'],
        	$params['domain'],
        	$params['secure'],
        	$params['httponly']
    	);
}

session_destroy();

header('Location: login.php');
exit();
?>