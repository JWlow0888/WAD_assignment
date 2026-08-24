<?php
/**
 * session_config.php
 * Hardened session settings. Include this BEFORE calling session_start()
 * on every page that uses sessions (login.php, dashboard.php, cart.php, etc).
 *
 * Why this matters for the report's "security" checkpoints:
 * - httponly:   stops JavaScript (XSS payloads) from reading the session cookie
 * - samesite:   stops the cookie being sent on cross-site requests (CSRF mitigation)
 * - gc_maxlifetime: auto-expires idle sessions server-side
 */

ini_set('session.use_strict_mode', 1);      // reject uninitialized session IDs
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', 1800);    // 30 minutes of inactivity

// If serving over HTTPS (e.g. your final deployment), also uncomment:
// ini_set('session.cookie_secure', 1);
?>