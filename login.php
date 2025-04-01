<?php
session_start();

$teacher_username = "teacher";
$teacher_password = "123456";  // Change this for security

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $teacher_username && $password === $teacher_password) {
        $_SESSION['teacher'] = true;
        header("Location: teacher_dashboard.php");
        exit();
    } else {
        echo "<p style='color: red; text-align: center;'>❌ Invalid login credentials</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Teacher Login</title>
</head>
<body>

<div class="container">
    <h1>👩‍🏫 Teacher Login</h1>

    <form action="" method="POST">
        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Login">
    </form>

    <a href="index.php">⬅️ Back to Homepage</a>
</div>

</body>
<div class="footer">
    &copy; 2025 Student Note Bank | Powered by PHP & MySQL
</div>
</html>
