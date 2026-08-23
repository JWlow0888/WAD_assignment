<?php
// Shared database connection
// Edit these if your WAMP MySQL setup uses a different username/password
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "gamegear_exchange";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
