<?php
include 'db.php';

$result = $conn->query("SELECT * FROM notes");
?>

<h1>Student Notes</h1>
<table>
    <tr>
        <th>Subject</th>
        <th>File Name</th>
        <th>Download</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['subject'] ?></td>
            <td><?= $row['file_name'] ?></td>
            <td><a href="<?= $row['file_path'] ?>" download>Download</a></td>
        </tr>
    <?php endwhile; ?>
</table>
<a href="index.php">Go Back</a>
<div class="footer">
    &copy; 2025 Student Note Bank | Powered by PHP & MySQL
</div>
