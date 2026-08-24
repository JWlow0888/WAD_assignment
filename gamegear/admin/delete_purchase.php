<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $purchase_id = (int)$_GET['id'];
    $conn = mysqli_connect('localhost', 'root', '', 'gamegear_db');

    if ($conn) {
        $sql = "DELETE FROM purchases WHERE id = $purchase_id";
        mysqli_query($conn, $sql);
        mysqli_close($conn);
    }
}

header('Location: dashboard.php');
exit();
?>