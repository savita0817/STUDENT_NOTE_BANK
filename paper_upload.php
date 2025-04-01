<?php
include 'db.php';

// Handle form submission for uploading papers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'];
    $year = $_POST['year'];
    $file = $_FILES['paper'];

    if ($file['error'] == 0) {
        $upload_dir = "uploads/papers/";

        // Create the folder if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = uniqid() . "_" . $file['name'];
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            $stmt = $conn->prepare("INSERT INTO question_papers (file_name, subject, year, file_path) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $file['name'], $subject, $year, $file_path);
            $stmt->execute();
            echo "<p style='color: green;'>✅ Question Paper uploaded successfully!</p>";
        } else {
            echo "<p style='color: red;'>❌ Failed to upload the file.</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Error uploading file.</p>";
    }
}

// Fetch uploaded papers to display for download
$result = $conn->query("SELECT * FROM question_papers ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <title>Upload and View Question Papers</title>
</head>
<body>

<div class="container">
    <h1>📄 Upload Question Paper</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Subject:</label>
        <input type="text" name="subject" required>

        <label>Year:</label>
        <input type="number" name="year" min="2000" max="2099" required>

        <label>Upload Paper (DOC/DOCX/PDF/TXT/MP4/MPEG/MOV):</label>
        <input type="file" name="paper" accept=".doc, .docx, .pdf, .txt, " required>

        <input type="submit" value="Upload Question Paper">
    </form>

    <h2>📥 Download Question Papers</h2>
    <table>
        <tr>
            <th>Subject</th>
            <th>Year</th>
            <th>File Name</th>
            <th>Download</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['subject']) ?></td>
                <td><?= htmlspecialchars($row['year']) ?></td>
                <td><?= htmlspecialchars($row['file_name']) ?></td>
                <td><a href="<?= $row['file_path'] ?>" download>Download</a></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="index.php">⬅️ Back to Home</a>
</div>

</body>
<div class="footer">
    &copy; 2025 Student Note Bank | Powered by PHP & MySQL
</div>
</html>
