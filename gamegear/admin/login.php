<?php
$pageTitle = "Admin Login";
session_start();

// Check if the user is already logged in
if (isset($_SESSION['email'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = mysqli_connect('localhost', 'root', '', 'gamegear_db', 3306);
    
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT * FROM admin_users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify the hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $email;
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Invalid email or password.';
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <title>Admin Login | GameGear Exchange</title>
</head>
<body>
    <?php include('../content/header.php'); ?>
    <?php include('../content/navigation.php'); ?>

    <div id="contentWrapper">
        <div class="form-container" style="max-width: 400px;">
            <h2 style="text-align:center;">Admin Login</h2>
            
            <?php if ($error): ?>
                <div class="error" style="text-align:center; margin-bottom:15px;"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="post" action="">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" name="login" class="submit-btn">Login</button>
            </form>
            <p style="text-align:center; margin-top: 15px; font-size: 14px;">Need an account? <a href="register.php">Register here</a>.</p>
        </div>
    </div>

    <?php include('../content/footer.php'); ?>
</body>
</html>