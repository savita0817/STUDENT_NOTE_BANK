<?php
include 'db.php';

$notes = $conn->query("SELECT * FROM notes");
$papers = $conn->query("SELECT * FROM question_papers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Student View</title>
</head>
<body>

<div class="container">
    <h1>👩‍🎓 Student Dashboard</h1>

    <h2>📥 Download Notes</h2>
    <?php while ($row = $notes->fetch_assoc()): ?>
        <a href="<?= $row['file_path'] ?>" download><?= $row['file_name'] ?></a><br>
    <?php endwhile; ?>

    <h2>📥 Download Question Papers</h2>
    <?php while ($row = $papers->fetch_assoc()): ?>
        <a href="<?= $row['file_path'] ?>" download><?= $row['file_name'] ?></a><br>
    <?php endwhile; ?>

    <a href="index.php">⬅️ Back to Homepage</a>
</div>
<div class="footer">
    &copy; 2025 Student Note Bank | Powered by PHP & MySQL
</div>
</body>
</html>
