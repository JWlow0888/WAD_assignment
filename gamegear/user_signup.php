<?php
$pageTitle = "Create Buyer Account";
session_start();

if (isset($_SESSION['user_email'])) {
    header('Location: feature/index.php');
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic Validation
    if (empty($full_name)) $errors[] = "Full Name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "A valid email is required.";
    if (empty($password)) $errors[] = "Password is required.";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match.";

    if (empty($errors)) {
        $conn = mysqli_connect('localhost', 'root', '', 'gamegear_exchange');
        
        if ($conn) {
            $name_safe = mysqli_real_escape_string($conn, $full_name);
            $email_safe = mysqli_real_escape_string($conn, $email);
            $phone_safe = mysqli_real_escape_string($conn, $phone);
            
            $check_sql = "SELECT email FROM users WHERE email = '$email_safe'";
            $check_result = mysqli_query($conn, $check_sql);
            
            if (mysqli_num_rows($check_result) > 0) {
                $errors[] = "An account with this email already exists. Please log in.";
            } else {
                $insert_sql = "INSERT INTO users (full_name, email, phone, password) VALUES ('$name_safe', '$email_safe', '$phone_safe', '$password')";
                
                if (mysqli_query($conn, $insert_sql)) {
                    // Success! Log them in automatically
                    $_SESSION['user_email'] = $email_safe;
                    $_SESSION['user_name'] = $name_safe;
                    
                    // Redirect to checkout if they were trying to buy, or homepage if not
                    if (isset($_SESSION['redirect_after_login'])) {
                        $redirect = $_SESSION['redirect_after_login'];
                        unset($_SESSION['redirect_after_login']);
                        header('Location: ' . $redirect);
                    } else {
                        header('Location: index.php'); 
                    }
                    exit();
                } else {
                    $errors[] = "Database error: Could not create account.";
                }
            }
            mysqli_close($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <title>Sign Up | GameGear Exchange</title>
</head>
<body>
    <?php include('content/header.php'); ?>
    <?php include('content/navigation.php'); ?>

    <div id="contentWrapper">
        <div class="form-container" style="max-width: 500px; margin: 60px auto;">
            <h2 style="text-align: center; color: rgb(33, 37, 41); margin-bottom: 30px;">Join GameGear Exchange</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="error" style="color: #c62828; background-color: #ffebee; border: 1px solid #ffcdd2; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php foreach ($errors as $error) echo "<li>" . htmlspecialchars($error) . "</li>"; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="full_name" style="font-size: 1.1em; font-weight: bold;">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" required value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>" style="width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="email" style="font-size: 1.1em; font-weight: bold;">Email Address:</label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" style="width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="phone" style="font-size: 1.1em; font-weight: bold;">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" style="width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="password" style="font-size: 1.1em; font-weight: bold;">Password:</label>
                    <input type="password" id="password" name="password" required style="width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="confirm_password" style="font-size: 1.1em; font-weight: bold;">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required style="width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                </div>

                <button type="submit" class="submit-btn" style="width: 100%; margin-top: 20px; padding: 15px; font-size: 18px; background-color: #82e043; color: #111111; font-weight: bold; border-radius: 4px;">Create Account</button>
            </form>
            
            <p style="text-align: center; margin-top: 20px; color: #666;">
                Already have an account? <a href="user_login.php" style="color: rgb(30, 136, 229);">Log in here.</a>
            </p>
        </div>
    </div>

    <?php include('content/footer.php'); ?>
</body>
</html>