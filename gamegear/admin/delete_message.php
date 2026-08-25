<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $message_id = (int)$_GET['id'];
    
    $conn = mysqli_connect('localhost', 'root', '', 'gamegear_exchange');

    if ($conn) {
        $sql = "DELETE FROM contact_messages WHERE id = $message_id";
        mysqli_query($conn, $sql);
        mysqli_close($conn);
    }
}

header('Location: dashboard.php');
exit();
?>