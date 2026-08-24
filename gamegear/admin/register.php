<?php
$pageTitle = "Admin Registration";
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $conn = mysqli_connect('localhost', 'root', '', 'gamegear_db', 3306);

    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Search for a matching email
    $sql = "SELECT * FROM admin_users WHERE email = '$email'";
    $checkResult = mysqli_query($conn, $sql);

    // Reject if email already exists
    if (mysqli_num_rows($checkResult) > 0) {
        $error = 'Email already exists. Please use a different email.';
    } else {
        // Hash the password securely before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO admin_users (email, password) VALUES ('$email','$hashed_password')";

        if (mysqli_query($conn, $sql)) {
            $success = 'Account created successfully. <a href="login.php">Login here.</a>';
        } else {
            $error = 'Error creating account.';
        }
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <title>Admin Register | GameGear Exchange</title>
</head>
<body>
    <?php include('../content/header.php'); ?>
    <?php include('../content/navigation.php'); ?>

    <div id="contentWrapper">
        <div class="form-container">
            <h2 style="text-align:center;">Admin Registration</h2>
            
            <?php if ($error): ?>
                <div class="error" style="text-align:center; margin-bottom:15px;"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message" style="text-align:center;"><?php echo $success; ?></div>
            <?php else: ?>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="email">Admin Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <button type="submit" name="register" class="submit-btn">Register as Admin</button>
                </form>
                <p style="text-align:center; margin-top: 15px; font-size: 14px;">Already have an account? <a href="login.php">Login here</a>.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include('../content/footer.php'); ?>
</body>
</html>