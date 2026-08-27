<?php
$pageTitle = "Buyer Login";
session_start();

if (isset($_SESSION['user_email'])) {
    header('Location: feature/index.php');
    exit();
}

$errors = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $conn = mysqli_connect('localhost', 'root', '', 'gamegear_exchange');
    
    if ($conn) {
        $email_safe = mysqli_real_escape_string($conn, $email);
        
        $sql = "SELECT * FROM users WHERE email = '$email_safe' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            if ($password === $user['password']) {
                // Log the user in!
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['full_name']; 
                
                if (isset($_SESSION['redirect_after_login'])) {
                    $redirect = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']); // Clear the memory
                    header('Location: ' . $redirect);
                } else {
                    // Otherwise, just send them to the homepage
                    header('Location: index.php'); 
                }
                exit();
            } else {
                $errors = "Invalid password. Please try again.";
            }
        } else {
            $errors = "No account found with that email address.";
        }
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <title>User Login | GameGear Exchange</title>
</head>
<body>
    <?php include('content/header.php'); ?>
    <?php include('content/navigation.php'); ?>

    <div id="contentWrapper">
        <div class="form-container" style="max-width: 500px; margin: 60px auto;">
            <h2 style="text-align: center; color: rgb(33, 37, 41); margin-bottom: 30px;">Login to Purchase Gear</h2>
            
            <?php if ($errors): ?>
                <div class="error" style="color: #c62828; background-color: #ffebee; border: 1px solid #ffcdd2; padding: 15px; border-radius: 4px; text-align: center; margin-bottom: 20px;">
                    <?php echo $errors; ?>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-group">
                    <label for="email" style="font-size: 1.1em; font-weight: bold;">Email Address:</label>
                    <input type="email" id="email" name="email" required style="width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                </div>
                
                <div class="form-group" style="margin-top: 20px;">
                    <label for="password" style="font-size: 1.1em; font-weight: bold;">Password:</label>
                    <input type="password" id="password" name="password" required style="width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                </div>

                <button type="submit" class="submit-btn" style="width: 100%; margin-top: 30px; padding: 15px; font-size: 18px; background-color: #82e043; color: #111111; font-weight: bold; border-radius: 4px;">Log In Securely</button>
            </form>
            
            <p style="text-align: center; margin-top: 20px; color: #666;">
                Don't have an account? <a href="user_signup.php" style="color: rgb(30, 136, 229);">Sign up here.</a>
            </p>
        </div>
    </div>

    <?php include('content/footer.php'); ?>
</body>
</html>