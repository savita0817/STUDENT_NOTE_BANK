<?php
session_start();
include 'db.php';

if (!isset($_SESSION['teacher'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];

    if ($type === 'note') {
        $query = "SELECT file_path FROM notes WHERE id = ?";
        $delete_query = "DELETE FROM notes WHERE id = ?";
    } elseif ($type === 'paper') {
        $query = "SELECT file_path FROM question_papers WHERE id = ?";
        $delete_query = "DELETE FROM question_papers WHERE id = ?";
    } else {
        header("Location: teacher_dashboard.php");
        exit();
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $file_path = $row['file_path'];

        // Delete the file from the server
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Delete the record from the database
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        echo "<p style='color: green;'>✅ File deleted successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ File not found.</p>";
    }
}

header("Location: teacher_dashboard.php");
exit();
?>
