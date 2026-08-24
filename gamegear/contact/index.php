<?php
$pageTitle = "Contact Us";

$name = $email = $phone = $message = $salutation = '';
$enquiry = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $message = $_POST['message'] ?? '';
    $salutation = $_POST['salutation'] ?? '';
    $enquiry = $_POST['enquiry'] ?? [];

    if (empty($salutation)) { $errors['salutation'] = 'Salutation is required'; }
    if (empty($name)) { $errors['name'] = 'Name is required'; }
    elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) { $errors['name'] = 'Only alphabets and white space is allowed.'; }
    
    if (empty($email)) { $errors['email'] = 'Email is required'; }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors['email'] = 'A valid email is required'; }

    $phone = str_replace([' ', '-', '(', ')'], "", $phone);
    if (empty($phone)) { $errors['phone'] = 'Phone is required'; }
    elseif (!ctype_digit($phone)) { $errors['phone'] = 'Phone num must be numeric.'; }
    elseif (strlen($phone) < 10 || strlen($phone) > 15) { $errors['phone'] = 'Phone num must be between 10 and 15 digits.'; }

    if (empty($enquiry)) { $errors['enquiry'] = 'At least one type of enquiry is required'; }
    if (empty($message)) { $errors['message'] = 'Message is required'; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <title>Contact Us | GameGear Exchange</title>
</head>
<body>
    <?php include('../content/header.php'); ?>
    <?php include('../content/navigation.php'); ?>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)) {
        echo '<div id="contentWrapper"><div class="form-container success-message">';
        echo "<h2 style='text-align:center;'>Submission Details</h2>";
        echo "<p><strong>Salutation:</strong> " . htmlspecialchars($salutation) . "</p>";
        echo "<p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
        echo "<p><strong>Phone:</strong> " . htmlspecialchars($phone) . "</p>";
        echo "<p><strong>Enquiry:</strong> " . implode(', ', array_map('htmlspecialchars', $enquiry)) . "</p>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($message) . "</p>";
        echo '<hr><p style="text-align:center; color: #0056b3; font-weight:bold;">Thank you for your submission. We have received your enquiry.</p>';
        echo '<div style="display: flex; justify-content: center; gap: 15px; margin-top: 25px;">';
        echo '<a href="index.php" class="submit-btn" style="width: auto; text-decoration: none; padding: 10px 20px; background-color: #6c757d;">Back to Contact Us</a>';
        echo '<a href="../index.php" class="submit-btn" style="width: auto; text-decoration: none; padding: 10px 20px;">Return to Homepage</a>';
        echo '</div>';
        
        echo "</div></div>";
    } else {
        include('_form.php');
    }
    ?>

    <?php include('../content/footer.php'); ?>

    <!-- Client-Side Validation Script -->
    <script>
    function validateForm(event) {
        let isValid = true;
        const form = document.getElementById('contactForm');
        
        // Clear previous error messages
        document.querySelectorAll('.error').forEach(div => div.textContent = '');
        
        if (form['salutation'].value.trim() === '') {
            document.getElementById('salutationError').textContent = 'Please select your salutation.';
            isValid = false;
        }
        if (form['name'].value.trim() === '') {
            document.getElementById('nameError').textContent = 'Name is required.';
            isValid = false;
        }
        let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (form['email'].value.trim() === '') {
            document.getElementById('emailError').textContent = 'Email is required.';
            isValid = false;
        } else if (!emailPattern.test(form['email'].value)) {
            document.getElementById('emailError').textContent = 'Email is not valid.';
            isValid = false;
        }
        if (form['phone'].value.trim() === '') {
            document.getElementById('phoneError').textContent = 'Phone number is required.';
            isValid = false;
        } else if (!/^[\d\s\-\(\)]{10,15}$/.test(form['phone'].value)) {
            document.getElementById('phoneError').textContent = 'Enter a valid phone number (10-15 digits).';
            isValid = false;
        }
        let checkboxes = document.querySelectorAll('input[name="enquiry[]"]');
        let isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        if (!isChecked) {
            document.getElementById('enquiryError').textContent = 'Please select at least one enquiry.';
            isValid = false;
        }
        if (form['message'].value.trim() === '') {
            document.getElementById('messageError').textContent = 'Message is required.';
            isValid = false;
        }
        
        if (!isValid) { event.preventDefault(); }
        return isValid;
    }
    </script>
</body>
</html>