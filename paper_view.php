<?php
include 'db.php';

$query = "SELECT * FROM question_papers";
$result = $conn->query($query);
?>

<h1>Question Papers</h1>
<table border="1">
    <tr>
        <th>File Name</th>
        <th>Subject</th>
        <th>Year</th>
        <th>Download</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['file_name'] ?></td>
            <td><?= $row['subject'] ?></td>
            <td><?= $row['year'] ?></td>
            <td><a href="<?= $row['file_path'] ?>" download>Download</a></td>
        </tr>
    <?php endwhile; ?>
</table>

<a href="index.php">Back to Home</a>
<div class="footer">
    &copy; 2025 Student Note Bank | Powered by PHP & MySQL
</div>
