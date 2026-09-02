<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn = mysqli_connect('localhost', 'root', '', 'gamegear_exchange');
    
    if ($conn) {
        $sql = "DELETE FROM purchases WHERE id = $id";
        mysqli_query($conn, $sql);
        
        if (mysqli_affected_rows($conn) > 0) {
            echo "<script>alert('Purchase record deleted successfully!'); window.location.href='dashboard.php';</script>";
        } else {
            echo "<script>alert('Validation Error: Purchase ID not found.'); window.location.href='dashboard.php';</script>";
        }
        mysqli_close($conn);
    }
} else {
    header('Location: dashboard.php');
}
?>