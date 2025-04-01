<?php
$host = "localhost";      // Database server
$user = "root";           // XAMPP default user
$pass = "";               // No password for local setup
$db = "note_bank";        // Your database name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
?>
