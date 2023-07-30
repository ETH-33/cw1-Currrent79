<?php
include 'config.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format!';
    } else {
        $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('Query failed');

        if (mysqli_num_rows($select_users) > 0) {
            $error = 'User already exists!';
        } else {
            // Validate password length and special character presence
            if (strlen($password) < 8 || !preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password)) {
                $error = 'Password should be at least 8 characters long and contain at least one special character!';
            } else {
                if ($password !== $cpassword) {
                    $error = 'Confirm password does not match!';
                } else {
                    // Hash the password using PASSWORD_BCRYPT
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                    mysqli_query($conn, "INSERT INTO `users`(name, email, password) VALUES('$name', '$email', '$hashedPassword')") or die('Query failed');
                    header('location: login.php');
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php
if (isset($error)) {
    echo '
    <div class="message">
        <span>'.$error.'</span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>
    ';
}
?>

<div class="form-container">
    <form action="" method="post">
        <h3>Register Now</h3>
        <input type="text" name="name" placeholder="Enter your name" required class="box">
        <input type="email" name="email" placeholder="Enter your email" required class="box">
        <input type="password" name="password" placeholder="Password (8+ characters, special character)" required class="box">
        <input type="password" name="cpassword" placeholder="Confirm your password" required class="box">
        <input type="submit" name="submit" value="Register Now" class="btn">
        <p>Already have an account? <a href="login.php">Login Now</a></p>
    </form>
</div>

</body>
</html>
