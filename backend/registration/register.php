<?php
/** Handles new user sign-up.
 * - Server-side validation (never trust client-side JS validation alone —
 * - Passwords are hashed with password_hash(), never stored in plain text
 * - Uses a prepared statement to prevent SQL injection
 */

require 'db.php';
	
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = $_POST['username'];
    	$email    = $_POST['email'];
    	$password = $_POST['password'];
    	$confirm  = $_POST['confirm_password'];

    	// --- Validation ---
    	if ($username === '' || $email === '' || $password === '') {
        	$errors[] = 'All fields are required.';
    	}

   	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        	$errors[] = 'Please enter a valid email address.';
    	}

    	if (strlen($password) < 8) {
        	$errors[] = 'Password must be at least 8 characters long.';
    	}

    	if ($password !== $confirm) {
        	$errors[] = 'Passwords do not match.';
    	}

    	// --- Check for existing username/email before inserting ---
    	if (empty($errors)) {
        	$stmt = $pdo->prepare('SELECT user_id FROM users WHERE username = ? OR email = ?');
        	$stmt->execute([$username, $email]);
        	if ($stmt->fetch()) {
            		$errors[] = 'That username or email is already registered.';
        	}
    	}

    	// --- Insert new user ---
    	if (empty($errors)) {
        	$hash = password_hash($password, PASSWORD_DEFAULT);
	
        	$stmt = $pdo->prepare(
            		'INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)'
        	);
        	$stmt->execute([$username, $email, $hash]);

        	$success = true;
    	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <h1>Create an Account</h1>

    <?php if ($success): ?>
        <p>Registration successful! You can now <a href="login.php">Log In</a>.</p>
    <?php else: ?>

        <?php if (!empty($errors)): ?>
            <ul style="color:red;">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <label>Username:
                <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </label><br>

            <label>Email:
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </label><br>

            <label>Password:
                <input type="password" name="password">
            </label><br>

            <label>Confirm Password:
                <input type="password" name="confirm_password">
            </label><br>

            <button type="submit">Register</button>
        </form>

    <?php endif; ?>
</body>
</html>